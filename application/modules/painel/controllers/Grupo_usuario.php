<?php

/**
 * @author Romário Nascimento Beckman <romabeckman@gmail.com,romario@pa.senac.br>
 * @link https://www.linkedin.com/in/romabeckman
 * @link https://www.facebook.com/romabeckman
 * @link http://twitter.com/romabeckman
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Grupo_usuario extends ABS_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('grupo_usuario_model');
        $this->load->dao('usu_grupo_usuario_dao');
    }

    protected function load() {
        
    }

    public function index() {
        $vPaginate = $this->grupo_usuario_model->paginate();
        $data['sPaginacao'] = $vPaginate['links'];
        $data['roGrupoUsuario'] = $vPaginate['result'];

        $data['title'] = "Grupo de Usuários (" . $vPaginate['total'] . ")";
        $this->templatePainel($data);
    }

    public function adicionar() {
        $this->grupo_usuario_model->save();

        $data['migalha'] = array('painel/grupo_usuario' => 'Grupo de Usuários');
        $data['title'] = "Adicionar Grupo de Usuário";
        $this->templatePainel($data);
    }

    public function alterar() {
        $nId = $this->uri->segment(4);
        $data['oGrupoUsuario'] = $this->usu_grupo_usuario_dao->fetchRowById($nId);

        if (empty($data['oGrupoUsuario'])) {
            $this->mensagem_model->setFlashData(7);
            redirect('painel/grupo_usuario');
            return;
        }
        if ($this->validacao()) {
            $this->_vPost['id'] = $data['oGrupoUsuario']->id;
            $this->grupo_usuario_model->save($this->_vPost);
            redirect('painel/grupo_usuario');
            return;
        }

        $data['migalha'] = array('painel/grupo_usuario' => 'Grupo de Usuário');
        $data['title'] = "Alterar Grupo de Usuário";
        $this->templatePainel($data);
    }

    public function remover() {
        $nId = $this->uri->segment(4);

        if ($this->usu_grupo_usuario_dao->remove($nId))
            $this->mensagem_model->setFlashData(8);
        else
            $this->mensagem_model->setFlashData(1);

        redirect('painel/grupo_usuario');
    }

}

?>
