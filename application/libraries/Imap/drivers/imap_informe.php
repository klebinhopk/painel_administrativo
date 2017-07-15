<?php

class imap_informe extends CI_Driver {

    const categoria = 198;

    private $oMail, $oUsuario;

    public function novoChamado($oMail, $oUsuario) {
        $this->oMail = $oMail;
        $this->oUsuario = $oUsuario;

        $sMensagem = $this->mensagem($this->oMail->msgno);
        $vAnexos = $this->anexos($this->oMail->msgno);

        $this->ci->load->library('envia_email');
        $this->ci->envia_email->enviar("romario@pa.senac.br", 'Informe Teste - ' . $this->oMail->subject, $sMensagem);
    }

}

?>