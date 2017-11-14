<?php
/**
 * @author Romário Nascimento Beckman <romabeckman@gmail.com,romario@pa.senac.br>
 * @link https://www.linkedin.com/in/romabeckman
 * @link https://www.facebook.com/romabeckman
 * @link http://twitter.com/romabeckman
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Sys_configuracao_dao extends MY_Dao {

    protected function load() {
        $this->_sTable = 'sys_configuracao';
    }

    public function fetchPaginate(array $vData = array(), int $nPerPage, int $nPage) {
        
    }

    function salvar($sNome, $sValor) {
        $this->save(array('valor' => $sValor, 'nome' => $sNome), 'nome');
    }

    function getValor(string $sNome) {
        return (STRING) $this->db->get_where($this->_sTable, array('nome' => $sNome))->row('valor');
    }
}
