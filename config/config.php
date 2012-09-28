<?php
$ret = array(
    'icons' => array(
        '64' => 'TO BE DEFINED',
        '32' => 'TO BE DEFINED',
        '16' => 'TO BE DEFINED'
    ),
    'application_name' => 'TO BE DEFINED',
    'categories' => array(
        'enabled' => true,
        'show'    => true,
    ),
    'tags' => array(
        'enabled' => true,
        'show'    => true,
    ),
    'authors' => array(
        'enabled' => true,
        'show'    => true,
    ),
    'summary' => array(
        'enabled' => true,
        'show'    => true,
    ),
    'comments' => array(
        'enabled'       => true,
        'show'          => true,
        'show_nb'       => true,
        'use_recaptcha' => false,
        'can_post'      => true
    ),
    'publication_date' => array(
        'enabled' => true,
        'show'    => true,
        'front' => array(
            'format' => 'eu_full'
        )
    ),
    'application' => array(
        'actions' => array(),
        'name' => _('Blog'),
        'icons' => array(
            'large' => '',
            'medium' => '',
            'small' => '',
        ),
        'actions' => array(
            'crud' => array(
                '{{namespace}}\Model_Post',
                '{{namespace}}\Model_Category',
                '{{namespace}}\Model_Tag'
            ),
        )
    )
);


return $ret;