$(function () {
    $(".ver-dados").click(function () {
        var text = $(this).parent().find('.descricao').html();
        $("#myModalDados .modal-body").html(text);
        $("#myModalDados").modal('show');
    });
});