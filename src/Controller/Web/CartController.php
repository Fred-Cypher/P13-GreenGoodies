<?php

namespace App\Controller\Web;

use App\Entity\Product;
use App\Enum\OrderStatusEnum;
use App\Service\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Random\RandomException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Handles the cart lifecycle (display, add, validate, and delete).
 * Access restricted to authenticated users via #[IsGranted].
 */
#[Route('/cart')]
#[IsGranted('ROLE_USER')]
class CartController extends AbstractController
{
    public function __construct(
        private readonly CartService $cartService,
        private readonly EntityManagerInterface $em,
    ) {
    }

    /**
     * Display the current user's shopping cart.
     */
    #[Route('/', name: 'app_cart_show', methods: ['GET'])]
    public function cartShow(): Response
    {
        // Fetch the current cart using the CartService
        $cart = $this->cartService->getCurrentCart($this->getUser());

        return $this->render('cart/index.html.twig', [
            'cart' => $cart,
        ]);
    }

    /**
     * Add a product to the cart or update its quantity.
     * Uses Symfony's ParamConverter to automatically fetch the Product entity.
     */
    #[Route('/add/{id}', name: 'app_add_product', methods: ['POST'])]
    public function addOrUpdateProduct(Product $product, Request $request): Response
    {
        // Ensure quantity is at least 1
        $quantity = max(1, (int)$request->request->get('quantity', 0));

        // Get existing cart or create a new one
        $cart = $this->cartService->getOrCreateCurrentCart($this->getUser());

        // Delegate add/update business logic to CartService
        $this->cartService->addOrUpdateProduct($cart, $product, $quantity);

        return $this->redirectToRoute('app_cart_show');
    }


    /**
     * Validate the cart to finalize the order.
     * @throws RandomException
     */
    #[Route('/validate', name: 'app_validate_order', methods: ['POST'])]
    public function validateOrder(): Response
    {
        $cart = $this->cartService->getOrCreateCurrentCart($this->getUser());

        // Security check: ensure cart is not empty and is in PENDING status
        if ($cart->getStatus() !== OrderStatusEnum::PENDING || $cart->getOrderDetails()->isEmpty()) {
            $this->addFlash('warning', 'Commande invalide ou panier vide');
            return $this->redirectToRoute('app_home');
        }

        // Switch status from cart to validated order
        $this->cartService->validateOrder($cart);

        $this->addFlash('success', 'Votre commande a bien été validée');

        return $this->redirectToRoute('app_home');
    }

    /**
     * Completely remove the cart and its associated items.
     */
    #[Route('/delete', name: 'app_cart_delete', methods: ['POST'])]
    public function deleteCart(): Response
    {
        $cart = $this->cartService->getCurrentCart($this->getUser());

        if (!$cart) {
            return $this->redirectToRoute('app_home');
        }

        // Physical deletion from the database
        $this->em->remove($cart);
        $this->em->flush();

        return $this->redirectToRoute('app_cart_show');
    }

}
