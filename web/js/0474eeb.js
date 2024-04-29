(function($){
    var Atualizar = {
        init: function(){
            this.events();
        },
        events: function(){
            $('#nova-publicacao').click(this.handleClickNovaPublicacao);
            $('#cancelar-publicacao').click(this.handleClickCancelarPublicacao);
            $('.inativar-publicacao').click(this.handleClickInativarPublicacao);
            var selectArea = document.getElementById("atualizar_programa_tpAreaTematica");
            if( selectArea != null ) {
                for (var i = 0; i < selectArea.options.length; i++) {
                    if (selectArea.options[i].value === '2') {
                        selectArea.remove(i);
                        break;
                    }
                }
            }
        },
        handleClickNovaPublicacao: function(){
            $('#container-nova-publicacao').removeClass('hidden');
            $('#nova-publicacao').addClass('hidden');
        },
        handleClickCancelarPublicacao: function(){
            $('#container-nova-publicacao').addClass('hidden');
            $('#nova-publicacao').removeClass('hidden');
            $('#container-nova-publicacao').find('input,select,textarea').val('');
        },
        handleClickInativarPublicacao: function(){
            var that = this;
            bootbox.confirm('Deseja realmente inativar essa publicação?', function(result){
                if (result) {
                    $.post(
                        Routing.generate('publicacao_inativar', {publicacao: $(that).attr('data-coSeqPublicacao')}),
                        {},
                        function(data){
                            if (data.status) {
                                $(that).parents('tr').remove();
                            }
                            bootbox.alert(data.message);
                        },
                        'json'
                    );
                }
            });
        }
    };
    $(document).ready(function(){
        Atualizar.init();
    });
})(jQuery);