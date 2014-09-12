<?php

/**
 * @author Romário Nascimento Beckman <romabeckman@gmail.com,romario@pa.senac.br>
 * @link https://www.linkedin.com/in/romabeckman
 * @link https://www.facebook.com/romabeckman
 * @link http://twitter.com/romabeckman
 */
class grupo_usuario_model extends MY_Model {

    function __construct() {
        parent::__construct();
        $this->sTable = 'usu_grupo_usuario';
        $this->bDeletado = TRUE;
    }

    public function getPaginate($sUrl, $vDados = array()) {
        $vDados['deletado'] = 0;
        $nTotal = $this->db->select('COUNT(*) AS total')
                ->where($vDados)
                ->get_where($this->sTable)
                ->row('total');

        $nPerPage = 30;
        $nPaginas = (INT) $this->input->get('per_page');

        $result = $this->db
                ->select('*')
                ->order_by('id', 'DESC')
                ->where($vDados)
                ->get($this->sTable, $nPerPage, $nPaginas);

        $this->load->library('paginacao', array('total_rows' => $nTotal, 'base_url' => $sUrl, 'per_page' => $nPerPage, 'cur_page' => $nPaginas));
        $sLinks = $this->paginacao->painel();
        return array('result' => $result, 'links' => $sLinks, 'total' => $nTotal);
    }

    public function save($vDados, $sCampoReferencia = 'id') {
        $vReg = array(
            'nome' => $vDados["nome"],
        );

        //UPDATE
        if (isset($vDados[$sCampoReferencia]) AND !empty($vDados[$sCampoReferencia])) {
            if ($this->grupo_usuario_model->update($vReg, $vDados['id'])) {
                $this->sys_mensagem_model->setFlashData(9);
            } else {
                $this->sys_mensagem_model->setFlashData(2);
            }
        }
        //INSERT
        else {
            if ($this->grupo_usuario_model->insert($vReg)) {
                $nId = $this->db->insert_id();
                $this->metodo_model->save($nId);

                $this->sys_mensagem_model->setFlashData(9);
            } else {
                $this->sys_mensagem_model->setFlashData(2);
            }
        }
    }

}
