<?php

class imap extends CI_Driver_Library {

    public $ci, $mailbox, $mime;
    public $valid_drivers;

    function __construct() {
        $this->ci = & get_instance();
        $this->valid_drivers = array('helpdesk', 'informe');
        $this->_conexao();
        $this->setMime();
    }

    private function _conexao() {
        $sServer = "mail.pa.senac.br";
        $sLogin = "sends";
        $sSenha = "816xy0";

        $this->mailbox = imap_open("{" . $sServer . ":110/pop3}INBOX", $sLogin, $sSenha, NULL, 1);

        if (!$this->mailbox)
            throw new Exception('Dados de acesso inválidos para conexão IMAP ou conexão não disponível. ' . imap_last_error());
    }

    public function listarCaixaEntrada($bExcluirEmails = TRUE) {
        $vMails = imap_fetch_overview($this->mailbox, "1:*"); // This is fetching an overview of all emails 

        if (!empty($vMails)) {
            foreach ($vMails as $nIndice => $oMail) {
                if ($bExcluirEmails)
                    imap_delete($this->mailbox, $oMail->msgno, FT_UID);

                $oHeaderInfo = imap_headerinfo($this->mailbox, $oMail->msgno);
                //VALIDA DOMINIO
                if ($oHeaderInfo->from[0]->host != 'pa.senac.br' OR $oMail->from == 'suporte@pa.senac.br' OR $oMail->from == 'informe@pa.senac.br')
                    continue;

                $oMail->from = $this->headerDecode($oMail->from);
                $oMail->subject = $this->headerDecode($oMail->subject);
                $oMail->from_email = $oHeaderInfo->from[0]->mailbox . "@" . $oHeaderInfo->from[0]->host;
                $oMail->usuario = $this->ci->usuario_model->get($oMail->from_email, 'email');

                //VALIDA USUÁRIO DO SEND
                if (empty($oMail->usuario))
                    continue;

                switch (TRUE) {
                    case $oHeaderInfo->toaddress == 'chamado@pa.senac.br':
                        $this->helpdesk->novoChamado($oMail, $oMail->usuario);
                        break;
                    case $oHeaderInfo->toaddress == 'informe@pa.senac.br':
                        $this->informe->novoChamado($oMail, $oMail->usuario);
                        break;
                }

                echo "<p>Host: {$oHeaderInfo->from[0]->host}<br />";
                echo "From: {$oMail->from} ({$oMail->from_email})<br />";
                echo "Subject: {$oMail->subject}<br />";
                echo "ID: {$oMail->msgno}</p>";
            }

            if ($bExcluirEmails)
                imap_expunge($this->mailbox);
        }
    }

    public function anexos($nId) {
        $oStructure = imap_fetchstructure($this->mailbox, $nId);
        $vAnexos = array();

        if (isset($oStructure->parts) && count($oStructure->parts)) {
            for ($i = 0; $i < count($oStructure->parts); $i++) {
                $bArquivo = FALSE;
                $vArquivo = array(
                    'type' => '',
                    'filename' => '',
                    'name' => '',
                    'filepath' => '',
                    'type' => '',
                );

                if ($oStructure->parts[$i]->ifdparameters) {
                    foreach ($oStructure->parts[$i]->dparameters as $object) {
                        if (strtolower($object->attribute) == 'filename') {
                            $bArquivo = TRUE;
                            $vArquivo['filename'] = $this->headerDecode(trim($object->value));
                        }
                    }
                }

                if ($oStructure->parts[$i]->ifparameters) {
                    foreach ($oStructure->parts[$i]->parameters as $object) {
                        if (strtolower($object->attribute) == 'name') {
                            $bArquivo = TRUE;
                            $vArquivo['name'] = $this->headerDecode(trim($object->value));
                        }
                    }
                }

                //VALIDA SE É UM ARQUIVO
                if (!$bArquivo)
                    continue;

                //VALIDA A EXTENSÃO DO ARQUIVO COM O MIME
                $vArquivo['type'] = $this->getExtension($vArquivo['filename']);
                if (in_array(strtolower($oStructure->parts[$i]->subtype), $this->mimeType($vArquivo['type']), TRUE) === FALSE)
                    continue;

                $vArquivo['filename'] = $this->_encrypt($vArquivo);
                $vArquivo['filepath'] = FCPATH . "resources/upload/temp/" . $vArquivo['filename'];
                $anexo = imap_fetchbody($this->mailbox, $nId, $i + 1);
                $handle = fopen($vArquivo['filepath'], "wb");

                switch ($oStructure->parts[$i]->encoding) {
                    case 3:// Base64
                        $anexo = base64_decode($anexo);
                        break;
                    case 4:// Quoted-Printable
                        $anexo = quoted_printable_decode($anexo);
                        break;
                }

                fwrite($handle, $anexo);
                fclose($handle);

                $vAnexos[] = $vArquivo;
            } // for ($i = 0; $i < count($oStructure->parts); $i++) {
        } // if (isset($oStructure->parts) && count($oStructure->parts)) {

        return $vAnexos;
    }

