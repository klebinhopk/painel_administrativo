<?php

/**
 * @author Romário Nascimento Beckman <gtisuporte@pa.senac.br,romario@pa.senac.br>
 */
class PainelHelper {

    static function setMensagemSave($bSave) {
        $bSave ?
                        PainelHelper::setMensagem(9) :
                        PainelHelper::setMensagem(2);
    }

    static function setMensagem($nMensagem, array $vDados = array()) {
        $CI = & get_instance();
        $CI->mensagem_model->setFlashData($nMensagem, $vDados);
    }

    static function exibirMensagem() {
        $CI = &get_instance();
        $CI->mensagem_model->exibirMensagem();
    }

    static public function usuarioSessao() {
        $CI = &get_instance();
        $_vPainel = (ARRAY) $CI->session->userdata('painel');
        return $CI->painel_usu_usuario_dao->fetchRowById($_vPainel['id']);
    }

}

?>