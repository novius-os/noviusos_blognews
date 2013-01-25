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

if ($blognews_config['comments']['enabled'] && $blognews_config['comments']['show_nb']) {
    \Nos\I18n::current_dictionary(array('noviusos_blognews::common'));
    ?>
    <div class="blognews_nb_comments">
    <?php
    if ($item->count_comments() > 0) {
        if ($item->count_comments() > 1) {
            echo e(strtr(__('{{nb}} comments'), array('{{nb}}' => $item->count_comments())));
        } else {
            echo e(__('1 comment'));
        }
    } else {
        echo e(__('No comments'));
    }
    ?>
    </div>
    <?php
}
