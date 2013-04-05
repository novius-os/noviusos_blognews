<?php
/**
 * NOVIUS OS - Web OS for digital communication
 *
 * @copyright  2011 Novius
 * @license    GNU Affero General Public License v3 or (at your option) any later version
 *             http://www.gnu.org/licenses/agpl-3.0.html
 * @link http://www.novius-os.org
 */

if ($blognews_config['summary']['enabled'] && $blognews_config['summary']['show']) {
    ?>
    <div class="blognews_summary">
        <p><?= nl2br(e($item->post_summary)) ?></p>
    </div>
    <?php
}
