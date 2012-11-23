<?php

namespace Nos\BlogNews;

class Model_Category extends \Nos\Orm\Model
{
    protected static $_primary_key = array('category_id');
    protected static $_table_name = '';

    protected static $_observers = array(
        'Orm\Observer_CreatedAt' => array(
            'events' => array('before_insert'),
            'mysql_timestamp' => true,
            'property'=>'cat_created_at'
        ),
        'Orm\Observer_UpdatedAt' => array(
            'events' => array('before_save'),
            'mysql_timestamp' => true,
            'property'=>'cat_updated_at'
        )
    );

    protected static $_behaviours = array(
        'Nos\Orm_Behaviour_Tree' => array(
            'events' => array('before_query', 'after_delete'),
            'parent_relation' => 'parent',
            'children_relation' => 'children',
        ),
        'Nos\Orm_Behaviour_Sortable' => array(
            'events' => array('before_insert', 'before_save', 'after_save'),
            'sort_property' => 'cat_sort',
        ),
        'Nos\Orm_Behaviour_Urlenhancer' => array(
            'enhancers' => array(),
        ),
        'Nos\Orm_Behaviour_Virtualname' => array(
            'events' => array('before_save', 'after_save'),
            'virtual_name_property' => 'cat_virtual_name',
        ),
        'Nos\Orm_Behaviour_Translatable' => array(
            'events' => array('before_insert', 'after_insert', 'before_save', 'after_delete', 'change_parent'),
            'lang_property'      => 'cat_lang',
            'common_id_property' => 'cat_lang_common_id',
            'is_main_property' => 'cat_lang_is_main',
            'invariant_fields'   => array(),
        ),
    );

    protected static $_has_many  = array();
    protected static $_belongs_to = array();
    protected static $_many_many = array();

    public static function _init()
    {
        static::$_behaviours['Nos\Orm_Behaviour_Sharable'] = array(
            'data' => array(
                \Nos\DataCatcher::TYPE_TITLE => array(
                    'value' => 'cat_title',
                    'useTitle' => __('Use category title'),
                ),
                \Nos\DataCatcher::TYPE_URL => array(
                    'value' => function($category) {
                        return $category->url_canonical();
                    },
                    'options' => function($category) {
                        $urls = array();
                        foreach ($category->urls() as $possible) {
                            $urls[$possible['page_id'].'::'.$possible['itemUrl']] = $possible['url'];
                        }

                        return $urls;
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
