<?php

namespace Painel;

class Usu_log_dao extends \ABS_Dao {

    protected function load() {
        $this->_sTable = 'usu_log';
    }

    public function fetchPaginate(array $vData = array(), int $nPerPage, int $nPage) {
        
    }

}
