<?php

namespace AppBundle\Datatables;

use AppBundle\Datatables\AbstractDatatable;

/**
 * Tag datatable.
 *
 * @author Pierre Jolly <pie.jolly@gmail.com>
 */
class TagDatatable extends AbstractDatatable
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
        $this->ajax->set($this->getDefaultAjax('tag_list_results'));
        $this->options->set($this->getDefaultOptions());

        $this->columnBuilder
            ->add('name', 'column', [
                'title' => 'Nom',
                'filter' => ['text', [
                    'class' => 'form-control',
                ]],
            ])
            ->add('color', 'column', [
                'title' => 'Couleur',
                'searchable' => false,
            ])
            ->add(null, 'action', [
                'title' => $this->translator->trans('datatables.actions.title'),
                'actions' => [
                    [
                        'route' => 'tag_edit',
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
                                $this->authorizationChecker->isGranted('ROLE_EDIT_TAG')
                            );
                        },
                    ],
                    [
                        'route' => 'tag_delete',
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
                                $this->authorizationChecker->isGranted('ROLE_DELETE_TAG')
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
        return 'AppBundle\Entity\Tag';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'tag_datatable';
    }
}
