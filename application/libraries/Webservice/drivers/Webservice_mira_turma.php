<?php

class Webservice_mira_turma extends CI_Driver {

    const urlSoap = 'http://www.mira.senac.br/wspa?WSDL';

    public $oClient;

    function setClient() {
        if (empty($this->oClient))
            $this->oClient = $this->conn((STRING) self::urlSoap);
    }

    public function pesquisaDadosDeTurmasParaPublicarNaInternet($sData) {
        $this->setClient();
        $vParams = array(
            'data' => $sData,
        );
        return $this->chamarSoap('pesquisaDadosDeTurmasParaPublicarNaInternet', $vParams, $this->oClient);
    }

    public function pesquisaDadosDeTurmasParaPublicarNaInternetAno($nAno) {
        $this->setClient();
        $vParams = array(
            'DataAno' => $nAno
        );
        return $this->chamarSoap('pesquisaDadosDeTurmasParaPublicarNaInternetAno', $vParams, $this->oClient);
    }

    public function pesquisaDadosDeTurmasAno($nAno) {
        $this->setClient();
        $vParams = array(
            'DataAno' => $nAno
        );
        return $this->chamarSoap('pesquisaDadosDeTurmasAno', $vParams, $this->oClient);
    }

    public function validaCadastroDePessoa($vParams) {
        return $this->chamarSoap('validaCadastroDePessoa', $vParams, $this->oClient);
    }

    /* @ Class pesquisaDadosDeTurmasPorCpfAluno
      $result->[Codigo_Aluno]
      $result->[Nome_Aluno]
      $result->[CPF_Aluno]
      $result->[Codigo_Turma]
      $result->[Ano_Turma]
      $result->[Unidade_Turma]
      $result->[Codigo_Area_Curso]
      $result->[Nome_Area_Curso]
      $result->[Nome_Turma]
      $result->[Data_Inicio_Dia_Letivo_Turma]
      $result->[Data_Termino_Dia_Letivo_Turma]
      $result->[Horario_Turma]
      $result->[Situacao_Turma]
      $result->[Estado_Matricula]
      $result->[CodigoFinanciador]
      $result->[Financiador]
     */

    public function pesquisaDadosDeTurmasPorCpfAluno($nCpf, $nTipo = 1) {
        $this->setClient();
        $vParams = array(
            'CPF' => $nCpf,
            'TIPO' => $nTipo,
        );
        return $this->chamarSoap('pesquisaDadosDeTurmasPorCpfAluno', $vParams, $this->oClient);
    }

    public function pesquisaDadosDePessoaFisicaDoAluno($nIdMatricula, $nAnoMatricula, $nCodigoUnidadeMatricula) {
        $this->setClient();
        $vParams = array(
            'ID_Matricula' => $nIdMatricula,
            'Ano_Matricula' => $nAnoMatricula,
            'CodigoUnidade_Matricula' => $nCodigoUnidadeMatricula
        );

        return $this->chamarSoap('pesquisaDadosDePessoaFisicaDoAluno', $vParams, $this->oClient);
    }

    public function pesquisaDadosDeTurmasPorDiaLetivo($sData, $nCodigoUnidade, $nCodigoTurma) {
        $this->setClient();
        $vParams = array(
            'Data' => $sData,
            'CodigoUnidade' => $nCodigoUnidade,
            'CodigoTurma' => $nCodigoTurma
        );

        return $this->chamarSoap('pesquisaDadosDeTurmasPorDiaLetivo', $vParams, $this->oClient);
    }

    public function pesquisaDadosDeTurma($nCodigoTurma, $nAnoTurma, $nCodigoUnidadeTurma) {
        $this->setClient();
        $vParams = array(
            'CodigoTurma' => $nCodigoTurma,
            'AnoTurma' => $nAnoTurma,
            'CodigoUnidadeTurma' => $nCodigoUnidadeTurma
        );

        return $this->chamarSoap('pesquisaDadosDeTurma', $vParams, $this->oClient);
    }

    public function pesquisaCoordenadoresDaTurma($nAnoTurma) {
        $this->setClient();
        $vParams = array(
            'AnoTurma' => $nAnoTurma,
        );

        return $this->chamarSoap('pesquisaCoordenadoresDaTurma', $vParams, $this->oClient);
    }

    public function pesquisaDadosDeDivulgacaoDeTurma($nCodigoTurma, $nAnoTurma, $nCodigoUnidadeTurma) {
        $this->setClient();
        $vParams = array(
            'CodigoTurma' => $nCodigoTurma,
            'DataAno' => $nAnoTurma,
            'CodigoUnidade' => $nCodigoUnidadeTurma
        );

        return $this->chamarSoap('pesquisaDadosDeDivulgacaoDeTurma', $vParams, $this->oClient);
    }

