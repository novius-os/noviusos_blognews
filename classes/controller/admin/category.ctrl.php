<?php
namespace NoviusDev\BlogNews;

class Controller_Admin_Category extends \Nos\Controller_Admin_Crud {


    /**
     * nom de la classe avec ns pour le modèle Model_Post
     * (on le déduit du ns qui instancie le modèle)
     *     ex, dans l'app News, renvoie NoviusDev\BlogNews\News\Model_Post
     * @var string
     */
    protected static $class_category;

    /**
     * répertoire/path admin pour le controlleur appelant
     *     ex, dans l'app News, noviusdev_news
     * @var string
     */
    protected static $ns_folder;


    /**
     * méthode magique appelée à l'initialisation du controlleur.
     * renseigne nos variables statiques
     */
    public function before()
    {
        static::$class_category = $class_category = namespacize($this, 'Model_Category');
        list($provider,$generic,$app) = explode('\\', $class_category);
        static::$ns_folder = strtolower($provider).'_'.strtolower($app);


        // @todo voir l'extension des modules -> refactoring a faire au niveau generique
        list($application_name) = static::getLocation();
        \Config::load('noviusdev_blognews::controller/admin/category', true);

        // We are manually merging configuration since we are not using the extend functionnality as intended.
        // In novius-os, if many application are extending one application, all configuration file on equivalent
        // paths are merged. Extend application tweek and add some functionnality to the existing application.
        // This is not what we want here since this is an headless application used by other application.
        // We do not want configuration files from different applications merged.
        $this->config                       = \Arr::merge($this->config,\Config::get('noviusdev_blognews::controller/admin/category'), static::loadConfiguration($application_name, 'controller/admin/category'));

        $this->config['controller_url']     = 'admin/'.$application_name.'/category';
        $this->config['model']              = $class_category;
        $this->config['fields']['cat_parent_id']['widget_options']['namespace'] =  __NAMESPACE__;
        $this->config['fields']['cat_parent_id']['widget_options']['application_name'] = $application_name;


        parent::before();
    }


}
