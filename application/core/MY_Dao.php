<?php

defined('BASEPATH') OR exit('No direct script access allowed');

abstract class MY_Dao {

    protected $_sTable, $_bDeletado = FALSE;
    protected $nInsertId;

    /*
      array('fk_field' => array('name' => 'name', 'column' => 'primary_key', 'table' => 'table_reference'));
     */
    protected $_vReferenceMap = array();

    const defaultReferenceId = 'id';
    const defaultReferenceName = 'nome';

    /**
     * Class constructor
     *
     * @return	void
     */
    public function __construct() {
        log_message('info', 'DAO Class Initialized');

        $this->load();

        if (empty($this->_sTable))
            throw new LogicException(strtolower(get_called_class()) . ' deve conter $_sTable informado em load()');

        if (!is_bool($this->_bDeletado))
            throw new LogicException(strtolower(get_called_class()) . ' com $_bDeletado deve ser Boolean em load()');

        if (!is_array($this->_vReferenceMap))
            throw new LogicException(strtolower(get_called_class()) . ' com $_vReferenceMap deve ser Array em load()');

        $this->setDefaultReferenceMap();
    }

    // --------------------------------------------------------------------

    /**
     * __get magic
     *
     * Allows models to access CI's loaded classes using the same
     * syntax as controllers.
     *
     * @param	string	$key
     */
    public function __get($key) {
        return get_instance()->$key;
    }

    public function __toString() {
        return $this->_sTable;
    }

    // --------------------------------------------------------------------

    abstract protected function load();

    private function setDefaultReferenceMap() {
        if (!empty($this->_vReferenceMap)) {
            foreach (array_keys($this->_vReferenceMap) as $sKey) {
                $this->_vReferenceMap[$sKey] = $this->getDefaultReferenceMap($sKey);
            }
        }
    }

    protected function getDefaultReferenceMap(string $sField) {
        //DEFAUT id
        if (!isset($this->_vReferenceMap[$sField]['column']))
            $this->_vReferenceMap[$sField]['column'] = self::defaultReferenceId;

        //DEFAUT nome
        if (!isset($this->_vReferenceMap[$sField]['name']))
            $this->_vReferenceMap[$sField]['name'] = self::defaultReferenceName;

        return $this->_vReferenceMap[$sField];
    }

    protected function checkField(string $sField, $oObject = NULL) {
        if (!isset($this->_vReferenceMap[$sField]) OR empty($this->_vReferenceMap[$sField])) {
            throw new LogicException(strtolower(get_called_class()) . ' ' . $sField . ' não existe em $_vReferenceMap');
            return FALSE;
        }

        if (!isset($this->_vReferenceMap[$sField]['table']) OR empty($this->_vReferenceMap[$sField]['table'])) {
            throw new LogicException(strtolower(get_called_class()) . ' ' . $sField . ' o valor table não informado em $_vReferenceMap');
            return FALSE;
        }

        if (!empty($oObject)) {
            if (!isset($oObject->{$sField}) OR empty($oObject->{$sField})) {
                throw new LogicException(strtolower(get_called_class()) . ' ' . $sField . ' o valor table não informado ou não informado no $oObject');
                return FALSE;
            }
        }

        return TRUE;
    }

    final public function fetchRowById($nId, string $sFieldIdKey = 'id') {
        return $this->fetchAll(array($sFieldIdKey => $nId))->row();
    }

    final public function fetchAll(array $vData = array(), string $sOrderBy = '', int $nLimit = 0) {
        $this->whereDeleted();
        if ($nLimit > 0)
            $this->db->limit($nLimit);

        if (!empty($sOrderBy))
            $this->db->order_by($sOrderBy);

        return $this->db
                        ->select("{$this}.*")
                        ->where($vData)
                        ->get((STRING) $this);
    }

    final public function fetchField(array $vData, $sField) {
        $this->whereDeleted();

        $vResult = $this->db
                ->select($sField)
                ->where($vData)
                ->limit(1)
                ->get((STRING) $this)
                ->row_array();

        return (!empty($vResult) AND is_array($vResult)) ?
                array_shift($vResult) :
                NULL;
    }

    final public function selectParent(string $sField, string $sSelect = '', string $sAS = '') {
        try {
            $this->checkField($sField);
            $sQuery = "(SELECT " . (empty($sSelect) ? $this->_vReferenceMap[$sField]['name'] : $sSelect)
                    . " FROM "
                    . "{$this->_vReferenceMap[$sField]['table']} WHERE "
                    . "{$this->_vReferenceMap[$sField]['table']}.{$this->_vReferenceMap[$sField]['column']} = {$this}.{$sField})" . (!empty($sAS) ? " {$sAS}" : '');
            $this->db->select($sQuery, FALSE);
        } catch (LogicException $ex) {
            UtilHelper::printR($ex->getMessage());
            UtilHelper::printR($ex->getTraceAsString());
        }

        return $this;
    }

