<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

/* load the MX_Loader class */
require APPPATH . "third_party/MX/Loader.php";
require APPPATH . 'core/MY_Dao' . EXT;

class MY_Loader extends MX_Loader {

    /**
     * List of loaded daos
     *
     * @var	array
     */
    protected $_ci_daos = array();

    /**
     * List of paths to load daos from
     *
     * @var	array
     */
    protected $_ci_dao_paths = array(APPPATH . 'daos/');

    private function _dao_default_load($dao, $name = NULL) {
        if (empty($dao))
            return $this;

        if (empty($name))
            $name = strtolower($dao);

        if (in_array($name, $this->_ci_daos, TRUE))
            return $this;

        $CI = & get_instance();
        if (isset($CI->$name))
            throw new RuntimeException('The DAO name you are loading is the name of a resource that is already being used: ' . $name);

        $dao = ucfirst($dao);
        if (!class_exists($dao, FALSE)) {
            foreach ($this->_ci_dao_paths as $mod_path) {
                if (!file_exists($mod_path . $dao . '.php'))
                    continue;

                require_once($mod_path . $dao . '.php');

                if (!class_exists($dao, FALSE))
                    throw new RuntimeException($mod_path . $dao . ".php exists, but doesn't declare class " . $dao);
                break;
            }
            
            if (!class_exists($dao, FALSE))
                throw new RuntimeException('Unable to locate the dao you have specified: ' . $dao);
        } elseif (!is_subclass_of($dao, 'MY_Dao'))
            throw new RuntimeException("Class " . $dao . " already exists and doesn't extend MY_Dao");

        $this->_ci_daos[] = $name;
        $CI->$name = new $dao();
        return $this;
    }

    /** Load an array of daos * */
    public function daos($dao) {
        foreach ($dao as $dao => $alias) {
            (is_int($dao)) ? $this->dao($alias) : $this->dao($dao, $alias);
        }
        return $this;
    }

    public function dao($dao, $object_name = NULL) {
        if (is_array($dao))
            return $this->daos($dao);

        ($_alias = $object_name) OR $_alias = basename($dao);

        if (in_array($_alias, $this->_ci_daos, TRUE))
            return $this;

        /* check module */
        list($path, $_dao) = Modules::find(strtolower($dao), $this->_module, 'daos/');
        
        if ($path == FALSE) {
            /* check application & packages */
            $this->_dao_default_load($dao, $object_name);
        } else {
            class_exists('MY_Dao', FALSE) OR load_class('Dao', 'core');

            Modules::load_file($_dao, $path);

            $dao = ucfirst($_dao);
            CI::$APP->$_alias = new $dao();

            $this->_ci_daos[] = $_alias;
        }
        return $this;
    }

    private function __autoload_dao() {
        if (file_exists(APPPATH . 'config/autoload.php')) {
            include(APPPATH . 'config/autoload.php');
        }

        if (file_exists(APPPATH . 'config/' . ENVIRONMENT . '/autoload.php')) {
            include(APPPATH . 'config/' . ENVIRONMENT . '/autoload.php');
        }

        if (!isset($autoload)) {
            return;
        }

        /* autoload module controllers */
        if (isset($autoload['dao'])) {
            foreach ($autoload['dao'] as $controller) {
                ($controller != $this->_module) && $this->dao($controller);
            }
        }
    }

    public function initialize($controller = NULL) {
        parent::initialize();
        $this->__autoload_dao();
    }

}
