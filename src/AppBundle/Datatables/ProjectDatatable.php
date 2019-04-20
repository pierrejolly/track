<?php

namespace AppBundle\Datatables;

use AppBundle\Datatables\AbstractDatatable;

/**
 * Project datatable.
 *
 * @author Pierre Jolly <pie.jolly@gmail.com>
 */
class ProjectDatatable extends AbstractDatatable
{
    /**
     * {@inheritdoc}
     */
    public function getLineFormatter()
    {
        $formatter = function($line){
            $line['color'] = '<div class="color-box" style="background-color: ' . $line['color'] . ';"></div>';

            return $line;
        };

        return $formatter;
    }

    /**
     * {@inheritdoc}
     */
    public function buildDatatable(array $options = [])
    {
        $this->features->set($this->getDefaultFeatures());
        $this->ajax->set($this->getDefaultAjax('project_list_results'));
        $this->options->set($this->getDefaultOptions());

        $this->columnBuilder
            ->add('name', 'column', [
                'title' => 'Nom',
                'filter' => ['text', [
                    'class' => 'form-control',
                ]],
            ])
            ->add('client', 'column', [
                'title' => 'Client',
                'filter' => ['text', [
                    'class' => 'form-control',
                ]],
            ])
            ->add('color', 'column', [
                'title' => 'Couleur',
                'searchable' => false,
            ])
            ->add('enabled', 'boolean', [
                'title' => 'Actif ?',
                'true_icon' => 'fa fa-check',
                'false_icon' => 'fa fa-remove',
                'filter' => array('select', array(
                    'search_type' => 'eq',
                    'class' => 'form-control',
                    'select_options' => array('' => 'Tous', '1' => 'Oui', '0' => 'Non'),
                )),
            ])
            ->add(null, 'action', [
                'title' => $this->translator->trans('datatables.actions.title'),
                'actions' => [
                    [
                        'route' => 'project_edit',
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
                                $this->authorizationChecker->isGranted('ROLE_EDIT_PROJECT')
                            );
                        },
                    ],
                    [
                        'route' => 'project_delete',
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
                                $this->authorizationChecker->isGranted('ROLE_DELETE_PROJECT')
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
        return 'AppBundle\Entity\Project';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'project_datatable';
    }
}
