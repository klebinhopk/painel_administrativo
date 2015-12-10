<?php
/**
 * @author Romário Nascimento Beckman <romabeckman@gmail.com,romario@pa.senac.br>
 * @link https://www.linkedin.com/in/romabeckman
 * @link https://www.facebook.com/romabeckman
 * @link http://twitter.com/romabeckman
 */
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>

        <?php echo headerjscss::putCss(); ?>
        <!--[if lt IE 9]>
          <script src="<?php echo base_url(); ?>resources/painel/assets/javascripts/ie/html5shiv.js" type="text/javascript"></script>
          <script src="<?php echo base_url(); ?>resources/painel/assets/javascripts/ie/respond.min.js" type="text/javascript"></script>
          <![endif]-->

        <!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
        <!--[if lt IE 9]>
          <script src="js/html5shiv.js"></script>
          <script src="js/respond.min.js"></script>
          <![endif]-->
    </head>

    <body class='contrast-fb login contrast-background'>
        <div class='middle-container'>
            <div class='middle-row'>
                <div class='middle-wrapper'>
                    <div class='login-container-header'>
                        <div class='container'>
                            <div class='row'>
                                <div class='col-sm-12'>
                                    <div class='text-center'>
                                        <?php echo NOME_CLIENTE; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class='login-container'>
                        <div class='container'>
                            <div class='row'>
                                <div class='col-sm-4 col-sm-offset-4'>
                                    <h1 class='text-center title'>Login</h1>
                                    <?php
                                    $sValidationError = validation_errors('<div>', '</div>');
                                    if (!empty($sValidationError)) {
                                        ?>
                                        <div class="alert alert-danger">
                                            <button class="close" data-dismiss="alert" type="button">×</button>
                                            <?php echo $sValidationError; ?>
                                        </div>
                                        <?
                                    }
                                    ?>
                                    <?php $this->sys_mensagem_model->exibirMensagem(); ?>
                                    <?php echo form_open("painel/main/login", "class='form-validate'"); ?>
                                    <div class='form-group'>
                                        <div class='controls with-icon-over-input'>
                                            <input value="<?php echo set_value('user') ?>" placeholder="Login" class="form-control" required data-rule-required="true" name="user" type="text" />
                                            <i class='icon-user text-muted'></i>
                                        </div>
                                    </div>
                                    <div class='form-group'>
                                        <div class='controls with-icon-over-input'>
                                            <input value="" placeholder="Senha" class="form-control" required data-rule-required="true" name="pass" type="password" />
                                            <i class='icon-lock text-muted'></i>
                                        </div>
                                    </div>
                                    <button class='btn btn-block btn-success'>Login</button>
                                    <?php echo form_close(); ?>
                                    <div class='text-center'>
                                        <hr class='hr-normal'>
                                        <a data-toggle="modal" href="#myModal" href='javascript:;'>Esqueceu sua senha?</a>
                                    </div>

                                    <!-- Modal -->
                                    <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModal" class="modal fade">
                                        <div class="modal-dialog">
                                            <?php echo form_open("painel/main/recupera", "class='form-validate'"); ?>
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                    <h4 class="modal-title">Esqueceu sua senha?</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Informe seu login para recuperar por e-mail sua senha.</p>
                                                    <input type="text" name="user" value="<?php echo set_value('user') ?>" required placeholder="Login" autocomplete="off" class="form-control placeholder-no-fix">
                                                </div>
                                                <div class="modal-footer">
                                                    <button data-dismiss="modal" class="btn btn-default" type="button">Cancel</button>
                                                    <button class="btn btn-success" type="submit">Recuperar</button>
                                                </div>
                                            </div>
                                            <?php echo form_close(); ?>
                                        </div>
                                    </div>
                                    <!-- modal -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class='login-container-footer'></div>
                </div>
            </div>
        </div>
        <?php echo headerjscss::putJs(); ?>
    </body>
</html>

<!-- Localized -->