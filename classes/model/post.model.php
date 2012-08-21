<?php

namespace Nos\BlogNews;

class Model_Post extends \Nos\Orm\Model
{

    protected static $_primary_key = array('news_id');
    protected static $_table_name = 'blognews_post';

    protected static $_observers = array(
        'Orm\Observer_CreatedAt' => array(
            'events' => array('before_insert'),
            'mysql_timestamp' => true,
            'property'=>'post_created_at'
        ),
        'Orm\Observer_UpdatedAt' => array(
            'events' => array('before_save'),
            'mysql_timestamp' => true,
            'property'=>'post_updated_at'
        )
    );

    protected static $_behaviours = array(
        'Nos\Orm_Behaviour_Publishable' => array(
            'publication_bool_property' => 'post_published',
        ),
        'Nos\Orm_Behaviour_Url' => array(),
        'Nos\Orm_Behaviour_Virtualname' => array(
            'events' => array('before_save', 'after_save'),
            'virtual_name_property' => 'post_virtual_name',
        ),
        'Nos\Orm_Behaviour_Translatable' => array(
            'events' => array('before_insert', 'after_insert', 'before_save', 'after_delete', 'change_parent'),
            'lang_property'      => 'post_lang',
            'common_id_property' => 'post_lang_common_id',
            'single_id_property' => 'post_lang_single_id',
            'invariant_fields'   => array(),
        )
    );

    protected static $_belongs_to  = array();
    protected static $_has_many  = array();
    protected static $_many_many = array();

    public static function relations($specific = false) {
        $class = get_called_class();
        $category_class = \Inflector::get_namespace($class).'Model_Category';
        list($category_pk) = $category_class::primary_key();
        $tag_class = \Inflector::get_namespace($class).'Model_Tag';
        list($tag_pk) = $tag_class::primary_key();



        // @todo: should be loaded on config somewhere maybe
        $table_prefix_pos = strrpos(static::$_table_name, '_');
        $table_prefix = substr(static::$_table_name, 0, $table_prefix_pos);


        static::$_many_many['categories'] = array(
            'table_through' => $table_prefix.'_category_post',
            'key_from' => static::$_primary_key[0],
            'key_through_from' => static::$_primary_key[0],
            'key_through_to' => $category_pk,
            'key_to' => $category_pk,
            'cascade_save' => true,
            'cascade_delete' => false,
            'model_to'       => $category_class,
        );

        static::$_many_many['tags'] = array(
            'table_through' => $table_prefix.'_tag_post',
            'key_from' => static::$_primary_key[0],
            'key_through_from' => static::$_primary_key[0],
            'key_through_to' => $tag_pk,
            'key_to' => $tag_pk,
            'cascade_save' => true,
            'cascade_delete' => false,
            'model_to'       => $tag_class,
        );

        //var_dump(static::$_many_many);

        static::$_belongs_to['author'] = array(
            'key_from' => static::get_prefix().'author_id',
            'model_to' => 'Nos\Model_User',
            'key_to' => 'user_id',
            'cascade_save' => false,
            'cascade_delete' => false,
        );

        list(,,$app,) = explode('\\', $class);
        $app = strtolower($app);
        \Config::load('noviusos_'.$app.'::config', true);
        $withCom = \Config::get('noviusos_'.$app.'::config.comments.enabled');
        if ($withCom)
        {
            static::$_has_many['comments'] = array(
                'key_from' => 'post_id',
                'model_to' => '\Nos\Comments\Model_Comment',
                'key_to' => 'comm_foreign_id',
                'cascade_save' => false,
                'cascade_delete' => true,
                'conditions' => array('where' => array(array('comm_from_table', '=', static::$_table_name)), 'order_by' => array('comm_created_at' => 'ASC'))
            );
        }

        return parent::relations($specific);
    }

    public static function get_primary_key() {
        return static::$_primary_key;
    }

    public static function get_table_name() {
        return static::$_table_name;
    }


