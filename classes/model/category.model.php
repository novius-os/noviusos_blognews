<?php

namespace Nos\BlogNews;

class Model_Category extends \Nos\Orm\Model
{
    protected static $_primary_key = array('category_id');
    protected static $_table_name = 'blognews_category';

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
            'events' => array('after_sort'),
            'sort_property' => 'cat_sort',
        ),
        'Nos\Orm_Behaviour_Url' => array(
            'urls' => array(),
        ),
        'Nos\Orm_Behaviour_Virtualname' => array(
            'events' => array('before_save', 'after_save'),
            'virtual_name_property' => 'cat_virtual_name',
        ),
        'Nos\Orm_Behaviour_Translatable' => array(
            'events' => array('before_insert', 'after_insert', 'before_save', 'after_delete', 'change_parent'),
            'lang_property'      => 'cat_lang',
            'common_id_property' => 'cat_lang_common_id',
            'single_id_property' => 'cat_lang_single_id',
            'invariant_fields'   => array('cat_parent_id', 'cat_sort'),
        ),
    );

    protected static $_has_many  = array();
    protected static $_belongs_to = array();
    protected static $_many_many = array();

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

    public static function get_primary_key() {
        return static::$_primary_key;
    }

    public static function get_table_name() {
        return static::$_table_name;
    }

    public function get_url($params = array()) {
        $url = isset($params['urlPath']) ? $params['urlPath'] : \Nos\Nos::main_controller()->getEnhancedUrlPath();
        $page = isset($params['page']) ? $params['page'] : 1;

        $titre = $this->cat_virtual_name;

        return $url.'category/'.$titre.($page > 1 ? '/'.$page : '').'.html';
    }
}