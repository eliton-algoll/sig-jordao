var abertura_cadastro_participante_index = {
  
    init : function () {
        this.events();
    },
    
    events : function () {
        $("#consultar_abertura_sistema_cadastro_participante_publicacao").change(abertura_cadastro_participante_index.handleProgramaPublicacao);
        $(document).on('click', '.btn-delete-abertura', abertura_cadastro_participante_index.handleDelete);
    },
    
    handleProgramaPublicacao : function () {
        
        var id = $(this).val();
        
        if (!id) {
            return;
        }
        
        abertura_cadastro_participante_index.loadProjetos(id);
        abertura_cadastro_participante_index.loadFolhas(id);
    },
    
    loadProjetos : function (id) {
        helper.makeOptions(
            '#consultar_abertura_sistema_cadastro_participante_projeto',
            Routing.generate('abertura_cadastro_participante_get_projetos', { id : id }),
            {},
            'coSeqProjeto',
            'nuSipar',
            true
        );
    },
    
    loadFolhas : function (id) {
        helper.makeOptions(
            '#consultar_abertura_sistema_cadastro_participante_folhaPagamento',
            Routing.generate('abertura_cadastro_participante_get_folhas', { id : id }),
            {},
            'coSeqFolhaPagamento',
            'referenciaExtenso',
            true
        );
    },
    
    handleDelete : function () {
        
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
                    document.location.href = Routing.generate('abertura_cadastro_participante_delete', { id : id });
                }
            }
        });
    }
    
};

abertura_cadastro_participante_index.init();


