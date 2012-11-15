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
        'models' => array('{{namespace}}\Model_Post', '{{namespace}}\Model_Category')
    ),
    'query' => array(
        'model' => '{{namespace}}\Model_Post',
        'order_by' => array('post_created_at' => 'DESC'),
        'limit' => 20,
    ),
    'search_text' => 'post_title',
);
