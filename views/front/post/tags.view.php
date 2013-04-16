<?php
/**
 * NOVIUS OS - Web OS for digital communication
 *
 * @copyright  2011 Novius
 * @license    GNU Affero General Public License v3 or (at your option) any later version
 *             http://www.gnu.org/licenses/agpl-3.0.html
 * @link http://www.novius-os.org
 */

if ($blognews_config['tags']['enabled'] && $blognews_config['tags']['show']) {
    \Nos\I18n::current_dictionary(array('noviusos_blognews::front'));
    ?>
    <div class="blognews_tags">
    <?php
    if (count($item->tags) > 0) {
        $tags = array();
        foreach ($item->tags as $tag) {
            $tags[$tag->url()] = $tag->tag_label;
        }
        $tags_str = implode(', ', array_map(function($href, $title) {
            return '<a href="'.$href.'">'.e($title).'</a>';
        }, array_keys($tags), array_values($tags)));
        echo strtr(__('Tags: {{tags}}'), array('{{tags}}' => $tags_str));
    }
    ?>
    </div>
    <?php
}
