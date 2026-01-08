<?php

namespace App\Controller;

use App\Entity\User;
use App\Enum\OrderStatusEnum;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function profile(OrderRepository $orderRepository): Response
    {
        $user = $this->getUser();

        if(!$user instanceof User){
            return $this->redirectToRoute('app_login');
        }

        $orders = $orderRepository->findBy([
            'user' => $user,
            'status' => OrderStatusEnum::VALIDATED,
        ], [
            'validatedAt' => 'DESC'
        ]);

        return $this->render('user/profile.html.twig', [
            'orders' => $orders,
        ]);
    }

    #[Route('/profile/api-access', name: 'app_profile_api_access', methods: ['POST'])]
    public function apiAccess(EntityManagerInterface $em): Response{
        $user = $this->getUser();

        if(!$user instanceof User){
            return $this->redirectToRoute('app_login');
        }

        $user->setApiAccess(!$user->isApiAccess());
        $user->setUpdatedAt(new \DateTimeImmutable());

        $em->flush();

        return $this->redirectToRoute('app_profile');
    }

    #[Route('/profile/delete', name: 'app_profile_delete', methods: ['POST'])]
    public function delete(EntityManagerInterface $em, Request $request, Security $security): Response
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            return $this->redirectToRoute('app_login');
        }

        if (!$this->isCsrfTokenValid('delete-user', $request->request->get('_token'))) {
            throw $this->createAccessDeniedException();
        }

        $security->logout(false);

        $em->remove($user);
        $em->flush();

        return $this->redirectToRoute('app_home');
    }
}
