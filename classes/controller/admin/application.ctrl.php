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

// Handles the enhancer configuration popup & preview
// WARNING : Because of the namespace, this controllers has to be extended in Blog & News
class Controller_Admin_Application extends \Nos\Controller
{
    public static function _init()
    {
        \Nos\I18n::current_dictionary(array('noviusos_blognews::common'));
    }

    protected $class_cat;

    public function before()
    {
        $this->class_cat = namespacize($this, 'Model_Category');
        $this->class_post = namespacize($this, 'Model_Post');

        parent::before();
        \Nos\I18n::current_dictionary(array('noviusos_blognews::common'));
    }

    public function action_popup()
    {
        $params = array();
        list($application_name) = \Config::configFile(get_called_class());
        $params['application_name'] = $application_name;

        if ($this->app_config['categories']['enabled']) {
            $cat_id = \Input::get('cat_id', null);
            $params['renderer'] = Renderer_Category_Selector::renderer(
                array(
                    'width'                     => '260px',
                    'height'                    => '200px',
                    'input_name'                => 'cat_id',
                    'selected'                  => !empty($cat_id) ? array('id' => $cat_id) : null,
                    'treeOptions'               => array(
                        'context'               => \Input::get('nosContext', false) ?: \Nos\Tools_Context::default_context(),
                    ),
                    'namespace'                 => \Inflector::get_namespace(get_called_class()),
                    'application_name'          => $application_name,
                    'multiple'                  => '0',
                )
            );
        }

        return \View::forge('noviusos_blognews::admin/application/popup', $params, false);
    }

    public function action_popup_save()
    {
        return $this->action_preview();
    }

    public function action_preview()
    {
        $params = $_POST;

        $post = $this->class_post;
        $cat = $this->class_cat;
        $params['context'] = \Input::post('nosContext', false) ?: \Nos\Tools_Context::default_context();
        $params['cat_id'] = \Input::post('cat_id', null);
        $params['limit'] = \Input::post('item_per_page', null);
        $params['datas'] = $post::get_all($params);
        if (isset($params['cat_id'])) {
            $params['category']        = strtr(__('Category: {{category}}'), array(
                '{{category}}' => $cat::find($params['cat_id'])->cat_title,
            ));
        } else {
            $params['category']        = '';
        }
        $body = array(
            'config'        => \Format::forge()->to_json($_POST),
            'preview'       => \View::forge('noviusos_blognews::admin/application/preview', $params, false)->render(),
        );
        \Response::json($body);
    }
}
