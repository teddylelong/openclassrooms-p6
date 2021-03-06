<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;

class AccessDeniedListener implements EventSubscriberInterface
{
    private RouterInterface $router;
    private $security;

    /**
     * @param RouterInterface $router
     * @param Security $security
     */
    public function __construct(RouterInterface $router, Security $security)
    {
        $this->router = $router;
        $this->security = $security;
    }

    /**
     * @return array[]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            // the priority must be greater than the Security HTTP
            // ExceptionListener, to make sure it's called before
            // the default exception listener
            KernelEvents::EXCEPTION => ['onKernelException', 2],
        ];
    }

    /**
     * @param ExceptionEvent $event
     * @return void
     */
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        if (!$exception instanceof AccessDeniedException) {
            return;
        }

        $request = $event->getRequest();

        $user = $this->security->getUser();

        $message = "Pour accéder à cette page, vous devez être connecté";
        if ($user) {
            $message = "Vous n'avez pas les droits nécessaires pour accéder à cette page.";
        }

        $request->getSession()->getFlashBag()->add('danger', $message);

        $response = new RedirectResponse(
            $this->router->generate('app_login')
        );
        $event->setResponse($response);
    }
}