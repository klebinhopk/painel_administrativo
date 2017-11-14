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

class Log_model extends \MY_Model {

    function __construct() {
        parent::__construct();
        $this->load->dao('painel/usu_log_dao');
    }

    function filtro($vDados = array()) {
        if (isset($vDados["ip"])) {
            $this->db->like('ip', $vDados['ip']);
            unset($vDados["ip"]);
        }
        if (isset($vDados["descricao"])) {
            $this->db->like('descricao', $vDados['descricao']);
            unset($vDados["descricao"]);
        }
        if (isset($vDados["acesso"])) {
            $this->db->like('acesso', $vDados['acesso']);
            unset($vDados["acesso"]);
        }
        if (isset($vDados["data_inicio"])) {
            $this->db->where('data_cadastro >=', dataHelper::converteDataParaBanco($vDados['data_inicio'] . ' 00:00:00'));
            unset($vDados["data_inicio"]);
        }
        if (isset($vDados["data_fim"])) {
            $this->db->where('data_cadastro <=', dataHelper::converteDataParaBanco($vDados['data_fim'] . ' 23:59:59'));
            unset($vDados["data_fim"]);
        }

        if (!empty($vDados))
            $this->db->where($vDados);
    }

    public function paginate($vData = array()) {
        $nPerPage = 30;
        $nPage = (INT) $this->input->get('per_page');
        
        $nTotal = $this->painel_usu_log_dao->fetchField($vData, "COUNT(*) AS total");
        $rResult = $this->painel_usu_log_dao->fetchPaginate($vData, $nPerPage, $nPage);

        $this->load->library('paginacao', array('total_rows' => $nTotal, 'base_url' => \UtilHelper::url_paginate(), 'per_page' => $nPerPage, 'cur_page' => $nPage));
        $sLinks = $this->paginacao->painel();
        return array('result' => $rResult, 'links' => $sLinks, 'total' => $nTotal);
    }

    /**
     * <p>Registra o LOG</p>
     * <p>Também serão registrados os campos:</p>
     * <p><b>acesso</b> (módulo, controller, method);</p>
     * <p><b>id</b> do usuário caso esteja logado no painel; </p>
     * <p><b>ip</b> IP de acesso <i>$this->input->ip_address()</i>; </p>
     *
     * @param	string Titulo do LOG
     * @param	array Dados para registro do LOG
     */
    function saveLog($vDados = array()) {
        $vPainel = $this->session->userdata('painel');

        if (!empty($vDados)) {
            foreach ($vDados as $sIndice => $sValor) {
                if (is_string($sValor) AND strlen($sValor) > 50)
                    $vDados[$sIndice] = substr($sValor, 50);
            }

            $vLog = array(
                'acesso' => $this->uri->uri_string(),
                'descricao' => serialize($vDados),
                'id_usuario' => isset($vPainel['id']) ? $vPainel['id'] : NULL,
                'ip' => $this->input->ip_address(),
            );

            $this->painel_usu_log_dao->insert($vLog);
        }
    }

}
