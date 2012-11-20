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

class Controller_Admin_Post extends \Nos\Controller_Admin_Crud
{

    /**
     * nom de la classe avec ns pour le modèle Model_Post
     * (on le déduit du ns qui instancie le modèle)
     *     ex, dans l'app News, renvoie Nos\BlogNews\News\Model_Post
     * @var string
     */
    protected static $class_post;

    /**
     * répertoire/path admin pour le controlleur appelant
     *     ex, dans l'app News, noviusos_news
     * @var string
     */
    protected static $ns_folder;

    /**
     * méthode magique appelée à l'initialisation du controlleur.
     * renseigne nos variables statiques
     */
    public function before()
    {

        static::$class_post = $class_post = namespacize($this, 'Model_Post');
        list($provider,$generic,$app) = explode('\\', $class_post);
        static::$ns_folder = strtolower($provider).'_'.strtolower($app);

        parent::before();

        // @todo voir l'extension des modules -> refactoring a faire au niveau generique
        list($application_name) = \Config::configFile(get_called_class());
        \Config::load('noviusos_blognews::controller/admin/post', true);

        // We are manually merging configuration since we are not using the extend functionnality as intended.
        // In novius-os, if many application are extending one application, all configuration file on equivalent
        // paths are merged. Extend application tweek and add some functionnality to the existing application.
        // This is not what we want here since this is an headless application used by other application.
        // We do not want configuration files from different applications merged.
        $this->config = \Arr::merge($this->config, \Config::get('noviusos_blognews::controller/admin/post'), \Config::loadConfiguration($application_name, 'controller/admin/post'));

        //$this->config['controller_url'] = 'admin/'.$application_name.'/post';
        //$this->config['model'] = $class_post;
        //$this->config['fields'] = $this->config['fields'](\Inflector::get_namespace(get_class($this)), $application_name);

        if (!$this->app_config['summary']['enabled']) {
            unset($this->config['layout']['subtitle']);
        }
        if (!$this->app_config['tags']['enabled']) {
            unset($this->config['layout']['menu'][__('Tags')]);
        }
        if (!$this->app_config['categories']['enabled']) {
            unset($this->config['layout']['menu'][__('Categories')]);
        }
        if (!$this->app_config['authors']['enabled']) {
            $this->config['layout']['menu'][__('Meta')] = array('field_template' => '{field}', 'fields' => array('post_created_at_date', 'post_created_at_time', 'post_read'));
        }

        $this->config_build();
    }

    protected function init_item()
    {
        parent::init_item();

        $this->item->author = \Session::user();
        if ($this->item_from) {
            $this->item->tags = $this->item_from->tags;

            foreach ($this->item_from->categories as $category_from) {
                $category = $category_from->find_context($this->item->post_context);
                if (!empty($category)) {
                    $this->item->categories[$category->cat_id] = $category;
                }
            }
        }
    }

    protected function fields($fields)
    {
        $fields = parent::fields($fields);
        \Arr::set($fields, 'author->user_fullname.form.value', $this->item->author->fullname());

        return $fields;
    }
}
