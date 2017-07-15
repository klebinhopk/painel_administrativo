<?php 

/**
 * @author Romário Nascimento Beckman <romabeckman@gmail.com,romario@pa.senac.br>
 * @link https://www.linkedin.com/in/romabeckman
 * @link https://www.facebook.com/romabeckman
 * @link http://twitter.com/romabeckman
 */

?>
<?php echo form_open(NULL, 'class="form-horizontal form-validate" role="form"'); ?>

<div class="form-group">
    <label for="nome" class="col-sm-2">Nome: </label>
    <div class="col-sm-10">
        <?php echo form_input('nome', set_value('nome', (isset($oGrupoUsuario) ? $oGrupoUsuario->nome : '')), 'size="45" id="nome" class="required  form-control "'); ?>
    </div>
</div>
<div class="form-actions form-actions-padding text-right">
    <?php echo form_button(array("type" => "submit"), '<i class="icon-save"></i> Salvar', 'class="btn btn-primary"'); ?>
</div>

<?php echo form_close(); ?>