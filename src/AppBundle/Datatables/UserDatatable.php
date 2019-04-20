<?php

namespace AppBundle\Datatables;

use AppBundle\Datatables\AbstractDatatable;

/**
 * User datatable.
 *
 * @author Pierre Jolly <pie.jolly@gmail.com>
 */
class UserDatatable extends AbstractDatatable
{
    /**
     * {@inheritdoc}
     */
    public function buildDatatable(array $options = [])
    {
        $this->features->set($this->getDefaultFeatures());
        $this->ajax->set($this->getDefaultAjax('user_list_results'));
        $this->options->set($this->getDefaultOptions());

        $this->columnBuilder
            ->add('username', 'column', [
                'title' => 'Nom d\'utilisateur',
                'visible' => false,
            ])
            ->add('firstName', 'column', [
                'title' => 'Prénom',
                'filter' => ['text', [
                    'class' => 'form-control',
                ]],
            ])
            ->add('lastName', 'column', [
                'title' => 'Nom',
                'filter' => ['text', [
                    'class' => 'form-control',
                ]],
            ])
            ->add(null, 'action', [
                'title' => $this->translator->trans('datatables.actions.title'),
                'actions' => [
                    [
                        'route' => 'user_edit',
                        'route_parameters' => [
                            'id' => 'id'
                        ],
                        'icon' => 'fa fa-edit',
                        'attributes' => [
                            'rel' => 'tooltip',
                            'title' => $this->translator->trans('datatables.actions.edit'),
                            'class' => 'btn btn-primary btn-xs',
                            'role' => 'button'
                        ],
                        'render_if' => function() {
                            return (
                                $this->authorizationChecker->isGranted('ROLE_EDIT_USER')
                            );
                        },
                    ],
                    [
                        'route' => 'homepage',
                        'route_parameters' => [
                            '_switch_user' => 'username'
                        ],
                        'icon' => 'fa fa-user-secret',
                        'attributes' => [
                            'rel' => 'tooltip',
                            'title' => $this->translator->trans('datatables.actions.show'),
                            'class' => 'btn btn-primary btn-xs',
                            'role' => 'button'
                        ],
                        'render_if' => function() {
                            return (
                                $this->authorizationChecker->isGranted('ROLE_ALLOWED_TO_SWITCH')
                            );
                        },
                    ],
                ]
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        return 'AppBundle\Entity\User';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'user_datatable';
    }
}