    public function pesquisaDadosDeTurmaPorAluno($sCodigo, $sNome, $sCpf) {
        $this->setClient();
        $vParams = array(
            'Codigo' => $sCodigo,
            'Nome' => $sNome,
            'Cpf' => $sCpf
        );

        return $this->chamarSoap('pesquisaDadosDeTurmaPorAluno', $vParams, $this->oClient);
    }

    public function pesquisaTurmasDoMinistrantePorCpf($sCpf) {
        $this->setClient();
        $vParams = array(
            'CPF' => $sCpf
        );

        return $this->chamarSoap('pesquisaTurmasDoMinistrantePorCpf', $vParams, $this->oClient);
    }

    /*
      DataInicial -> data de inicio de atividade em bloco
      DataFinal -> data final de atividade em bloco
      DataInicialPagamento -> data início da "solicitacao de pagamento" (não corresponde a data de pagamento)
      DataFinalPagamento -> data final da "solicitacao de pagamento"
     */

    public function pesquisaPagamentoMinistrante($sDataInicio, $sDataFim) {
        $this->setClient();
        $vParams = array(
            'DataInicial' => $sDataInicio,
            'DataFinal' => $sDataFim,
            'DataInicialPagamento' => "",
            'DataFinalPagamento' => ""
        );

        return $this->chamarSoap('pesquisaPagamentoMinistrante', $vParams, $this->oClient);


//         Util::printR($this->getResultArray());
//        exit();
    }

    public function pesquisaPlanoDeCurso($nAnoClassificacao) {
        $this->setClient();
        $vParams = array(
            'AnoIniCls' => $nAnoClassificacao
        );

        return $this->chamarSoap('pesquisaPlanoDeCurso', $vParams, $this->oClient);
    }

    public function pesquisaSaidaDoPlanoDeCurso($nCodigoPlano) {
        $this->setClient();
        $vParams = array(
            'CodigoPlano' => $nCodigoPlano
        );
        return $this->chamarSoap('pesquisaSaidaDoPlanoDeCurso', $vParams, $this->oClient);
    }

    public function pesquisaEstruturaCurricular($CodigoSaida) {
        $this->setClient();
        $vParams = array(
            'CodigoSaida' => $CodigoSaida
        );

        return $this->chamarSoap('pesquisaEstruturaCurricular', $vParams, $this->oClient);
    }

    public function pesquisarTurmas($nCodigoTurma, $nAno, $nUnidade) {
        $this->setClient();
        $vParams = array(
            'CodigoTurma' => $nCodigoTurma,
            'AnoTurma' => $nAno,
            'CodigoUnidadeTurma' => $nUnidade
        );

        return $this->chamarSoap('pesquisarTurmas', $vParams, $this->oClient);
    }

    public function pesquisaDadosDeTurmasAlunosFinanciadoresPorFinanciadorDoContrato($sIdentificacao, $nTipo) {
        $this->setClient();
        $vParams = array(
            'Identificacao' => $sIdentificacao,
            'Tipo' => $nTipo
        );

        return $this->chamarSoap('pesquisaDadosDeTurmasAlunosFinanciadoresPorFinanciadorDoContrato', $vParams, $this->oClient);
    }

    public function pesquisaDadosBlocoTematicoAno($sAno) {
        $this->setClient();
        $vParams = array(
            'Ano' => $sAno
        );

        return $this->chamarSoap('pesquisaDadosBlocoTematicoAno', $vParams, $this->oClient);
    }

    public function pesquisaSolicitacoesParticipanteServicoPedagogico($sDataInicio, $sDataFinal) {
        $this->setClient();
        $vParams = array(
            'DataInicial' => $sDataInicio,
            'DataFinal' => $sDataFinal
        );
        return $this->chamarSoap('pesquisaSolicitacoesParticipanteServicoPedagogico', $vParams, $this->oClient);
    }

    public function relatorio_15_Producao($nMes, $nAno) {
        $this->setClient();
        $vParams = array(
            'Mes' => $nMes,
            'Ano' => $nAno
        );
        return $this->chamarSoap('relatorio_15_Producao', $vParams, $this->oClient);
    }

    public function pesquisaDadosDeTurmaDiaLetivoDependencia($nCodigoTurma, $nCodigoUnidade, $nAno) {
        $this->setClient();
        $vParams = array(
            'DataAno' => $nAno,
            'CodigoUnidade' => $nCodigoUnidade,
            'CodigoTurma' => $nCodigoTurma
        );
        return $this->chamarSoap('pesquisaDadosDeTurmaDiaLetivoDependencia', $vParams, $this->oClient);
    }

