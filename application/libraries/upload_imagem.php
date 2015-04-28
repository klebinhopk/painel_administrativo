<?php

/**
 * @author Romário Nascimento Beckman <romabeckman@gmail.com,romario@pa.senac.br>
 * @link https://www.linkedin.com/in/romabeckman
 * @link https://www.facebook.com/romabeckman
 * @link http://twitter.com/romabeckman
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

include_once APPPATH . '/libraries/wideimage/WideImage.php';

class upload_imagem {

    function upload_imagem() {
        $CI = & get_instance();
        log_message('Debug', 'WideImage class is loaded.');
    }

    function load($param = NULL) {
        return new WideImage();
    }

    function doUpload($vImage, $vvConfig, $nQualidade = 70) {
        $vsNameImage = array();

        if (isset($vvConfig['root']))
            $vvConfig = array('default' => $vvConfig);

        if (isset($vImage[0])) {
            foreach ($vImage[0]['name'] as $nIndice => $sNameImagem) {
                if ($vImage['error'][$nIndice] === UPLOAD_ERR_OK) {
                    $vsImagemFile = array(
                        "name" => $sNameImagem,
                        "type" => $vImage['type'][$nIndice],
                        "tmp_name" => $vImage['tmp_name'][$nIndice],
                        "error" => $vImage['error'][$nIndice],
                        "size" => $vImage['size'][$nIndice]
                    );

                    $vsNameImage[] = self::doUploadImagem($vsImagemFile, $vvConfig, $nQualidade);
                }
            }
        } else {
            $sExtensao = strtolower(strrchr($vImage['name'], '.'));
            if ($sExtensao == '.zip') {
                $vsNameImage = self::doUploadImagemZip($vImage, FCPATH . "resources/upload/temp/", $vvConfig, $nQualidade);
            } else {
                $vsNameImage[] = self::doUploadImagem($vImage, $vvConfig, $nQualidade);
            }
        }

        return $vsNameImage;
    }

    function doUploadImagem($vImage, $vvConfig, $nQualidade = 70) {
        $sExtensao = self::VerificaExtensaoImagem($vImage['name']);
        $sName = NULL;

        if (isset($vvConfig['root']))
            $vvConfig = array('default' => $vvConfig);

        if ($sExtensao == 'png')
            $nQualidade = ($nQualidade / 10) - 1;
        foreach ($vvConfig as $vConfig) {
            $sName = array_shift(explode('.', $vImage['name']));
            $sName = self::removeAcentuacao($sName);
            $sName = url_title($sName, '-', TRUE);
            $sName = self::validaNomeDaImagem(strtolower($sName), $sExtensao, $vConfig['root']);

            WideImage::load($vImage['tmp_name'])
                    ->resizeDown($vConfig['bound'][0], $vConfig['bound'][1])
                    ->saveToFile($vConfig['root'] . $sName, $nQualidade);
        }

        return $sName;
    }

    function doUploadImagemZip($vZip, $sDirTemp, $vvConfig, $nQualidade = 60) {
        $zip = new ZipArchive;
        $vsPathImages = array();

        if ($zip->open($vZip['tmp_name']) === TRUE) {
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $filename = $zip->getNameIndex($i);
                $fileinfo = pathinfo($filename);

                if (isset($fileinfo['extension'])) {
                    $Tmpname = $sDirTemp . $fileinfo['basename'];
                    copy("zip://" . $vZip['tmp_name'] . "#" . $filename, $Tmpname);

                    foreach ($vvConfig as $vsConfiguracao) {
                        if (is_dir($vsConfiguracao["root"])) {
                            $sExtensao = end(explode('.', $fileinfo['basename']));
                            $sFile = array_shift(explode('.', $fileinfo['basename']));
                            $sFile = $this->removeAcentuacao($sFile);
                            $sFile = url_title($sFile, '-', TRUE);
                            $sFile = $this->validaNomeDaImagem($sFile, $sExtensao, $vsConfiguracao['root']);
                            $endFoto = $vsConfiguracao["root"] . $sFile;

                            if (in_array($sFile, $vsPathImages) == false)
                                $vsPathImages[] = $sFile;

                            if (!in_array(strtolower($sExtensao), array('png', 'jpg', 'jpeg', 'gif'))) {
                                copy($Tmpname, $endFoto);
                            } else {
                                if ($sExtensao == 'png' OR $sExtensao == 'PNG')
                                    $nQualidade = ($nQualidade / 10) - 1;

                                WideImage::load($Tmpname)
                                        ->resizeDown($vsConfiguracao["bound"][0], $vsConfiguracao["bound"][1])
                                        ->saveToFile($endFoto, $nQualidade);
                            }
                        }
                    }
                    unlink($Tmpname);
                }
            }

            $zip->close();
        }
        return $vsPathImages;
    }

    private function verificaExtensaoImagem($verificaExtensao) {
        preg_match("/\.(jpeg|jpg|png|gif){1}$/i", $verificaExtensao, $ext);
        return isset($ext[1]) ? strtolower($ext[1]) : '';
    }

    private function validaNomeDaImagem($Nome, $sExtensao, $sRoot) { // acrescena conador na imagem caso exista nomes de imagens repetidas
        $existe = true;
        $cont = 1;
        $Nome = strtolower($Nome);
        $aux = "$Nome.$sExtensao";
        do {
            if (@file_exists($sRoot . $aux)) {
                $cont++;
                $aux = "{$Nome}-{$cont}.{$sExtensao}";
            } else
                $existe = false;
        }while ($existe);
        return $aux;
    }

    private function removeAcentuacao($string) {
        $characteres = array(
            '?' => 'S', '?' => 's', 'Ð' => 'Dj', '?' => 'Z', '?' => 'z', 'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A',
            'Å' => 'A', 'Æ' => 'A', 'Ç' => 'C', 'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I',
            'Ï' => 'I', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U',
            'Û' => 'U', 'Ü' => 'U', 'Ý' => 'Y', 'Þ' => 'B', 'ß' => 'Ss', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a',
            'å' => 'a', 'æ' => 'a', 'ç' => 'c', 'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i',
            'ï' => 'i', 'ð' => 'o', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ø' => 'o', 'ù' => 'u',
            'ú' => 'u', 'û' => 'u', 'ý' => 'y', 'ý' => 'y', 'þ' => 'b', 'ÿ' => 'y', '?' => 'f', ' ' => '-'
        );
        return strtr($string, $characteres);
    }

}

?>
