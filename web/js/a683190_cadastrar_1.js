(function($){
    var Cadastrar = {
        init: function(){
            this.events();
            this.listAreasTematicas();
        },
        events: function(){
            $('#cadastrar_projeto_publicacao').change(this.handleChangePublicacao);
            $('#btn-salvar').click(this.handleClickSalvar);
        },
        handleChangePublicacao: function(){
            Cadastrar.listAreasTematicas();
        },
        listAreasTematicas: function(){
            var tpAreaTematica = $('#cadastrar_projeto_publicacao > option:selected').attr('data-tp-area-tematica')

            var pane = $('#dados-area-tematica');
            
            pane.find('input[data-tp-area-tematica!=' + tpAreaTematica + ']')
                .prop('checked', false)
                .parents('.checkbox')
                .hide();
        
            pane.find('input[data-tp-area-tematica="' + tpAreaTematica + '"]')
                .parents('.checkbox')
                .show();
        },
        handleClickSalvar: function(){
            if ($('#table-secretarias').find('input[type="hidden"]').length < 1) {
                bootbox.alert('É obrigatório o preenchimento de pelo menos uma Secretaria de Saúde Proponente.');
                return;
            }
            if ($('#table-campus').find('input[type="hidden"]').length < 1) {
                bootbox.alert('Atenção', 'É obrigatório o preenchimento de pelo menos uma Insituição de Ensino Superior Proponente.');
                return;
            }
            $('form[name="cadastrar_projeto"]').submit();
        }
    };
    
    $(document).ready(function(){
        Cadastrar.init();
    })
})(jQuery);