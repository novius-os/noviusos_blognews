<?php
namespace Nos\BlogNews;

use Nos\Controller;
use View;

class Controller_Admin_Tag extends Controller {

    public function action_delete_confirm() {

        $tag_model = namespacize($this, 'Model_Tag');
        $tag = $tag_model::find(\Input::post('id', 0));

        if ( empty($tag) )
        {
            $this->response(array(
                'notify' => __('The tag has successfully been deleted !'),
            ));
            return false;
        }

        // Recover infos before delete, if not id is null
        $dispatchEvent = array(
            'name'      => get_class($tag),
            'action'    => 'delete',
            'id'        => $tag->tag_id,
        );

        if ( !empty($tag) && $tag instanceof Model_tag )
        {
            $tag->delete();
        }

        $this->response(array(
            'notify' => __('The tag has successfully been deleted !'),
            'dispatchEvent' => $dispatchEvent,
        ));
    }
}
