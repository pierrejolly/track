<?php

namespace AppBundle\EventListener;

use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Session\Session;

use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

/**
 * Login listener.
 *
 * @author Pierre Jolly <pie.jolly@gmail.com>
 */
class LoginListener
{
    /**
     * Security AuthorizationChecker.
     *
     * @var AuthorizationCheckerInterface
     */
    private $authorizationChecker;

    /**
     * @var Router
     */
    private $router;

    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @var Session
     */
    private $session;

    /**
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @param Router                        $router
     * @param EventDispatcherInterface      $dispatcher
     */
    public function __construct(
        AuthorizationCheckerInterface $authorizationChecker,
        Router $router,
        EventDispatcherInterface $dispatcher,
        Session $session
    ) {
        $this->authorizationChecker = $authorizationChecker;
        $this->router               = $router;
        $this->dispatcher           = $dispatcher;
        $this->session              = $session;
    }

    /**
     * @param InteractiveLoginEvent $event
     */
    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        if ($this->authorizationChecker->isGranted('IS_AUTHENTICATED_FULLY')) {
            $user = $event->getAuthenticationToken()->getUser();

            if (null === $user->getLastLogin()) {
                $this->session->getFlashBag()->add('info', 'Il s\'agit de votre première connexion, veuillez redéfinir votre mot passe.');

                $this->dispatcher->addListener(KernelEvents::RESPONSE, [
                    $this,
                    'onKernelResponse'
                ]);
            }
        }
    }

    /**
     * @param FilterResponseEvent $event
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        $response = new RedirectResponse ($this->router->generate('fos_user_change_password'));
        $event->setResponse($response);
    }
}