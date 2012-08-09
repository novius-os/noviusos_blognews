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
use Nos\Model_Page;
use \Nos\Comments\Model_Comment;

use Fuel\Core\Inflector;
use Fuel\Core\Str;
use View;

class Controller_Front extends Controller_Front_Application {

    /**
     * @var Nos\Pagination
     */
    public $pagination;
    public $current_page = 1;


    public $page_from = false;

    public $enhancerUrl_segments;

    public static $tag_class;
    public static $post_class;
    public static $category_class;

    public function before()
    {
        parent::before();
        static::$tag_class = namespacize($this, 'Model_Tag');
        static::$post_class = namespacize($this, 'Model_Post');
        static::$category_class = namespacize($this, 'Model_Category');

        // @todo voir l'extension des modules -> refactoring a faire au niveau generique
        list($application_name) = static::getLocation();
        \Config::load('noviusos_blognews::controller/front', true);


        // We are manually merging configuration since we are not using the extend functionnality as intended.
        // In novius-os, if many application are extending one application, all configuration file on equivalent
        // paths are merged. Extend application tweek and add some functionnality to the existing application.
        // This is not what we want here since this is an headless application used by other application.
        // We do not want configuration files from different applications merged.
        $this->config = \Arr::merge(\Config::get('noviusos_blognews::controller/front'), \Config::extendable_load($application_name, 'controller/front'));
        $this->config['classes'] = array(
            'post' => static::$post_class,
            'tag' => static::$tag_class,
            'category' => static::$category_class,
        );

    }

    public function action_home($args = array())
    {
        $this->page_from = $this->main_controller->getPage();
        $this->config['item_per_page'] = (int)$args['item_per_page'];
        return $this->display_list_main($args);
    }


    public function action_main($args = array()) {

        list($application_name) = static::getLocation();

        $this->page_from = $this->main_controller->getPage();

        $this->config['item_per_page'] = (int)$args['item_per_page'];

        \View::set_global('config', $this->config);

        $enhancer_url = $this->main_controller->getEnhancerUrl();
        if (!empty($enhancer_url)) {
	        $this->enhancerUrl_segments = explode('/', $enhancer_url);
                $segments = $this->enhancerUrl_segments;

	        if (empty($segments[1])) {
                return $this->display_item($args);
            } else if ($segments[0] == 'stats') {

                $post = $this->_get_post(array(array('post_id', $segments[1])));
                if (!empty($post)) {
                    $stats = \Session::get('noviusos_'.$application_name.'_stats', array());
                    if (!in_array($post->id, $stats)) {
                        $post->read++;
                        $post->save();
                        $stats[] = $post->id;
                        \Session::set('noviusos_'.$application_name.'_stats', $stats);
                    }
                }
                \Nos\Tools_File::send(DOCROOT.'static/apps/noviusos_blognews/img/transparent.gif');

	        } else if ($segments[0] === 'page') {
		        $this->init_pagination(empty($segments[1]) ? 1 : $segments[1]);
		        return $this->display_list_main($args);
	        } else if ($segments[0] === 'author') {
		        $this->init_pagination(!empty($segments[2]) ? $segments[2] : 1);
		        return $this->display_list_author($args);
	        } else if ($segments[0] === 'tag') {
		        $this->init_pagination(!empty($segments[2]) ? $segments[2] : 1);
		        return $this->display_list_tag($args);
	        } else if ($segments[0] === 'category') {
		        $this->init_pagination(!empty($segments[2]) ? $segments[2] : 1);
		        return $this->display_list_category($args);
	        }

	        throw new \Nos\NotFoundException();
        }


        $this->init_pagination(1);
        return $this->display_list_main($args);
    }

    protected function init_pagination($page) {
        $this->current_page = $page;
        $this->pagination   = new \Nos\Pagination();
    }

    public function display_list_main($args) {
        $posts = $this->_get_post_list($args);

        return View::forge($this->config['views']['list'], array(
            'posts'       => $posts,
            'type'        => 'main',
            'object'      => 'main',
            'pagination' => $this->pagination
        ), false);
    }

    public function display_list_tag() {
        $tag_class = static::$tag_class;

        list(, $tag) = $this->enhancerUrl_segments;
        $tag = $tag_class::find('first', array('where' => array(array(
            'tag_label', 'LIKE', strtolower($tag),
        ))));

        $posts = $this->_get_post_list(array('tag' => $tag));

        return View::forge('noviusos_blognews::front/post/list', array(
            'posts'       => $posts,
            'type'        => 'tag',
            'object'      => $tag,
            'pagination' => $this->pagination,
        ), false);
    }

