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

use Nos\Controller_Front_Application;
use View;

class Controller_Front extends Controller_Front_Application
{
    /**
     * @var \Nos\Pagination
     */
    public $pagination;
    public $current_page = 1;

    public $page_from = false;

    public $enhancerUrl_segments;

    public $action;

    public static $tag_class;
    public static $post_class;
    public static $category_class;
    public static $author_class;
    public static $application_name;

    public static function _init()
    {
        if (is_subclass_of(get_called_class(), 'Nos\\BlogNews\\Blog\\Controller_Front')) {
            $namespace = 'Nos\\BlogNews\\Blog\\';
        } else if (is_subclass_of(get_called_class(), 'Nos\\BlogNews\\News\\Controller_Front')) {
            $namespace = 'Nos\\BlogNews\\News\\';
        } else {
            $namespace = \Inflector::get_namespace(get_called_class());
        }

        static::$tag_class = $namespace.'Model_Tag';
        static::$category_class = $namespace.'Model_Category';
        $class = $namespace.'Model_Post';
        static::$post_class = $class;
        $relation = $class::relations('author');
        static::$author_class = $relation->model_to;
    }

    public function before()
    {
        parent::before();

        \Nos\I18n::current_dictionary(array('noviusos_blognews::front'));
        $this->app_config = \Arr::merge($this->app_config, static::getGlobalConfiguration());

        \View::set_global('blognews_config', $this->app_config);
        \View::set_global('app_config', $this->app_config); // @deprecated DON'T USE ANYMORE app_config BUT blognews_config

        // @todo voir l'extension des modules -> refactoring a faire au niveau generique
        list($this->application_name) = \Config::configFile(get_called_class());
        \Config::load('noviusos_blognews::controller/front', true);


        // We are manually merging configuration since we are not using the extend functionnality as intended.
        // In novius-os, if many application are extending one application, all configuration file on equivalent
        // paths are merged. Extend application tweek and add some functionnality to the existing application.
        // This is not what we want here since this is an headless application used by other application.
        // We do not want configuration files from different applications merged.
        $this->config = \Arr::merge(\Config::get('noviusos_blognews::controller/front'), \Config::loadConfiguration($this->application_name, 'controller/front'));
        $this->config['classes'] = array(
            'post' => static::$post_class,
            'tag' => static::$tag_class,
            'category' => static::$category_class,
        );

        $this->page_from = $this->main_controller->getPage();

        $this->config['item_per_page'] = (int) isset($this->enhancer_args['item_per_page']) ?
            $this->enhancer_args['item_per_page'] : $this->config['item_per_page'];
        \View::set_global('enhancer_args', $this->enhancer_args);

        parent::before();
    }

    public function after($response)
    {
        // Note to translator: The following texts are related to RSS feeds
        $this->main_controller->addMeta('<link rel="alternate" type="application/rss+xml" title="'.htmlspecialchars(\Security::html_entity_decode(__('Posts list'))).'" href="'.$this->main_controller->getContextUrl().$this->main_controller->getEnhancedUrlPath().'rss/posts.html">');
        if ($this->app_config['comments']['enabled']) {
            $this->main_controller->addMeta('<link rel="alternate" type="application/rss+xml" title="'.htmlspecialchars(\Security::html_entity_decode(__('Comments list'))).'" href="'.$this->main_controller->getContextUrl().$this->main_controller->getEnhancedUrlPath().'rss/comments.html">');
        }

        $this->main_controller->addCss('static/apps/noviusos_blognews/css/blognews.css');

        return parent::after($response);
    }

    public function action_list($page)
    {
        $this->init_pagination($page);
        return $this->display_list_main($this->enhancer_args);
    }

