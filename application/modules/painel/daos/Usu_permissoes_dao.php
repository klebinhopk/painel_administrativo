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

class Usu_permissoes_dao extends \ABS_Dao {

    protected function load() {
        $this->_sTable = 'usu_permissoes';
        $this->_vReferenceMap = array(
            'id_metodo' => array('table' => 'usu_metodo'),
            'id_grupo_usuario' => array('table' => 'usu_grupo_usuario'),
        );
    }

    public function fetchPaginate(array $vData = array(), int $nPerPage, int $nPage) {
        
    }

}
