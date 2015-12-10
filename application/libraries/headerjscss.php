<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class headerjscss {

    private $ci;

    function __construct() {
        $this->ci = &get_instance();
        $this->ci->config->load('header_css');
        $this->ci->config->load('header_js');
    }

    #PUBLIC

    public function addHeaders($file) {
        $this->_add('js', $file);
        $this->_add('css', $file);
    }

    public function addJs($file) {
        $this->_add('js', $file);
    }

    public function addCss($file) {
        $this->_add('css', $file);
    }

    #PRIVATE

    private function _add($sType, $file) {
        $header = (ARRAY) $this->ci->config->item('header_' . $sType);

        if (!empty($file)) {
            if (!is_array($file))
                $file = array(0 => $file);

            foreach ($file AS $item) {
                $configuration = $this->_getConfiguration($sType, $item);
                if (!empty($configuration)) {
                    $header = array_merge($header, $configuration);
                } elseif (is_file(FCPATH . $item)) {
                    $header[] = $item;
                }
            }

            $this->ci->config->set_item('header_' . $sType, $header);
        }
    }

    private function _getConfiguration($sType, $sItem) {
        $headerConfiguration = $this->ci->config->item('configuration_' . $sType);

        if (isset($headerConfiguration[$sItem])) {
            if (is_array($headerConfiguration[$sItem])) {
                $vLinks = array();
                foreach ($headerConfiguration[$sItem] as $sLink) {
                    if (is_file(FCPATH . $sLink)) {
                        $vLinks[] = $sLink;
                    }
                }

                return $vLinks;
            } elseif (is_file(FCPATH . $headerConfiguration[$sItem])) {
                return array($headerConfiguration[$sItem]);
            }
        }

        return array();
    }

    private function _loadFile($sType, $bCretaMin = TRUE) {
        $file = '';
        $sModule = $this->ci->router->fetch_module();
        $sClass = $this->ci->router->class;
        $sMethod = $this->ci->router->method;

        $sFile = "{$sClass}_{$sMethod}";
        $sDirLink = "resources/modules/{$sModule}/{$sType}";
        $sDirRoot = FCPATH . "resources/modules/{$sModule}/{$sType}";

        if (is_file("{$sDirRoot}/{$sFile}.min.{$sType}") AND $bCretaMin)
            $file = "{$sDirLink}/minify/{$sFile}.min.{$sType}";

        if (is_file("{$sDirRoot}/{$sFile}.{$sType}")) {
            $this->ci->load->driver("Minify");

            if ($sType == 'js') {
                $sText = $this->ci->minify->js->min("{$sDirRoot}/{$sFile}.{$sType}");
            } else {
                $sText = $this->ci->minify->css->min("{$sDirRoot}/{$sFile}.{$sType}");
            }

            if ($bCretaMin AND is_dir("{$sDirRoot}/minify")) {
                if (!is_file("{$sDirRoot}/minify/{$sFile}.min.{$sType}"))
                    file_put_contents("{$sDirRoot}/minify/{$sFile}.min.{$sType}", $sText);

                $file = "{$sDirLink}/minify/{$sFile}.min.{$sType}";
            } else {
                $file = "{$sDirLink}/{$sFile}.{$sType}";
            }
        }

        if ($sType == 'js') {
            $this->addJs($file);
        } else {
            $this->addCss($file);
        }
    }

    #STATIC

    static function putHeaders() {
        return headerJsCss::putCss() . headerJsCss::putJs();
    }

    static function putCss() {
        $str = '';
        $ci = &get_instance();
        $ci->headerjscss->_loadFile('css', (ENVIRONMENT === 'production'));
        $header = $ci->config->item('header_css');

        foreach ($header AS $item) {
            $str .= '<link rel="stylesheet" href="' . base_url($item) . '" type="text/css" />' . "\n";
        }

        return $str;
    }

    static function putJs() {
        $str = '';
        $ci = &get_instance();
        $ci->headerjscss->_loadFile('js', (ENVIRONMENT === 'production'));
        $header = $ci->config->item('header_js');

        foreach ($header AS $item) {
            $str .= '<script type="text/javascript" src="' . base_url($item) . '"></script>' . "\n";
        }

        return $str;
    }

}
