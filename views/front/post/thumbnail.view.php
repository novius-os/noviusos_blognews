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
    ?>
    <img src="<?= $item->medias->thumbnail->get_public_path_resized(200) ?>" class="blognews_thumbnail" />
    <?php
}
