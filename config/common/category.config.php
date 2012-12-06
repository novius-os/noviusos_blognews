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
        'cat_title' => array(
            'title' => __('Category'),
        ),
    ),
    'i18n' => array(
        // Crud
        'successfully added' => __('Category successfully added.'),
        'successfully saved' => __('Category successfully saved.'),
        'successfully deleted' => __('The category has successfully been deleted!'),

        // General errors
        'item deleted' => __('This category has been deleted.'),
        'not found' => __('Category not found'),

        // Blank slate
        'error added in context' => __('This category cannot be added {context}.'),
        'item inexistent in context yet' => __('This category has not been added in {context} yet.'),
        'add an item in context' => __('Add a new category in {context}'),

        // Deletion popup
        'delete an item' => __('Delete a category'),
        'you are about to delete' => __('You are about to delete the category <span style="font-weight: bold;">":title"</span>.'),
        'you are about to delete, confim' => __('You are about to delete the category <span style="font-weight: bold;">":title"</span>. Are you sure you want to continue?'),
        'exists in multiple context' => __('This category exists in <strong>{count} contexts</strong>.'),
        'delete in the following contexts' => __('Delete this category in the following contexts:'),
    ),
);