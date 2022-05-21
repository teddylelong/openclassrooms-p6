<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class AccessDeniedListener implements EventSubscriberInterface
{
    private RouterInterface $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            // the priority must be greater than the Security HTTP
            // ExceptionListener, to make sure it's called before
            // the default exception listener
            KernelEvents::EXCEPTION => ['onKernelException', 2],
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        if (!$exception instanceof AccessDeniedException) {
            return;
        }

        // ... perform some action (e.g. logging)
        $request = $event->getRequest();
        $request->getSession()->getFlashBag()->add('danger', "Vous n'avez pas les droits nécessaires pour utiliser cette fonctionnalité.");

        // optionally set the custom response
        $response = new RedirectResponse(
            $this->router->generate('app_login')
        );
        $event->setResponse($response);

        // or stop propagation (prevents the next exception listeners from being called)
        //$event->stopPropagation();
    }
}