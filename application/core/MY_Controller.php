<?php

/**
 * @author Romário Nascimento Beckman <gtisuporte@pa.senac.br,romario@pa.senac.br>
 */
(defined('BASEPATH')) OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    public $_vPost, $_vGet, $_vPainel;

    function __construct() {
        parent::__construct();
        $this->_vPost = (ARRAY) $this->input->post(NULL, TRUE);
        $this->_vGet = (ARRAY) $this->input->get(NULL, TRUE);
        $this->_vPainel = (ARRAY) $this->session->userdata('painel');
        $this->form_validation->CI =& $this;
    }

    protected function template($sTemplate, $sView, $vVars = array(), $bReturn = FALSE) {
        $vVars['_vPost'] = $this->_vPost;
        $vVars['_vGet'] = $this->_vGet;
        $vVars['_vPainel'] = $this->_vPainel;
        $vVars['conteudo'] = $sView;
        $this->load->view($sTemplate, $vVars, $bReturn);
    }

    protected function view($sView, $vVars = array(), $bReturn = FALSE) {
        $vars['_vPost'] = $this->_vPost;
        $vars['_vGet'] = $this->_vGet;
        $vars['_vPainel'] = $this->_vPainel;
        return $this->load->view($sView, $vVars, $bReturn);
    }
    
    protected function templatePainel($vVars = array(), $bReturn = FALSE) {
        $vVars['vPainelPermissao'] = $this->usu_metodo_dao->permissaoPorGrupo($this->_vPainel['id_grupo_usuario']);
        return $this->template('template/painel', strtolower($this->router->class . '/' . $this->router->method), $vVars, $bReturn);
    }
    
    protected function templateSite($vVars = array(), $bReturn = FALSE) {
        return $this->template('template/site', strtolower($this->router->class . '/' . $this->router->method), $vVars, $bReturn);
    }

}
