<?php

namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Menu builder.
 *
 * @author Pierre Jolly <pie.jolly@gmail.com>
 */
class MenuBuilder
{
    /**
     * Menu factory.
     *
     * @var FactoryInterface
     */
    protected $factory;

    /**
     * Security AuthorizationChecker.
     *
     * @var AuthorizationCheckerInterface
     */
    protected $authorizationChecker;

    /**
     * @param FactoryInterface              $factory
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(
        FactoryInterface $factory,
        AuthorizationCheckerInterface $authorizationChecker
    ) {
        $this->factory              = $factory;
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * Create main menu.
     *
     * @param RequestStack $requestStack
     * @return \Knp\Menu\ItemInterface
     */
    public function createMainMenu(RequestStack $requestStack)
    {
        $routeName = $requestStack->getMasterRequest()->get('_route');

        $menu = $this->factory->createItem('root', [
            'childrenAttributes' => [
                'class' => 'nav navbar-nav',
            ],
        ]);

        // Dashboard
        $dashboard = $menu->addChild('dashboard', [
            'label' => 'Tableau de bord',
            'route' => 'dashboard',
        ]);
        if ('dashboard' === $routeName
            || 'homepage' === $routeName
        ) {
            $dashboard->setCurrent(true);
        }

        // Task
        $task = $menu->addChild('task', [
            'label' => 'Tâches',
            'route' => 'task_list',
        ]);
        if (preg_match('/^task_?_/', $routeName)) {
            $task->setCurrent(true);
        }

        // Project
        $project = $menu->addChild('project', [
            'label' => 'Projets',
            'route' => 'project_list',
        ]);
        if (preg_match('/^project_?_/', $routeName)) {
            $project->setCurrent(true);
        }

        // Tag
        $tag = $menu->addChild('tag', [
            'label' => 'Tags',
            'route' => 'tag_list',
        ]);
        if (preg_match('/^tag_?_/', $routeName)) {
            $tag->setCurrent(true);
        }

        if ($this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            // User
            $user = $menu->addChild('user', [
                'label' => 'Utilisateurs',
                'route' => 'user_list',
            ]);
            if (preg_match('/^user_?_/', $routeName)) {
                $user->setCurrent(true);
            }
        }

        return $menu;
    }
}
