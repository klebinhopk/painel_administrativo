<?php

/**
 * @author Romário Nascimento Beckman <gtisuporte@pa.senac.br,romario@pa.senac.br>
 */
class DataHelper {

    static function getTurno($var) {
        // Usar funcao timr('w'); do php
        $hora = explode(':', $var);

        switch ($hora) {
            case $hora[0] >= 1 && $hora[0] < 12: return "Manhã";
                break;
            case $hora[0] >= 13 && $hora[0] < 18: return "Tarde";
                break;
            case $hora[0] >= 18 && $hora[0] < 23: return "Noite";
                break;
        }
    }

    static function getSemana($var) {
        // Usar funcao date('w'); do php
        switch ($var) {
            case"0": return $var = "Domingo";
                break;
            case"1": return $var = "Segunda-Feira";
                break;
            case"2": return $var = "Ter&ccedil;a-Feira";
                break;
            case"3": return $var = "Quarta-Feira";
                break;
            case"4": return $var = "Quinta-Feira";
                break;
            case"5": return $var = "Sexta-Feira";
                break;
            case"6": return $var = "S&aacute;bado";
                break;
        }
    }

    static function getSemanaCurto($var) {
        // Usar funcao date('w'); do php
        switch ($var) {
            case"0": return $var = "Dom";
                break;
            case"1": return $var = "Seg";
                break;
            case"2": return $var = "Ter";
                break;
            case"3": return $var = "Qua";
                break;
            case"4": return $var = "Qui";
                break;
            case"5": return $var = "Sex";
                break;
            case"6": return $var = "Sab";
                break;
        }
    }

    static function getMes($var) {
        // Usar funcao date('n'); do php
        switch ($var) {
            case"1": return $var = "Janeiro";
                break;
            case"2": return $var = "Fevereiro";
                break;
            case"3": return $var = "Mar&ccedil;o";
                break;
            case"4": return $var = "Abril";
                break;
            case"5": return $var = "Maio";
                break;
            case"6": return $var = "Junho";
                break;
            case"7": return $var = "Julho";
                break;
            case"8": return $var = "Agosto";
                break;
            case"9": return $var = "Setembro";
                break;
            case"10": return $var = "Outubro";
                break;
            case"11": return $var = "Novembro";
                break;
            case"12": return $var = "Dezembro";
                break;
        }
    }

    static function getMesCurto($var) {
        // Usar funcao date('n'); do php
        switch ($var) {
            case"1": return $var = "jan";
                break;
            case"2": return $var = "fev";
                break;
            case"3": return $var = "mar";
                break;
            case"4": return $var = "abr";
                break;
            case"5": return $var = "mai";
                break;
            case"6": return $var = "jun";
                break;
            case"7": return $var = "jul";
                break;
            case"8": return $var = "ago";
                break;
            case"9": return $var = "set";
                break;
            case"10": return $var = "out";
                break;
            case"11": return $var = "nov";
                break;
            case"12": return $var = "dez";
                break;
        }
    }

    private static function converteDmaParaAmd($sData, $sSeparador = "", $bValidate = FALSE) {
        if (!empty($sData)) {
            $sSeparadorQuebra = !is_numeric($sData[2]) ? $sData[2] : (!is_numeric($sData[4]) ? $sData[4] : "/");

            list ( $nDia, $nMes, $nAno ) = explode($sSeparadorQuebra, $sData);

            $sSeparador = $sSeparador ? $sSeparador : $sSeparadorQuebra;
            if (checkdate((INT) $nMes, (INT) $nDia, (INT) $nAno))
                return ($nAno . $sSeparador . $nMes . $sSeparador . $nDia);
            else
                return $bValidate ? NULL : $sData;
        } else {
            return $bValidate ? NULL : $sData;
        }
    }

    static public function converteDataParaBanco($sDataHora, $bValidate = FALSE) {
        if (!empty($sDataHora)) {
            $sDataHora = explode(" ", trim($sDataHora));
            $sDataHora[0] = self::converteDmaParaAmd($sDataHora[0], "-", $bValidate);

            return !empty($sDataHora[0]) ? trim(implode(" ", $sDataHora)) : NULL;
        } else {
            return NULL;
        }
    }