    public function mensagem($nId, $bText = TRUE) {
        $sMail = imap_fetchbody($this->mailbox, $nId, '1.1.1.1', FT_UID);
        if (empty($sMail))
            $sMail = imap_fetchbody($this->mailbox, $nId, '1.1.1', FT_UID);
        if (empty($sMail))
            $sMail = imap_fetchbody($this->mailbox, $nId, '1.1', FT_UID);
        if (empty($sMail))
            $sMail = imap_fetchbody($this->mailbox, $nId, '1', FT_UID);

        $sMail = quoted_printable_decode($sMail);
        $sMail = $this->headerDecode($sMail);

        if ($bText) {
            $sMail = Util::removeHtml($sMail);
            $sMail = str_replace(PHP_EOL, '', $sMail);
            $sMail = str_replace('  ', '', $sMail);
            $sMail = trim($sMail);
            $sMail = str_replace("\n\n", "\n", $sMail);
            $sMail = str_replace("\r\r", "\r", $sMail);
            return $sMail;
        } else {
            return $sMail;
        }
    }

    public function fecharConexao() {
        imap_close($this->mailbox);
    }

    #FUNÇÕES DE APOIO

    private function _encrypt($vAnexo) {
        mt_srand();
        return md5(uniqid(mt_rand())) . '.' . $vAnexo['type'];
    }

    public function headerDecode($text) {
        $text = imap_mime_header_decode($text);
        $text = Util::utf8Encode($text[0]->text);
        return $text;
    }

    public function setMime() {
        $this->mime = array(
//            'csv' => array('x-comma-separated-values', 'comma-separated-values', 'octet-stream', 'vnd.ms-excel', 'x-csv', 'x-csv', 'csv', 'csv', 'excel', 'vnd.msexcel'),
//            'psd' => 'x-photoshop',
            'pdf' => array('pdf', 'x-download'),
//            'xls' => array('excel', 'vnd.ms-excel', 'msexcel'),
//            'ppt' => array('powerpoint', 'vnd.ms-powerpoint'),
//            'swf' => 'x-shockwave-flash',
//            'zip' => array('x-zip', 'zip', 'x-zip-compressed', 'octet-stream'),
//            'rar' => 'octet-stream',
//            'mpga' => 'mpeg',
//            'mp2' => 'mpeg',
            'mp3' => array('mpeg', 'mpg', 'mpeg3', 'mp3'),
//            'wav' => array('x-wav', 'wave', 'wav'),
            'gif' => 'gif',
            'jpeg' => array('jpeg', 'pjpeg'),
            'jpg' => array('jpeg', 'pjpeg'),
            'jpe' => array('jpeg', 'pjpeg'),
            'png' => array('png', 'x-png'),
            'txt' => 'plain',
//            'rem' => 'plain',
//            'pag' => 'plain',
//            'text' => 'plain',
//            'log' => array('plain', 'x-log'),
//            'rtf' => 'rtf',
//            'xml' => 'xml',
//            'xsl' => 'xml',
//            'mpeg' => 'mpeg',
//            'mpg' => 'mpeg',
//            'mpe' => 'mpeg',
//            'qt' => 'quicktime',
//            'mov' => 'quicktime',
//            'avi' => 'x-msvideo',
//            'movie' => 'x-sgi-movie',
//            'docx' => array('octet-stream', 'x-ima', 'vnd.openxmlformats-officedocument.wordprocessingml.document', 'msword'),
//            'xlsx' => array('octet-stream', 'vnd.ms-excel', 'msexcel', 'x-msexcel', 'x-ms-excel', 'x-excel', 'x-dos_ms_excel', 'xls', 'x-xls', 'x-ima', 'vnd.openxmlformats-officedocument.spreadsheetml.sheet'),
//            'word' => array('x-ima', 'msword', 'octet-stream'),
//            'xl' => 'excel',
//            'eml' => array('rfc822', 'plain')
        );
    }

    public function mimeType($type) {
        if (isset($this->mime[$type])) {
            return is_array($this->mime[$type]) ? $this->mime[$type] : array($this->mime[$type]);
        } else {
            return array();
        }
    }

    public function getExtension($filename) {
        $x = strrchr($filename, ".");
        return $x !== FALSE ? str_replace('.', '', strtolower(ltrim($x))) : null;
    }

}

?>