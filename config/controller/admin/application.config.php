<?php
/**
 * NOVIUS OS - Web OS for digital communication
 *
 * @copyright  2011 Novius
 * @license    GNU Affero General Public License v3 or (at your option) any later version
 *             http://www.gnu.org/licenses/agpl-3.0.html
 * @link http://www.novius-os.org
 */

return array(
    'popup' => array(
        'layout' => array(
            'view' => 'noviusos_blognews::admin/application/popup',
        ),
    ),
    'category_selector_options' => array(
        'width'                     => '260px',
        'height'                    => '200px',
        'input_name'                => 'cat_id',
        'treeOptions'               => array(
            'context'               => \Input::get('nosContext', false) ?: \Nos\Tools_Context::defaultContext(),
        ),
        'multiple'              => '1',
        'inspector'             => 'admin/{{application_name}}/inspector/category',
        'model'                 => '{{namespace}}\\Model_Category',
        'main_column'           => 'cat_title',
    )
);
