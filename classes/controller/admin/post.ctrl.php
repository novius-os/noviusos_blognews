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
    public function prepare_i18n()
    {
        parent::prepare_i18n();
        \Nos\I18n::current_dictionary('noviusos_blognews::common');
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
        \Arr::set($fields, 'author->user_fullname.form.value', !empty($this->item->author) ? $this->item->author->fullname() : '');

        return $fields;
    }
}
