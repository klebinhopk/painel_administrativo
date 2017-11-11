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

class Usuario_model extends\ABS_Model {

    function __construct() {
        parent::__construct();
        $this->load->dao('painel/usu_usuario_dao');
    }

    protected function validar() {
        $this->my_form_validation->set_rules('id_grupo_usuario', 'Grupo usuário', 'required|max_length[10]');
        $this->my_form_validation->set_rules('nome', 'Nome', 'required|max_length[200]');
        $this->my_form_validation->set_rules('login', 'Login', 'required|max_length[100]');
        $this->my_form_validation->set_rules('senha', 'Senha', 'max_length[200]');
        $this->my_form_validation->set_rules('email', 'Email', 'required|max_length[100]|valid_email');
        $this->my_form_validation->set_rules('ativo', 'Ativo', '');
        $this->my_form_validation->set_rules('deletado', 'Deletado', '');
        return $this->my_form_validation->run();
    }

    protected function validar_meus_dados() {
        $this->my_form_validation->set_rules('nome', 'Nome', 'required|max_length[200]');
        $this->my_form_validation->set_rules('login', 'Login', 'required|max_length[100]');
        $this->my_form_validation->set_rules('email', 'Email', 'required|max_length[100]|valid_email');
        return $this->my_form_validation->run();
    }

    protected function load() {
        
    }

    public function paginate() {
        $nPerPage = 30;
        $nPage = (INT) $this->input->get('per_page');

        $vData = array();
        $nTotal = $this->painel_usu_usuario_dao->fetchField($vData, "COUNT(*) AS total");
        $rResult = $this->painel_usu_usuario_dao->fetchPaginate($vData, $nPerPage, $nPage);

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
            'id_grupo_usuario' => $this->_vPost["id_grupo_usuario"],
            'nome' => $this->_vPost["nome"],
            'login' => $this->_vPost["login"],
            'email' => $this->_vPost["email"],
            'ativo' => $this->_vPost["ativo"]
        );

        $this->load->library('encrypt');
        if (!empty($this->_vPost['senha']))
            $vReg['senha'] = $this->encrypt->encode($this->_vPost['senha']);

        $vReg = array_map('UtilHelper::arrayMapEmpty', $vReg);
        $bSave = $this->painel_usu_usuario_dao->save($vReg);
        \PainelHelper::setMensagemSave($bSave);

        if ($bSave)
            redirect('painel/usuario');
    }

    public function save_meus_dados() {
        if (empty($this->_vPost))
            return;
        if ($this->validar_meus_dados() == FALSE)
            return;

        $vReg = array(
            'nome' => $this->_vPost["nome"],
            'login' => $this->_vPost["login"],
            'email' => $this->_vPost["email"],
        );

        if (!empty($vData['senha']))
            $vReg['senha'] = $this->encrypt->encode($this->_vPost['senha']);

        $bSave = $this->painel_usu_usuario_dao->update($vReg, $this->_vPainel['id']);
        \PainelHelper::setMensagemSave($bSave);

        if ($bSave)
            redirect('painel/usuario/meus_dados');
    }

    public function processaLogin() {
        $usuario = $this->_vPost['user'];
        $senha = $this->_vPost['pass'];
        $oUsuario = $this->painel_usu_usuario_dao->getLogin($usuario, $senha);

        if (!empty($oUsuario)) {
            $login = array(
                'id' => $oUsuario->id,
                'nome' => $oUsuario->nome,
                'email' => $oUsuario->email,
                'id_grupo_usuario' => $oUsuario->id_grupo_usuario,
                'logged' => 1,
                'datalogin' => date("Y-m-d h:i:s")
            );

            $this->session->set_userdata(array('painel' => $login));
            $this->session->set_userdata('painel_nav', 1);
            \PainelHelper::setMensagem(3);
            $this->painel_log_model->saveLog(array('id' => $oUsuario->id, 'nome' => $oUsuario->nome, 'emil' => $oUsuario->email, 'id_grupo_usuario' => $oUsuario->id_grupo_usuario));
        }
    }

}
