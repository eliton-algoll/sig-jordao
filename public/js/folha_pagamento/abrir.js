(function($){
    var afp = {
        init: function(){
            this.events();
        },
        events: function(){
            $('#btn-salvar').click(this.handleClickSalvar);
        },
        handleClickSalvar: function() {
            bootbox.confirm('Os coordenadores de projetos vinculados à esta publicação ficarão impedidos de cadastrar novos participantes enquanto a folha estiver aberta. Deseja prosseguir?', function(response) {
                if(response) {
                    $('form').submit();
                }
            });
        }
    };
    $(document).ready(function(){
        afp.init();
    });
})(jQuery);