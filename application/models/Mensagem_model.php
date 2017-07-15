<?php

/**
 * @author Romário Nascimento Beckman <romabeckman@gmail.com,romario@pa.senac.br>
 * @link https://www.linkedin.com/in/romabeckman
 * @link https://www.facebook.com/romabeckman
 * @link http://twitter.com/romabeckman
 */
class Mensagem_model extends MY_Model {

    function __construct() {
        parent::__construct();
        $this->load->dao('sys_mensagem_dao');
    }

    function exibirMensagem() {
        if ($this->session->flashdata('painel_msg')) {
            $vSessionMensagem = $this->session->flashdata('painel_msg');
            $oSysMensagem = $this->sys_mensagem_dao->fetchRowById($vSessionMensagem['id']);

            if (!empty($oSysMensagem)) {
                if (!empty($vSessionMensagem['dados']))
                    $oSysMensagem->nome = str_replace(array_keys($vSessionMensagem['dados']), $vSessionMensagem['dados'], $oSysMensagem->nome);
                ?>
                <div class="alert <?php echo $oSysMensagem->tipo ? "alert-" . $oSysMensagem->tipo : "" ?>">
                    <button class="close" data-dismiss="alert" type="button">×</button>
                    <strong><?php echo $oSysMensagem->id ?></strong> - 
                    <?php echo $oSysMensagem->nome ?>
                </div>
                <?
            }
        }
    }

    function setFlashData($nId, $vDados = array()) {
        $oSysMensagem = $this->sys_mensagem_dao->fetchRowById($nId);

        !empty($oSysMensagem) ?
                        $this->session->set_flashdata('painel_msg', array('id' => $nId, 'dados' => $vDados)) :
                        $this->session->set_flashdata('painel_msg', 1);
    }

}
