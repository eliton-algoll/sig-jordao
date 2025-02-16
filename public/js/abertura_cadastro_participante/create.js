var abertura_cadastro_participante_create = {
  
    init : function () {
        this.events();        
    },
    
    events : function () {
        $("#cadastar_abertura_sistema_cadastro_participante_publicacao").change(abertura_cadastro_participante_create.handleProgramaPublicacao);
        $("#btn-save").click(abertura_cadastro_participante_create.handleSubmit);
    },
    
    handleProgramaPublicacao : function () {
        
        var id = $(this).val();
        
        if (!id) {
            return;
        }
        
        abertura_cadastro_participante_create.loadProjetos(id);
        abertura_cadastro_participante_create.loadInfoFolha(id);
    },
    
    loadProjetos : function (id) {
        helper.makeOptions(
            '#cadastar_abertura_sistema_cadastro_participante_projeto',
            Routing.generate('abertura_cadastro_participante_get_projetos', { id : id }),
            {},
            'coSeqProjeto',
            'nuSipar',
            true
        );
    },
    
    loadInfoFolha : function (id) {
        $.ajax({
            url : Routing.generate('abertura_cadastro_participante_get_info_folha', { id : id }),
            success : function (response) {
                if (response.error == undefined) {
                    $("#cadastar_abertura_sistema_cadastro_participante_noMesAnoReferencia").val(response.referenciaExtenso);
                } else {
                    $("#cadastar_abertura_sistema_cadastro_participante_noMesAnoReferencia").val('');
                }
            }
        });
    },
    
    handleSubmit : function (e) {        
        e.preventDefault();
        $('select').prop('disabled', false);        
        $('form').submit();
    }
    
};

abertura_cadastro_participante_create.init();