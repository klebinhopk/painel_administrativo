<?php echo form_open(NULL, 'class="form-validate" id="form"'); ?>

<div class="form-group">
    <label for="nome">Nome: </label>
    <?php echo form_input('nome', set_value('nome', isset($oUsuario) ? $oUsuario->nome : ''), 'size="80" id="nome" class="required form-control"'); ?>
</div>
<div class="form-group">
    <label for="email">E-mail: </label>
    <?php echo form_input('email', set_value('email', isset($oUsuario) ? $oUsuario->email : ''), 'size="50" id="email" class="required email form-control"'); ?>
</div>
<div class="form-group">
    <label for="login">Login: </label>
    <?php echo form_input('login', set_value('login', isset($oUsuario) ? $oUsuario->login : ''), 'size="30" id="login" class="required form-control"'); ?>
</div>
<div class="form-group">
    <label for="senha">Senha: </label>
    <?php echo form_password('senha', '', 'size="30" id="senha" data-rule-minlength="4" class="form-control"'); ?>
</div>
<div class="form-group">
    <label for="confirmar_senha">Confirmar Senha: </label>
    <?php echo form_password('confirmar_senha', '', 'size="30" id="confirmar_senha" data-rule-equalTo="#senha" class="form-control"'); ?>
</div>
<div class="form-actions form-actions-padding text-right">
    <?php echo form_button(array("type" => "submit"), '<i class="icon-save"></i> Salvar', 'class="btn btn-primary"'); ?>
</div>

<?php echo form_close(); ?>