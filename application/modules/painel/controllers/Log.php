<?php

/**
 * @author Romário Nascimento Beckman <romabeckman@gmail.com,romario@pa.senac.br>
 * @link https://www.linkedin.com/in/romabeckman
 * @link https://www.facebook.com/romabeckman
 * @link http://twitter.com/romabeckman
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Log extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('painel/log_model');
        $this->load->model('painel/usuario_model');
    }

    function index() {
        $data['conteudo'] = "log/main";
        $data['title'] = "Log";
        if (isset($this->_vGet['filtro'])) {
            $vFiltro = array(
                "ip" => $this->_vGet['ip'],
                "acesso" => $this->_vGet['acesso'],
                "descricao" => $this->_vGet['descricao'],
                "data_inicio" => $this->_vGet['data_inicio'],
                "data_fim" => $this->_vGet['data_fim']
            );
            $vFiltro = array_filter($vFiltro);
        } else {
            $vFiltro = array();
        }
        
        $vPaginate = $this->painel_log_model->paginate($vFiltro);
        $data['paginacao'] = $vPaginate['links'];
        $data['roLog'] = $vPaginate['result'];
        $this->templatePainel($data);
    }

}

?>
