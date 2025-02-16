(function($){
    var Estabelecimento = {
        init: function(){
            this.events();
        },
        events: function(){
            $('.inativar-estabelecimento').click(this.handleClickInativarEstabelecimento);
        },
        handleClickInativarEstabelecimento: function(){
            var that = this;
            bootbox.confirm('Deseja realmente remover esse estabelecimento?', function(result){
                if (result) {
                    $.post(
                        Routing.generate('estabelecimento_inativar', {estabelecimento: $(that).attr('data-estabelecimento')}),
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
        Estabelecimento.init();
    });
})(jQuery);