<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

require_once APPPATH . 'interfaces/Crud.php';

abstract class Send extends MY_Controller implements Crud {

    function __construct() {
        parent::__construct();

        $this->setDados();
    }

    abstract protected function setDados();
}