    public function alteraSituacaoParticipanteServicoPedagogico($nCodigo, $nSituacao, $sUsuario, $sSenha) {
        $this->setClient();
        $vParams = array(
            'Codigo' => $nCodigo,
            'Situacao' => $nSituacao, // (0 para canelado e 1 para executado)
            'Usuario' => $sUsuario, // login Mira
            'Senha' => $sSenha
        );
        return $this->chamarSoap('alteraSituacaoParticipanteServicoPedagogico', $vParams, $this->oClient);
    }

    function buscaCpf($sCpf) {
        $oAluno = NULL;
        $oDadosTruma = $this->pesquisaDadosDeTurmasPorCpfAluno((STRING) $sCpf);

        if (!empty($oDadosTruma)) {
            $oDadosAluno = $this->pesquisaDadosDeTurmaPorAluno($oDadosTruma[0]->Codigo_Aluno, $oDadosTruma[0]->Nome_Aluno, $oDadosTruma[0]->CPF_Aluno);

            if (!empty($oDadosAluno)) {
                $oDadosAluno = $oDadosAluno[0];
                $oDadosCadastro = $this->pesquisaDadosDePessoaFisicaDoAluno($oDadosAluno[0]->ID_MTR, $oDadosAluno[0]->ANO_MTR, $oDadosAluno[0]->UNIDADE_MTR);

                if (!empty($oDadosCadastro)) {
                    $oAluno = (OBJECT) $oDadosCadastro[0];
                } else {
                    $this->ci->load->library('webservice_cadastro_mira');
                    $oDadosCadastro = $this->ci->webservice_cadastro_mira->PesquisaPessoaFisica($oDadosTruma[0]->Codigo_Aluno);
                    $oDadosDocs = $this->ci->webservice_cadastro_mira->PesquisaDocumentacaoDaPessoaFisica($oDadosTruma[0]->Codigo_Aluno);
                    $oDadosEnd = $this->ci->webservice_cadastro_mira->PesquisaEnderecoDaPessoaFisica($oDadosTruma[0]->Codigo_Aluno);

                    $oAluno = (OBJECT) $this->dadosAlunoPessoaFisica($oDadosCadastro, $oDadosDocs, $oDadosEnd);
                }
            }
        }

        return $oAluno;
    }

    function dadosAlunoPessoaFisica($oDados, $oDadosDocs, $oDadosEnd) {
        return (ARRAY) array(
                    'CODIGO_PSAFSC' => (STRING) $oDados[0]->CD_PsaFsc,
                    'NOME_PSAFSC' => (STRING) $oDados[0]->NM_CmtPsaFsc,
                    'DATANASCIMENTO_PSAFSC' => (STRING) $oDados[0]->DT_NscPsaFsc,
                    'DATACADASTRO_PSAFSC' => (STRING) $oDados[0]->DT_CdsPsaFsc,
                    'SITUACAO_PSAFSC' => (STRING) NULL,
                    'RG_PSAFSC' => (STRING) $oDadosDocs[0]->NR_IdnPsaFsc,
                    'RGORGAOEXP_PSAFSC' => (STRING) $oDadosDocs[0]->DC_OroExoIdnPsaFsc,
                    'SEXO_PSAFSC' => (STRING) NULL,
                    'FONE_PSAFSC' => (STRING) NULL,
                    'TIPOENDERECO_PSAFSC' => (STRING) NULL,
                    'ENDLOGRADOURO_PSAFSC' => (STRING) $oDadosEnd[0]->NM_LgrPsaFsc,
                    'ENDCOMPLEMENTO_PSAFSC' => (STRING) $oDadosEnd[0]->NM_CmlLgrPsaFsc,
                    'ENDBAIRRO_PSAFSC' => (STRING) $oDadosEnd[0]->NM_BrrPsaFsc,
                    'ENDCEP_PSAFSC' => (STRING) $oDadosEnd[0]->NR_CEPPsaFsc,
                    'ENDLOCALIDADE_PSAFSC' => (STRING) NULL,
                    'ESTCIVIL_PSAFSC' => (STRING) $oDados[0]->CD_TpoEstCvl,
                    'TIPODEF_PSAFSC' => (STRING) $oDados[0]->CD_TpoDfc,
                    'TIPORACA_PSAFSC' => (STRING) $oDados[0]->CD_TpoRca,
                    'NACIONALIDADE_PSAFSC' => (STRING) NULL,
                    'NOMEPAI_PSAFSC' => (STRING) NULL,
                    'NOMEMAE_PSAFSC' => (STRING) $oDados[0]->NM_MeaPsaFsc,
                    'EMAIL_PSAFSC' => (STRING) $oDados[0]->NM_EndCriEltPsaFsc,
                    'GRAUESC_PSAFSC' => (STRING) NULL
        );
    }

}

?>