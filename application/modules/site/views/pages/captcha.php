<div class="row">
    <div class="col-sm-6 col-xs-6"><img id="siimage" class="img-responsive img-thumbnail" src="<?php echo base_url(); ?>pages/captcha?sid=<?php echo md5(uniqid()) ?>" alt="CAPTCHA Image" align="left"></div>
    <div class="col-sm-6 col-xs-6">
        <label>Informe o código:</label>
        <div class="controls">
            <input type="text" required name="code" size="12" maxlength="16" />
            <a tabindex="-1" style="border-style: none;" href="#" title="Atualizar código" onclick="document.getElementById('siimage').src = '<?php echo base_url(); ?>pages/captcha?' + Math.random();
                this.blur();
                return false;">
                <i class="glyphicon glyphicon-refresh"></i>
            </a>
        </div>
    </div>
</div>