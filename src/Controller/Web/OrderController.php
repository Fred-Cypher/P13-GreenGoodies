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

#[Route('/cart')]
class OrderController extends AbstractController
{
    public function __construct(
        private readonly CartService $cartService,
        private readonly EntityManagerInterface $em,
    ){}

    #[Route('/', name: 'app_cart_show', methods: ['GET'])]
    public function cartShow(): Response
    {
        $cart = $this->cartService->getCurrentCart($this->getUser());

        return $this->render('cart/index.html.twig', [
            'cart' => $cart,
        ]);
    }

    #[Route('/add/{id}', name: 'app_add_product', methods: ['POST'])]
    public function addOrUpdateProduct(Product $product, Request $request): Response
    {
        $quantity = max(1, (int) $request->request->get('quantity', 0));

        $cart = $this->cartService->getOrCreateCurrentCart($this->getUser());
        $this->cartService->addOrUpdateProduct($cart, $product, $quantity);

        return $this->redirectToRoute('app_cart_show');
    }


    /**
     * @throws RandomException
     */
    #[Route('/validate', name:'app_validate_order', methods: ['POST'])]
    public function validateOrder(): Response
    {
        $cart = $this->cartService->getOrCreateCurrentCart($this->getUser());

        if ($cart->getStatus() !== OrderStatusEnum::PENDING || $cart->getOrderDetails()->isEmpty()) {
            $this->addFlash('warning', 'Commande invalide ou panier vide');
            return $this->redirectToRoute('app_home');
        }

        $this->cartService->validateOrder($cart);

        $this->addFlash('success', 'Votre commande a bien été validée');

        return $this->redirectToRoute('app_home');
    }


    #[Route('/delete', name: 'app_cart_delete', methods: ['POST'])]
    public function deleteCart(): Response
    {
        $order = $this->cartService->getCurrentCart($this->getUser());

        if (!$order) {
            return $this->redirectToRoute('app_home');
        }

        $this->em->remove($order);
        $this->em->flush();

        return $this->redirectToRoute('app_cart_show');
    }

}
