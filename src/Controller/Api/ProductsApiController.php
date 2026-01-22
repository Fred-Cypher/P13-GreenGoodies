<?php

namespace App\Controller\Api;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class ProductsApiController extends AbstractController
{
    #[Route('/api/products', name: 'api_products', methods: ['GET'])]
    public function getAllProducts(ProductRepository $productRepository): JsonResponse
    {
        $user = $this->getUser();
        $products = $productRepository->findAll();

        if (empty($products)) {
            return $this->json([], 200);
        }

        if (!$user || !$user->isApiAccess()) {
            return $this->json([
                'error' => 'Accès API désactivé ou non autorisé'
            ], 403);
        }

        return $this->json(
            $products,
            200,
            [],
            ['groups' => ['products']]
        );
    }
}
