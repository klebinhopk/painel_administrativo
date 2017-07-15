<?php

class Webservice extends CI_Driver_Library {

    public $valid_drivers;

    function __construct() {
        $this->valid_drivers = array('mira_pessoa_fisica', 'mira_turma');
    }

    public function conn(string $sUrl) {
        $vParametros = array(
            'trace' => 1,
            'exceptions' => 1,
            'soap_version' => SOAP_1_1,
            'encoding' => 'UTF-8',
            'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP,
            'style' => SOAP_DOCUMENT,
            'use' => SOAP_LITERAL,
            'features' => SOAP_SINGLE_ELEMENT_ARRAYS
        );
        if (SERVER == 'development') {
            $vParametros['proxy_host'] = "192.168.0.1";
            $vParametros['proxy_port'] = 3128;
            $vParametros['proxy_login'] = "sends";
            $vParametros['proxy_password'] = "816xy0";
        }

        return new SoapClient($sUrl, $vParametros);
    }

    public function chamarSoap(string $sFunction, array $vParams, $oClient) {
        try {
            if (empty($oClient))
                throw new LogicException(strtolower(get_called_class()) . ' $oClient não especificado');


            $oResposta = $oClient->__soapCall($sFunction, array($vParams));
            eval("\$this->oResponse = \$oResposta->{$sFunction}Result;");
            $sXmlAny = (STRING) $this->oResponse->any;
            $oXml = simplexml_load_string($sXmlAny);
            $this->oResult = $oXml->NewDataSet->Table;
        } catch (Exception $ex) {
            trigger_error("SOAP Fault: (faultcode: {$ex->faultcode}, faultstring: {$ex->faultstring})", E_USER_ERROR);
            $this->oResult = array();
        }

        return $this->getResultArray();
    }

    public function getResult() {
        return $this->oResult;
    }

    public function getResultArray() {
        $handsets = array();
        if (!empty($this->oResult)) {
            foreach ($this->oResult as $obj) {
                $handsets[] = $obj;
            }
        }
        return $handsets;
    }

    public function debug() {
        echo "====== REQUEST HEADERS =====" . PHP_EOL;
        var_dump($oClient->__getLastRequestHeaders());
        echo "========= REQUEST ==========" . PHP_EOL;
        var_dump(htmlentities($oClient->__getLastRequest()));
        echo "========= RESPONSE =========" . PHP_EOL;
        print_r($this->getResultArray());
    }

}

?>