    public function action_item($title)
    {
        $post = $this->_get_post(array(
            'where' => array(
                array('post_virtual_name', '=', $title),
                array('post_context', '=', $this->page_from->page_context),
            ),
        ));
        if (empty($post)) {
            throw new \Nos\NotFoundException();
        }

        if ($this->app_config['comments']['enabled']) {
            $rss_title = strtr(__('{{post}}: Comments list'), array('{{post}}' => $post->post_title));
            $rss_url = $this->main_controller->getContextUrl().$this->main_controller->getEnhancedUrlPath().
                'rss/comments/'.$post->post_virtual_name.'.html';

            $this->main_controller->addMeta('<link rel="alternate" type="application/rss+xml" '.
                'title="'.htmlspecialchars(\Security::html_entity_decode($rss_title)).'" '.
                'href="'.\Nos\Tools_Url::encodePath($rss_url).'">');
        }

        $page = $this->main_controller->getPage();
        $this->main_controller->setTitle($page->page_title.' - '.$post->post_title);
        $page->page_title = $post->post_title;

        if ($this->app_config['comments']['enabled'] && $this->app_config['comments']['can_post']) {
            if (\Input::post('action') == 'addComment') {
                $post::commentApi()->addComment(\Input::post());
                $this->main_controller->deleteCache();
                \Response::redirect(\Nos\Tools_Url::encodePath($this->main_controller->getUrl()).'#comment_form');
            }
        }

        return View::forge(
            $this->config['views']['item'],
            array(
                'item' => $post,
            ),
            false
        );
    }

    public function action_stats($id)
    {
        $post = $this->_get_post(array(
            'where' => array(
                array('post_id', $id),
            ),
        ));
        if (!empty($post)) {
            $stats = \Session::get('noviusos_'.$this->application_name.'_stats', array());
            if (!in_array($post->post_id, $stats)) {
                $post->post_read++;
                $post->save();
                $stats[] = $post->post_id;
                \Session::set('noviusos_'.$this->application_name.'_stats', $stats);
                \Session::write();
            }
        }
        \Nos\Tools_File::send(DOCROOT.'static/apps/noviusos_blognews/img/transparent.gif');
    }

    public function action_subscribe($title)
    {
        return $this->changeSubscribeUnsubscribe($title, true);
    }

    public function action_unsubscribe($title)
    {
        return $this->changeSubscribeUnsubscribe($title, false);
    }

    public function action_author($title, $page)
    {
        $this->init_pagination(is_array($page) ? 1 : (int) $page);
        return $this->display_list_author($title);
    }

    public function action_tag($title, $page)
    {
        $this->init_pagination(is_array($page) ? 1 : (int) $page);
        return $this->display_list_tag($title);
    }

    public function action_category($title, $page)
    {
        $this->init_pagination(is_array($page) ? 1 : (int) $page);
        return $this->display_list_category($title);
    }

    public function changeSubscribeUnsubscribe($title, $subscribe)
    {
        $this->main_controller->disableCaching();
        if (isset($_GET['email'])) {
            $post = $this->_get_post(array(
                'where' => array(
                    array('post_virtual_name', '=', $title),
                    array('post_context', '=', $this->page_from->page_context),
                ),
            ));
            $post::commentApi()->changeSubscriptionStatus($post, $_GET['email'], $subscribe);
            return render(
                'noviusos_comments::front/subscriptions/'.($subscribe ? 'subscribe' : 'unsubscribe'),
                array(
                    'item' => $post,
                    'email' => $_GET['email']
                ),
                false);
        }
    }

    public function action_rss_posts()
    {
        $rss = $this->initRss();
        $posts = $this->_get_post_list();
        $rss->set(array(
            'title' => \Security::html_entity_decode(__('Posts list')),
            'description' => \Security::html_entity_decode(__('The full list of blog posts.')),
        ));
        $this->postsToRss($rss, $posts);
        $this->displayRss($rss);
    }

    public function action_rss_posts_category($category_title)
    {
        $rss = $this->initRss();
        $category = $this->_get_category($category_title);
        $posts = $this->_get_post_list(array('category' => $category));
        $rss->set(array(
            'title' => \Security::html_entity_decode(strtr(__('{{category}}: Posts list'), array('{{category}}' => $category->cat_title))),
            'description' => \Security::html_entity_decode(strtr(__('Blog posts listed under the ‘{{category}}’ category.'), array('{{category}}' => $category->cat_title))),
        ));
        $this->postsToRss($rss, $posts);
        $this->displayRss($rss);
    }

