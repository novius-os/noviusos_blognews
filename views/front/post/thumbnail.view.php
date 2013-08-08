<?php
/**
 * NOVIUS OS - Web OS for digital communication
 *
 * @copyright  2011 Novius
 * @license    GNU Affero General Public License v3 or (at your option) any later version
 *             http://www.gnu.org/licenses/agpl-3.0.html
 * @link http://www.novius-os.org
 */

if (isset($item->medias->thumbnail)) {

    $context = empty($context) || $context != 'show' ? 'list' : 'item';

    if ($context == 'list') {
        $print_link     = \Arr::get($blognews_config, 'thumbnail.front.list.link_to_item', false);
        $thumbnail_width = \Arr::get($blognews_config, 'thumbnail.front.list.max_width', 120);
        $thumbnail_height = \Arr::get($blognews_config, 'thumbnail.front.list.max_height', $thumbnail_width);
    } else {
        $print_link = \Arr::get($blognews_config, 'thumbnail.front.item.link_to_fullsize', true);
        $thumbnail_width = \Arr::get($blognews_config, 'thumbnail.front.item.max_width', 200);
        $thumbnail_height = \Arr::get($blognews_config, 'thumbnail.front.list.max_height', $thumbnail_width);
    }

    $img = $item->medias->thumbnail->htmlImgResized($thumbnail_width, $thumbnail_height, array(
        'alt' => $item->post_title,
        'class' => 'blognews_thumbnail',
    ));
    if ($print_link) {
        if ($context == 'list') {
            echo $item->htmlAnchor(array(
                'class' => 'blognews_thumbnail_link',
                'text' => $img,
            ));
        } else {
            echo $item->medias->thumbnail->htmlAnchor(array(
                'class' => 'blognews_thumbnail_link_fullsize',
                'text' => $img,
            ));
        }
    } else {
        echo $img;
    }
}
