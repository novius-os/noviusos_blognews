<?php
/**
 * NOVIUS OS - Web OS for digital communication
 *
 * @copyright  2011 Novius
 * @license    GNU Affero General Public License v3 or (at your option) any later version
 *             http://www.gnu.org/licenses/agpl-3.0.html
 * @link http://www.novius-os.org
 */
?>
<div class="<?= $blognews_config['application_name'] ?> noviusos_enhancer blognews_posts_list">
    <?= \View::forge('noviusos_blognews::front/post/list_title', array('type' => $type, 'item' => $item), false) ?>
    <div class="blognews_list">
<?php
foreach ($posts as $post) {
    echo \View::forge('noviusos_blognews::front/post/item', array('item' => $post), false);
}
?>
    </div>
<?php
if ($pagination) {
    echo \View::forge('noviusos_blognews::front/post/pagination', array(
        'type' => $type,
        'item' => $item,
        'pagination' => $pagination,
        'pagination_callback' => $pagination_callback,
    ), false);
}
?>
</div>
