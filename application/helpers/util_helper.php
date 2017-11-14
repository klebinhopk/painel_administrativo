<?php

/**
 * @author Romário Nascimento Beckman <gtisuporte@pa.senac.br,romario@pa.senac.br>
 */
class UtilHelper {

    static function decimalParaBanco($valor) {
        return (DOUBLE) str_replace(",", ".", str_replace(".", "", str_replace('R$', '', trim($valor))));
    }

    static function decimalParaPagina($valor, $decimals = 2) {
        return number_format($valor, $decimals, ",", ".");
    }

    static function removeAcentuacao($string) {
        $characteres = array(
            'Ð' => 'Dj', 'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A',
            'Å' => 'A', 'Æ' => 'A', 'Ç' => 'C', 'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I',
            'Ï' => 'I', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U',
            'Û' => 'U', 'Ü' => 'U', 'Ý' => 'Y', 'Þ' => 'B', 'ß' => 'Ss', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a',
            'å' => 'a', 'æ' => 'a', 'ç' => 'c', 'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i',
            'ï' => 'i', 'ð' => 'o', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ø' => 'o', 'ù' => 'u',
            'ú' => 'u', 'û' => 'u', 'ý' => 'y', 'ý' => 'y', 'þ' => 'b', 'ÿ' => 'y'
        );
        return strtr($string, $characteres);
    }

    static function printR($sValor) {
        echo "\n\n<pre>\n";
        print_r($sValor);
        echo "\n</pre>\n\n";
    }

    static function varDump($sValor) {
        echo "\n\n<pre>\n";
        var_dump($sValor);
        echo "\n</pre>\n\n";
    }

    static function gerarSenha($tamanho = 6, $maiuscula = true, $minuscula = true, $numeros = true, $codigos = false) {
        $maius = "ABCDEFGHIJKLMNOPQRSTUWXYZ";
        $minus = "abcdefghijklmnopqrstuwxyz";
        $numer = "0123456789";
        $codig = '!@#$%&*()-+.,;?{[}]^><:|';

        $base = '';
        $base .= ($maiuscula) ? $maius : '';
        $base .= ($minuscula) ? $minus : '';
        $base .= ($numeros) ? $numer : '';
        $base .= ($codigos) ? $codig : '';

        srand((float) microtime() * 10000000);
        $senha = '';
        for ($i = 0; $i < $tamanho; $i++) {
            $senha .= substr($base, rand(0, strlen($base) - 1), 1);
        }
        return $senha;
    }

    static function substr($sString, $nComprimento = 100, $sUltimaOcorencia = " ", $sEnd = ' (...)') {
        $sString = strip_tags($sString);

        if (strlen($sString) > $nComprimento) {
            $sString = substr($sString, 0, $nComprimento);
            $sString = substr($sString, 0, strrpos($sString, $sUltimaOcorencia));
            return $sString . $sEnd;
        } else {
            return $sString;
        }
    }

    static function array2xml($x, $debug = false, $header = true) {
        $enterchar = "";
        if (empty($x))
            return false;

        if ($debug)
            $enterchar = "\n";

        if ($header) {
            header('Content-Type: text/xml; charset=UTF-8');
            header('Content-Disposition: inline; filename=file.xml');

            echo '<?' . 'xml version="1.0" encoding="UTF-8"' . '?' . '>' . $enterchar;
            echo '<root>' . $enterchar;
        }

        foreach ($x as $field => $value) {
            $temp = explode(' ', $field);
            $field2 = $temp[0];
            if (is_array($value)) {
                if (is_numeric($field)) {
                    $field = 'reg id="' . $field . '"';
                    $field2 = 'reg';
                }
                echo '<' . $field . '>' . $enterchar;
                array2xml($value, $debug, false);
                echo '</' . $field2 . '>' . $enterchar;
            } else {

                if (!is_numeric($field)) {
                    if ((strpos($value, '<') !== false) || (strpos($value, '>') !== false) || (strpos($value, '&') !== false)) {
                        echo '<' . $field . '><![CDATA[' . $value . ']]></' . $field2 . '>' . $enterchar;
                    } else
                        echo '<' . $field . '>' . $value . '</' . $field2 . '>' . $enterchar;
                }

                //Strip numeric keys to economize
                /*
                  if(!is_numeric($field)) {
                  if((is_numeric($value)) || empty($value) || (!$usarcdata)) echo "<$field>$value</$field2>$enterchar";
                  else echo "<$field><![CDATA[$value]]></$field2>$enterchar";
                  }
                 */
            }
        }

        if ($header)
            echo '</root>';
    }

