<?php

namespace Nos\BlogNews;

class Model_Category extends \Nos\Orm\Model
{
    protected static $_primary_key = array('category_id');
    protected static $_table_name = 'blognews_category';

    protected static $_behaviours = array(
        'Nos\Orm_Behaviour_Tree' => array(
            'events' => array('before_query', 'after_delete'),
            'parent_relation' => 'parent',
            'children_relation' => 'children',
        ),
        'Nos\Orm_Behaviour_Sortable' => array(
            'events' => array('after_sort'),
            'sort_property' => 'sort',
        ),
        'Nos\Orm_Behaviour_Url' => array(),
    );

    protected static $_has_many  = array();
    protected static $_belongs_to = array();
    protected static $_many_many = array();

    public static function _init() {
        static::$_behaviours['Nos\Orm_Behaviour_Translatable'] = array(
            'events' => array('before_insert', 'after_insert', 'before_save', 'after_delete', 'before_change_parent', 'after_change_parent'),
            'lang_property'      => static::get_prefix().'lang',
            'common_id_property' => static::get_prefix().'lang_common_id',
            'single_id_property' => static::get_prefix().'lang_single_id',
            'invariant_fields'   => array(static::get_prefix().'parent_id',static::get_prefix().'sort'),
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


    public function & get($property)
    {
        if (array_key_exists(static::get_prefix().$property, static::properties()))
        {
           $property = static::get_prefix().$property;
        }
        return parent::get($property);
    }

    public function set($property, $value)
    {
        if (array_key_exists(static::get_prefix().$property, static::properties()))
        {
           $property = static::get_prefix().$property;
        }
        return parent::set($property,$value);
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

        $titre = $this->virtual_name;

        return $url.'category/'.$titre.($page > 1 ? '/'.$page : '').'.html';
    }
}