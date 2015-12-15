<?php

/**
 * @author Romário Nascimento Beckman <romabeckman@gmail.com,romario@pa.senac.br>
 * @link https://www.linkedin.com/in/romabeckman
 * @link https://www.facebook.com/romabeckman
 * @link http://twitter.com/romabeckman
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class headerjscss {

    private $ci, $sDir = 'resources/modules';

    function __construct() {
        $this->ci = &get_instance();
        $this->ci->config->load('header_css');
        $this->ci->config->load('header_js');
    }

    #PUBLIC

    public function addHeaders($file, $bMin = FALSE) {
        $this->_add('js', $file, $bMin);
        $this->_add('css', $file, $bMin);
    }

    public function addJs($file, $bMin = FALSE) {
        $this->_add('js', $file, $bMin);
    }

    public function addCss($file, $bMin = FALSE) {
        $this->_add('css', $file, $bMin);
    }

    #PRIVATE

    private function _add($sType, $file, $bMin = FALSE) {
        $header = (ARRAY) $this->ci->config->item('header_' . $sType);
        $headerMin = (ARRAY) $this->ci->config->item('min_' . $sType);

        if (!empty($file)) {
            if (!is_array($file))
                $file = array(0 => $file);
            foreach ($file AS $item) {
                $configuration = $this->_getConfiguration($sType, $item);

                if (!empty($configuration)) {
                    $bMin ? $headerMin = array_merge($headerMin, $configuration) : $header = array_merge($header, $configuration);
                } elseif (is_file(FCPATH . $item)) {
                    $bMin ? $headerMin[] = $item : $header[] = $item;
                }
            }

            $this->ci->config->set_item('header_' . $sType, $header);
            $this->ci->config->set_item('min_' . $sType, $headerMin);
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

        $sDirLink = "{$this->sDir}/{$sModule}/{$sType}";
        $sDirRoot = FCPATH . "{$this->sDir}/{$sModule}/{$sType}";

        if (is_file("{$sDirRoot}/{$sFile}.min.{$sType}") AND $bCretaMin)
            $file = "{$sDirLink}/min/{$sFile}.min.{$sType}";
        if (is_file("{$sDirRoot}/{$sFile}.{$sType}")) {
            $this->ci->load->driver("Minify");
            if ($sType == 'js') {
                $sText = $this->ci->minify->js->min("{$sDirRoot}/{$sFile}.{$sType}");
            } else {
                $sText = $this->ci->minify->css->min("{$sDirRoot}/{$sFile}.{$sType}");
            }
            if ($bCretaMin AND is_dir("{$sDirRoot}/min")) {
                if (!is_file("{$sDirRoot}/min/{$sFile}.min.{$sType}"))
                    file_put_contents("{$sDirRoot}/min/{$sFile}.min.{$sType}", $sText);
                $file = "{$sDirLink}/min/{$sFile}.min.{$sType}";
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

    private function _puts($sType, $bMin = FALSE) {
        $str = '';
        $this->_loadFile($sType, (ENVIRONMENT === 'production'));

        if ($bMin) {
            $headerMin = (ARRAY) $this->ci->config->item('min_' . $sType);
            if (!empty($headerMin)) {
                $sModule = $this->ci->router->fetch_module();
                $sDirLink = base_url($this->sDir . "/{$sModule}/min/all.{$sType}");
                $sDirRoot = FCPATH . "{$this->sDir}/{$sModule}/min";

                if (is_dir($sDirRoot)) {
                    if (!file_exists($sDirRoot . "/all.{$sType}") OR ( ENVIRONMENT !== 'production')) {
                        $this->ci->load->driver("Minify");
                        $contents = $this->ci->minify->combine_files($headerMin, $sType);
                        $this->ci->minify->save_file($contents, $sDirRoot . "/all.{$sType}");
                    }

                    if ($sType == 'css') {
                        $str .= '<link rel="stylesheet" href="' . $sDirLink . '" type="text/css" />' . "\n";
                    } else {
                        $str .= '<script type="text/javascript" src="' . $sDirLink . '"></script>' . "\n";
                    }
                }
            }
        } else {
            $header = (ARRAY) $this->ci->config->item('header_' . $sType);
            foreach ($header AS $item) {
                if ($sType == 'css') {
                    $str .= '<link rel="stylesheet" href="' . base_url($item) . '" type="text/css" />' . "\n";
                } else {
                    $str .= '<script type="text/javascript" src="' . base_url($item) . '"></script>' . "\n";
                }
            }
        }

        return $str;
    }

    #STATIC

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

}
