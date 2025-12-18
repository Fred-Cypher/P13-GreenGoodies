<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\User;
use App\Form\OrderDetailFormType;
use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProductController extends AbstractController
{
    public function __construct(
        private readonly CartService $cartService,
    ){}
    #[Route('/product', name: 'app_product')]
    public function index(): Response
    {
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }

    #[Route('/product/{id}', name: 'app_product_show')]
    public function show(Product $product, Request $request): Response
    {
        $quantityInCart = 0;

        $user = $this->getUser();

        if ($user instanceof User) {
            $order = $this->cartService->getOrCreateCurrentCart($user);

            foreach ($order->getOrderDetails() as $orderDetail) {
                if ($orderDetail->getProduct()->getId() === $product->getId()) {
                    $quantityInCart = $orderDetail->getQuantity();
                    break;
                }
            }
        }

        $form = $this->createForm(OrderDetailFormType::class, null, [
            'quantity' => $quantityInCart,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && $user instanceof User) {
            $data = $form->getData();

            $this->cartService->addOrUpdateProduct($order, $product, $data['quantity']);
            return $this->redirectToRoute('app_cart_show');
        }

        return $this->render('product/show.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
            'quantity' => $quantityInCart,
        ]);
    }
}
