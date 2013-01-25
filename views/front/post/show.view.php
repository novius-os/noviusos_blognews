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
<div class="blognews_post blognews_post_show">
    <?= \View::forge('noviusos_blognews::front/post/stats', array('item' => $item), false) ?>

    <?= \View::forge('noviusos_blognews::front/post/thumbnail', array('item' => $item), false) ?>
    <?= \View::forge('noviusos_blognews::front/post/title', array('item' => $item, 'context' => 'show'), false) ?>
    <?= \View::forge('noviusos_blognews::front/post/publication_date', array('item' => $item), false) ?>

    <?= \View::forge('noviusos_blognews::front/post/summary', array('item' => $item), false) ?>
    <?= \View::forge('noviusos_blognews::front/post/content', array('item' => $item), false) ?>

    <?= \View::forge('noviusos_blognews::front/post/tags', array('item' => $item), false) ?>
    <?= \View::forge('noviusos_blognews::front/post/categories', array('item' => $item), false) ?>
<?php
if ($blognews_config['comments']['enabled']) {
    ?>
    <div class="blognews_comments" id="comments">
        <?= \View::forge('noviusos_blognews::front/comment/nb', array('item' => $item), false) ?>
    <?php
    if ($blognews_config['comments']['show']) {
        ?>
            <?= \View::forge('noviusos_comments::front/list', array('comments' => $item->comments), true) ?>
        <?php
    }
    if ($blognews_config['comments']['can_post']) {
        ?>
            <?= \View::forge('noviusos_comments::front/form', array('add_comment_success' => $add_comment_success, 'use_recaptcha' => $blognews_config['comments']['use_recaptcha']), true) ?>
        <?php
    }
    ?>
    </div>
    <?php
}
?>
</div>
