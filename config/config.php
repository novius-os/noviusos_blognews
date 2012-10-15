<?php
$ret = array(
    'namespace' => '{{namespace}}',
    'application_name' => '{{application_name}}',
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
        ),
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
            '{{namespace}}\Model_Tag.edit' => false,
        ),
    ),
);


return $ret;
