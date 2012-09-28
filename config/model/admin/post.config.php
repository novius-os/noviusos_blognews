<?php
/**
 * NOVIUS OS - Web OS for digital communication
 *
 * @copyright  2011 Novius
 * @license    GNU Affero General Public License v3 or (at your option) any later version
 *             http://www.gnu.org/licenses/agpl-3.0.html
 * @link http://www.novius-os.org
 */
return array(
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
        'author' => array(
            'headerText'    => __('Author'),
            'search_relation' => 'author',
            'search_column' => 'author.user_name',
            'value' => function ($item) {
                return $item->author->fullname();
            },
        ),
        'post_created_at' => array(
            'headerText'    => __('Creation date'),
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
    /*
    'dataset' => array(
        'title' => array(
            'column'        => 'title',
            'headerText'    => __('Title')
        ),
        'lang' => true,
        'author' => array(
            'search_relation' => 'author',
            'search_column' => 'author.user_name',
            'value' => function ($item) {
                return $item->author->fullname();
            },
        ),
        'post_created_at' => array(
            'search_column' => 'post_created_at',
            'dataType' => 'datetime',
            'value' => function ($item) {
                return \Date::create_from_string($item->post_created_at, 'mysql')->format('%m/%d/%Y %H:%M:%S'); //%m/%d/%Y %H:%i:%s
            },
        ),
        'published' => true,
        'preview_url' => array(
            'value' => function($item) {
                return $item->preview_url();
            },
            'visible' => false
        ),
    ),
    */
);
