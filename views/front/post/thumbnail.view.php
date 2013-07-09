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

        $link_url   = $item->url();
        $link_class = 'blognews_thumbnail_link';
    } else {
        $print_link = \Arr::get($blognews_config, 'thumbnail.front.item.link_to_fullsize', true);
        $thumbnail_width = \Arr::get($blognews_config, 'thumbnail.front.item.max_width', 200);
        $thumbnail_height = \Arr::get($blognews_config, 'thumbnail.front.list.max_height', $thumbnail_width);

        $link_url   = $item->medias->thumbnail->url();
        $link_class = 'blognews_thumbnail_link_fullsize';
    }

    $print_link and print '<a href="'.$link_url.'" class="'.$link_class.'">';
    echo $item->medias->thumbnail->getImgTagResized($thumbnail_width, $thumbnail_height, array(
        'alt' => $item->post_title,
        'class' => 'blognews_thumbnail',
    ));
    $print_link and print '</a>';
}
