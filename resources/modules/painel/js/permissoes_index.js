$(function () {
    $("#selecionar-todos").click(function () {
        var checked = $(this).prop('checked');

        $('input[type=checkbox]').each(function (indice, obj) {
            if (checked) {
                $(obj).prop('checked', true);
            } else {
                $(obj).removeAttr('checked');
            }
        });
    });

    $(".permissao").click(function () {
        var modulo = $(this).data('modulo');
        var classe = $(this).data('classe');
        var checked = $(this).prop('checked');
        var css = modulo;

        if (classe)
            css += "-" + classe;
       
        $('.' + css).each(function (indice, obj) {
            if (checked) {
                $(obj).prop('checked', true);
            } else {
                $(obj).removeAttr('checked');
            }
        });
    });

    var dl = $("dl:eq(0)");
    dl.find("dt a").click(function () {
        var $this = $(this);
        if ($this.find("i").hasClass("icon-caret-down")) {
            $this.find("i").removeClass("icon-caret-down");
            $this.find("i").addClass("icon-caret-up");
            $this.parents('dt:eq(0)').next().show();
        } else {
            $this.find("i").removeClass("icon-caret-up");
            $this.find("i").addClass("icon-caret-down");
            $this.parents('dt:eq(0)').next().hide();
        }
    });
});