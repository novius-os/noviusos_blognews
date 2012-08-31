<?php

namespace Nos\BlogNews;

use Asset, Format, Input, Session, View, Uri;

class Controller_Admin_Appdesk extends \Nos\Controller_Admin_Appdesk
{

    protected $class_post;

    public function before()
    {
        $this->class_post = namespacize($this, 'Model_Post');
        parent::before();
    }

}
