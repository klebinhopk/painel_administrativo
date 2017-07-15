<?php

abstract class Upload_model extends MY_Model {

    protected $_sNome;
    protected $_vConfig;

    public function __construct() {
        parent::__construct();
        $this->setConfig();
    }

    final public function getConfig() {
        if (empty($this->_vConfig))
            throw new LogicException(get_class($this) . ' $this->_vConfig não possui dados de configuração');

        return $this->_vConfig;
    }

    final public function setConfig() {
        $this->config->load('upload', TRUE);
        $this->_vConfig = $this->config->item('upload');

        try {
            if (empty($this->_vConfig))
                throw new LogicException(get_class($this) . ' $this->_vConfig não possui dados de configuração de upload em application/upload.php');

            if (isset($this->_vConfig[$this->_sNome]))
                $this->_vConfig = $this->_vConfig[$this->_sNome];
            else
                throw new LogicException(get_class($this) . ' $this->_sNome não informado');
        } catch (LogicException $ex) {
            UtilHelper::printR($ex->getMessage());
            UtilHelper::printR($ex->getTraceAsString());
        }
    }

    abstract public function upload(int $nId, string $sFile = 'userfile', string $sTipo = NULL);

    abstract public function fileupload(int $nId, string $sTipo = NULL);
}
