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

class Renderer_Selector extends \Nos\Renderer_Selector
{
    public $context = null;
    /**
     * Add a class and an id with a prefix to the renderer attributes
     * @param $attributes
     * @param $rules
     */

    public function before_construct(&$attributes, &$rules)
    {
        $attributes['class'] = (isset($attributes['class']) ? $attributes['class'] : '').' selector';

        if (empty($attributes['id'])) {
            $attributes['id'] = uniqid('selector_');
        }

        if (isset($attributes['renderer_options']['instance'])) {
            $this->context = $attributes['renderer_options']['instance']->get_context();
        }

        if (isset($attributes['renderer_options']) && isset($attributes['renderer_options']['parents'])) {
            $this->renderer_options['parents'] = $attributes['renderer_options']['parents'];
            unset($attributes['renderer_options']['parents']);
        }

    }

    public function build()
    {
        if (isset($this->renderer_options) && isset($this->renderer_options['multiple'])&& $this->renderer_options['multiple']) {

            //it is necessary to construct the "selected values" array with keys written like "namespace\model|id"
            // because it must be considered as JS Object when transformed to json (see modeltree_checkbox)
            // and this is the syntax used in this renderer.
            $ids = (array) $this->value;
            $selected = array();
            $pre_selected = array();
            $disabled =  array();
            if (isset($this->renderer_options) && isset($this->renderer_options['parents'])) {
                $pre_selected = $this->renderer_options['parents'];
                unset($this->renderer_options['parents']);
            }
            foreach ($ids as $id => $value) {
                $selected[$this->renderer_options['model'].'|'.$id] = array(
                    'id' => $id,
                    'model' => $this->renderer_options['model'],
                );
                if (in_array($id, $pre_selected)) {
                    $disabled[$this->renderer_options['model'].'|'.$id] = array(
                        'id' => $id,
                        'model' => $this->renderer_options['model'],
                    );
                }
            }
        } else {
            $id = (string) (int) $this->value;
            $selected = array('id' => $id);
            $disabled = array();
        }

        $context = \Arr::get($this->renderer_options, 'context', $this->context);

        return $this->template(static::renderer(array(
            'input_name' => $this->name,
            'selected' => $selected,
            'disabled' => $disabled,
            'model'  => $this->renderer_options['model'],
            'reloadEvent' => \Inflector::get_namespace($this->renderer_options['model']).'Model_Post',
            'contextChange' => true,
            'inspector'  => $this->renderer_options['inspector'],
            'multiple' => isset($this->renderer_options['multiple']) ? $this->renderer_options['multiple'] : 0,
            'sortable' => isset($this->renderer_options['sortable']) ? $this->renderer_options['sortable'] : 0,
            'main_column' => $this->renderer_options['main_column'],
            'treeOptions' => array(
                'context' => $context == null ? '' : $context,
            ),
            'height' => \Arr::get($this->renderer_options, 'height', '150px'),
            'width' => \Arr::get($this->renderer_options, 'width', null),
        )));
    }

    /**
     * Construct the radio selector renderer
     * When using a fieldset,
     * build() method should be overwritten to call the template() method on renderer() response
     * @static
     * @abstract
     * @param array $options
     */

    public static function renderer($options = array(), $attributes = array())
    {
        $view = 'inspector/modeltree_radio';
        $defaultSelected = array(
            'id' => 0,
            'model' => $options['model'],
        );
        if (isset($options['multiple']) && $options['multiple']) {
            $view = 'inspector/modeltree_checkbox';
            $defaultSelected = array($defaultSelected);
        }

        $overloaded_selected = null;
        if (isset($options['multiple']) && $options['multiple'] && isset($options['selected']['id']) && is_array($options['selected']['id'])) {
            $ids = $options['selected']['id'];
            $model = $options['model'];
            $overloaded_selected = array();
            foreach ($ids as $id) {
                $overloaded_selected[] = array(
                    'id' => $id,
                    'model' => $model,
                );
            }
        }

        $options = \Arr::merge(array(
            'urlJson' => $options['inspector'].'/json',
            'input_name' => null,
            'selected' => $defaultSelected,
            'disabled' => array(
            ),
            'columns' => array(
                array(
                    'dataKey' => $options['main_column'],
                )
            ),
            'treeOptions' => array(
                'context' => null,
            ),
            'height' => '150px',
            'width' => null,
        ), $options);

        if ($overloaded_selected !== null) {
            $options['selected'] = $overloaded_selected;
        }

        if ($options['selected'] === null) {
            $options['selected'] = array();
        }

        return (string) \Request::forge($options['inspector'].'/list')->execute(
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
