<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Model extends CI_Model {

    public $_vPost, $_vGet, $_vPainel;

    function __construct() {
        $this->_vPost = (ARRAY) $this->input->post(NULL, TRUE);
        $this->_vGet = (ARRAY) $this->input->get(NULL, TRUE);
        $this->_vPainel = (ARRAY) $this->session->userdata('painel');
        parent::__construct();
    }

}

?>
