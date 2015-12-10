<?php

/**
 * @author Romário Nascimento Beckman <romabeckman@gmail.com,romario@pa.senac.br>
 * @link https://www.linkedin.com/in/romabeckman
 * @link https://www.facebook.com/romabeckman
 * @link http://twitter.com/romabeckman
 */
class painel_helper {

    static function setMensagemSave($bSave) {
        $CI = & get_instance();
        
        if ($bSave) {
            $CI->sys_mensagem_model->setFlashData(9);
        } else {
            $CI->sys_mensagem_model->setFlashData(2);
        }
    }

}
