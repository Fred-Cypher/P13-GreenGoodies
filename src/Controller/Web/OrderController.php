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

#[Route('order')]
class OrderController extends AbstractController
{
    public function __construct(
        private readonly CartService $cartService,
        private readonly EntityManagerInterface $em,
    ){}

    #[Route('/cart', name: 'app_cart_show', methods: ['GET'])]
    public function cartShow(): Response
    {
        $user = $this->getUser();

        if(!$user instanceof \App\Entity\User){
            return $this->redirectToRoute('app_login');
        }

        $order = $this->cartService->getCurrentCart($user);
        if(!$order){
            return $this->render('order/cart.html.twig', [
                'order' => $order,
            ]);
        }
        $cartItems = $order->getOrderDetails();

        return $this->render('order/cart.html.twig', [
            'order' => $order,
            'cartItems' => $cartItems,
        ]);
    }

    #[Route('/cart/add/{id}', name: 'app_add_product', methods: ['POST'])]
    public function addOrUpdateProduct(Product $product, Request $request): Response
    {
        $user = $this->getUser();

        if(!$user instanceof \App\Entity\User){
            return $this->redirectToRoute('app_login');
        }

        $quantity = max(1, (int) $request->request->get('quantity', 0));

        $order = $this->cartService->getOrCreateCurrentCart($user);
        $this->cartService->addOrUpdateProduct($order, $product, $quantity);

        return $this->redirectToRoute('app_cart_show');
    }


    /**
     * @throws RandomException
     */
    #[Route('/cart/validate', name:'app_validate_order', methods: ['POST'])]
    public function validateOrder(): Response
    {
        $user = $this->getUser();

        if(!$user instanceof \App\Entity\User){
            return $this->redirectToRoute('app_login');
        }

        $order = $this->cartService->getOrCreateCurrentCart($user);

        if ($order->getStatus() !== OrderStatusEnum::PENDING || $order->getOrderDetails()->isEmpty()) {
            $this->addFlash('warning', 'Commande invalide ou panier vide');
            return $this->redirectToRoute('app_home');
        }

        $this->cartService->validateOrder($order);

        $this->addFlash('success', 'Votre commande a bien été validée');

        return $this->redirectToRoute('app_home');
    }


    #[Route('/cart/delete', name: 'app_cart_delete', methods: ['POST'])]
    public function deleteCart(): Response
    {
        $user = $this->getUser();

        if(!$user instanceof \App\Entity\User){
            return $this->redirectToRoute('app_login');
        }
        $order = $this->cartService->getCurrentCart($user);

        if (!$order) {
            return $this->redirectToRoute('app_home');
        }

        $this->em->remove($order);
        $this->em->flush();

        return $this->redirectToRoute('app_cart_show');
    }

}
