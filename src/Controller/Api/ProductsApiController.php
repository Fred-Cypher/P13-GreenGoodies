<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Handles API requests related to Product resources.
 * All routes are protected by JWT authentication via security.yaml.
 */
class ProductsApiController extends AbstractController
{
    /**
     * Fetches all products and returns them as a JSON collection.
     * Implements granular access control via the isApiAccess property.
     */
    #[Route('/api/products', name: 'api_products', methods: ['GET'])]
    public function getAllProducts(ProductRepository $productRepository): JsonResponse
    {
        /** @var User|null $user */
        $user = $this->getUser();
        $products = $productRepository->findAll();

        // If no products exist, return an empty array with a 200 OK status
        if (empty($products)) {
            return $this->json([], 200);
        }

        // Secondary security check: Verify if the authenticated user has API access enabled
        if (!$user || !$user->isApiAccess()) {
            return $this->json([
                'error' => 'Accès API désactivé ou non autorisé'
            ], 403);
        }

        // Return serialized products using the 'products' serialization group
        return $this->json(
            $products,
            200,
            [],
            ['groups' => ['products']]
        );
    }
}
