<?php

/**
 * @author Romário Nascimento Beckman <gtisuporte@pa.senac.br,romario@pa.senac.br>
 */
class validacaoHelper {

    static function validaCPF($nCpf) {
        $nCpf = preg_replace('/[.-]/', "", $nCpf);
        $proibidos = array('11111111111', '22222222222', '33333333333',
            '44444444444', '55555555555', '66666666666', '77777777777',
            '88888888888', '99999999999', '00000000000', '12345678909');
        if (is_numeric($nCpf) AND strlen($nCpf) == 11 AND ! in_array($nCpf, $proibidos)) {
            $a = 0;
            for ($i = 0; $i < 9; $i++) {
                $a += ( $nCpf[$i] * (10 - $i));
            }
            $b = ($a % 11);
            $a = (($b > 1) ? (11 - $b) : 0);
            if ($a != $nCpf[9]) {
                return false;
            }
            $a = 0;
            for ($i = 0; $i < 10; $i++) {
                $a += ( $nCpf[$i] * (11 - $i));
            }
            $b = ($a % 11);
            $a = (($b > 1) ? (11 - $b) : 0);
            if ($a != $nCpf[10]) {
                return false;
            }

            return true;
        } else {
            return false;
        }
    }

    static function validaCNPJ($str) {
        if (!preg_match('|^(\d{2,3})\.?(\d{3})\.?(\d{3})\/?(\d{4})\-?(\d{2})$|', $str, $matches))
            return false;

        array_shift($matches);

        $str = implode('', $matches);
        if (strlen($str) > 14)
            $str = substr($str, 1);

        $sum1 = 0;
        $sum2 = 0;
        $sum3 = 0;
        $calc1 = 5;
        $calc2 = 6;

        for ($i = 0; $i <= 12; $i++) {
            $calc1 = $calc1 < 2 ? 9 : $calc1;
            $calc2 = $calc2 < 2 ? 9 : $calc2;

            if ($i <= 11)
                $sum1 += $str[$i] * $calc1;

            $sum2 += $str[$i] * $calc2;
            $sum3 += $str[$i];
            $calc1--;
            $calc2--;
        }

        $sum1 %= 11;
        $sum2 %= 11;

        return ($sum3 && $str[12] == ($sum1 < 2 ? 0 : 11 - $sum1) && $str[13] == ($sum2 < 2 ? 0 : 11 - $sum2)) ? $str : false;
    }

}

?>