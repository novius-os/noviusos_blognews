<?php
/**
 * NOVIUS OS - Web OS for digital communication
 *
 * @copyright  2011 Novius
 * @license    GNU Affero General Public License v3 or (at your option) any later version
 *             http://www.gnu.org/licenses/agpl-3.0.html
 * @link http://www.novius-os.org
 */

$tag = empty($context) || $context != 'show' ? 'h2' : 'h1';

// Always display the link in the list
if ($tag == 'h1' && empty($enhancer_args['link_on_title'])) {
    ?>

    <<?= $tag ?> class="blognews_title">
        <?= e($item->post_title) ?>
    </<?= $tag ?>>

    <?php
} else {
    ?>

    <<?= $tag ?> class="blognews_title">
        <a href="<?= $item->url() ?>">
            <?= e($item->post_title) ?>
        </a>
    </<?= $tag ?>>

    <?php
}

