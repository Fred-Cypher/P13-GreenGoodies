<?php

namespace App\Controller\Web;

use App\Entity\Product;
use App\Entity\User;
use App\Form\OrderDetailFormType;
use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Handles product display and interaction on the storefront.
 */
final class ProductController extends AbstractController
{
    public function __construct(
        private readonly CartService $cartService,
    ) {
    }

    /**
     * Displays a single product detail page and handles the "Add to Cart" form.
     * Publicly accessible, but cart features require authentication.
     */
    #[Route('/product/{id}', name: 'app_product_show')]
    public function show(Product $product, Request $request): Response
    {
        $quantityInCart = 0;

        /** @var User|null $user */
        $user = $this->getUser();

        // If the user is logged in, we fetch the current quantity of this product in their cart
        if ($user instanceof User) {
            $cart = $this->cartService->getOrCreateCurrentCart($user);

            $quantityInCart = $this->cartService->getProductQuantityInCart($cart, $product);
        }

        // Initialize the form with the current quantity already in the cart
        $form = $this->createForm(OrderDetailFormType::class, null, [
            'quantity' => $quantityInCart,
        ]);

        $form->handleRequest($request);

        // Handle form submission: users must be logged in to add items to their cart
        if ($form->isSubmitted() && $form->isValid() && $user instanceof User) {
            $data = $form->getData();

            $this->cartService->addOrUpdateProduct($cart, $product, $data['quantity']);
            return $this->redirectToRoute('app_cart_show');
        }

        return $this->render('product/show.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
            'quantity' => $quantityInCart,
        ]);
    }
}
