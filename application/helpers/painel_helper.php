<?php

/**
 * @author Romário Nascimento Beckman <romabeckman@gmail.com,romario@pa.senac.br>
 * @link https://www.linkedin.com/in/romabeckman
 * @link https://www.facebook.com/romabeckman
 * @link http://twitter.com/romabeckman
 */
class PainelHelper {

    static function setMensagemSave($bSave) {
        if ($bSave) {
            PainelHelper::setMensagem(9);
        } else {
            PainelHelper::setMensagem(2);
        }
    }

    static function setMensagem($nMensagem, array $vDados = array()) {
        $CI = & get_instance();
        $CI->sys_mensagem_model->setFlashData($nMensagem, $vDados);
    }

}
