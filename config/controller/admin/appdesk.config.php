<?php
use Nos\I18n;

return array(
    'model' => '{{namespace}}\Model_Post',
    'inspectors' => array(
        'author',
        'tag',
        'category',
        'date'
    ),
    'toolbar' => array(
        'actions' => array(
            'Nos\User\Model_User.add' => false,
            '{{namespace}}\Model_Tag.add' => false,
        )
    ),
    'query' => array(
        'model' => '{{namespace}}\Model_Post',
        'order_by' => array('post_created_at' => 'DESC'),
        'limit' => 20,
    ),
    'search_text' => 'post_title',
    'dataset' => array(
        'title' => array(
            'column' => 'post_title',
            'headerText'    => __('Title')
        ),
        'context' => true,
        'author' => array(
            'headerText'    => __('Author'),
            'search_relation' => 'author',
            'search_column' => 'author.user_name',
            'value' => function ($item) {
                return $item->author->fullname();
            },
        ),
        'publication_status' => true,
        'post_created_at' => array(
            'headerText'    => __('Date'),
            'search_column' => 'post_created_at',
            'dataType' => 'datetime',
            'value' => function ($item) {
                return \Date::create_from_string($item->post_created_at, 'mysql')->format('%m/%d/%Y %H:%M:%S'); //%m/%d/%Y %H:%i:%s
            },
        ),
        'preview_url' => array(
            'value' => function($item) {
                return $item->preview_url();
            },
            'visible' => false
        ),
    ),
);
