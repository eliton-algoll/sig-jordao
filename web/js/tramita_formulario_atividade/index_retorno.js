var tramita_formulario_atividade_index_retorno = {
    
    init : function() {
        this.events();
    },
    
    events : function() {
        $(document).on('click', '.btn-historico', tramita_formulario_atividade_index_retorno.handleHistoricoTramitacao);
        $(document).on('click', '.btn-detail-justificativa', tramita_formulario_atividade_index_retorno.handleShowJustificativa);
    },
    
    handleHistoricoTramitacao : function () {
        var id = $(this).attr('data-id');
        
        if (id == '') {
            return;
        }
        
        $.ajax({
            url : Routing.generate('tramita_formulario_atividade_historico', { id : id }),
            success : function (response) {
                bootbox.alert({
                    message : response,
                    size : 'large'
                });
            }
        });
    },
    
    handleShowJustificativa : function () {
        
        var response = '<label class="control-label">Justificativa</label>';
        response += '<textarea class="form-control" disabled="true">'+ $(this).attr('ds-justificativa') +'</textarea>';
        
        bootbox.alert({
            message : response,
            size : 'large'
        });
    }
};

tramita_formulario_atividade_index_retorno.init();