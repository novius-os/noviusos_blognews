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
        'successfully added' => __('Done! The category has been added.'),
        'successfully deleted' => __('The category has been deleted.'),

        // General errors
        'item deleted' => __('This category doesn’t exist any more. It has been deleted.'),
        'not found' => __('We cannot find this category.'),

        // Blank slate
        'error added in context not parent' => __('We’re afraid this category cannot be added in {{context}} because its <a>parent</a> is not available in this context yet.'), #wtf two strings needed here (this context / this language)

        // Deletion popup
        'delete an item' => __('Deleting the category ‘{{title}}’'),
        'you are about to delete, confim' => __('Last chance, there’s no undo. Do you really want to delete this category?'),
        'exists in multiple context' => __('This category exists in <strong>{{count}} contexts</strong>.'),
        'delete in the following contexts' => __('Delete this category in the following contexts:'),
        'item has 1 sub-item' => __('This category has <strong>1 sub-category</strong>.'),
        'item has multiple sub-items' => __('This category has <strong>{{count}} sub-category</strong>.'),
        'yes delete sub-items' => __('Yes, I want to delete this category and all of its {{count}} sub-category.'),
        'confirm deletion wrong_confirmation' => __('We cannot delete this category as the number of sub-items you’ve entered is wrong. Please amend it.'),
    ),
);