<?php
/**
 * NOVIUS OS - Web OS for digital communication
 *
 * @copyright  2011 Novius
 * @license    GNU Affero General Public License v3 or (at your option) any later version
 *             http://www.gnu.org/licenses/agpl-3.0.html
 * @link http://www.novius-os.org
 */

if ($blognews_config['categories']['enabled'] && $blognews_config['categories']['show']) {
    \Nos\I18n::current_dictionary(array('noviusos_blognews::front'));
    ?>
    <div class="blognews_categories">
    <?php
    if (count($item->categories) > 0) {
        $categories = array();
        foreach ($item->categories as $category) {
            $categories[] = $category->htmlAnchor();
        }
        $categories_str = implode(', ', $categories);
        echo strtr(__('Categories: {{categories}}'), array('{{categories}}' => $categories_str));
    }
    ?>
    </div>
    <?php
}
