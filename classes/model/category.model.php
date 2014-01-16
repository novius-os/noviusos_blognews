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

class Model_Category extends \Nos\Orm\Model
{
    protected static $_primary_key = array('category_id');
    protected static $_table_name = '';

    protected static $_title_property = 'cat_title';
    protected static $_properties = array(
        'cat_id' => array(
            'default' => null,
            'data_type' => 'int',
            'null' => false,
        ),
        'cat_title' => array(
            'default' => null,
            'data_type' => 'varchar',
            'null' => false,
        ),
        'cat_virtual_name' => array(
            'default' => null,
            'data_type' => 'varchar',
            'null' => false,
            'character_maximum_length' => 100,
        ),
        'cat_context' => array(
            'default' => null,
            'data_type' => 'varchar',
            'null' => false,
        ),
        'cat_context_common_id' => array(
            'default' => null,
            'data_type' => 'int',
            'null' => false,
        ),
        'cat_context_is_main' => array(
            'default' => 0,
            'data_type' => 'tinyint',
            'null' => false,
        ),
        'cat_parent_id' => array(
            'default' => null,
            'data_type' => 'int',
            'null' => true,
            'convert_empty_to_null' => true,
        ),
        'cat_sort' => array(
            'default' => null,
            'data_type' => 'float',
            'null' => true,
            'convert_empty_to_null' => true,
        ),
        'cat_created_at' => array(
            'data_type' => 'timestamp',
            'null' => false,
        ),
        'cat_updated_at' => array(
            'data_type' => 'timestamp',
            'null' => false,
        ),
        'cat_created_by_id' => array(
            'default' => null,
            'data_type' => 'int unsigned',
            'null' => true,
            'convert_empty_to_null' => true,
        ),
        'cat_updated_by_id' => array(
            'default' => null,
            'data_type' => 'int unsigned',
            'null' => true,
            'convert_empty_to_null' => true,
        ),
    );

    protected static $_observers = array(
        'Orm\Observer_CreatedAt' => array(
            'mysql_timestamp' => true,
            'property'=>'cat_created_at'
        ),
        'Orm\Observer_UpdatedAt' => array(
            'mysql_timestamp' => true,
            'property'=>'cat_updated_at'
        ),
        'Nos\Orm_Behaviour_Author' => array(
            'created_by_property' => 'cat_created_by_id',
            'updated_by_property' => 'cat_updated_by_id',
        ),
    );

    protected static $_behaviours = array(
        'Nos\Orm_Behaviour_Tree' => array(
            'parent_relation' => 'parent',
            'children_relation' => 'children',
        ),
        'Nos\Orm_Behaviour_Sortable' => array(
            'sort_property' => 'cat_sort',
        ),
        'Nos\Orm_Behaviour_Urlenhancer' => array(
            'enhancers' => array(),
        ),
        'Nos\Orm_Behaviour_Virtualname' => array(
            'virtual_name_property' => 'cat_virtual_name',
        ),
        'Nos\Orm_Behaviour_Twinnable' => array(
            'context_property'      => 'cat_context',
            'common_id_property' => 'cat_context_common_id',
            'is_main_property' => 'cat_context_is_main',
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

    public static function _init()
    {
        \Nos\I18n::current_dictionary(array('noviusos_blognews::common'));
        static::$_behaviours['Nos\Orm_Behaviour_Sharable'] = array(
            'data' => array(
                \Nos\DataCatcher::TYPE_TITLE => array(
                    'value' => 'cat_title',
                    'useTitle' => __('Use category title'),
                ),
                \Nos\DataCatcher::TYPE_URL => array(
                    'value' =>
                        function ($category) {
                            return $category->url_canonical();
                        },
                    'options' =>
                        function ($category) {
                            return $category->urls();
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
            'table_through' => $table_prefix.'_category_post',
            'key_from' => static::$_primary_key[0],
            'key_through_from' => static::$_primary_key[0],
            'key_through_to' => $post_pk,
            'key_to' => $post_pk,
            'cascade_save' => true,
            'cascade_delete' => false,
            'model_to'       => $post_class,
        );

        static::$_has_many['children'] = array(
            'key_from'       => static::$_primary_key[0], //cat_id
            'model_to'       => $class,
            'key_to'         => 'cat_parent_id', //cat_parent_id
            'cascade_save'   => false,
            'cascade_delete' => false,
        );

        static::$_belongs_to['parent'] = array(
            'key_from'       => 'cat_parent_id', //cat_parent_id
            'model_to'       => $class,
            'key_to'         => static::$_primary_key[0], //cat_id
            'cascade_save'   => false,
            'cascade_delete' => false,
        );

        return parent::relations($specific);
    }

    public static function get_primary_key()
    {
        return static::$_primary_key;
    }

    public static function get_table_name()
    {
        return static::$_table_name;
    }
}
