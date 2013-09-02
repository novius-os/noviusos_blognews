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
    'link_on_title' => false,
    'item_per_page' => 10,
    'order_by'    => array('post_created_at' => 'DESC', 'post_id' => 'DESC'),
    'views' => array(
        'list' => 'noviusos_blognews::front/post/list',
        'item' => 'noviusos_blognews::front/post/show'
    ),
    'rss_cache_duration' => 60 * 30,
);
