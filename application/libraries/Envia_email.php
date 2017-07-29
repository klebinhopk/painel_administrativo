<?php

/**
 * @author Romário Nascimento Beckman <gtisuporte@pa.senac.br,romario@pa.senac.br>
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Envia_email {

    private $configEmail, $CI;

    public function __construct($vParams = array()) {
        $this->CI = &get_instance();
        $this->CI->load->model('sys_configuracao_dao');

        $sFrom = $this->CI->sys_configuracao_dao->getValor('EMAIL_FROM');
        $sFromName = $this->CI->sys_configuracao_dao->getValor('EMAIL_FROM_NAME');

        $sSmtp = $this->CI->sys_configuracao_dao->getValor('EMAIL_SMTP');
        $nPort = (INT) $this->CI->sys_configuracao_dao->getValor('EMAIL_PORT');
        $sSecure = $this->CI->sys_configuracao_dao->getValor('EMAIL_SECURE');

        $sUserName = $this->CI->sys_configuracao_dao->getValor('EMAIL_USERNAME');
        $sPassword = $this->CI->sys_configuracao_dao->getValor('EMAIL_PASSWORD');

        $this->configEmail = new \PHPMailer();

        if (!empty($sSmtp)) {
            $this->configEmail->IsSMTP(); // Define que a mensagem será SMTP
            $this->configEmail->Host = $sSmtp; // Endereço do servidor SMTP

            if (!empty($sUserName) AND ! empty($sPassword)) {
                $this->configEmail->SMTPAuth = TRUE; // Usa autenticação SMTP? (opcional)
                $this->configEmail->Username = $sUserName; // Usuário do servidor SMTP
                $this->configEmail->Password = $sPassword; // Senha do servidor SMTP
            }

            if (!empty($sSecure)) {
                $this->configEmail->SMTPSecure = $sSecure;
            }
        }

        $this->configEmail->From = $sFrom;
        $this->configEmail->FromName = $sFromName;

        if (isset($vParams['SMTPDebug'])) {
            $this->configEmail->Debugoutput = 'html';
            $this->configEmail->SMTPDebug = $vParams['SMTPDebug'];
        }

        $this->configEmail->IsHTML(true); // Define que o e-mail será enviado como HTML
        $this->configEmail->CharSet = 'utf-8'; // Charset da mensagem (opcional)
        $this->configEmail->Port = !empty($nPort) ? $nPort : 25; // Charset da mensagem (opcional)
    }

    function enviar($sPara, $sAssunto, $sMensagem, $nIdNotificacao = NULL) {
        if (!empty($sPara) AND ! empty($sAssunto) AND ! empty($sMensagem) AND ENVIRONMENT == 'production' AND ATIVAR_ENVIO_EMAIL) {
            if (is_array($sPara)) {
                foreach ($sPara as $para) {
                    $para = trim($para);
                    if (filter_var($para, FILTER_VALIDATE_EMAIL)) {
                        $bReceberEmail = $this->CI->usuario_model->receberEmail($para);

                        if ($bReceberEmail)
                            $this->configEmail->AddAddress($para);
                    }
                }
            } else {
                $sPara = trim($sPara);
                if (filter_var($sPara, FILTER_VALIDATE_EMAIL)) {
                    $bReceberEmail = $this->CI->usuario_model->receberEmail($para);

                    if ($bReceberEmail)
                        $this->configEmail->AddAddress($sPara);
                }
            }

            $this->configEmail->Subject = strip_tags($sAssunto) . " - " . NOME_CLIENTE;

            if (!empty($nIdNotificacao)) {
                $this->CI->load->library('encrypt');
                $nIdNotificacao = $this->CI->encrypt->encode($nIdNotificacao);
                $sMensagem .= '<br /><br /><a href="' . base_url() . 'painel?t=' . urlencode($nIdNotificacao) . '">Link de Acesso</a>';
            }
            return $this->send($sMensagem, $sAssunto);
        } else {
            return false;
        }
    }

    private function send($sMensagem, $sAssunto) {
        $vVars = array(
            'mensagem' => $sMensagem,
            'assunto' => $sAssunto
        );
        $this->configEmail->Body = $this->CI->load->view('template/email', $vVars, true);
        $enviado = $this->configEmail->Send();
        $this->configEmail->ClearAllRecipients();
        $this->configEmail->ClearAttachments();

        return $enviado;
    }

}

?>
