<?php
/**
 * NOVIUS OS - Web OS for digital communication
 *
 * @copyright  2011 Novius
 * @license    GNU Affero General Public License v3 or (at your option) any later version
 *             http://www.gnu.org/licenses/agpl-3.0.html
 * @link http://www.novius-os.org
 */

if ($app_config['categories']['enabled'] && $app_config['categories']['show']) {
    \Nos\I18n::current_dictionary(array('noviusos_blognews::common'));
    ?>
    <div class="blognews_categories">
    <?php
    if (count($item->categories) > 0) {
        $categories = array();
        foreach ($item->categories as $category) {
            $categories[$category->url()] = $category->cat_title;
        }
        $categories_str = implode(', ', array_map(function($href, $title) {
            return '<a href="'.$href.'">'.e($title).'</a>';
        }, array_keys($categories), array_values($categories)));
        echo Str::tr(e(__('Categories: :categories')), array('categories' => $categories_str));
    }
    ?>
    </div>
    <?php
}
