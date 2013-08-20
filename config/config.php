<?php
/**
 * NOVIUS OS - Web OS for digital communication
 *
 * @copyright  2011 Novius
 * @license    GNU Affero General Public License v3 or (at your option) any later version
 *             http://www.gnu.org/licenses/agpl-3.0.html
 * @link http://www.novius-os.org
 */

\Nos\I18n::current_dictionary('noviusos_blognews::front');

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
        'can_post'      => true,
    ),
    'publication_date' => array(
        'enabled' => true,
        'show'    => true,
    ),
    // Sample of config to display sumarry + content in RSS
    /*'rss' => array(
        'description_template' => '<p>{{summary}}</p>{{content}}',
    ),*/
    'thumbnail' => array(
        'front' => array(
            'list' => array(
                'link_to_item' => true,
                'max_width' => 120,
                //'max_height' => 0, // 0 means same as max_width
            ),
            'item' => array(
                'link_to_fullsize' => true,
                'max_width' => 200,
                //'max_height' => 0, // 0 means same as max_width
            ),
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
