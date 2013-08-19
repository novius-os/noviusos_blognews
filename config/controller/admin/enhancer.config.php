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
    'fields' => array(
        'item_per_page' => array(
            'label' => __('Posts per page:'),
            'form' => array(
                'value' => '10',
            ),
        ),
        'cat_id' => array(
            'label' => __('Category:'),
            'renderer' => 'Nos\BlogNews\Renderer_Selector',
            'renderer_options' => array(
                'width'       => '260px',
                'height'      => '200px',
                'context'     => '{{_parent_context}}',
                'selected'    => null,
                'multiple'    => '0',
                'inspector'   => 'admin/{{application_name}}/inspector/category',
                'model'       => '{{namespace}}Model_Category',
                'main_column' => 'cat_title',
            ),
            'template' => '<div style="margin-bottom: 0.5em;">{label} {field} <p>'.__('Leave blank to select all categories').'</p></div>',
        ),
        'link_on_title' => array(
            'label' => __('Clickable post titles'),
            'template' => '<p>{field} {label}</p>',
            'form' => array(
                'type' => 'checkbox',
                'value' => 1,
                'empty' => 0,
            ),
        ),
    ),
);
