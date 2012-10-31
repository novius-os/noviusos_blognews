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

class Widget_Category_Selector extends \Nos\Widget_Selector
{
    public $lang = null;
    /**
     * Add a class and an id with a prefix to the widget attributes
     * @param $attributes
     * @param $rules
     */

    public function before_construct(&$attributes, &$rules)
    {
        $attributes['class'] = (isset($attributes['class']) ? $attributes['class'] : '').' category-selector';

        if (empty($attributes['id'])) {
            $attributes['id'] = uniqid('category_');
        }

        if (isset($attributes['widget_options']['instance'])) {
            $this->lang = $attributes['widget_options']['instance']->get_lang();
        }

        if (isset($attributes['widget_options']) && isset($attributes['widget_options']['parents'])) {
            $this->widget_options['parents'] = $attributes['widget_options']['parents'];
            unset($attributes['widget_options']['parents']);
        }

    }

    public function build()
    {
        if (isset($this->widget_options) && isset($this->widget_options['multiple'])&& $this->widget_options['multiple']) {

            //it is necessary to construct the "selected values" array with keys written like "namespace\model|id"
            // because it must be considered as JS Object when transformed to json (see modeltree_checkbox)
            // and this is the syntax used in this widget.
            $ids = (array) $this->value;
            $selected = array();
            $pre_selected = array();
            $disabled =  array();
            if (isset($this->widget_options) && isset($this->widget_options['parents'])) {
                $pre_selected = $this->widget_options['parents'];
                unset($this->widget_options['parents']);
            }
            foreach ($ids as $id => $value) {
                $selected[$this->widget_options['namespace'].'Model_Category|'.$id] = array(
                    'id' => $id,
                    'model' => $this->widget_options['namespace'].'Model_Category',
                );
                if (in_array($id, $pre_selected)) {
                    $disabled[$this->widget_options['namespace'].'Model_Category|'.$id] = array(
                        'id' => $id,
                        'model' => $this->widget_options['namespace'].'Model_Category',
                    );
                }
            }
        } else {
            $id = $this->value;
            $selected = array('id'=>$id);
            $disabled = array();
        }

        $lang = \Arr::get($this->widget_options, 'lang', $this->lang);

        return $this->template(static::widget(array(
            'input_name' => $this->name,
            'selected' => $selected,
            'disabled' => $disabled,
            'multiple' => isset($this->widget_options['multiple']) ? $this->widget_options['multiple'] : 0,
            'sortable' => isset($this->widget_options['sortable']) ? $this->widget_options['sortable'] : 0,
            'application_name' => $this->widget_options['application_name'],
            'treeOptions' => array(
                'lang' => $lang == null ? '' : $lang,
            ),
            'height' => \Arr::get($this->widget_options, 'height', '150px'),
            'width' => \Arr::get($this->widget_options, 'width', null),
        )));
    }

    /**
     * Construct the radio selector widget
     * When using a fieldset,
     * build() method should be overwritten to call the template() method on widget() response
     * @static
     * @abstract
     * @param array $options
     */

    public static function widget($options = array(), $attributes = array())
    {
        $view = 'inspector/modeltree_radio';
        $defaultSelected = null;
        if (isset($options['multiple']) && $options['multiple']) {
            $view = 'inspector/modeltree_checkbox';
            $defaultSelected = array();
        }

        $options = \Arr::merge(array(
            'treeUrl' => 'admin/'.$options['application_name'].'/inspector/category/json',
            'input_name' => null,
            'selected' => $defaultSelected,
            'disabled' => array(
            ),
            'columns' => array(
                array(
                    'dataKey' => 'title',
                )
            ),
            'treeOptions' => array(
                'lang' => null,
            ),
            'height' => '150px',
            'width' => null,
        ), $options);

        return (string) \Request::forge($options['application_name'].'/admin/inspector/category/list')->execute(
            array(
                $view,
                array(
                    'attributes' => $attributes,
                    'params' => $options,
                )
            )
        )->response();
    }

}
