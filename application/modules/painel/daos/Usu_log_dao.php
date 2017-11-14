<?php

namespace Painel;

class Usu_log_dao extends \MY_Dao {

    protected function load() {
        $this->_sTable = 'usu_log';
        $this->_vReferenceMap = array('id_usuario' => array('name' => 'nome', 'column' => 'id', 'table' => 'usu_usuario'));
    }

    public function fetchPaginate(array $vData = array(), int $nPerPage, int $nPage) {
        $this->db->order_by('usuario')->limit($nPerPage, $nPage);
        return $this
                        ->selectParent('id_usuario', 'nome', 'usuario')
                        ->fetchAll($vData);
    }

}
