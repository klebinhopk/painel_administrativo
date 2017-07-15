<?php

defined('BASEPATH') OR exit('No direct script access allowed');

abstract class ABS_Model extends MY_Model {

    function __construct() {
        $this->load();
        parent::__construct();
    }

    abstract protected function load();

    abstract protected function validar();
    
    abstract public function save();

    abstract public function paginate();

    static public function url_paginate($vParams = array()) {
        $CI = &get_instance();
        $vArray = (ARRAY) $CI->input->get(NULL, TRUE);
        if (isset($vArray['per_page']))
            unset($vArray['per_page']);
        return current_url() . '?' . http_build_query(array_merge($vArray, $vParams));
    }

}

?>
