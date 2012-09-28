<?php
namespace Nos\BlogNews;

use Nos\Controller;

class Controller_Admin_Tag extends \Nos\Controller_Admin_Crud
{
    /**
     * nom de la classe avec ns pour le mod�le Model_Post
     * (on le d�duit du ns qui instancie le mod�le)
     *     ex, dans l'app News, renvoie Nos\BlogNews\News\Model_Post
     * @var string
     */
    protected static $class_post;

    /**
     * r�pertoire/path admin pour le controlleur appelant
     *     ex, dans l'app News, noviusos_news
     * @var string
     */
    protected static $ns_folder;

    /**
     * m�thode magique appel�e � l'initialisation du controlleur.
     * renseigne nos variables statiques
     */
    public function before()
    {

        static::$class_post = $class_post = namespacize($this, 'Model_Tag');
        list($provider,$generic,$app) = explode('\\', $class_post);
        static::$ns_folder = strtolower($provider).'_'.strtolower($app);
        parent::before();

        // @todo voir l'extension des modules -> refactoring a faire au niveau generique
        //\Config::load('noviusos_blognews::controller/admin/tag', true);

        // We are manually merging configuration since we are not using the extend functionnality as intended.
        // In novius-os, if many application are extending one application, all configuration file on equivalent
        // paths are merged. Extend application tweek and add some functionnality to the existing application.
        // This is not what we want here since this is an headless application used by other application.
        // We do not want configuration files from different applications merged.
        //$this->config = \Arr::merge($this->config, \Config::get('noviusos_blognews::controller/admin/tag'), static::loadConfiguration($application_name, 'controller/admin/tag'));
        //$this->config['controller_url'] = 'admin/'.$application_name.'/tag';
        //$this->config['model'] = $class_post;

        $this->config_build();
    }

}
