<?php

/**
 * @author Romário Nascimento Beckman <romabeckman@gmail.com,romario@pa.senac.br>
 * @link https://www.linkedin.com/in/romabeckman
 * @link https://www.facebook.com/romabeckman
 * @link http://twitter.com/romabeckman
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Usuario extends ABS_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('painel/usuario_model');
        $this->load->model('painel/grupo_usuario_model');
        $this->load->dao('painel/usu_grupo_usuario_dao');
    }

    protected function load() {
        
    }

    public function index() {
        $vPaginate = $this->painel_usuario_model->paginate();
        $data['sPaginacao'] = $vPaginate['links'];
        $data['roUsuario'] = $vPaginate['result'];

        $data['conteudo'] = "usuario/main";
        $data['title'] = "Usuário (" . $vPaginate['total'] . ")";
        $this->templatePainel($data);
    }

    public function adicionar() {
        $this->painel_usuario_model->save();

        $data['vsGrupoUsuario'] = array('' => 'Selecione') + $this->painel_usu_grupo_usuario_dao->fetchToDropdown();
        $data['migalha'] = array('painel/usuario' => 'Usuário');
        $data['title'] = "Adicionar Usuário";
        $this->templatePainel($data);
    }

    public function alterar() {
        $nId = $this->uri->segment(4);
        $data['oUsuario'] = $this->painel_usu_usuario_dao->fetchRowById($nId);

        if (empty($data['oUsuario'])) {
            $this->mensagem_model->setFlashData(7);
            redirect('painel/usuario');
            return;
        }

        $this->painel_usuario_model->save();

        $data['vsGrupoUsuario'] = array('' => 'Selecione') + $this->painel_usu_grupo_usuario_dao->fetchToDropdown();
        $data['migalha'] = array('painel/usuario' => 'Usuário');
        $data['title'] = "Alterar Usuário";
        $this->templatePainel($data);
    }

    public function remover() {
        $nId = $this->uri->segment(4);
        
        if ($this->painel_usu_usuario_dao->remove($nId))
            \PainelHelper::setMensagem (8);
        else
            \PainelHelper::setMensagem (1);

        redirect('painel/usuario');
    }

    public function meus_dados() {
        $this->painel_usuario_model->save_meus_dados();

        $data['oUsuario'] = \PainelHelper::usuarioSessao();
        $data['conteudo'] = "usuario/meus_dados";
        $data['title'] = "Alterar meus dados de acesso";
        $this->templatePainel($data);
    }
}

?>
