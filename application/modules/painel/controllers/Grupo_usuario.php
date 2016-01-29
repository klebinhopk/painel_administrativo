<?php

/**
 * @author Romário Nascimento Beckman <romabeckman@gmail.com,romario@pa.senac.br>
 * @link https://www.linkedin.com/in/romabeckman
 * @link https://www.facebook.com/romabeckman
 * @link http://twitter.com/romabeckman
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Grupo_usuario extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('grupo_usuario_model');
    }

    public function index() {
        $vPaginate = $this->grupo_usuario_model->getPaginate(base_url() . "painel/grupo_usuario/index/?");
        $data['sPaginacao'] = $vPaginate['links'];
        $data['roGrupoUsuario'] = $vPaginate['result'];

        $data['conteudo'] = "grupo_usuario/main";
        $data['title'] = "Grupo de Usuário (" . $vPaginate['total'] . ")";
        $this->loadTemplatePainel(NULL, $data);
    }

    public function adicionar() {
        if ($this->validation()) {
            $this->grupo_usuario_model->save($this->_vPost);
            redirect('painel/grupo_usuario', 'refresh');
        } else {
            $this->headerjscss->addJs('validate');
            $data['action'] = "adicionar";
            $data['migalha'] = array('painel/grupo_usuario' => 'Grupo de Usuário');
            $data['conteudo'] = "grupo_usuario/save";
            $data['title'] = "Adicionar Grupo de Usuário";
            $this->loadTemplatePainel(NULL, $data);
        }
    }

    public function alterar() {
        $nId = $this->uri->segment(4);
        $data['oGrupoUsuario'] = $this->grupo_usuario_model->get($nId);

        if (empty($data['oGrupoUsuario'])) {
            $this->sys_mensagem_model->setFlashData(7);
            redirect('painel/grupo_usuario', 'refresh');
        } else {
            if ($this->validation()) {
                $this->_vPost['id'] = $data['oGrupoUsuario']->id;
                $this->grupo_usuario_model->save($this->_vPost);
                redirect('painel/grupo_usuario', 'refresh');
            } else {
                $this->headerjscss->addJs('validate');
                $data['action'] = "alterar/" . $data['oGrupoUsuario']->id;
                $data['migalha'] = array('painel/grupo_usuario' => 'Grupo de Usuário');
                $data['conteudo'] = "grupo_usuario/save";
                $data['title'] = "Alterar Grupo de Usuário";
                $this->loadTemplatePainel(NULL, $data);
            }
        }
    }

    public function remover() {
        $nId = $this->_vGet['id'];

        if (empty($nId)) {
            $this->sys_mensagem_model->setFlashData(2);
        } else {
            if ($this->grupo_usuario_model->remove($nId))
                $this->sys_mensagem_model->setFlashData(8);
            else
                $this->sys_mensagem_model->setFlashData(1);
        }

        redirect('painel/grupo_usuario', 'refresh');
    }

    private function validation() {
        if (empty($this->_vPost))
            return FALSE;

        $this->pt_form_validation->set_rules('nome', 'Nome', 'required|max_length[45]');
        $this->pt_form_validation->set_rules('deletado', 'Deletado', '');
        return $this->pt_form_validation->run();
    }

}

?>
