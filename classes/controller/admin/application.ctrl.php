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
class Controller_Admin_Application extends \Nos\Controller_Admin_Enhancer
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

            $category_selector_options                  = $this->config['category_selector_options'];

            if (!isset($category_selector_options['selected'])) {
                $category_selector_options['selected']      = !empty($cat_id) ? array('id' => $cat_id) : null;
            }

            if (!isset($category_selector_options['treeOptions'])) {
                $category_selector_options['treeOptions'] = array();
            }

            if (!isset($category_selector_options['treeOptions']['context'])) {
                $category_selector_options['treeOptions']['context'] = \Input::get('nosContext', false) ?: \Nos\Tools_Context::defaultContext();
            }

            $this->config['popup']['params']['renderer'] = Renderer_Selector::renderer(
                $category_selector_options
            );
        }

        return parent::action_popup();
    }
}