    final public function findParentName(string $sField, $oObject) {
        try {
            $this->checkField($sField, $oObject);
            return $this->db
                            ->select($this->_vReferenceMap[$sField]['name'], FALSE)
                            ->get_where($this->_vReferenceMap[$sField]['table'], array($this->_vReferenceMap[$sField]['column'] => $oObject->{$sField}))
                            ->row($this->_vReferenceMap[$sField]['name']);
        } catch (LogicException $ex) {
            UtilHelper::printR($ex->getMessage());
            UtilHelper::printR($ex->getTraceAsString());
        }

        return $this;
    }

    final static public function find_parent_name(string $sField, $oObject) {
        $CI = &get_instance();
        $class = strtolower(get_called_class());
        $CI->load->dao($class);

        try {
            if (isset($CI->{$class}))
                return $CI->{$class}->findParentName($sField, $oObject);
            else
                throw new LogicException(get_class() . " \$CI->{$class} não informado corretamente");
            return;
        } catch (Exception $ex) {
            UtilHelper::printR($ex->getMessage());
            UtilHelper::printR($ex->getTraceAsString());
        }
    }

    final public function findParentRow(string $sField, $oObject) {
        try {
            $this->checkField($sField, $oObject);
            return $this->db
                            ->get_where($this->_vReferenceMap[$sField]['table'], array($this->_vReferenceMap[$sField]['column'] => $oObject->{$sField}))
                            ->row();
        } catch (LogicException $ex) {
            UtilHelper::printR($ex->getMessage());
            UtilHelper::printR($ex->getTraceAsString());
        }
    }

    final static public function find_parent_row(string $sField, $oObject) {
        $CI = &get_instance();
        $class = strtolower(get_called_class());
        $CI->load->dao($class);

        try {
            if (isset($CI->{$class}))
                return $CI->{$class}->findParentRow($sField, $oObject);
            else
                throw new LogicException(get_class() . " \$CI->{$class} não informado corretamente");
            return;
        } catch (Exception $ex) {
            UtilHelper::printR($ex->getMessage());
            UtilHelper::printR($ex->getTraceAsString());
        }
    }

    final public function fetchToDropdown(array $vData = array(), string $sFieldName = 'nome', string $sFieldIdKey = 'id') {
        $this->whereDeleted();

        $this->db
                ->order_by($sFieldName)
                ->select($sFieldName . ', ' . $sFieldIdKey, FALSE);
        $roResult = $this->fetchAll($vData);

        $vArray = array();
        if ($roResult->num_rows() > 0) {
            foreach ($roResult->result() as $oObject) {
                $vArray[$oObject->{$sFieldIdKey}] = $oObject->{$sFieldName};
            }
        }

        return $vArray;
    }

    final public function save(array $vData, $sFieldIdKey = 'id') {
        $oRow = $this->fetchRowById($vData[$sFieldIdKey], $sFieldIdKey);
        return !empty($oRow) ?
                $this->update($vData, $vData[$sFieldIdKey], $sFieldIdKey) :
                $this->insert($vData);
    }

    public function whereDeleted() {
        if ($this->_bDeletado)
            $this->db->where($this . '.deletado', 0);
    }

    public function insert(array $vData) {
        try {
            if ($this->db->insert((STRING) $this, $vData)) {
                $this->nInsertId = $this->db->insert_id();
                return TRUE;
            } else {
                $this->nInsertId = NULL;
                throw new Exception(strtolower(get_called_class()) . ' erro na execução do insert em ' . $this);
                return FALSE;
            }
        } catch (Exception $ex) {
            UtilHelper::printR($ex->getMessage());
            UtilHelper::printR($ex->getTraceAsString());
        }
    }

    public function update(array $vUpdate, $Key, string $sFieldIdKey = 'id') {
        try {
            return $this->db->update((STRING) $this, $vUpdate, array($sFieldIdKey => $Key));
        } catch (Exception $ex) {
            UtilHelper::printR($ex->getMessage());
            UtilHelper::printR($ex->getTraceAsString());
            return false;
        }
    }

    public function delete($Key, string $sFieldIdKey = 'id') {
        return $this->db->delete((STRING) $this, array($sFieldIdKey => $Key));
    }

    public function remove($Key, string $sFieldIdKey = 'id') {
        if ($this->_bDeletado === FALSE)
            return $this->delete($Key, $sFieldIdKey);

        return $this->db
                        ->where($sFieldIdKey, $Key)
                        ->update((STRING) $this, array('deletado' => 1));
    }

}

?>
