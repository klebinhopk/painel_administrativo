<?php

/**
 * @author Romário Nascimento Beckman <gtisuporte@pa.senac.br,romario@pa.senac.br>
 */
class mascaraHelper {

    static function mask($val, $mask) {
        if (empty($val))
            return '';

        $maskared = '';
        $k = 0;
        for ($i = 0; $i <= strlen($mask) - 1; $i++) {
            if ($mask[$i] == '#') {
                if (isset($val[$k]))
                    $maskared .= $val[$k++];
            }
            else {
                if (isset($mask[$i]))
                    $maskared .= $mask[$i];
            }
        }
        return $maskared;
    }

    static function cpf($val) {
        return self::mask($val, '###.###.###-##');
    }

    static function cnpj($val) {
        return self::mask($val, '##.###.###/####-##');
    }

    static function cep($val) {
        return self::mask($val, '#####-###');
    }

}

?>