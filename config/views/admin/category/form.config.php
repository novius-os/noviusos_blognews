<?php
return array(
    'title' => 'cat_title',
    //'id' => 'blog_id',
    'large' => false,

    'save' => 'save',

    'content' => array(
        View::forge('form/expander', array(
            'title'   => __('Content'),
            'nomargin' => false,
            'content' => '',
            'options' => array(
                'allowExpand' => false,
            ),
        ), false),
    ),
    'menu' => array(
        // user_fullname is not a real field in the database
        __('URL (category address)') => array('cat_virtual_name'),

    ),
);
