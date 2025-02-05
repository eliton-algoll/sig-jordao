(function($){
    function init() {
        var labelConteudo = $('label[for="modelo_certificado_descricao"]');
        labelConteudo.append('&nbsp;<i class="cursor-pointer glyphicon glyphicon-info-sign" title="Clique para visualizar informações" />');
        labelConteudo.click(abrirModalInfo);

        var form = $('form[name="modelo_certificado"]');

        form.find('input[type="file"]').change(readURL);

        form.submit(salvar);
    }

    function salvar(event) {
        event.preventDefault();
        bootbox.confirm({
            message : 'Deseja mesmo salvar o registro? O registro ativo atualmente será inativado.',
            callback : function (result) {
                if (result === true) {
                    event.target.submit();
                }
            }
        });
    }

    function abrirModalInfo(event) {
        event.preventDefault();
        bootbox.dialog({
            title: 'Informações',
            message: $('#modal-info').html(),
            buttons: {
                close: {
                    label: 'Fechar'
                }
            }
        });
    }

    function readURL(event) {
        var input = $(event.target);
        var id = input.attr('id');
        var img = $('#preview_' + id);
        var files = event.target.files;

        img.attr('src', '');
        img.parent().hide();

        if (files && files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                if (e.target.result.indexOf('data:image') === 0) {
                    img.attr('src', e.target.result);
                    img.parent().show();
                }
            };
            reader.readAsDataURL(files[0]);
        }
    }

    $(document).ready(init);
})(jQuery);