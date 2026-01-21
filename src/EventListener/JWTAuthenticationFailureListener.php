<?php

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationFailureEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Response\JWTAuthenticationFailureResponse;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;


#[AsEventListener(event: 'lexik_jwt_authentication.on_authentication_failure', method: 'onAuthenticationFailure')]
class JWTAuthenticationFailureListener
{
    public function onAuthenticationFailure(AuthenticationFailureEvent $event): void
    {
        $exception = $event->getException();

        if ($exception->getMessage() === 'API_ACCESS_DISABLED')
        {
            $response = new JWTAuthenticationFailureResponse(
                'Accès API non activé pour cet utilisateur.',
                403
            );
            $event->setResponse($response);
        } elseif (in_array($exception->getMessage(), ['Bad credentials.', 'The presented password is invalid.'])) {
            $response = new JWTAuthenticationFailureResponse(
                'Identifiants incorrects, veuillez réessayer.',
                401
            );
            $event->setResponse($response);
        }
    }
}
