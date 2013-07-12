<?php

Nos\I18n::current_dictionary(array('nos::common', 'noviusos_blognews::common'));

return array(
    'all' => array(
        'view' => 'nos::form/accordion',
        'params' => array(
            'accordions' => array(
                'general' => array(
                    'title' => __('Permissions for this application'),
                    'view' => 'noviusos_blognews::admin/permissions/post_and_category',
                    'params' => array(
                        'application' => '{{application_name}}',
                    ),
                ),
                'other_authors_post' => array(
                    'title' => __("Other authors’ posts"),
                    'view' => 'noviusos_blognews::admin/permissions/other_authors',
                    'params' => array(
                        'application' => '{{application_name}}',
                    ),
                ),
            ),
        ),
    ),
);