    public function action_rss_posts_tag($tag_title)
    {
        $rss = $this->initRss();
        $tag = $this->_get_tag($tag_title);
        $posts = $this->_get_post_list(array('tag' => $tag));
        $rss->set(array(
            'title' => \Security::html_entity_decode(strtr(__('{{tag}}: Posts list'), array('{{tag}}' => $tag->tag_label))),
            'description' => \Security::html_entity_decode(strtr(__('Blog posts listed under the ‘{{tag}}’ tag.'), array('{{tag}}' => $tag->tag_label))),
        ));
        $this->postsToRss($rss, $posts);
        $this->displayRss($rss);
    }

    public function action_rss_posts_author($author_name)
    {
        $rss = $this->initRss();
        $author = $this->_get_author($author_name);
        $posts = $this->_get_post_list(array('author' => $author));
        $rss->set(array(
            'title' => \Security::html_entity_decode(strtr(__('{{author}}: Posts list'), array('{{author}}' => $author->fullname()))),
            'description' => \Security::html_entity_decode(strtr(__('Blog posts written by {{author}}.'), array('{{author}}' => $author->fullname()))),
        ));
        $this->postsToRss($rss, $posts);
        $this->displayRss($rss);
    }

    public function action_rss_comments()
    {
        $api_request = array();
        $api_request['model'] = static::$post_class;
        $rss = $api_request['model']::commentApi()->getRss($api_request);
        $rss->set(array(
            'title' => \Security::html_entity_decode(__('Comments list')),
            'description' => \Security::html_entity_decode(__('The full list of comments.')),
        ));
        $this->displayRss($rss);
    }

    public function action_rss_comments_post($post_title)
    {
        $api_request = array();
        $api_request['model'] = static::$post_class;
        $api_request['item'] = $this->_get_post(array(
            'where' => array(
                array('post_virtual_name', '=', $post_title),
                array('post_context', '=', $this->page_from->page_context),
            ),
        ));
        $rss = $api_request['model']::commentApi()->getRss($api_request);
        $rss->set(array(
            'title' => \Security::html_entity_decode(strtr(__('{{post}}: Comments list'), array('{{post}}' => $api_request['item']->title_item()))),
            'description' => \Security::html_entity_decode(strtr(__('Comments to the post ‘{{post}}’.'), array('{{post}}' => $api_request['item']->title_item()))),
        ));

        $this->displayRss($rss);
    }

    public function initRss()
    {
        $rss = \Nos\Tools_RSS::forge(array(
            'link' => \Nos\Tools_Url::encodePath($this->main_controller->getUrl()),
            'language' => \Nos\Tools_Context::locale($this->page_from->page_context),
        ));
        return $rss;
    }

    public function postsToRss($rss, $posts)
    {
        $items = array();
        foreach ($posts as $post) {
            $items[] = static::_get_rss_post($post, $this->app_config);
        }
        $rss->set_items($items);
    }

    public function displayRss($rss)
    {
        $this->main_controller->setHeader('Content-Type', 'application/xml');
        $this->main_controller->setCacheDuration($this->config['rss_cache_duration']);
        return $this->main_controller->sendContent($rss->build());
    }


    public function action_home($args = array())
    {
        $this->page_from = $this->main_controller->getPage();
        $this->config['item_per_page'] = (int) isset($args['item_per_page']) ? $args['item_per_page'] : $this->config['item_per_page'];

        return $this->display_list_main($args);
    }

    protected function init_pagination($page)
    {
        if ($this->config['item_per_page']) {
            $this->current_page = $page;
            $this->pagination = new \Nos\Pagination();
        }
    }

    public function display_list_main($args)
    {
        $posts = $this->_get_post_list($args);

        return View::forge(
            $this->config['views']['list'],
            array(
                'posts' => $posts,
                'type' => 'main',
                'item' => 'main',
                'pagination' => $this->pagination,
            ),
            false
        );
    }

