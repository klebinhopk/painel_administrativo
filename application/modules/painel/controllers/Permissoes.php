<?php

/**
 * @author Romário Nascimento Beckman <romabeckman@gmail.com,romario@pa.senac.br>
 * @link https://www.linkedin.com/in/romabeckman
 * @link https://www.facebook.com/romabeckman
 * @link http://twitter.com/romabeckman
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Permissoes extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('painel/grupo_usuario_model');
        $this->load->model('painel/metodo_model');
    }

    function index() {
        $nIdGrupoUsuario = $this->uri->segment(4);

        if (empty($nIdGrupoUsuario)) {
            $this->mensagem_model->setFlashData(12);
            redirect('painel/grupo_usuario');
            return;
        }
        
        $data['oGrupoUsuario'] = $this->painel_usu_grupo_usuario_dao->fetchRowById($nIdGrupoUsuario);
        $data['migalha'] = array("painel/grupo_usuario" => "Grupo de Usuário");
        $data['title'] = "Permissões - " . $data['oGrupoUsuario']->nome;
        $data = array_merge($data, $this->painel_metodo_model->listarPermissoesPorGrupo($nIdGrupoUsuario));
        $this->templatePainel($data);
        
        
    }

    function save() {
        $vDados = $this->input->post();
        $vDados = $this->security->xss_clean($vDados);

        if (!empty($vDados)) {
            $this->painel_metodo_model->save($vDados['id_grupo_usuario'], isset($vDados['id_metodo']) ? $vDados['id_metodo'] : NULL);
            $this->mensagem_model->setFlashData(9);
            redirect('/painel/permissoes/index/' . $vDados['id_grupo_usuario'], 'refresh');
        } else
            $this->mensagem_model->setFlashData(1);
    }

}

?>
