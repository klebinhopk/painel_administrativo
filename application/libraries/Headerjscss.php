<?php

/**
 * @author Romário Nascimento Beckman <romabeckman@gmail.com,romario@pa.senac.br>
 * @link https://www.linkedin.com/in/romabeckman
 * @link https://www.facebook.com/romabeckman
 * @link http://twitter.com/romabeckman
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Headerjscss {

    private $ci, $sDir = 'resources/modules';

    function __construct() {
        $this->ci = &get_instance();
        $this->ci->config->load('header_css');
        $this->ci->config->load('header_js');
    }

    #PUBLIC

    static public function addHeaders($sFile) {
        Headerjscss::addJs($sFile);
        Headerjscss::addCss($sFile);
    }

    static public function addJs($sFile) {
        $ci = &get_instance();
        $ci->headerjscss->_add('js', $sFile);
    }

    static public function addCss($sFile) {
        $ci = &get_instance();
        $ci->headerjscss->_add('css', $sFile);
    }

    static function putHeaders($bMin = FALSE) {
        return headerJsCss::putCss($bMin) . headerJsCss::putJs($bMin);
    }

    static function putCss($bMin = FALSE) {
        $ci = &get_instance();
        return $ci->headerjscss->_puts('css', $bMin);
    }

    static function putJs($bMin = FALSE) {
        $ci = &get_instance();
        return $ci->headerjscss->_puts('js', $bMin);
    }

    #PRIVATE

    private function _add($sType, $sFile) {
        $vHeader = (ARRAY) $this->ci->config->item('header_' . $sType);

        if (!empty($sFile)) {
            if (is_array($sFile)) {
                foreach ($sFile AS $item) {
                    if (in_array($item, $vHeader))
                        continue;

                    $vHeader[] = $item;
                }
            } elseif (!in_array($sFile, $vHeader)) {
                $vHeader[] = $sFile;
            }
        }
        $this->ci->config->set_item('header_' . $sType, $vHeader);
    }

    private function _getConfiguration($sType, $sItem) {
        $vHeaderConfiguration = $this->ci->config->item('configuration_' . $sType);
        if (isset($vHeaderConfiguration[$sItem])) {
            if (is_array($vHeaderConfiguration[$sItem])) {
                $vLinks = array();
                foreach ($vHeaderConfiguration[$sItem] as $sLink) {
                    if (is_file(FCPATH . $sLink)) {
                        $vLinks[] = $sLink;
                    }
                }
                return $vLinks;
            } elseif (is_file(FCPATH . $vHeaderConfiguration[$sItem])) {
                return array($vHeaderConfiguration[$sItem]);
            }
        }
        return array();
    }

    private function _processHeader($sType) {
        $vHeader = (ARRAY) $this->ci->config->item('header_' . $sType);
        $vNewHeader = array();

        foreach ($vHeader AS $item) {
            $vHeaderConfiguration = $this->_getConfiguration($sType, $item);

            if (!empty($vHeaderConfiguration)) {
                $vNewHeader = array_merge($vNewHeader, $vHeaderConfiguration);
            } elseif (is_file(FCPATH . $item)) {
                $vNewHeader[] = $item;
            }
        }

        return $vNewHeader;
    }

    private function _loadFileForModule($sType, $bCretaMin = TRUE) {
        $sFile = '';
        $sModule = $this->ci->router->fetch_module();
        $sClass = $this->ci->router->class;
        $sMethod = $this->ci->router->method;
        $sFileName = "{$sClass}_{$sMethod}";

        $sDirLink = "{$this->sDir}/{$sModule}/{$sType}";
        $sDirRoot = FCPATH . "{$this->sDir}/{$sModule}/{$sType}";

        if (is_file("{$sDirRoot}/{$sFileName}.min.{$sType}") AND $bCretaMin)
            $sFile = "{$sDirLink}/{$sFileName}.min.{$sType}";

        if (is_file("{$sDirRoot}/{$sFileName}.{$sType}")) {
            if ($bCretaMin AND is_dir("{$sDirRoot}")) {
                $this->ci->load->driver("Minify");
                if ($sType == 'js') {
                    $sText = $this->ci->minify->js->min("{$sDirRoot}/{$sFileName}.{$sType}");
                } else {
                    $sText = $this->ci->minify->css->min("{$sDirRoot}/{$sFileName}.{$sType}");
                }

                if (!is_file("{$sDirRoot}/{$sFileName}.min.{$sType}"))
                    file_put_contents("{$sDirRoot}/{$sFileName}.min.{$sType}", $sText);
                    
                $sFile = "{$sDirLink}/{$sFileName}.min.{$sType}";
            } else {
                $sFile = "{$sDirLink}/{$sFileName}.{$sType}";
            }

            if ($sType == 'js') {
                $this->addJs($sFile);
            } else {
                $this->addCss($sFile);
            }
        }
    }

    private function _puts($sType, $bMin = FALSE) {
        $str = '';
        $this->_loadFileForModule($sType, $bMin);
        $vHeader = $this->_processHeader($sType);

        if ($bMin) {
            $sModule = $this->ci->router->fetch_module();
            $sDirLink = base_url($this->sDir . "/{$sModule}/all.{$sType}");
            $sDirRoot = FCPATH . "{$this->sDir}/{$sModule}";

            if (is_dir($sDirRoot)) {
                if (!file_exists($sDirRoot . "/all.{$sType}") OR $bMin) {
                    $this->ci->load->driver("Minify");
                    $contents = $this->ci->minify->combine_files($vHeader, $sType);
                    $this->ci->minify->save_file($contents, $sDirRoot . "/all.{$sType}");
                }

                $str .= $this->_getHeader($sDirLink, $sType);
            }
        } else {
            foreach ($vHeader AS $item) {
                $boolUrl = (BOOLEAN) (filter_var($item, FILTER_VALIDATE_URL) !== false); #IS URL?
                $vHeaderConfiguration = $boolUrl ? array() : $this->_getConfiguration($sType, $item);

                switch (TRUE) {
                    case!empty($vHeaderConfiguration):
                        foreach ($vHeaderConfiguration as $item) {
                            $str .= $this->_getHeader(base_url($item), $sType);
                        }
                        break;
                    case is_file(FCPATH . $item):
                        $str .= $this->_getHeader(base_url($item), $sType);
                        break;
                    case $boolUrl:
                        $str .= $this->_getHeader($item, $sType);
                        break;
                }
            }
        }

        return $str;
    }

    private function _getHeader($sDirLink, $sType) {
        if ($sType == 'css') {
            return '<link rel="stylesheet" href="' . $sDirLink . '" type="text/css" />' . "\n";
        } else {
            return '<script type="text/javascript" src="' . $sDirLink . '"></script>' . "\n";
        }
    }

}
