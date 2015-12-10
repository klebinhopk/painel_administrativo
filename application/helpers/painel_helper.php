<?php

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
