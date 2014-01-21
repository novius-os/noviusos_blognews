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
    $title  = $item->htmlAnchor(array(
        'text' => e(strtr(__('Tag: {{tag}}'), array('{{tag}}' => $item->tag_label)))
    ));
}
if ($type == 'category') {
    $title  = $item->htmlAnchor(array(
        'text' => e(strtr(__('Category: {{category}}'), array('{{category}}' => $item->cat_title)))
    ));
}
if ($type == 'author') {
    $title  = $item->htmlAnchor(array(
        'text' => e(strtr(__('Author: {{author}}'), array('{{author}}' => $item->fullname())))
    ));
}
if ($title !== null) {
    echo '<h1>'.$title.'</h1>';
}
