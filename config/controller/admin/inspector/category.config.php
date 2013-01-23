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

return array(
    'model' => '{{namespace}}\Model_Category',
    'input' => array(
        'key' => 'categories.cat_id'
    ),
    'root_node' => array(
        'cat_title' => __('Root'),
    ),
);
