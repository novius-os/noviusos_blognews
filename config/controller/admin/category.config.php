<?php
/**
 * NOVIUS OS - Web OS for digital communication
 *
 * @copyright  2011 Novius
 * @license    GNU Affero General Public License v3 or (at your option) any later version
 *             http://www.gnu.org/licenses/agpl-3.0.html
 * @link http://www.novius-os.org
 */

return array (
    'controller_url'  => 'admin/{{application_name}}/category',
    'model' => '{{namespace}}\\Model_Category',
    'messages' => array(
        'successfully added' => __('Category successfully added.'),
        'successfully saved' => __('Category successfully saved.'),
        'successfully deleted' => __('The category has successfully been deleted!'),
        'you are about to delete, confim' => __('You are about to delete the category <span style="font-weight: bold;">":title"</span>. Are you sure you want to continue?'),
        'you are about to delete' => __('You are about to delete the category <span style="font-weight: bold;">":title"</span>.'),
        'exists in multiple context' => __('This category exists in <strong>{count} contexts</strong>.'),
        'delete in the following contexts' => __('Delete this category in the following contexts:'),
        'item deleted' => __('This category has been deleted.'),
        'not found' => __('category not found'),
        'error added in context not parent' => __('This category cannot be added {context} because its {parent} is not available in this context yet.'),
        'error added in context' => __('This category cannot be added {context}.'),
        'item inexistent in context yet' => __('This category has not been added in {context} yet.'),
        'add an item in context' => __('Add a new category in {context}'),
    ),
    'tab' => array(
        'labels' => array(
            'insert' => __('Add a category'),
            'blankSlate' => __('Translate a category'),
        ),
    ),
    'layout' => array(
        'title' => 'cat_title',
        'large' => true,
        'content' => array(
            'expander' => array(
                'view' => 'nos::form/expander',
                'params' => array(
                    'title'   => __('Properties'),
                    'nomargin' => true,
                    'options' => array(
                        'allowExpand' => false,
                    ),
                    'content' => array(
                        'view' => 'nos::form/fields',
                        'params' => array(
                            'begin' => '<table class="fieldset">',
                            'fields' => array(
                                'cat_virtual_name',
                                'cat_parent_id'
                            ),
                            'end' => '</table>',
                        ),
                    ),
                ),
            ),
        ),
        'save' => 'save',
    ),
    'fields' => array(
        'cat_title' => array (
            'label' => __('category'),
            'form' => array(
                'type' => 'text',
            ),
            'validation' => array(
                'required',
                'min_length' => array(2),
            ),
        ),
        'cat_virtual_name' => array(
            'label' => __('URL: '),
            'renderer' => 'Nos\Renderer_Virtualname',
            'validation' => array(
                'required',
                'min_length' => array(2),
            ),
            'template' => "\t\t<tr><th class=\"{error_class}\">{label}{required}</th><td class=\"{error_class}\">{field} {use_title_checkbox} {error_msg}</td></tr>\n"
        ),
        'cat_parent_id' => array(
            'label' => __('Location: '),
            'renderer' => 'Nos\BlogNews\Renderer_Category_Selector',
            'renderer_options' => array(
                'width'                 => '100%',
                'height'                => '350px',
                'namespace'             => '{{namespace}}',
                'sortable'              => true,
                'application_name'      => '{{application_name}}'
            ),
        ),
        'save' => array(
            'label' => '',
            'form' => array(
                'type' => 'submit',
                'tag' => 'button',
                'value' => __('Save'),
                'class' => 'primary',
                'data-icon' => 'check',
            ),
        ),
    ),
);
