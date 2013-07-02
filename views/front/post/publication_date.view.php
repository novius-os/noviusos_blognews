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

    if (isset($blognews_config['publication_date']['front']) && isset($blognews_config['publication_date']['front']['format'])) {
        $date_format = $blognews_config['publication_date']['front']['format'];
        // Deprecated
        $main_controller = Nos\Nos::main_controller();
        if (!$main_controller->getCustomData('noviusos_blognews_deprecated_date_format', false)) {
            $main_controller->setCustomData('noviusos_blognews_deprecated_date_format', true);
            \Log::warning("Deprecated blognews enhancer don't use the publication_date.front.format anymore, please translate 'PUBLICATION_DATE_FORMAT' key instead.");
        }
    } else {
        Nos\I18n::current_dictionary('noviusos_blognews::front');
        $date_format = __('PUBLICATION_DATE_FORMAT');
    }
    ?>
    <div class="blognews_date">
    <?= e(Date::forge(strtotime($item->post_created_at))->format($date_format)); ?>
    </div>
    <?php
}
