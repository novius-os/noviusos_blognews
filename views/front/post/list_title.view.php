<?php
/**
 * NOVIUS OS - Web OS for digital communication
 *
 * @copyright  2011 Novius
 * @license    GNU Affero General Public License v3 or (at your option) any later version
 *             http://www.gnu.org/licenses/agpl-3.0.html
 * @link http://www.novius-os.org
 */

$title = null;

\Nos\I18n::current_dictionary(array('noviusos_blognews::front'));

if ($type == 'tag') {
    $title = strtr(__('Tag: {{tag}}'), array('{{tag}}' => $item->tag_label));
    $link  = $item->url();
}
if ($type == 'category') {
    $title = strtr(__('Category: {{category}}'), array('{{category}}' => $item->cat_title));
    $link  = $item->url();
}
if ($title !== null) {
    echo '<h1><a href="'.$link.'">'.e($title).'</a></h1>';
}
