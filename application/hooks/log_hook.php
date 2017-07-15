<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @author Romário Nascimento Beckman <romabeckman@gmail.com,romario@pa.senac.br>
 * @link https://www.linkedin.com/profile/view?id=260417728&trk=spm_pic
 * @link https://www.facebook.com/romabeckman
 * @link http://twitter.com/romabeckman
 */
class Log_hook {

    private $CI;

    public function __construct() {
        $this->CI = &get_instance();
    }

    function registrar() {
        $sModule = $this->CI->router->fetch_module();
        $sClass = $this->CI->router->class;
        $sMethod = $this->CI->router->method;
        $roMetodo = $this->CI->usu_metodo_dao->fetchAll(array('modulo' => $sModule, 'classe' => $sClass, 'metodo' => $sMethod));

        if ($roMetodo->num_rows() > 0) {
            $oMetodo = $roMetodo->row(0);
            if ($oMetodo->privado) {
                $vDados = $this->CI->input->post(NULL, TRUE);
                if (isset($vDados['senha']))
                    unset($vDados['senha']);
                if (isset($vDados['confirmar_senha']))
                    unset($vDados['confirmar_senha']);
                
                $this->CI->log_model->saveLog($vDados);
            }
        }
    }

}
