<?php
namespace Nos\BlogNews;

class Model_Tag extends \Nos\Orm\Model {
    protected static $_table_name = 'nos_tag';
    protected static $_primary_key = array('tag_id');

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

    function get_url($params = array()) {
        $url = isset($params['urlPath']) ? $params['urlPath'] : \Nos\Nos::main_controller()->getEnhancedUrlPath();
        $page = isset($params['page']) ? $params['page'] : 1;
        return $url.'tag/'.urlencode($this->tag_label).($page > 1 ? '/'.$page : '').'.html';
    }
}