    private static function converteAmdParaDma($sData, $sSeparador = "", $bValidate = FALSE) {
        if (!empty($sData)) {
            $sSeparadorQuebra = !is_numeric($sData[4]) ? $sData[4] : (!is_numeric($sData[2]) ? $sData[2] : "-");

            list ( $nAno, $nMes, $nDia ) = explode($sSeparadorQuebra, $sData);

            $sSeparador = $sSeparador ? $sSeparador : $sSeparadorQuebra;
            if (checkdate((INT) $nMes, (INT) $nDia, (INT) $nAno))
                return ($nDia . $sSeparador . $nMes . $sSeparador . $nAno);
            else
                return $bValidate ? NULL : $sData;
        } else {
            return $bValidate ? NULL : $sData;
        }
    }

    static public function converteDataParaPagina($sDataHora, $bValidate = FALSE) {
        if (!empty($sDataHora)) {
            $sDataHora = explode(" ", trim($sDataHora));
            $sDataHora[0] = self::converteAmdParaDma($sDataHora[0], "/", $bValidate);

            return !empty($sDataHora[0]) ? trim(implode(" ", $sDataHora)) : NULL;
        } else {
            return NULL;
        }
    }

    static public function converteDataCurtaParaPagina($sDataHora, $bValidate = FALSE) {
        if (!empty($sDataHora)) {
            $sDataHora = explode(" ", $sDataHora);
            $sDataHora[0] = self::converteAmdParaDma($sDataHora[0], "/", $bValidate);

            return !empty($sDataHora[0]) ? $sDataHora[0] : NULL;
        } else {
            return NULL;
        }
    }

    static function tempoDecorrido($AnoMesDiaInicio, $AnoMesDiaFim = NULL, $nLoop = 1) {
        if (empty($AnoMesDiaInicio))
            $AnoMesDiaInicio = date("Y-m-d H:i:s");
        if (empty($AnoMesDiaFim))
            $AnoMesDiaFim = date("Y-m-d H:i:s");

        $time = abs(strtotime($AnoMesDiaFim) - strtotime($AnoMesDiaInicio)); // to get the time since that moment
        $tokens = array(31536000 => 'ano', 31536000 / 12 => 'mês', 31536000 / 52 => 'semana', (31536000 / 365.2425) => 'dia', 3600 => 'hora', 60 => 'minuto', 1 => 'segundo');
        $sReturn = "";

        $nIndice = 0;
        foreach ($tokens as $unit => $text) {
            if ($time < $unit)
                continue;

            if ($nIndice < $nLoop AND $time > 0) {
                $numberOfUnits = floor($time / $unit);

                if ($numberOfUnits > 0) {
                    $text = ($text == 'mês' && $numberOfUnits > 1) ? 'mese' : $text;
                    $sReturn .= $numberOfUnits . ' ' . $text . (($numberOfUnits > 1) ? 's' : '') . ($nIndice == $nLoop - 2 ? " e " : " ");
                    $time = $time - ($unit * $numberOfUnits);
                }
            }

            $nIndice++;
        }

        $sReturn = trim($sReturn);
        return empty($sReturn) ? "pouco tempo" : $sReturn;
    }

    static function diferencaDataEmDias($sDe, $sAte) {
        if ($sDe AND $sAte) {
            $sDe = strtotime(Util::converteDataParaBanco($sDe));
            $sAte = strtotime(Util::converteDataParaBanco($sAte));

            if ($sDe < $sAte)
                return floor(($sAte - $sDe) / 86400);
            else
                return ceil(($sAte - $sDe) / 86400);
        } else
            return false;
    }

    static function convertDecimalEmHora($sNum) {
        $sNum = number_format($sNum, 2);
        $num_temp = explode('.', $sNum);
        $num_temp[1] = $sNum - (number_format($num_temp[0], 2));
        $saida = number_format(((($num_temp[1]) * 60 / 100) + $num_temp[0]), 2);
        $saida = strtr($saida, '.', ':');
        return $saida;
    }

    static function convertHoraEmDecimal($time) {
        $separar = explode(':', $time);
        $decimal = ($separar[0] * 3600) + ($separar[1] * 60);
        return $decimal;
    }

    private static function diasUteisCalculo($begin, $end) {
        $no_days = 0;
        $weekends = 0;
        while ($begin <= $end) {
            $no_days++; // no of days in the given interval
            $what_day = date("N", $begin);
            if ($what_day > 5) { // 6 and 7 are weekend days
                $weekends++;
            }
            $begin += 86400; // +1 day
        }
        return $no_days - $weekends;
    }

