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

class Grupo_usuario_model extends \ABS_Model {

    function __construct() {
        parent::__construct();
        $this->load->dao('painel/usu_grupo_usuario_dao');
    }

    protected function load() {
        
    }

    protected function validar() {
        $this->my_form_validation->set_rules('nome', 'Nome', 'required|max_length[45]');
        $this->my_form_validation->set_rules('deletado', 'Deletado', '');
        return $this->my_form_validation->run();
    }

    public function paginate() {
        $nPerPage = 30;
        $nPage = (INT) $this->input->get('per_page');

        $vData = array();
        $nTotal = $this->painel_usu_grupo_usuario_dao->fetchField($vData, "COUNT(*) AS total");
        $rResult = $this->painel_usu_grupo_usuario_dao->fetchPaginate($vData, $nPerPage, $nPage);

        $this->load->library('paginacao', array('total_rows' => $nTotal, 'base_url' => self::url_paginate(), 'per_page' => $nPerPage, 'cur_page' => $nPage));
        $sLinks = $this->paginacao->painel();
        return array('result' => $rResult, 'links' => $sLinks, 'total' => $nTotal);
    }

    public function save() {
        if (empty($this->_vPost))
            return;
        if ($this->validar() == FALSE)
            return;

        $vReg = array(
            'id' => $this->uri->segment(4),
            'nome' => $this->_vPost["nome"]
        );

        $vReg = array_map('UtilHelper::arrayMapEmpty', $vReg);
        $bSave = $this->painel_usu_grupo_usuario_dao->save($vReg);
        \PainelHelper::setMensagemSave($bSave);

        if ($bSave)
            redirect('painel/grupo_usuario');
    }

}
