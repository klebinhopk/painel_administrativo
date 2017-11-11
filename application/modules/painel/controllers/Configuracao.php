<?php

/**
 * @author Romário Nascimento Beckman <romabeckman@gmail.com,romario@pa.senac.br>
 * @link https://www.linkedin.com/in/romabeckman
 * @link https://www.facebook.com/romabeckman
 * @link http://twitter.com/romabeckman
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Configuracao extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('configuracao_model');
    }

    function index() {
        if (!empty($this->_vPost)) {
            $this->save();
        } else {
            $data['conteudo'] = "configuracao/main";
            $data['title'] = "Configuração";
            $data['vConfiguracao'] = $this->sys_configuracao_dao->fetchToDropdown(array(), 'valor', 'nome');
            $this->templatePainel($data);
        }
    }

    private function save() {
        if (!empty($this->_vPost)) {
            foreach ($this->_vPost['configuracao'] as $sNome => $sValor) {
                $this->sys_configuracao_dao->salvar($sNome, $sValor);
            }
            $this->mensagem_model->setFlashData(9);
        } else
            $this->mensagem_model->setFlashData(1);

        redirect('/painel/configuracao', 'refresh');
    }

}

?>
