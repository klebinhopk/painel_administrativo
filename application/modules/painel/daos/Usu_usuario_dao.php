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

class Usu_usuario_dao extends \MY_Dao {

    protected function load() {
        $this->_sTable = 'usu_usuario';
        $this->_bDeletado = TRUE;
        $this->_vReferenceMap = array(
            'id_grupo_usuario' => array('name' => 'nome', 'column' => 'id', 'table' => 'usu_grupo_usuario'),
        );
    }

    public function fetchPaginate(array $vData = array(), int $nPerPage, int $nPage) {
        $this->db->order_by('nome')->limit($nPerPage, $nPage);
        return $this
                        ->selectParent('id_grupo_usuario', '', 'grupo_usuario')
                        ->fetchAll($vData);
    }

    public function getLogin($sLogin, $sSenha) {
        $oRow = $this->db
                ->select($this->_sTable . ".*")
                ->join("usu_grupo_usuario", "usu_grupo_usuario.id = " . $this->_sTable . ".id_grupo_usuario")
                ->get_where($this->_sTable, array('login' => $sLogin, 'usu_grupo_usuario.deletado' => 0, "" . $this->_sTable . ".deletado" => 0, 'ativo' => 1))
                ->row(0);

        if (!empty($oRow)) {
            if ($this->encrypt->decode($oRow->senha) == $sSenha) {
                return $oRow;
            }
        }

        return NULL;
    }

}
