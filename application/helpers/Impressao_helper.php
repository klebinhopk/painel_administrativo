<?php

/**
 * @author Romário Nascimento Beckman <gtisuporte@pa.senac.br,romario@pa.senac.br>
 */
class ImpressaoHelper {

    static function imprimirPdfPaisagem($sHtml) {
        $CI = & get_instance();
        $vConfig = array('format' => 'A4-L', 'mgl' => 5, 'mgr' => 5, 'mgt' => 10, 'mgb' => 20, 'orientation' => 'L');
        $CI->load->library('print_pdf', $vConfig);
        $sStylesheet = file_get_contents(FCPATH . "resources/site/css/impressao_pss.css");
        $CI->print_pdf->mpdf->WriteHTML($sStylesheet, 1);
        $CI->print_pdf->mpdf->WriteHTML('table{font-size: 9px;}', 1);
        $CI->print_pdf->printPdf($sHtml, '', TRUE);
    }

    static function imprimirPdf($sHtml) {
        $CI = & get_instance();
        $CI->load->library('print_pdf');
        $CI->print_pdf->printPdf($sHtml);
    }

    static function imprimirExcel($sHtml, $sTitulo) {
        header("Content-Type: text/html; charset=ISO-8859-1", true);
        // Determina que o arquivo é uma planilha do Excel
        header("Content-type: application/x-msexcel");
        header("Content-Disposition: attachment; filename={$sTitulo}.xls");
        header("Pragma: no-cache");
        echo utf8_decode($sHtml);
    }

    static function imprimirWord($sHtml, $sTitulo) {
        header("Cache-Control: no-store");
        header("Content-Type: application/octet-stream");
        //header("Content-type: application/msword");
        header("Content-Disposition: attachment; filename={$sTitulo}.doc");
        header('Content-Transfer-Encoding: binary');
        header("Content-Length: {$sHtml}");
        ob_clean();
        flush();
        echo readfile($sHtml);
    }

    static function imprimirForcePdf($sHtml, $sTitulo) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header("Content-Type: application/force-download");
        header('Content-Disposition: attachment; filename=' . urlencode(basename($sTitulo)));
        // header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($sHtml));
        ob_clean();
        flush();
        echo readfile($sHtml);
    }

    static function formatoExibicao($sHtml, $Formato, $sTitulo = '') {
        switch ($Formato) {
            case 'pdf':
                ImpressaoHelper::imprimirPdf($sHtml);
                break;
            case 'pdf_paisagem':
                ImpressaoHelper::imprimirPdfPaisagem($sHtml);
                break;
            case 'excel':
                ImpressaoHelper::imprimirExcel($sHtml, $sTitulo);
                break;

            default:
                echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
                $sStylesheet = file_get_contents(FCPATH . "resources/painel/css/impressao_pdf.css");
                echo "<style>{$sStylesheet}</style>";
                echo $sHtml;
                break;
        }
    }

    static function getFormato() {
        $CI = & get_instance();
        $sExibir = $CI->input->get('exibir');
        return !empty($sExibir) ? $sExibir : 'html';
    }

}

?>