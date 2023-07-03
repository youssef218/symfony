<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $configurator): void {
    $configurator->extension('knp_paginator', [
        'page_range' => 5,
        'default_options' => [
            'page_name' => 'page',
            'sort_field_name' => 'sort',
            'sort_direction_name' => 'direction',
            'distinct' => true,
            'filter_field_name' => 'filterField',
            'filter_value_name' => 'filterValue',
        ],
        'template' => [
            'pagination' => '@KnpPaginator/Pagination/bootstrap_v5_pagination.html.twig',
            'sortable' => '@KnpPaginator/Pagination/sortable_link.html.twig',
            'filtration' => '@KnpPaginator/Pagination/filtration.html.twig',
        ],
    ]);
};
