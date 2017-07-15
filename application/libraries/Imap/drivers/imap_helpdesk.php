<?php

class imap_helpdesk extends CI_Driver {

    const categoria = 198;

    private $oMail, $nIdChamado, $oCategoria, $oSubCategoria, $oUsuario;

    public function novoChamado($oMail, $oUsuario) {
        $this->oMail = $oMail;
        $this->oUsuario = $oUsuario;

        if (empty($this->oSubCategoria)) {
            $this->oSubCategoria = $this->ci->categoria_model->get(self::categoria);
            $this->oCategoria = $this->ci->categoria_model->get($this->oSubCategoria->id_categoria);
        }

        if ($this->_inserirChamado()) {
            $sMensagem = $this->mensagem($this->oMail->msgno);
            $vAnexos = $this->anexos($this->oMail->msgno);

            $nIdInteracao = $this->_inserirInteracao($sMensagem);
            $this->_inserirAnexos($nIdInteracao, $vAnexos);
            $this->_notificarUsuarios($sMensagem, COUNT($vAnexos));
        }
    }

    private function _inserirChamado() {
        $this->nIdChamado = $this->ci->chamado_model->gerarId();

        $vReg = array(
            'id' => $this->nIdChamado,
            'id_cidade' => $this->oMail->usuario->id_cidade,
            'id_unidade' => $this->oMail->usuario->id_unidade,
            'id_setor' => $this->oMail->usuario->id_setor,
            'id_usuario' => $this->oMail->usuario->id,
            'id_usuario_cadastro' => $this->oMail->usuario->id,
            'id_setor_atendimento' => $this->oCategoria->id_setor,
            'id_categoria' => $this->oSubCategoria->id,
            'id_status' => 1,
            'nome' => $this->oMail->usuario->nome,
            'email' => $this->oMail->usuario->email,
            'telefone' => $this->oMail->usuario->telefone,
            'ramal' => $this->oMail->usuario->ramal,
            'prioridade' => $this->oSubCategoria->prioridade,
            'por_email' => 1,
        );

        return $this->ci->chamado_model->insert($vReg);
    }

    private function _inserirInteracao($sMensagem) {
        $vReg = array(
            'id_chamado' => $this->nIdChamado,
            'id_usuario' => $this->oMail->usuario->id,
            'id_status' => 1,
            'descricao' => $sMensagem
        );

        $this->ci->interacao_model->insert($vReg);
        return $this->ci->db->insert_id();
    }

    private function _inserirAnexos($nIdInteracao, $vAnexos) {
        if (!empty($vAnexos)) {
            foreach ($vAnexos as $vAnexo) {
                if (!is_file($vAnexo['filepath']))
                    continue;

                $sTamanho = filesize($vAnexo['filepath']);
                $bCopy = copy($vAnexo['filepath'], $this->ci->arquivo_model->vConfig['root'] . $vAnexo['filename']);

                if ($bCopy) {
                    unlink($vAnexo['filepath']);
                    $this->ci->arquivo_model->insert(array(
                        "id_interacao" => $nIdInteracao,
                        "arquivo" => $vAnexo['filename'],
                        "extensao" => $vAnexo['type'],
                        "tamanho" => round($sTamanho / 1024, 2)
                    ));
                }
            }
        }
    }

    private function _notificarUsuarios($sDescricao, $nTotalAnexos) {
        $vPrioridade = (ARRAY) unserialize(HELPDESK_PRIORIDADE);
        $sMensagem = "<p>Segue abaixo os dados do chamado:</p>
                        <p>
                            Categoria: {$this->oCategoria->nome}<br />
                            Sub-categoria: {$this->oSubCategoria->nome}<br />
                            Prioridade: {$vPrioridade[$this->oSubCategoria->prioridade]}<br />
                            Nome: {$this->oMail->usuario->nome}<br />
                            E-mail: {$this->oMail->usuario->email}<br />
                            Telefone: {$this->oMail->usuario->telefone}<br />
                            Anexo: " . ($nTotalAnexos > 0 ? "Sim" : "Não") . "<br />
                            Ramal: {$this->oMail->usuario->ramal}<br />
                            IP: {$_SERVER['REMOTE_ADDR']}<br />
                            Descrição: " . nl2br($sDescricao) . "
                        </p>";

        $this->ci->load->library('envia_email');
        if (!empty($this->oCategoria->email) AND filter_var($this->oCategoria->email, FILTER_VALIDATE_EMAIL))
            $this->ci->envia_email->enviar($this->oCategoria->email, 'Novo Chamado', $sMensagem);

        $vCategoriaUsuario = $this->ci->categoria_usuario_model->getAllSelect(array("id_categoria" => $this->oCategoria->id), "id_usuario", "id_usuario");
        if (!empty($vCategoriaUsuario)) {
            foreach ($vCategoriaUsuario as $nIdUsuarioTecnico) {
                $this->ci->notificacao_model->novaNotficacao($nIdUsuarioTecnico, "Novo chamado", $sMensagem, 'helpdesk/interacao/index/' . $this->nIdChamado, 'fa fa-comments');
            }
        }

        if (!empty($this->oUsuario->email)) {
            $sMensagem = '<p>Prezado(a) ' . SendHelper::primeiroNome($this->oUsuario->nome) . ',</p>';
            $sMensagem .= '<p>Sua solicitação está registrada no Helpdesk com número <strong>' . $this->nIdChamado . '</strong>. Em breve nossa equipe estará respondendo, por favor aguarde.</p>';
            $this->ci->envia_email->enviar($this->oUsuario->email, "Novo Chamado - " . $this->nIdChamado, $sMensagem);
        }
    }

}

?>