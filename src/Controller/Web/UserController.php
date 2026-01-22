<?php

namespace App\Controller\Web;

use App\Entity\User;
use App\Enum\OrderStatusEnum;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/profile')]
#[IsGranted('ROLE_USER')]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_profile')]
    public function profile(OrderRepository $orderRepository): Response
    {
        $orders = $orderRepository->findBy([
            'user' => $this->getUser(),
            'status' => OrderStatusEnum::VALIDATED,
        ], [
            'validatedAt' => 'DESC'
        ]);

        return $this->render('user/profile.html.twig', [
            'orders' => $orders,
        ]);
    }

    #[Route('/api-access', name: 'app_profile_api_access', methods: ['POST'])]
    public function apiAccess(EntityManagerInterface $em): Response
    {
        $this->getUser()->setApiAccess(!$this->getUser()->isApiAccess());
        $this->getUser()->setUpdatedAt(new \DateTimeImmutable());

        $em->flush();

        return $this->redirectToRoute('app_profile');
    }

    #[Route('/delete', name: 'app_profile_delete', methods: ['POST'])]
    public function delete(EntityManagerInterface $em, Request $request, Security $security): Response
    {
        $user = $this->getUser();
        /** @var User $user */

        if (!$this->isCsrfTokenValid('delete-user', $request->request->get('_token'))) {
            throw $this->createAccessDeniedException();
        }

        $em->remove($user);
        $em->flush();

        $security->logout(false);

        $this->addFlash('success', 'Votre compte a été supprimé avec succès.');

        return $this->redirectToRoute('app_home');
    }
}
