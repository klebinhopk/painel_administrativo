<?php

/**
 * @author Romário Nascimento Beckman <gtisuporte@pa.senac.br,romario@pa.senac.br>
 */
class JsCssHelper {

    const path = 'resources/modules/';

    private static $vJs, $vCss;

    private static function loadJs() {
        if (file_exists(APPPATH . 'config/header_js.php'))
            include(APPPATH . 'config/header_js.php');

        if (!isset($config))
            return;

        if (empty(self::$vJs))
            self::$vJs = $config;
    }

    private static function loadCss() {
        if (file_exists(APPPATH . 'config/header_css.php'))
            include(APPPATH . 'config/header_css.php');

        if (!isset($config))
            return;

        if (empty(self::$vCss))
            self::$vCss = $config;
    }

    private static function list_files_css($list) {
        if (!empty($list) AND is_array($list)) {
            foreach ($list as $file) {
                self::css($file);
            }
        }
    }

    private static function list_files_js($list) {
        if (!empty($list) AND is_array($list)) {
            foreach ($list as $file) {
                self::js($file);
            }
        }
    }

    private static function _view($type) {
        $path = FCPATH . self::path;
        
        if (!is_dir($path))
            return;

        if (!($type == 'js' XOR $type == 'css'))
            return;

        $CI = &get_instance();
        $module = $CI->router->fetch_module();
        $class = $CI->router->class;
        $method = $CI->router->method;

        $file = strtolower($class . '_' . $method . '.' . $type);
        $path = $path . strtolower('/' . $module . '/' . $type . '/');
        $url = base_url(self::path) . strtolower($module . '/' . $type . '/');
        
        if (!is_file($path . $file))
            return;
        
        $type == 'js' ?
                        self::js($url . $file) :
                        self::css($url . $file);
    }

    public static function view_js() {
        self::_view('js');
    }

    public static function view_css() {
        self::_view('css');
    }

    static function css($Css, string $complement = 'rel="stylesheet"') {
        if (empty(self::$vCss))
            self::loadCss();

        if (is_array($Css))
            self::list_files_css($Css);

        $pos = strpos(strtolower($Css), 'http');
        if ($pos === FALSE) {
            if (isset(self::$vCss[$Css])) {
                if (is_array(self::$vCss[$Css]))
                    self::list_files_css(self::$vCss[$Css]);
            } else
                echo '<link href="' . base_url($Css) . '" ' . $complement . ' />';
        } else
            echo '<link href="' . $Css . '" ' . $complement . ' />';
    }

    static function js($Js, string $complement = '') {
        if (empty(self::$vJs))
            self::loadJs();

        if (is_array($Js)) {
            self::list_files_js($Js);
            return;
        }

        $pos = strpos(strtolower($Js), 'http');
        if ($pos === FALSE) {
            if (isset(self::$vJs[$Js])) {
                if (is_array(self::$vJs[$Js])) {
                    self::list_files_js(self::$vJs[$Js]);
                    return;
                }
            }

            echo '<script src="' . base_url($Js) . '" ' . $complement . '></script>';
        } else
            echo '<script src="' . $Js . '" ' . $complement . '></script>';
    }

}

?>