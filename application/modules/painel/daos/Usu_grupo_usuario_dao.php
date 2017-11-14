<?php

namespace Painel;

/**
 * @author Romário Nascimento Beckman <romabeckman@gmail.com,romario@pa.senac.br>
 * @link https://www.linkedin.com/in/romabeckman
 * @link https://www.facebook.com/romabeckman
 * @link http://twitter.com/romabeckman
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Usu_grupo_usuario_dao extends \MY_Dao {

    protected function load() {
        $this->_sTable = 'usu_grupo_usuario';
        $this->_bDeletado = TRUE;
    }

    public function fetchPaginate(array $vData = array(), int $nPerPage, int $nPage) {
        $this->db->order_by('nome')->limit($nPerPage, $nPage);
        return $this->fetchAll($vData);
    }

}
