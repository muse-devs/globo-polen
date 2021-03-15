<?php

namespace Polen\Includes\Talent;

class Polen_Talent_Controller_Base
{
    
    public function __construct()
    {
        $this->init();
        $this->check_permission();
    }

    public function __destroy()
    {
        $this->finish();
    }

    protected function init()
    {

    }

    protected function check_permission()
    {
        global $user;
    }

    protected function finish()
    {
        wp_die();
    }
}