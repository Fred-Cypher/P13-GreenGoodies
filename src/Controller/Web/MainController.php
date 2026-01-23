<?php

namespace App\Controller\Web;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Main entry point of the application.
 * Handles public-facing pages such as the landing page.
 */
final class MainController extends AbstractController
{
    /**
     * Displays the home page with the list of all available products.
     */
    #[Route('/', name: 'app_home')]
    public function index(ProductRepository $productRepository): Response
    {
        // Fetch all products to showcase on the landing page
        $products = $productRepository->findAll();

        return $this->render(
            'main/index.html.twig',
            [
                'products' => $products,
            ]
        );
    }
}
