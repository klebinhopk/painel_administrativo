<?php echo form_open(NULL, 'class="form-validate" role="form"'); ?>
<?php echo form_hidden('id', set_value('id', isset($oUsuario) ? $oUsuario->id : '')); ?>

<div class="form-group">
    <label for="id_grupo_usuario">Grupo de Usuário: </label>
    <?php echo form_dropdown('id_grupo_usuario', $vsGrupoUsuario, set_value('id_grupo_usuario', (isset($oUsuario) ? $oUsuario->id_grupo_usuario : 0)), 'id="id_grupo_usuario" class="required form-control "'); ?>
</div>
<div class="form-group">
    <label for="nome">Nome: </label>
    <?php echo form_input('nome', (isset($oUsuario) ? $oUsuario->nome : ''), 'size="70" id="nome" class="required form-control"'); ?>
</div>
<div class="form-group">
    <label for="login">Login: </label>
    <?php echo form_input('login', (isset($oUsuario) ? $oUsuario->login : ''), 'size="70" id="login" class="required form-control"'); ?>
</div>
<div class="form-group">
    <label for="email">Email: </label>
    <?php echo form_input('email', (isset($oUsuario) ? $oUsuario->email : ''), 'size="70" id="email" class="required email form-control"'); ?>
</div>
<div class="form-group">
    <label for="senha">Senha: </label>
    <?php echo form_password('senha', '', 'size="30" id="senha" data-rule-minlength="4" class="form-control ' . (isset($oUsuario) ? '' : 'required') . '"'); ?>
</div>
<div class="form-group">
    <label for="confirmar_senha">Confirmar Senha: </label>
    <?php echo form_password('confirmar_senha', '', 'size="30" id="confirmar_senha" class="form-control" data-rule-equalTo="#senha"'); ?>
</div>
<div class="form-group">
    <label for="ativo">Ativo: </label>
    <?php echo FormularioHelper::radioAtivo((isset($oUsuario) ? $oUsuario->ativo : 1)); ?>
</div>
<div class="form-actions form-actions-padding text-right">
    <?php echo form_button(array("type" => "submit"), '<i class="icon-save"></i> Salvar', 'class="btn btn-primary"'); ?>
</div>

<?php echo form_close(); ?>