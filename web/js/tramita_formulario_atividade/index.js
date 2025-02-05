var tramita_formulario_atividade_index = {
    
    init : function () {
        this.events();
        this.handleTitulo();
    },
    
    events : function () {
        $("input[name='consultar_envio_formularios_avaliacao_atividade[tpTramiteFormulario]'").click(tramita_formulario_atividade_index.handleTipoConsulta);
        $("#consultar_envio_formularios_avaliacao_atividade_formularioAvaliacaoAtividade").change(tramita_formulario_atividade_index.handleTitulo);
        $(document).on('click', '.btn-historico', tramita_formulario_atividade_index.handleHistoricoTramitacao);
        $(document).on('click', '.btn-detail-justificativa', tramita_formulario_atividade_index.handleShowJustificativa);
        $(document).on('click', '.btn-delete-tramitacao', tramita_formulario_atividade_index.handleDeleteTramitacao);
        $(document).on('click', '.btn-delete-envio', tramita_formulario_atividade_index.handleDeleteEnvio);
    },
    
    handleTipoConsulta : function () {
        if ($(this).val() == 'E') {            
            $(".tramite-retorno ").addClass('hidden');            
            $("#consultar_envio_formularios_avaliacao_atividade_stFinalizado").closest('div').removeClass('hidden');
        } else {
            $("#consultar_envio_formularios_avaliacao_atividade_stFinalizado").closest('div').addClass('hidden');
            $(".tramite-retorno").removeClass('hidden');            
        }
    },
    
    handleTitulo : function () {
        var titulo = $("#consultar_envio_formularios_avaliacao_atividade_formularioAvaliacaoAtividade").val();
        
        if (titulo) {
            $("#consultar_envio_formularios_avaliacao_atividade_tpTramiteFormulario_1").prop('disabled', false);
        } else {
            $("#consultar_envio_formularios_avaliacao_atividade_tpTramiteFormulario_1").prop('disabled', true);
        }
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
    },
    
    handleDeleteEnvio : function () {
        var id = $(this).attr('data-id');
        
        if (!id) {
            return;
        }
        
        bootbox.confirm({
            message : 'Tem certeza que deseja excluir?',
            buttons : {
                confirm : {
                    label : 'Sim'
                },
                cancel : {
                    label : 'Não'
                }                
            },
            callback : function (result) {
                if (result) {
                    window.location.href = Routing.generate('tramita_formulario_atividade_delete', { id : id });
                }
            }
        });        
    },
    
    handleDeleteTramitacao : function () {
        var id = $(this).attr('data-id');
        
        if (!id) {
            return;
        }
        
        bootbox.confirm({
            message : 'Tem certeza que deseja excluir?',
            buttons : {
                confirm : {
                    label : 'Sim'
                },
                cancel : {
                    label : 'Não'
                }                
            },
            callback : function (result) {
                if (result) {
                    window.location.href = Routing.generate('tramita_formulario_atividade_delete_tramitacao', { id : id });
                }
            }
        }); 
    }
    
};

tramita_formulario_atividade_index.init();