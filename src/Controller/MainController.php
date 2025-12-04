<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MainController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(UserRepository $userRepository, ProductRepository $productRepository): Response
    {
        $user = $this->getUser();
        $products = $productRepository->findAll();

        return $this->render('main/index.html.twig',
            [
                'user' => $user,
                'products' => $products,
            ]);
    }
}