    static function diasUteis($startDate, $endDate) {
        $begin = strtotime($startDate);
        $end = strtotime($endDate);
        if ($begin > $end) {
            $dInicio = $end;
            $dFim = $begin;
            return "(-) " . self::diasUteisCalculo($dInicio, $dFim);
        } else {
            $dInicio = $begin;
            $dFim = $end;
            return self::diasUteisCalculo($dInicio, $dFim);
        }
    }

    static function calcularDiasUteis($str_data, $int_qtd_dias_somar = 7) {
        // Caso seja informado uma data do MySQL do tipo DATETIME - aaaa-mm-dd 00:00:00
        // Transforma para DATE - aaaa-mm-dd
        $str_data = substr($str_data, 0, 10);
        // Se a data estiver no formato brasileiro: dd/mm/aaaa
        // Converte-a para o padrão americano: aaaa-mm-dd
        if (preg_match("@/@", $str_data) == 1) {
            $str_data = implode("-", array_reverse(explode("/", $str_data)));
        }
        $array_data = explode('-', $str_data);
        $count_days = 1;
        $int_qtd_dias_uteis = 0;
        while ($int_qtd_dias_uteis < $int_qtd_dias_somar) {
            $count_days++;
            if (( $dias_da_semana = gmdate('w', strtotime('+' . $count_days . ' day', mktime(0, 0, 0, $array_data[1], $array_data[2], $array_data[0]))) ) != '0' && $dias_da_semana != '6') {
                $int_qtd_dias_uteis++;
            }
        }
        return gmdate('d/m/Y', strtotime('+' . $count_days . ' day', strtotime($str_data)));
    }

    static function ultimoDiaMes($sMes, $sAno = NULL) {
        $mes = $sMes;      // Mês desejado, pode ser por ser obtido por POST, GET, etc.
        $ano = !empty($sAno) ? $sAno : date("Y"); // Ano atual
        return date("t", mktime(0, 0, 0, $mes, '01', $ano));
    }

    static function calculaIdade($data_nascimento) {

        $data_nasc = explode("-", $data_nascimento);
        $data = explode("-", date("Y-m-d"));
        $anos = $data[0] - $data_nasc[0];

        if ($data_nasc[1] >= $data[1]) {
            if ($data_nasc[0] <= $data[0]) {
                return $anos;
            } else {
                return $anos - 1;
            }
        } else {
            return $anos;
        }
    }

    static function tempoDecorridoEmDias($AnoMesDiaInicio, $AnoMesDiaFim = NULL, $nLoop = 1) {
        if (empty($AnoMesDiaInicio))
            $AnoMesDiaInicio = date("Y-m-d H:i:s");
        if (empty($AnoMesDiaFim))
            $AnoMesDiaFim = date("Y-m-d H:i:s");

        $time = abs(strtotime($AnoMesDiaFim) - strtotime($AnoMesDiaInicio)); // to get the time since that moment
        $tokens = array((31536000 / 365.2425) => 'dia');
        $sReturn = "";

        $nIndice = 0;
        foreach ($tokens as $unit => $text) {
            if ($time < $unit)
                continue;

            if ($nIndice < $nLoop AND $time > 0) {
                $numberOfUnits = floor($time / $unit);

                if ($numberOfUnits > 0) {
                    $text = ($text == 'mês' && $numberOfUnits > 1) ? 'mese' : $text;
                    $sReturn .= $numberOfUnits . ' ' . $text . (($numberOfUnits > 1) ? 's' : '') . " ";
                    $time = $time - ($unit * $numberOfUnits);
                }
            }

            $nIndice++;
        }

        $sReturn = trim($sReturn);
        return empty($sReturn) ? "pouco tempo" : $sReturn;
    }

    static function between($Data, $DataInicio, $DataFinal) {
        $Data = self::converteDataParaBanco($Data);
        $DataInicio = self::converteDataParaBanco($DataInicio);
        $DataFinal = self::converteDataParaBanco($DataFinal);

        $Data = strtotime($Data);
        $DataInicio = strtotime($DataInicio);
        $DataFinal = strtotime($DataFinal);
        
        return (BOOL) ($DataInicio <= $Data AND $DataFinal >= $Data);
    }

}

?>