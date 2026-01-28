<?php

namespace App\Controller\Web;

use App\Entity\User;
use App\Enum\OrderStatusEnum;
use App\Service\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Random\RandomException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
        /** @var User $user */
        $user = $this->getUser();
        // Fetch the current cart using the CartService
        $cart = $this->cartService->getCurrentCart($user);

        return $this->render('cart/index.html.twig', [
            'cart' => $cart,
        ]);
    }

    /**
     * Validate the cart to finalize the order.
     * @throws RandomException
     */
    #[Route('/validate', name: 'app_validate_order', methods: ['POST'])]
    public function validateOrder(): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $cart = $this->cartService->getOrCreateCurrentCart($user);

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
        /** @var User $user */
        $user = $this->getUser();

        $cart = $this->cartService->getCurrentCart($user);

        if (!$cart) {
            return $this->redirectToRoute('app_home');
        }

        // Physical deletion from the database
        $this->em->remove($cart);
        $this->em->flush();

        return $this->redirectToRoute('app_cart_show');
    }

}
