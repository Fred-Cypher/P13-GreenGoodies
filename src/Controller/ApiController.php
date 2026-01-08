<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class ApiController extends AbstractController
{
    #[Route('/api/login', name: 'api_login')]
    public function login(){

    }

    #[Route('/api/products', name: 'api_products', methods: ['GET'])]
    public function getAllProducts(ProductRepository $productRepository): JsonResponse
    {
        $products = $productRepository->findAll();

        if(empty($products)){
            return $this->json('Aucun produit trouvé', 404);
        }
        return $this->json(
            $products,
            200,
            [],
            ['groups' => ['products']]
        );
    }
}