    public static function get_first($where, $preview = false) {
        // First argument is a string => it's the virtual name
        if (!is_array($where)) {
            $where = array(array('post_virtual_name', '=', $where));
        }

        if (!$preview) {
            $where[] = array('post_published', '=', true);
        }
        return static::find('first', array('where' => $where));
    }

    public static function get_query($params) {
        $query = static::query()
            ->related(array('author'));

        $query->where(array('post_published', true));

        $query->where(array('post_lang', $params['lang']));

        if (!empty($params['author'])) {
            $query->where(array('post_author_id', $params['author']->user_id));
        }
        if (!empty($params['tag'])) {
            $query->related(array('tags'));
            $query->where(array('tags.tag_label', $params['tag']->tag_label));
        }
        if (!empty($params['category'])) {
            $query->related(array('categories'));
            $query->where(array('categories.cat_id', $params['category']->cat_id));
        }
        if (!empty($params['order_by'])) {
            $query->order_by($params['order_by']);
        }
        if (!empty($params['offset'])) {
            $query->rows_offset($params['offset']);
        }
        if (!empty($params['limit'])) {
            $query->rows_limit($params['limit']);
        }

        return $query;
    }

    public static function get_all($params) {
        $query = static::get_query($params);
        $posts = $query->get();


        // Re-fetch with a 2nd request to get all the relations (not only the filtered ones)
        // @todo : to take a look later, see if the orm can't be fixed
        if (!empty($params['tag']) || !empty($params['category'])) {
            $keys = array_keys((array) $posts);
            $posts = static::query(array(
                'where' => array(
                    array('post_id', 'IN', $keys),
                ),
                'order_by' => $params['order_by'],
                'related' => array('author', 'tags', 'categories'),
            ))->get();
        }
        static::count_multiple_comments($posts);
        return $posts;
    }

    public static function count_all($params) {
        $query = static::get_query($params);
        return $query->count();
    }


    // @todo: these function need to be moved
    public function get_url($params = array()) {

        return $this->first_url();
//        $url = isset($params['urlPath']) ? $params['urlPath'] : \Nos\Nos::main_controller()->getEnhancedUrlPath();
//        $page = isset($params['page']) ? $params['page'] : 1;

//        return $url.urlencode($this->post_virtual_name).'.html';
    }

    public static function get_list_url($params = array()) {
        $url = isset($params['urlPath']) ? $params['urlPath'] : \Nos\Nos::main_controller()->getEnhancedUrlPath();
        $page = isset($params['page']) ? $params['page'] : 1;

        if ($page == 1) {
            return mb_substr($url, 0, -1).'.html';
        }
        return $url.'page/'.$page.'.html';

    }

    public static function count_multiple_comments($items) {
        $class = get_called_class();
        list(,,$app,) = explode('\\', $class);
        $app = strtolower($app);
        \Config::load('noviusos_'.$app.'::config', true);
        $withCom = \Config::get('noviusos_'.$app.'::config.comments.enabled');
        if (!$withCom || count($items) == 0) {
            return $items;
        }
        $ids = array();

        foreach ($items as $post) {
            $ids[] = $post->id;
        }

        $comments_count = \Db::select(\Db::expr('COUNT(comm_id) AS count_result'), 'comm_foreign_id')
            ->from(\Nos\Comments\Model_Comment::table())
            ->where('comm_foreign_id', 'in', $ids)
            ->and_where('comm_from_table', '=', static::$_table_name)
            ->group_by('comm_foreign_id')
            ->execute()->as_array();

        $comments_count = \Arr::assoc_to_keyval($comments_count, 'comm_foreign_id', 'count_result');

        foreach ($items as $key => $item) {
            if (isset($comments_count[$items[$key]->id])) {
                $items[$key]->nb_comments = $comments_count[$items[$key]->id];
            }
        }
        return $items;
    }


    protected $nb_comments = null;
    public function count_comments() {
        if ($this->nb_comments === null) {
            $this->nb_comments = \Nos\Comments\Model_Comment::count(array('where' => array(array('comm_foreign_id' => $this->id), array('comm_from_table' => static::$_table_name))));
        }
        return $this->nb_comments;
    }
}