    public function display_list_tag($tag)
    {
        $tag = $this->_get_tag($tag);
        $posts = $this->_get_post_list(array('tag' => $tag));

        $rss_title = strtr(__('{{tag}}: Posts list'), array('{{tag}}' => $tag->tag_label));
        $rss_url = $this->main_controller->getContextUrl().$this->main_controller->getEnhancedUrlPath().
            'rss/posts/tag/'.urlencode($tag->tag_label).'.html';

        $this->main_controller->addMeta('<link rel="alternate" type="application/rss+xml" '.
            'title="'.htmlspecialchars(\Security::html_entity_decode($rss_title)).'" '.
            'href="'.\Nos\Tools_Url::encodePath($rss_url).'">');

        return View::forge('noviusos_blognews::front/post/list', array(
            'posts'       => $posts,
            'type'        => 'tag',
            'item'        => $tag,
            'pagination' => $this->pagination,
        ), false);
    }

    public function display_list_category($category)
    {
        $category = $this->_get_category($category);
        $posts = $this->_get_post_list(array('category' => $category));

        $rss_title = strtr(__('{{category}}: Posts list'), array('{{category}}' => $category->cat_title));
        $rss_url = $this->main_controller->getContextUrl().$this->main_controller->getEnhancedUrlPath().
            'rss/posts/category/'.$category->cat_virtual_name.'.html';

        $this->main_controller->addMeta('<link rel="alternate" type="application/rss+xml" '.
            'title="'.htmlspecialchars(\Security::html_entity_decode($rss_title)).'" '.
            'href="'.\Nos\Tools_Url::encodePath($rss_url).'">');

        return View::forge('noviusos_blognews::front/post/list', array(
            'posts'       => $posts,
            'type'        => 'category',
            'item'        => $category,
            'pagination' => $this->pagination,
        ), false);
    }

    public function display_list_author($parts_author)
    {
        //id_author is made with 3 parts, only the last one is the id (the others are used for SEO)
        $array_author = explode('_', $parts_author);
        $id_author = array_pop($array_author);
        $author = $this->_get_author($id_author);
        $posts = $this->_get_post_list(array('author' => $author));

        $rss_title = strtr(__('{{author}}: Posts list'), array('{{author}}' => $author->fullname()));
        $rss_url = $this->main_controller->getContextUrl().$this->main_controller->getEnhancedUrlPath().
            'rss/posts/author/'.$id_author.'.html';

        $this->main_controller->addMeta('<link rel="alternate" type="application/rss+xml" '.
            'title="'.htmlspecialchars(\Security::html_entity_decode($rss_title)).'" '.
            'href="'.\Nos\Tools_Url::encodePath($rss_url).'">');

        return View::forge('noviusos_blognews::front/post/list', array(
            'posts'       => $posts,
            'type'        => 'author',
            'item'        => $author,
            'pagination' => $this->pagination,
        ), false);
    }

    protected function _get_post($options = array())
    {
        $post_class = static::$post_class;

        return $post_class::get_first($options, $this->main_controller->isPreview());
    }

    protected function _get_category($category)
    {
        $category_class = static::$category_class;

        $category = $category_class::find(
            'first',
            array(
                'where' => array(
                    array('cat_virtual_name', 'LIKE', strtolower($category),),
                    array('cat_context', '=', $this->page_from->page_context),
                )
            )
        );
        if (empty($category)) {
            throw new \Nos\NotFoundException();
        }

        return $category;
    }

    protected function _get_author($id)
    {
        $author_class = static::$author_class;

        $author = $author_class::find(
            'first',
            array(
                'where' => array(
                    array('user_id', '=', $id,),
                )
            )
        );
        if (empty($author)) {
            throw new \Nos\NotFoundException();
        }

        return $author;
    }

    protected function _get_tag($tag)
    {
        $tag_class = static::$tag_class;

        $tag = $tag_class::find(
            'first',
            array(
                'where' => array(
                    array(
                        'tag_label',
                        'LIKE',
                        strtolower($tag),
                    )
                )
            )
        );
        if (empty($tag)) {
            throw new \Nos\NotFoundException();
        }

        return $tag;
    }

