<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\Entity\User;

class BanListener
{
    private $tokenStorage;
    private $urlGenerator;

    public function __construct(TokenStorageInterface $tokenStorage, UrlGeneratorInterface $urlGenerator)
    {
        $this->tokenStorage = $tokenStorage;
        $this->urlGenerator = $urlGenerator;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();
        $user = $this->tokenStorage->getToken()->getUser();

        // Vérifier si l'utilisateur est banni
        if ($user instanceof User && $user->isBanned()) {
            // Rediriger l'utilisateur vers une page de ban
            $response = new RedirectResponse($this->urlGenerator->generate('app_user_banned'));
            $event->setResponse($response);
        }
    }
}
