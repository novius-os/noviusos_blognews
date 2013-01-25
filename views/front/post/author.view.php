<?php
/**
 * NOVIUS OS - Web OS for digital communication
 *
 * @copyright  2011 Novius
 * @license    GNU Affero General Public License v3 or (at your option) any later version
 *             http://www.gnu.org/licenses/agpl-3.0.html
 * @link http://www.novius-os.org
 */

\Nos\I18n::current_dictionary('noviusos_blognews::common');

if ($blognews_config['authors']['enabled'] && $blognews_config['authors']['show']) {
    \Nos\I18n::current_dictionary(array('noviusos_blognews::common'));
    ?>
    <div class="blognews_author">
        <?= e(strtr(__('Author: {{author}}'), array('{{author}}' => !empty($item->author) ? $item->author->fullname() : $item->post_author_alias))) ?>
    </div>
    <?php
}
