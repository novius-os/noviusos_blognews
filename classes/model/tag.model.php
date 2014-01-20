<?php
/**
 * NOVIUS OS - Web OS for digital communication
 *
 * @copyright  2011 Novius
 * @license    GNU Affero General Public License v3 or (at your option) any later version
 *             http://www.gnu.org/licenses/agpl-3.0.html
 * @link http://www.novius-os.org
 */

namespace Nos\BlogNews;

class Model_Tag extends \Nos\Orm\Model
{
    protected static $_primary_key = array('tag_id');
    protected static $_table_name = '';

    protected static $_title_property = 'tag_label';
    protected static $_properties = array(
        'tag_id' => array(
            'default' => null,
            'data_type' => 'int unsigned',
            'null' => false,
        ),
        'tag_label' => array(
            'default' => null,
            'data_type' => 'varchar',
            'null' => false,
        ),
    );

    protected static $_has_one = array();
    protected static $_belongs_to  = array();
    protected static $_has_many  = array();
    protected static $_many_many = array();
    protected static $_twinnable_has_one = array();
    protected static $_twinnable_has_many = array();
    protected static $_twinnable_belongs_to = array();
    protected static $_twinnable_many_many = array();

    protected static $_behaviours = array(
        'Nos\Orm_Behaviour_Urlenhancer' => array(
            'enhancers' => array(),
        ),
    );

    public static function _init()
    {
        \Nos\I18n::current_dictionary(array('noviusos_blognews::common'));
        static::$_behaviours['Nos\Orm_Behaviour_Sharable'] = array(
            'data' => array(
                \Nos\DataCatcher::TYPE_TITLE => array(
                    'value' => 'tag_label',
                    'useTitle' => __('Use tag label'),
                ),
                \Nos\DataCatcher::TYPE_URL => array(
                    'value' =>
                        function ($tag) {
                            return $tag->url_canonical();
                        },
                    'options' =>
                        function ($tag) {
                            return $tag->urls();
                        },
                ),
            ),
        );
    }

    public static function relations($specific = false)
    {
        $class = get_called_class();
        $post_class = \Inflector::get_namespace($class).'Model_Post';
        list($post_pk) = $post_class::primary_key();

        // @todo: should be loaded on config somewhere maybe
        $table_prefix_pos = strrpos(static::$_table_name, '_');
        $table_prefix = substr(static::$_table_name, 0, $table_prefix_pos);

        static::$_many_many['posts'] = array(
            'table_through' => $table_prefix.'_tag_post',
            'key_from' => static::$_primary_key[0],
            'key_through_from' => static::$_primary_key[0],
            'key_through_to' => $post_pk,
            'key_to' => $post_pk,
            'cascade_save' => true,
            'cascade_delete' => false,
            'model_to'       => $post_class,
        );

        return parent::relations($specific);
    }
}
