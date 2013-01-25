<?php
/**
 * NOVIUS OS - Web OS for digital communication
 *
 * @copyright  2011 Novius
 * @license    GNU Affero General Public License v3 or (at your option) any later version
 *             http://www.gnu.org/licenses/agpl-3.0.html
 * @link http://www.novius-os.org
 */

if ($blognews_config['publication_date']['enabled'] && $blognews_config['publication_date']['show']) {
    ?>
    <div class="blognews_date">
    <?= e(Date::forge(strtotime($item->post_created_at))->format($blognews_config['publication_date']['front']['format'])); ?>
    </div>
    <?php
}
