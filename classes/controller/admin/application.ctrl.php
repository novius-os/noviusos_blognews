<?php
namespace Nos\BlogNews;

// gère popup de config des enhancers et previews
// ATTENTION : du fait du namespace, ce controlleur doit être étendu dans news et blog pour pouvoir fonctioner
class Controller_Admin_Application extends \Nos\Controller
{

    protected $class_cat;

    public function before()
    {
        $this->class_cat = namespacize($this, 'Model_Category');
        $this->class_post = namespacize($this, 'Model_Post');
        parent::before();
    }

    function action_popup() {

        $params = array();

        if ($this->app_config['categories']['enabled'])
        {
            list($application_name) = static::getLocation();
            $params['application_name'] = $application_name;
            $params['widget'] = Widget_Category_Selector::widget(
                array(
                    'width'                     => '260px',
                    'height'                    => '200px',
                    'input_name'                => 'cat_id',
                    'treeOptions'               => array(
                        'lang'                  => 'fr_FR'
                    ),
                    'namespace'                 => \Inflector::get_namespace(get_class($this)),
                    'application_name'          => $application_name,
                    'multiple'                  => '0',
                )
            );
        }

        return \View::forge('noviusos_blognews::admin/application/popup', $params, false);
    }

    function action_popup_save() {
        return $this->action_preview();
    }

    function action_preview() {
        $params = $_POST;

        $post = $this->class_post;
        $cat = $this->class_cat;
        //TODO : passer la langue du wysiwyg qui appelle la preview
        $params['lang']             = 'FR_fr';
        $params['cat_id']           = \Input::post('cat_id',null);
        $params['limit']            = \Input::post('item_per_page',null);
        $params['datas']            = $post::get_all($params);
        if (isset($params['cat_id']))
            $params['categorie']        = ' de la catégorie ' . $cat::find($params['cat_id'])->title;
        else
            $params['categorie']        = '';
        $body = array(
            'config'        => \Format::forge()->to_json($_POST),
            'preview'       => \View::forge('noviusos_blognews::admin/application/preview',$params)->render(),
        );
        \Response::json($body);
    }
}