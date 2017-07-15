<?php

class Webservice_mira_pessoa_fisica extends CI_Driver {

    const urlSoap = 'http://www.mira.senac.br/wspfpa?WSDL';

    public $oClient;
    
    function setClient() {
        if (empty($this->oClient))
            $this->oClient = $this->conn((STRING) self::urlSoap);
    }

    public function CadastroDemanda($nAno, $sDCad, $nInteressados, $nUO, $nPFisica, $sOutrosAss = '', $sPJuridica = '', $nRespCadatro, $nCodSaida, $nCodSegmento, $nDom = '', $nSeg = '', $nTerc = '', $nQua = '', $nQui = '', $nSex = '', $nSab = '', $nM = '', $nV = '', $nPreN = '', $nN = '') {
        $this->setClient();
        $vParams = array(
            'Ano' => $nAno,
            'DataCadastro' => $sDCad,
            'PessoasInteressadas' => $nInteressados,
            'UnidadeOperativa' => $nUO,
            'CodigoPessoaFisica' => $nPFisica,
            'OutrosAssuntos' => $sOutrosAss,
            'CodigoPessoaJuridica' => $sPJuridica,
            'CodigoPessoaRespCadatro' => $nRespCadatro,
            'CodigoSaida' => $nCodSaida,
            'CodigoSegmento' => $nCodSegmento,
            'CodigoDomingo' => $nDom,
            'CodigoSegunda' => $nSeg,
            'CodigoTerca' => $nTerc,
            'CodigoQuarta' => $nQua,
            'CodigoQuinta' => $nQui,
            'CodigoSexta' => $nSex,
            'CodigoSabado' => $nSab,
            'CodigoMatutino' => $nM,
            'CodigoVespertino' => $nV,
            'CodigoPreNoturno' => $nPreN,
            'CodigoNoturno' => $nN
        );

        $this->chamarSoap('CadastroDemanda', $vParams);
        return 'Cadastrado com sucesso';
    }

    public function PesquisaPessoaFisica($CodigoDaPessoaFisica) {
        $this->setClient();
        $vParams = array(
            'CodigoDaPessoaFisica' => $CodigoDaPessoaFisica
        );

        return $this->chamarSoap('PesquisaPessoaFisica', $vParams);
    }

    public function PesquisaPessoaFisicaPorCPF($nCpf) {
        $this->setClient();
        $vParams = array(
            'CPF' => $nCpf
        );

        return $this->chamarSoap('PesquisaPessoaFisicaPorCPF', $vParams);
    }

    public function PesquisaDocumentacaoDaPessoaFisica($CodigoDaPessoaFisica) {
        $this->setClient();
        $vParams = array(
            'CodigoDaPessoaFisica' => $CodigoDaPessoaFisica
        );

        return $this->chamarSoap('PesquisaDocumentacaoDaPessoaFisica', $vParams);
    }

    public function PesquisaEnderecoDaPessoaFisica($CodigoDaPessoaFisica) {
        $this->setClient();
        $vParams = array(
            'CodigoDaPessoaFisica' => $CodigoDaPessoaFisica
        );

        return $this->chamarSoap('PesquisaEnderecoDaPessoaFisica', $vParams);
    }

}

?>