<?php

/**
 * @author Romário Nascimento Beckman <romabeckman@gmail.com,romario@pa.senac.br>
 * @link https://www.linkedin.com/in/romabeckman
 * @link https://www.facebook.com/romabeckman
 * @link http://twitter.com/romabeckman
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sys_mensagem_dao extends MY_Dao {

    protected function load() {
        $this->_sTable = 'sys_mensagem';
    }

    public function fetchPaginate(array $vData = array(), int $nPerPage, int $nPage) {
        
    }

}
