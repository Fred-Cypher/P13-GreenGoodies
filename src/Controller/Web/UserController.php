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
    /**
     * Displays the user's profile page with their validated order history.
     * Orders are sorted by validation date (most recent first).
     */
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

    /**
     * Toggles the user's API access status.
     * Persists the change and updates the record's timestamp.
     */
    #[Route('/api-access', name: 'app_profile_api_access', methods: ['POST'])]
    public function apiAccess(EntityManagerInterface $em): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        // Toggle the boolean value and update the timestamp
        $user->setApiAccess(!$user->isApiAccess());
        $user->setUpdatedAt(new \DateTimeImmutable());

        $em->flush();

        return $this->redirectToRoute('app_profile');
    }

    /**
     * Deletes the user account.
     * Validates CSRF token, removes the user from DB, and performs a manual logout.
     */
    #[Route('/delete', name: 'app_profile_delete', methods: ['POST'])]
    public function delete(EntityManagerInterface $em, Request $request, Security $security): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        // Security: Check CSRF token to prevent cross-site request forgery
        if (!$this->isCsrfTokenValid('delete-user', $request->request->get('_token'))) {
            throw $this->createAccessDeniedException();
        }

        // Remove user from database
        $em->remove($user);
        $em->flush();

        // Manually invalidate the session and logout the user
        $security->logout(false);

        $this->addFlash('success', 'Votre compte a été supprimé avec succès.');

        return $this->redirectToRoute('app_home');
    }
}
