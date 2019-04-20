<?php

namespace AppBundle\Datatables;

use Sg\DatatablesBundle\Datatable\View\AbstractDatatableView;
use Sg\DatatablesBundle\Datatable\View\Style;

/**
 * Abstract datatable.
 *
 * @author Pierre Jolly <pie.jolly@gmail.com>
 */
abstract class AbstractDatatable extends AbstractDatatableView
{
    /**
     * Get default features configuration.
     *
     * @return array
     */
    public function getDefaultFeatures()
    {
        return [
            'auto_width'      => true,
            'defer_render'    => false,
            'info'            => true,
            'jquery_ui'       => false,
            'length_change'   => true,
            'ordering'        => true,
            'paging'          => true,
            'processing'      => true,
            'scroll_x'        => false,
            'scroll_y'        => '',
            'searching'       => true,
            'state_save'      => false,
            'delay'           => 0,
            'extensions'      => [],
            'highlight'       => false,
            'highlight_color' => 'red',
        ];
    }

    /**
     * Get default ajax configuration.
     *
     * @param string $routeName
     * @return array
     */
    public function getDefaultAjax($routeName)
    {
        return [
            'url'      => $this->router->generate($routeName),
            'type'     => 'GET',
            'pipeline' => 0,
        ];
    }

    /**
     * Get default options configuration.
     *
     * @return array
     */
    public function getDefaultOptions()
    {
        return [
            'display_start'                 => 0,
            'defer_loading'                 => -1,
            'dom'                           => 'lfrtip',
            'length_menu'                   => [10, 25, 50, 100],
            'order_classes'                 => true,
            'order'                         => [[0, 'asc']],
            'order_multi'                   => true,
            'page_length'                   => 25,
            'paging_type'                   => Style::FULL_NUMBERS_PAGINATION,
            'renderer'                      => '',
            'scroll_collapse'               => false,
            'search_delay'                  => 0,
            'state_duration'                => 7200,
            'stripe_classes'                => [],
            'class'                         => Style::BOOTSTRAP_3_STYLE,
            'individual_filtering'          => true,
            'individual_filtering_position' => 'head',
            'use_integration_options'       => true,
            'force_dom'                     => false,
            'row_id'                        => 'id',
        ];
    }
}
