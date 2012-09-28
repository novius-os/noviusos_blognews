<?php
return array(
    'model' => '{{namespace}}\Model_Category',
    'dataset' => array(
        'title' => array(
            'column' => 'cat_title',
            'headerText' => __('Category')
        ),
    ),
    'input' => array(
        'key' => 'categories.cat_id'
    ),
    /*
    'models' => array(
        array(
            'model' => '{{namespace}}\Model_Category',
            'order_by' => 'cat_sort',
            'childs' => array('{{namespace}}\Model_Category'),
            'dataset' => array(
                'id' => 'cat_id',
                'title' => 'cat_title',
            ),
        ),
    ),
    'roots' => array(
        array(
            'model' => '{{namespace}}\Model_Category',
            'where' => array(array('cat_parent_id', 'IS', \DB::expr('NULL'))),
            'order_by' => 'cat_sort',
        ),
    ),
    */
);
