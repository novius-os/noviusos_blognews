<?php
/**
 * NOVIUS OS - Web OS for digital communication
 *
 * @copyright  2011 Novius
 * @license    GNU Affero General Public License v3 or (at your option) any later version
 *             http://www.gnu.org/licenses/agpl-3.0.html
 * @link http://www.novius-os.org
 */

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
        'name' => __('Blog'),
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
