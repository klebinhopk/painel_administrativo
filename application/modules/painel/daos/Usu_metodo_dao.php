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

class Usu_metodo_dao extends \ABS_Dao {

    protected function load() {
        $this->_sTable = 'usu_metodo';
    }

    public function fetchPaginate(array $vData = array(), int $nPerPage, int $nPage) {
        
    }

    function checaSePermissaoExiste($nIdGrupoUsuario, $nIdMetodo) {
        return (INT) $this->db
                        ->select("COUNT(*) AS existe")
                        ->where(array('id_grupo_usuario' => $nIdGrupoUsuario, 'id_metodo' => $nIdMetodo, 'deletado' => 0))
                        ->join('usu_grupo_usuario', "usu_grupo_usuario.id = usu_permissoes.id_grupo_usuario")
                        ->get("usu_permissoes")
                        ->row('existe');
    }

    function todasPermissoes($nIdGrupoUsuario) {
        $voMetodo = $this->db
                ->order_by('classe, metodo')
                ->where(array('privado' => 1, 'default' => 0))
                ->get($this->_sTable)
                ->result();

        if (!empty($voMetodo)) {
            foreach ($voMetodo as $nIndice => $oMetodo) {
                $voMetodo[$nIndice]->permissao = $this->db
                        ->select('COUNT(*) AS existe')
                        ->get_where('usu_permissoes', array('id_metodo' => $oMetodo->id, 'id_grupo_usuario' => $nIdGrupoUsuario))
                        ->row('existe');
            }
        }

        return $voMetodo;
    }

    function permissaoPorGrupo($nIdGrupoUsuario) {
        $vPermissao = array();
        $voMetodo = $this->db
                ->order_by('classe, metodo')
                ->where(array('privado' => 1))
                ->get($this->_sTable)
                ->result();

        if (!empty($voMetodo)) {
            foreach ($voMetodo as $oMetodo) {
                $bPermissao = (INT) $this->db
                                ->select('COUNT(*) AS existe')
                                ->get_where('usu_permissoes', array('id_metodo' => $oMetodo->id, 'id_grupo_usuario' => $nIdGrupoUsuario))
                                ->row('existe');

                if (!empty($bPermissao))
                    $vPermissao["{$oMetodo->modulo}/{$oMetodo->classe}/{$oMetodo->metodo}"] = $bPermissao;
            }
        }

        return $vPermissao;
    }

    function checkPermissao($nIdGrupoUsuario, $sApelido) {
        return $this->db
                        ->select("COUNT(*) AS existe")
                        ->were(array('usu_permissoes.id_grupo_usuario' => $nIdGrupoUsuario, 'usu_metodo.apelido' => $sApelido))
                        ->join('usu_permissoes', "usu_permissoes.id_metodo = " . $this->_sTable . ".id")
                        ->get($this->_sTable)
                        ->row('existe');
    }

    function salvar($nIdGrupoUsuario, $vnIdMetodo = NULL) {
        $this->db->delete('usu_permissoes', array('id_grupo_usuario' => $nIdGrupoUsuario));

        if (!empty($vnIdMetodo)) {
            foreach ($vnIdMetodo as $nIdMetodo) {
                $this->db->insert('usu_permissoes', array('id_grupo_usuario' => $nIdGrupoUsuario, 'id_metodo' => $nIdMetodo));
            }
        } else {
            $voMetodo = $this->db
                    ->order_by('classe, metodo')
                    ->where(array('default' => 1))
                    ->get($this->_sTable)
                    ->result();

            if (!empty($voMetodo)) {
                foreach ($voMetodo as $oMetodo) {
                    $this->db->insert('usu_permissoes', array('id_grupo_usuario' => $nIdGrupoUsuario, 'id_metodo' => $oMetodo->id));
                }
            }
        }
    }

}
