<?php

/**
 * @author Romário Nascimento Beckman <gtisuporte@pa.senac.br,romario@pa.senac.br>
 */
class FileuploadHelper {

    static function printHeader() {
        echo '<link rel="stylesheet" href="' . base_url('resources/assets/jquery-file-upload/css/jquery.fileupload.css') . '">';
        echo '<script src="' . base_url('resources/assets/jquery-file-upload/js/jquery.iframe-transport.js') . '"></script>';
        echo '<script src="' . base_url('resources/assets/jquery-file-upload/js/jquery.fileupload.js') . '"></script>';
        echo '<script src="' . base_url('resources/painel/js/fileupload.js') . '"></script>';
    }

    static function printformUpload($sName, $sLocal, $sParametros = '', $sSave = 'db', $sTextoBtn = 'Selecionar arquivos') {
        if ($sSave != 'db' AND $sSave != 'temp') {
            $sSave = 'temp';
        }

        echo '<div class="arquivos">';
        echo '<div><div class="progress"><div class="progress-bar progress-bar-success"></div></div><div class="lista-arquivos up-lista-arquivos_0"></div></div>';

        echo '<div><span class="btn btn-info fileinput-button"><span>' . $sTextoBtn . '</span>';
        echo form_upload($sName, '', "class='fileupload' multiple data-local='" . $sLocal . "' data-url='" . base_url('painel/upload/adicionar/' . $sLocal . '?' . $sParametros . '&save=' . $sSave) . "'");
        echo '</span></div>';

        echo '</div>';
    }

}

?>