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
            'title' => __('Tags'),
        ),
    ),
    'title_property' => 'tag_label',
    'i18n' => array(
        // Crud
        'notification item deleted' => __('The tag has been deleted.'),

        // General errors
        'notification item does not exist anymore' => __('This tag doesn’t exist any more. It has been deleted.'),
        'notification item not found' => __('We cannot find this tag.'),

        // Deletion popup
        'deleting item title' => __('Deleting the tag ‘{{title}}’'),

        # Delete action's labels
        'deleting button N items' => n__(
            'Yes, delete this tag',
            'Yes, delete these {{count}} tags'
        ),
    ),
    'actions' => array(
        '{{namespace}}\Model_Tag.edit' => false,
        '{{namespace}}\Model_Tag.visualise' => false,
    ),
);
