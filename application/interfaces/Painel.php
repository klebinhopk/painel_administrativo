<?php

interface Painel {

    public function loadTemplatePainel($view = NULL, $vars = array(), $return = FALSE);
    
    public function setDados();
}