    public function display_list_category() {
        $category_class = static::$category_class;

        list(, $category) = $this->enhancerUrl_segments;
        $category = $category_class::find('first', array('where' => array(array(
            'cat_virtual_name', 'LIKE', strtolower($category),
        ))));
        $posts = $this->_get_post_list(array('category' => $category));

        return View::forge('noviusos_blognews::front/post/list', array(
            'posts'       => $posts,
            'type'        => 'category',
            'object'      => $category,
            'pagination' => $this->pagination,
        ), false);
    }

    /**
     * Display a single item (outside a list context)
     *
     * @param   type  $item_id
     * @return  \Fuel\Core\View
     */
    public function display_item() {


        list($item_virtual_name) = $this->enhancerUrl_segments;
        $post = $this->_get_post(array(array('post_virtual_name', '=', $item_virtual_name), array('post_lang', '=', $this->page_from->page_lang)));
        if (empty($post)) {
            throw new \Nos\NotFoundException();
        }
        $page = \Nos\Nos::main_controller()->getPage();
        \Nos\Nos::main_controller()->setTitle($page->page_title . ' - ' . $post->title);
        $page->page_title = $post->title;
        $add_comment_success = 'none';
        if ($this->app_config['comments']['enabled'] && $this->app_config['comments']['can_post']) {
            if ($this->app_config['comments']['use_recaptcha']) {
                \Package::load('fuel-recatpcha', APPPATH.'packages/fuel-recaptcha/');
            }
            $add_comment_success = $this->_add_comment($post);
        }

        return View::forge($this->config['views']['item'], array(
            'add_comment_success'   => $add_comment_success,
            'item'                  => $post
        ), false);
    }

    protected function _get_post($where = array()) {
        $post_class = static::$post_class;

        return $post_class::get_first($where, $this->main_controller->isPreview());
    }

    protected function _get_post_list($params = array()) {

        $post_class = static::$post_class;
        $params['cat_id'] = 15;
        // Apply language

        if (isset($this->page_from->page_lang))
            $params['lang'] = $this->page_from->page_lang;
        else
            $params['lang'] = 'FR_fr';

        // Apply pagination
        if (isset($this->pagination)) $this->pagination->set_config(array(
            'total_items'    => $post_class::count_all($params),
            'per_page'       => $this->config['item_per_page'],
            'current_page'   => $this->current_page,
        ));
        $params['offset']   = $this->pagination ? (int)$this->pagination->offset : 0;

        $params['limit']    = $this->config['item_per_page'];

        if (isset($this->config['order_by'])) {
            $params['order_by'] = $this->config['order_by'];
        }

        // Get objects
        $posts = $post_class::get_all($params);


        return $posts;
    }

    protected function url_stats($item) {
        return $this->main_controller->getEnhancedUrlPath().'stats/'.urlencode($item->post_id).'.html';
    }

    static function get_url_model($item, $params = array()) {
        $model = get_class($item);
        $url = isset($params['urlPath']) ? $params['urlPath'] : \Nos\Nos::main_controller()->getEnhancedUrlPath();
        $page = isset($params['page']) ? $params['page'] : 1;

        switch ($model) {
            case static::$post_class :
                return $url.urlencode($item->virtual_name).'.html';
                break;

            case static::$tag_class :
                return $url.'tag/'.urlencode($item->label).($page > 1 ? '/'.$page : '').'.html';
                break;

            case static::$category_class :
                return $item->get_url();
                break;
        }
        return false;
    }

    protected function _add_comment($post) {
        if (\Input::post('todo') == 'add_comment') {
            if (!$this->app_config['comments']['use_recaptcha'] || \ReCaptcha\ReCaptcha::instance()->check_answer(\Input::real_ip(), \Input::post('recaptcha_challenge_field'), \Input::post('recaptcha_response_field')))
            {
                $post_class = static::$post_class;
                $comm = new Model_Comment();
                $comm->comm_from_table = $post_class::get_table_name();
                $comm->comm_email = \Input::post('comm_email');
                $comm->comm_author = \Input::post('comm_author');
                $comm->comm_content = \Input::post('comm_content');
                $date = new \Fuel\Core\Date();
                $comm->comm_created_at = \Date::forge()->format('mysql');
                $comm->comm_foreign_id = $post->id;
                $comm->comm_state = $this->config['comment_default_state'];
                $comm->comm_ip = \Input::ip();
                $comm->save();

                \Cookie::set('comm_email', \Input::post('comm_email'));
                \Cookie::set('comm_author', \Input::post('comm_author'));
                return true;
            } else {
                return false;
            }
        }
        return 'none'; // @todo: see if we can't return null
    }
}
