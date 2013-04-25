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
    'model' => '\Nos\User\Model_User',
    'query' => array(
        'order_by' => \DB::expr('CONCAT(COALESCE(user_firstname, ""), user_name)'),
    ),
    'input' => array(
        'key'   => 'post_author_id',
    ),
    'appdesk' => array(
        'label' => __('Authors'),
    ),
    'data_mapping' => array(
        'fullname'=> array(
            'title' => __('Authors')
        )
    )
);
