var gerenciar_formulario_atividade_index = {
  
    init : function () {
        this.events();
    },
    
    events : function () {
        $(document).on('click', '.btn-detail-descricao', gerenciar_formulario_atividade_index.handleShowDescricao);
        $(document).on('click', '.btn-remove-formulario', gerenciar_formulario_atividade_index.handleDelete);
    },
    
    handleShowDescricao : function () {
        var html = $(this).attr('descricao');
        
        bootbox.dialog({
            title : 'Formulário de Avaliação de Atividades',
            message : html,
            buttons : {
                close : {
                    label : 'Fechar'
                }
            }
        });
    },
    
    handleDelete : function () {
        
        var id = $(this).attr('data-id');
        
        bootbox.confirm({
            message : 'Tem certeza que deseja excluir?',
            buttons : {
                confirm : {
                    label : 'Sim'
                },
                cancel : {
                    label : 'Não',
                    className : 'btn btn-default'
                }
            },
            callback : function (result) {
                if (result) {
                    window.location.href = Routing.generate('gerenciar_formulario_atividade_delete', { id : id, softDelete : 0 });
                }
            }
        });
        
    }
    
};

gerenciar_formulario_atividade_index.init();