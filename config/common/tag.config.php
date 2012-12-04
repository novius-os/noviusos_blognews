<?php
/**
 * NOVIUS OS - Web OS for digital communication
 *
 * @copyright  2011 Novius
 * @license    GNU Affero General Public License v3 or (at your option) any later version
 *             http://www.gnu.org/licenses/agpl-3.0.html
 * @link http://www.novius-os.org
 */

\Nos\I18n::current_dictionary(array('noviusos_blognews::common'));

return array(
    'data_mapping' => array(
        'tag_label' => array(
            'headerText' => __('Tag')
        ),
    ),
    'actions' => array(
        '{{namespace}}\Model_Tag.edit' => false,
        '{{namespace}}\Model_Tag.visualise' => false,
    )
);