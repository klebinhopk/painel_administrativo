<?php

/**
 * @author Romário Nascimento Beckman <gtisuporte@pa.senac.br,romario@pa.senac.br>
 */
class MathHelper {

    static function calculaMutiplos100($nValor) {
        return ($nValor > 0 AND $nValor < 100) ? 100 : round($nValor, -2);
    }

    static function calculaMutiplos1000($nValor) {
        return ($nValor > 0 AND $nValor < 1000) ? 1000 : round($nValor, -3);
    }

    #$nValor1 = VALOR PARCIAL 
    #$nValor2 = VALOR TOTAL (100%)

    static function diffPorcentagem($nValor1, $nValor2) {
        return (($nValor1 - $nValor2) / $nValor2) * 100;
    }

}

?>