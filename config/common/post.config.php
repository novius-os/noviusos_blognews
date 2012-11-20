<?php
return array(
    'data_mapping' => array(
        'post_title' => array(
            'title'    => __('Title'),
        ),
        'context' => true,
        'author->user_name' => array(
            'title'         => __('Author'),
            'value' => function ($item) {
                return $item->author->fullname();
            },
        ),
        'publication_status' => true,
        'post_created_at' => array(
            'title'    => __('Date'),
            'value' => function ($item) {
                return \Date::create_from_string($item->post_created_at, 'mysql')->format('%m/%d/%Y %H:%M:%S'); //%m/%d/%Y %H:%i:%s
            },
            'dataType' => 'datetime',
        ),
        'preview_url' => array(
            'value' => function($item) {
                return $item->preview_url();
            },
        ),
    ),
);