(function($){
    var Grupo = {
        init: function(){
            this.events();
        },
        events: function(){
            $('.inativar-grupo').click(this.handleClickInativarGrupo);
        },
        handleClickInativarGrupo: function(){
            var that = this;
            bootbox.confirm('Deseja realmente inativar o grupo?', function(result){
                if (result) {
                    $.post(
                        Routing.generate('grupo_atuacao_inativar', {grupoAtuacao: $(that).attr('data-grupo')}),
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
        Grupo.init();
    });
})(jQuery);