    static function trataCpfCnpj($sValor) {
        return preg_replace('/[.\/-]/', '', $sValor);
    }

    static function removeHtml($sText) {
        return htmlspecialchars(strip_tags($sText), ENT_QUOTES);
    }

    static function trataNome($sNome) {
        $sNome = str_ireplace('_', ' ', $sNome);
        $sNome = ucwords($sNome);

        $sNome = str_ireplace('cao', 'ção', $sNome);
        $sNome = str_ireplace('icia', 'ícia', $sNome);
        $sNome = str_ireplace('ssao', 'ssão', $sNome);
        $sNome = str_ireplace('aria', 'ária', $sNome);
        $sNome = str_ireplace('ario', 'ário', $sNome);
        $sNome = str_ireplace('encia', 'ência', $sNome);
        $sNome = str_ireplace('Numero', 'Número', $sNome);
        $sNome = str_ireplace('Endereco', 'endereço', $sNome);
        $sNome = str_ireplace('images', 'imagens', $sNome);
        $sNome = str_ireplace('cien', 'ciên', $sNome);
        $sNome = str_ireplace('metodos', 'métodos', $sNome);
        return $sNome;
    }

    static function gerarUrlEncurtada($url) {
        $xml = simplexml_load_file("http://migre.me/api.xml?url=" . urlencode($url));

        if ($xml->error != 0) {
            return (STRING) $xml->errormessage;
        } else {
            return (STRING) $xml->migre;
        }
    }

    static function arrayMapEmpty($valor) {
        if (!empty($valor) OR is_numeric($valor)) {
            return $valor;
        } else {
            return NULL;
        }
    }

    static function utf8Encode($sText) {
        if (mb_detect_encoding($sText, 'UTF-8', true) == 'UTF-8')
            return $sText;
        else
            return utf8_encode($sText);
    }

    static function randColor() {
        $chars = "ABCDEF0123456789";
        $size = strlen($chars);
        $str = '';
        for ($j = 0; $j < 6; $j++) {
            $str .= $chars[rand(0, $size - 1)];
        }
        return $str;
    }

    static function printComentarioHtml($texto) {
        echo "\n<!-- {$texto} -->\n";
    }

    static function urlAmigavel($sUrl, $sSeparator = NULL) {
        $sTraco = !empty($sSeparator) ? $sSeparator : '-';
        $sUrl = Util::removeAcentuacao($sUrl);
        $sUrl = preg_replace('/[^a-z0-9 ]/i', '', $sUrl);
        return url_title($sUrl, $sTraco, TRUE);
    }

    static function filtroArray($valor) {
        return is_numeric($valor) OR ! empty($valor);
    }

    static function simNao($val) {
        return $val ? 'Sim' : 'Não';
    }

    static function removecharactersEspecial($string, $sExcecao = '') {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
        $string = preg_replace('/[^A-Za-z0-9' . $sExcecao . '\-]/', '', $string); // Removes special chars.
        return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
    }

    static function linkify($text) {
        $reg_exUrl = '/\b(?:(?:https?|ftp|file):\/\/|www\.|ftp\.)[-A-Z0-9+&@#\/%=~_|$?!:,.]*[A-Z0-9+&@#\/%=~_|$]/i';
        preg_match_all($reg_exUrl, $text, $url);

        if (isset($url[0]) AND ! empty($url[0])) {
            foreach ($url[0] as $slink)
                $text = str_replace($slink, "<a target='_BLANK' href='{$slink}'>{$slink}</a>", $text);
            return $text;
        } else {
            return $text;
        }
    }

    // USAR COM ARRAY_MAP
    static function toUppercase($texto) {
        return strtoupper($texto);
    }

    static public function url_paginate($vParams = array()) {
        $CI = &get_instance();
        $vArray = (ARRAY) $CI->input->get(NULL, TRUE);
        if (isset($vArray['per_page']))
            unset($vArray['per_page']);
        return current_url() . '?' . http_build_query(array_merge($vArray, $vParams));
    }

}

?>