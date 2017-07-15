<?php

/**
 * @author Romário Nascimento Beckman <gtisuporte@pa.senac.br,romario@pa.senac.br>
 */
class arrayHelper {

    private static function agrupar($vAux, $oItem, $sValor, $vsCampos) {
        $sCampo = array_shift($vsCampos);
        $sField = isset($oItem->{$sCampo}) ? $oItem->{$sCampo} : $sCampo;

        if (!isset($vAux[$sField]))
            $vAux[$sField] = array();

        if (count($vsCampos) > 0)
            $vAux[$sField] = self::agrupar($vAux[$sField], $oItem, $sValor, $vsCampos);
        else
            $vAux[$sField][] = $oItem->{$sValor};


        return $vAux;
    }

    static function autoIncrement($vDados, $sValor, ...$vsCampos) {
        $array = array();
        if (!empty($vDados)) {
            foreach ($vDados as $oItem) {
                if (!empty($vsCampos)) {
                    $array = self::agrupar($array, $oItem, $oItem->{$sValor}, $vsCampos);
                } else {
                    $array[] = $oItem->{$sValor};
                }
            }
        }
        return $array;
    }

}

?>