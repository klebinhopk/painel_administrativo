<?php

/**
 * @author Romário Nascimento Beckman <romabeckman@gmail.com,romario@pa.senac.br>
 * @link https://www.linkedin.com/in/romabeckman
 * @link https://www.facebook.com/romabeckman
 * @link http://twitter.com/romabeckman
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Metodo_model extends MY_Model {

    function __construct() {
        parent::__construct();
        $this->load->dao('painel/usu_metodo_dao');
    }

    function save($nIdGrupoUsuario, $vnIdMetodo = NULL) {
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

    function listarPermissoesPorGrupo($nIdGrupoUsuario) {
        $data = array();
        $voMetodo = $this->usu_metodo_dao->todasPermissoes($nIdGrupoUsuario);
        $data['voMetodo'] = array();
        foreach ($voMetodo as $oMetodo) {
            $data['vsModulo'][$oMetodo->modulo] = $oMetodo->modulo;
            $data['vsClasses'][$oMetodo->classe] = $oMetodo->area;
            $data['voMetodo'][$oMetodo->modulo][$oMetodo->classe][] = $oMetodo;

            if (!isset($data['nPermissaoTotal']))
                $data['nPermissaoTotal'] = 0;
            if (!isset($data['vnPermissaoModulo'][$oMetodo->modulo]))
                $data['vnPermissaoModulo'][$oMetodo->modulo] = array("com" => 0, "total" => 0);
            if (!isset($data['vnPermissaoClasse'][$oMetodo->modulo][$oMetodo->classe]))
                $data['vnPermissaoClasse'][$oMetodo->modulo][$oMetodo->classe] = array("com" => 0, "total" => 0);
            if (!isset($data['vnPermissaoMetodo'][$oMetodo->modulo][$oMetodo->classe][$oMetodo->metodo]))
                $data['vnPermissaoMetodo'][$oMetodo->modulo][$oMetodo->classe][$oMetodo->metodo] = array("com" => 0, "total" => 0);

            $data['nPermissaoTotal'] ++;
            $data['vnPermissaoModulo'][$oMetodo->modulo]['com'] += $oMetodo->permissao;
            $data['vnPermissaoModulo'][$oMetodo->modulo]['total'] ++;
            $data['vnPermissaoClasse'][$oMetodo->modulo][$oMetodo->classe]['com'] += $oMetodo->permissao;
            $data['vnPermissaoClasse'][$oMetodo->modulo][$oMetodo->classe]['total'] ++;
            $data['vnPermissaoMetodo'][$oMetodo->modulo][$oMetodo->classe][$oMetodo->metodo]['com'] += $oMetodo->permissao;
            $data['vnPermissaoMetodo'][$oMetodo->modulo][$oMetodo->classe][$oMetodo->metodo]['total'] ++;
        }

        return $data;
    }

}
