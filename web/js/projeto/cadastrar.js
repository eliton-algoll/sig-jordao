(function($){
    var Cadastrar = {
        init: function(){
            this.events();
            this.listAreasTematicas();
        },
        events: function(){
            $('#label-todos-temas').hide();
            $('#todos-temas-saude').click(this.checkListTemaSaude);
            $('#todos-temas-cienciasHumanas').click(this.checkListTemaCienciasHumanas);
            $('#todos-temas-cienciasSociais').click(this.checkListTemaCienciasSociais);
            // $('#todos-temas').click(this.checkListAreasTematicas);
            $('#todos-temas-saude').click(this.checkListTemaSaude);
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

            if( pane.find('input[data-tp-area-tematica="' + tpAreaTematica + '"]').length > 0 ){
                $('#label-todos-temas').show();
            }
        },
        checkListTemaSaude: function () {
            var checked = $('#todos-temas-saude');
            var valueCheck = checked.is(':checked');
            var checkBoxs = document.querySelectorAll('input[type="checkbox"][data-tp-area-sub="1"]');
            [].forEach.call(checkBoxs, function(checkbox) {
                checkbox.checked = valueCheck;
            });
        },
        checkListTemaCienciasHumanas: function () {
            var checked = $('#todos-temas-cienciasHumanas');
            var valueCheck = checked.is(':checked');
            var checkBoxs = document.querySelectorAll('input[type="checkbox"][data-tp-area-sub="2"]');
            [].forEach.call(checkBoxs, function(checkbox) {
                checkbox.checked = valueCheck;
            });
        },
        checkListTemaCienciasSociais: function () {
            var checked = $('#todos-temas-cienciasSociais');
            var valueCheck = checked.is(':checked');
            var checkBoxs = document.querySelectorAll('input[type="checkbox"][data-tp-area-sub="3"]');
            [].forEach.call(checkBoxs, function(checkbox) {
                checkbox.checked = valueCheck;
            });
        },
        checkListTemaSaude: function () {
            var checkBoxs = document.querySelectorAll('input[type="checkbox"]:not([id=todos-temas])');
            [].forEach.call(checkBoxs, function(checkbox) {
                checkbox.checked = checkbox.checked ? false : true;
            });
        },
        checkListAreasTematicas: function () {
            var checkBoxs = document.querySelectorAll('input[type="checkbox"]:not([id=todos-temas])');
            [].forEach.call(checkBoxs, function(checkbox) {
                checkbox.checked = checkbox.checked ? false : true;
            });
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