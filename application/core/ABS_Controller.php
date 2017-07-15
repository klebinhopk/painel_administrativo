<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

abstract class ABS_Controller extends MY_Controller implements Crud {

    function __construct() {
        $this->load();
        parent::__construct();
    }

    abstract protected function load();
}
