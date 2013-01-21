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
        'related' => array('linked_medias'),
        'order_by' => array('post_created_at' => 'DESC'),
        'limit' => 20,
    ),
    'search_text' => 'post_title',
    'thumbnails' => true,
);
