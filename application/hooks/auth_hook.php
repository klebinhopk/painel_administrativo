<?php

/**
 * @author Romário Nascimento Beckman <romabeckman@gmail.com,romario@pa.senac.br>
 * @link https://www.linkedin.com/in/romabeckman
 * @link https://www.facebook.com/romabeckman
 * @link http://twitter.com/romabeckman
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Auth_hook {

    private $CI;

    public function __construct() {
        $this->CI = &get_instance();
    }

    function check() {
        $module = $this->CI->router->fetch_module();

        if ($module == "painel")
            $this->check_logged_painel($module, $this->CI->router->class, $this->CI->router->method);
    }

    protected function check_logged_painel($module, $classe, $metodo) {
        $this->CI->load->dao('painel/usu_permissoes_dao');
        $this->CI->load->dao('painel/usu_metodo_dao');
        $this->CI->load->dao('painel/usu_usuario_dao');        
        $this->CI->load->dao('painel/usu_log_dao');        
        
        $roMetodo = $this->CI->usu_metodo_dao->fetchAll(array('modulo' => $module, 'classe' => $classe, 'metodo' => $metodo));

        //Se este método ainda não existir na tabela, será cadastrado   
        if ($roMetodo->num_rows() == 0) {
            redirect('painel/main/sempermissao', 'refresh');
        }
        //Se já existir traz as informações de público ou privado
        else {
            $oMetodo = $roMetodo->row();
            $vPainel = $this->CI->session->userdata('painel');

            if ($oMetodo->privado) {
                //Se o usuário estiver logado vai verificar se tem permissão na tabela
                if (!empty($vPainel)) {
                    $bExist = $this->CI->usu_metodo_dao->checaSePermissaoExiste($vPainel['id_grupo_usuario'], $oMetodo->id);

                    //Se não vier nenhum resultado da consulta, manda para a página de usuário sem permissão
                    if ($bExist == 0 AND !$oMetodo->default)
                        redirect('painel/main/sempermissao', 'refresh');
                } else { //Se não estiver logado, será redirecionado para o login
                    redirect('painel/main/login', 'refresh');
                }
            }
        }
    }

}
