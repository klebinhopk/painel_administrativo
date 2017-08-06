<?php

/**
 * @author Romário Nascimento Beckman <romabeckman@gmail.com,romario@pa.senac.br>
 * @link https://www.linkedin.com/in/romabeckman
 * @link https://www.facebook.com/romabeckman
 * @link http://twitter.com/romabeckman
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Main extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->dao('usu_usuario_dao');
        $this->load->model('usuario_model');
    }

    public function index() {
        if (empty($this->_vPainel)) {
            redirect('painel/main/login', 'refresh');
        } else {
            $data['conteudo'] = "main/main";
            $data['title'] = "Bem vindo!";
            $this->templatePainel($data);
        }
    }

    function login() {
        if ($this->validation_login()) {
            $this->usuario_model->processaLogin();
            redirect('painel');
            return;
        }
        
        $this->load->view('main/login');
    }

    function page_not_found() {
        $this->load->view('main/404');
    }

    function sempermissao() {
        $this->load->view('main/sempermissao');
    }

    function logout() {
        $this->session->unset_userdata('painel');
        redirect('/painel/main/login', 'refresh');
    }

    function recupera_senha() {
        $this->load->view('main/recupera_senha');
    }

    function recupera() {
        $sLogin = $this->input->post('user', true);
        if (!empty($sLogin)) {
            $oUsuario = $this->usu_usuario_dao->fetchRowById($sLogin, 'login');

            //checa se o usuario exite no banco
            if (!empty($oUsuario)) {
                $this->load->library('encrypt');
                $sSenha = $this->encrypt->decode($oUsuario->senha);
                $this->load->library('envia_email');
                $sMensagem = '<p>Segue abaixo seu acesso ao painel do(a) ' . NOME_CLIENTE . ':</p>';
                $sMensagem .= '<p>Login: ' . $oUsuario->login . '<br />';
                $sMensagem .= 'Senha: ' . $sSenha . '</p>';

                $this->envia_email->enviar($oUsuario->email, 'Recuperação de senha', $sMensagem);
                $this->mensagem_model->setFlashData(13);
            } else {
                $this->mensagem_model->setFlashData(14);
            }
        } else {
            $this->mensagem_model->setFlashData(12);
        }

        redirect('/painel/main/login', 'refresh');
    }

    function validation_login() {
        if (empty($this->_vPost))
            return FALSE;
        
        $this->load->library('encrypt');
        $this->my_form_validation->set_rules('user', 'login', 'required');
        $this->my_form_validation->set_rules('pass', 'Senha', 'required|callback_check_login_senha[' . $this->_vPost['user'] . ']');

        return $this->my_form_validation->run();
    }
    
    function check_login_senha($sSenhaInput, $sLogin) {
        $sSenha = $this->usu_usuario_dao->fetchField(array('login' => $sLogin, 'ativo' => 1), 'senha');
        if (!empty($sSenha)) {
            $sSenha = $this->encrypt->decode($sSenha);

            if (strcmp($sSenha, $sSenhaInput) !== 0) {
                $this->my_form_validation->set_message('check_login_senha', 'Senha informada não é válida.');
                return FALSE;
            }
        } else {
            $this->my_form_validation->set_message('check_login_senha', 'Login informado não existe.');
            return FALSE;
        }

        return TRUE;
    }

}
