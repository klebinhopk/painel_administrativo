<?php

/**
 * @author Romário Nascimento Beckman <gtisuporte@pa.senac.br,romario@pa.senac.br>
 */
class FormularioHelper {

    static function inputDataInicio($nameDataInicio, $nameDataFim, $valorDataInicio = NULL, $classRequired = NULL) {
        $sIdIni = UtilHelper::removecharactersEspecial($nameDataInicio);
        $sIdFim = UtilHelper::removecharactersEspecial($nameDataFim);
        return form_input($nameDataInicio, set_value($nameDataInicio, (!empty($valorDataInicio) ? DataHelper::converteDataParaPagina($valorDataInicio) : '')), 'size="11" id="' . $sIdIni . '_0" data-target="#' . $sIdFim . '_1" class="data-range-start form-control dateBR ' . $classRequired . '"');
    }

    static function inputDataFim($nameDataInicio, $nameDataFim, $valorDataFim = NULL, $classRequired = NULL) {
        $sIdIni = UtilHelper::removecharactersEspecial($nameDataInicio);
        $sIdFim = UtilHelper::removecharactersEspecial($nameDataFim);
        return form_input($nameDataFim, set_value($nameDataFim, (!empty($valorDataFim) ? DataHelper::converteDataParaPagina($valorDataFim) : '')), 'size="11" id="' . $sIdFim . '_1" data-target="#' . $sIdIni . '_0" class="data-range-end form-control dateBR ' . $classRequired . '"');
    }

    static function intervaloDataPadrao($nameDataInicio, $nameDataFim, $valorDataInicio = NULL, $valorDataFim = NULL, $classRequired = NULL) {
        $sHtml = "";
        $sHtml .= "<div class='row'>";
        $sHtml .= "<div class='col-sm-6'><div class='form-group'>";
        $sHtml .= self::inputDataInicio($nameDataInicio, $nameDataFim, $valorDataInicio, $classRequired);
        $sHtml .= "</div></div>";
        $sHtml .= "<div class='col-sm-6'><div class='form-group'>";
        $sHtml .= self::inputDataFim($nameDataInicio, $nameDataFim, $valorDataFim, $classRequired);
        $sHtml .= "</div></div>";
        $sHtml .= "</div>";
        return $sHtml;
    }

    static function radioAtivo($valor = NULL) {
        return self::radioSimNao('ativo', $valor);
    }

    static function radioSimNao($sName, $valor = NULL) {
        $sHtml = self::radioInline($sName, 1, "Sim", is_null($valor) ? FALSE : $valor == 1);
        $sHtml .= self::radioInline($sName, 0, "Não", is_null($valor) ? FALSE : $valor == 0);
        return $sHtml;
    }

    static function checkbox($sName, $sValue, $sTitulo, $bChecked = FALSE, $sClass = "") {
        $sHtml = '<div class="checkbox"><label>';
        $sHtml .= form_checkbox($sName, $sValue, $bChecked, "class={$sClass}");
        $sHtml .= ' ' . $sTitulo;
        $sHtml .= '</label></div>';
        return $sHtml;
    }

    static function checkboxInline($sName, $sValue, $sTitulo, $bChecked = FALSE, $sClass = "") {
        $sHtml = '<label class="checkbox-inline">';
        $sHtml .= form_checkbox($sName, $sValue, $bChecked, "class={$sClass}");
        $sHtml .= ' ' . $sTitulo;
        $sHtml .= '</label>';
        return $sHtml;
    }

    static function radio($sName, $sValue, $sTitulo, $bChecked = FALSE, $sClass = "") {
        $sHtml = '<div class="radio"><label>';
        $sHtml .= form_radio($sName, $sValue, $bChecked, "class={$sClass}");
        $sHtml .= ' ' . $sTitulo;
        $sHtml .= '</label></div>';
        return $sHtml;
    }

    static function radioInline($sName, $sValue, $sTitulo, $bChecked = FALSE, $sClass = "") {
        $sHtml = '<label class="radio-inline">';
        $sHtml .= form_radio($sName, $sValue, $bChecked, "class={$sClass}");
        $sHtml .= ' ' . $sTitulo;
        $sHtml .= '</label>';
        return $sHtml;
    }

}

?>