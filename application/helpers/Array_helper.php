<?php

/**
 * @author Romário Nascimento Beckman <gtisuporte@pa.senac.br,romario@pa.senac.br>
 */
class arrayHelper {

    private static function agrupar($vAux, $Item, $Valor, $vsCampos, $type = '') {
        $sCampo = array_shift($vsCampos);
        $sField = isset($Item->{$sCampo}) ? $Item->{$sCampo} : $sCampo;

        if (!isset($vAux[$sField]))
            $vAux[$sField] = array();

        if (count($vsCampos) > 0)
            $vAux[$sField] = self::agrupar($vAux[$sField], $Item, $Valor, $vsCampos, $type);
        else {
            switch ($type) {
                case 'id':
                    $vAux[$sField] = $Item->{$Valor};
                    break;
                case 'object':
                    $vAux[$sField][] = $Item;
                    break;
                case 'object_id':
                    $vAux[$sField] = $Item;
                    break;
                default:
                    $vAux[$sField][] = $Item->{$Valor};
                    break;
            }
        }


        return $vAux;
    }

    static function autoIncrementById($vDados, $sValor, ...$vsCampos) {
        $array = array();
        if (!empty($vDados)) {
            foreach ($vDados as $Item) {
                $Item = (object) $Item;
                if (!empty($vsCampos)) {
                    $array = self::agrupar($array, $Item, $sValor, $vsCampos, 'id');
                } else {
                    $array[] = $Item->{$sValor};
                }
            }
        }
        return $array;
    }

    static function autoIncrement($vDados, $sValor, ...$vsCampos) {
        $array = array();
        if (!empty($vDados)) {
            foreach ($vDados as $Item) {
                $Item = (object) $Item;
                if (!empty($vsCampos)) {
                    $array = self::agrupar($array, $Item, $sValor, $vsCampos);
                } else {
                    $array[] = $Item->{$sValor};
                }
            }
        }
        return $array;
    }

    static function autoIncrementObject($vDados, ...$vsCampos) {
        $array = array();
        if (!empty($vDados)) {
            foreach ($vDados as $Item) {
                $Item = (object) $Item;
                if (!empty($vsCampos)) {
                    $array = self::agrupar($array, $Item, '', $vsCampos, 'object');
                } else {
                    $array[] = $Item;
                }
            }
        }
        return $array;
    }
    
    static function autoIncrementObjectById($vDados, ...$vsCampos) {
        $array = array();
        if (!empty($vDados)) {
            foreach ($vDados as $Item) {
                $Item = (object) $Item;
                if (!empty($vsCampos)) {
                    $array = self::agrupar($array, $Item, '', $vsCampos, 'object_id');
                } else {
                    $array = $Item;
                }
            }
        }
        return $array;
    }

}

?>