    protected function _get_post_list($params = array())
    {
        $post_class = static::$post_class;

        // Apply context
        if (isset($this->page_from->page_context)) {
            $params['context'] = $this->page_from->page_context;
        } else {
            $params['context'] = \Nos\Tools_Context::defaultContext();
        }

        // Apply pagination
        if (isset($this->pagination)) {
            $query_count = $post_class::get_query($params);
            $this->applyQueryCallback($query_count, $params);

            $this->pagination->set_config(
                array(
                    'total_items' => $query_count->count(),
                    'per_page' => $this->config['item_per_page'],
                    'current_page' => $this->current_page,
                )
            );
        }
        $params['offset'] = $this->pagination ? (int) $this->pagination->offset : 0;

        if ($this->config['item_per_page']) {
            $params['limit'] = $this->config['item_per_page'];
        }

        if (isset($params['cat_id'])) {
            if (!is_array($params['cat_id'])) {
                $params['cat_id'] = array($params['cat_id']);
            }
            $category_class = static::$category_class;
            $pk = $category_class::primary_key();

            $params['categories'] = $category_class::find('all', array('where' => array(array($pk[0], 'IN', $params['cat_id']))));
            if (!empty($params['category']) && !in_array($params['category']->cat_id, $params['cat_id'])) {
                $params['categories'][] = $params['category'];
                unset($params['category']);
            }
        }
        if (isset($this->config['order_by'])) {
            $params['order_by'] = $this->config['order_by'];
        }

        // Get objects
        $query = $post_class::get_query($params);

        $this->applyQueryCallback($query, $params);

        $posts = $post_class::get_all_from_query($query);

        // Re-fetch with a 2nd request to get all the relations (not only the filtered ones)
        // @todo : to take a look later, see if the orm can't be fixed
        if (!empty($posts) && (!empty($params['tag']) || !empty($params['category']) || !empty($params['categories']))) {
            $posts = $post_class::fetch_relations($posts, $params['order_by']);
        }

        return $posts;
    }

    protected function applyQueryCallback($query, $params)
    {
        if (isset($this->config['query_callback'])) {
            $this->config['query_callback']($query, $params, $this);
        }
    }

    protected static function _get_rss_post($post, $config)
    {
        $content = $post->get_default_nuggets();
        $item = array();
        $item['title'] = isset($content[\Nos\DataCatcher::TYPE_TITLE]) ? $content[\Nos\DataCatcher::TYPE_TITLE] : $post->post_title;
        $item['link'] = $post->url_canonical();
        if (isset($content[\Nos\DataCatcher::TYPE_IMAGE])) {
            $item['img'] = \Uri::base(false).$content[\Nos\DataCatcher::TYPE_IMAGE];
        }
        if (isset($config['rss']['description_template'])) {
            $item['description'] = \Config::placeholderReplace($config['rss']['description_template'], array(
                'summary' => $post->post_summary,
                'content' => \Nos\Nos::parse_wysiwyg($post->wysiwygs->content),
            ));
        } else {
            $item['description'] = isset($content[\Nos\DataCatcher::TYPE_TEXT]) ? $content[\Nos\DataCatcher::TYPE_TEXT] : $post->post_summary;
        }
        $item['pubDate'] = $post->post_created_at;
        $item['author'] = !empty($post->author) ? $post->author->fullname() : $post->post_author_alias;

        return $item;
    }

    protected function url_stats($item)
    {
        return $this->main_controller->getEnhancedUrlPath().'stats/'.urlencode($item->post_id).'.html';
    }

    public static function getUrlEnhanced($params = array())
    {
        $item = \Arr::get($params, 'item', false);
        if ($item) {
            $model = get_class($item);
            $page = \Arr::get($params, 'page', 1);

            switch ($model) {
                case static::$post_class:
                    $post_url = $item->post_virtual_name.'.html';
                    if (isset($params['unsubscribe']) && $params['unsubscribe']) {
                        return 'unsubscribe/'.$post_url;
                    }
                    if (isset($params['subscribe']) && $params['subscribe']) {
                        return 'subscribe/'.$post_url;
                    }
                    return $post_url;
                    break;

                case static::$tag_class:
                    return 'tag/'.urlencode($item->tag_label).($page > 1 ? '/'.$page : '').'.html';
                    break;

                case static::$category_class:
                    return 'category/'.$item->cat_virtual_name.($page > 1 ? '/'.$page : '').'.html';
                    break;

                case static::$author_class:
                    return 'author/'.urlencode($item->user_name.'_'.$item->user_firstname.'_'.$item->user_id).($page > 1 ? '/'.$page : '').'.html';
                    break;
            }
        }

        return false;
    }
}
