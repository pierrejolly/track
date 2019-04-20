<?php

namespace AppBundle\Datatables;

use AppBundle\Datatables\AbstractDatatable;

/**
 * Task datatable.
 *
 * @author Pierre Jolly <pie.jolly@gmail.com>
 */
class TaskDatatable extends AbstractDatatable
{
    /**
     * {@inheritdoc}
     */
    public function buildDatatable(array $options = [])
    {
        $tags     = $this->em->getRepository('AppBundle:Tag')->findAll();
        $users    = $this->em->getRepository('AppBundle:User')->findAll();
        $projects = $this->em->getRepository('AppBundle:Project')->findAll();

        $this->features->set($this->getDefaultFeatures());
        $this->ajax->set($this->getDefaultAjax('task_list_results'));
        $this->options->set($this->getDefaultOptions());

        $this->columnBuilder
            ->add('date', 'datetime', [
                'title' => 'Date',
                'date_format' => 'L',
                'filter' => ['daterange', [
                    'class' => 'form-control',
                ]],
            ])
            ->add('name', 'column', [
                'title' => 'Description',
                'filter' => ['text', [
                    'class' => 'form-control',
                ]],
            ])
            ->add('project.name', 'column', [
                'title' => 'Projet',
                'filter' => ['select2', [
                    'select_options' => $this->getCollectionAsOptionsArray($projects, 'name', 'name'),
                    'search_type' => 'eq',
                    'multiple' => true,
                    'placeholder' => 'Projet(s)',
                    'allow_clear' => true,
                    'tags' => false,
                    'language' => 'fr',
                ]],
            ])
            ->add('tags.name', 'array', [
                'title' => 'Tags',
                'data' => 'tags[, ].name',
                'filter' => ['select2', [
                    'select_options' => $this->getCollectionAsOptionsArray($tags, 'name', 'name'),
                    'search_type' => 'eq',
                    'multiple' => true,
                    'placeholder' => 'Tag(s)',
                    'allow_clear' => true,
                    'tags' => false,
                    'language' => 'fr',
                ]],
            ])
            ->add('duration', 'column', [
                'title' => 'Durée',
                'searchable' => false,
            ])
            ->add('user.username', 'column', [
                'title' => 'Utilisateur',
                'filter' => ['select2', [
                    'select_options' => $this->getCollectionAsOptionsArray($users, 'username', 'username'),
                    'search_type' => 'eq',
                    'multiple' => true,
                    'placeholder' => 'Utilisateur(s)',
                    'allow_clear' => true,
                    'tags' => false,
                    'language' => 'fr',
                ]],
                'add_if' => function() {
                    return (
                        $this->authorizationChecker->isGranted('ROLE_VIEW_USER')
                    );
                },
            ])
            ->add(null, 'action', [
                'title' => $this->translator->trans('datatables.actions.title'),
                'actions' => [
                    [
                        'route' => 'task_edit',
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
                                $this->authorizationChecker->isGranted('ROLE_EDIT_TASK')
                            );
                        },
                    ],
                    [
                        'route' => 'task_delete',
                        'route_parameters' => [
                            'id' => 'id'
                        ],
                        'icon' => 'fa fa-remove',
                        'attributes' => [
                            'rel' => 'tooltip',
                            'title' => $this->translator->trans('datatables.actions.delete'),
                            'class' => 'btn btn-danger btn-xs',
                            'role' => 'button',
                            'data-toggle'=> 'modal',
                            'data-target' => '#modal'
                        ],
                        'render_if' => function() {
                            return (
                                $this->authorizationChecker->isGranted('ROLE_DELETE_TASK')
                            );
                        },
                    ]
                ]
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        return 'AppBundle\Entity\Task';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'task_datatable';
    }
}
