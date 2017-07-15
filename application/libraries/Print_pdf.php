<?php

/**
 * @author Romário Nascimento Beckman <gtisuporte@pa.senac.br,romario@pa.senac.br>
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once FCPATH . '/vendor/mpdf/mpdf/mpdf.php';

class Print_pdf {

    private $CI;
    public $mpdf;

    function __construct($vConfig = array()) {
        $vConfig = array_merge(array('format' => 'A4', 'mgl' => 5, 'mgr' => 5, 'mgt' => 5, 'mgb' => 20, 'orientation' => 'P'), $vConfig);

        @ini_set('memory_limit', -1);
        $this->CI = & get_instance();
        log_message('Debug', 'mPDF class is loaded.');

        $this->mpdf = new mPDF('mm', $vConfig['format'], 0, '', $vConfig['mgl'], $vConfig['mgr'], $vConfig['mgt'], $vConfig['mgb'], 9, 9, $vConfig['orientation']);

        $this->mpdf->use_kwt = true;
        $this->mpdf->keep_table_proportions = true;
        $this->mpdf->SetDisplayMode('fullpage');
        $this->mpdf->SetHTMLHeader();
    }

    private function getHtmlFooter($bNumeroPagina = FALSE) {
        $this->CI->load->model('modelo_html_model');
        $sFooter = $this->CI->modelo_html_model->getCampo(array('id' => 8), 'texto');
        $sFooter .= ($bNumeroPagina ? '<div style="text-align: center; font-size: 10px;">{PAGENO}</div>' : '');
        $this->mpdf->SetHTMLFooter($sFooter);
    }

    function addMargin($nTop = 2.54, $nBottom = 2.54, $nLeft = 3.175, $nRight = 3.175) {
        $sStylesheet = "@page {
                margin-top: {$nTop}cm;
                margin-bottom: {$nBottom}cm;
                margin-left: {$nLeft}cm;
                margin-right: {$nRight}cm;
        }";

        $this->mpdf->WriteHTML($sStylesheet, 1);
    }

    public function printPdf($sHtml, $sFile = '', $bNumeroPagina = FALSE) {
        $this->getHtmlFooter($bNumeroPagina);
        $sStylesheet = file_get_contents(FCPATH . "resources/painel/css/impressao_pdf.css");
        $this->mpdf->WriteHTML($sStylesheet, 1);

        $this->mpdf->WriteHTML(Util::utf8Encode($sHtml), 2);
        $this->mpdf->Output($sFile, 'I');
    }

    public function printPdfOficio($sHtml, $sFile = '') {
        if (ESTADO_ORIGEM == 'PA' AND NOME_REGIONAL == 'senac') { //Exclusivo Senac PA
            $this->addMargin(5);
            $sStylesheet = file_get_contents(FCPATH . "resources/painel/css/impressao_pdf_oficio.css");
            $this->mpdf->WriteHTML($sStylesheet, 1);
            $this->mpdf->WriteHTML(Util::utf8Encode($sHtml), 2);
            $this->mpdf->Output($sFile, 'I');
        } else {
            $this->printPdf($sHtml, $sFile);
        }
    }

    public function addCss($sCss) {
        $sStylesheet = file_get_contents($sCss);
        $this->mpdf->WriteHTML($sStylesheet, 1);
    }

    public function addBootstrapCss() {
        $this->addCss(FCPATH . "resources/painel/css/bootstrap.min.css");
    }

}

?>