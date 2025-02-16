var valor_bolsa_index = {
    
    tipoConsultaHistorico : 'H',
    
    init : function() {
        this.events();
    },
    
    events : function() {
        $("#consultar_valor_bolsa_tipoConsulta").change(valor_bolsa_index.handleChangeTipoConsulta);
        $(".btn-delete-vl-bolsa").click(valor_bolsa_index.handleDeleteValorBolsa);
    },
    
    handleChangeTipoConsulta : function() {
        if ($(this).val() == valor_bolsa_index.tipoConsultaHistorico) {
            $("#consultar_valor_bolsa_publicacao").attr('required', 'required');
            $("#consultar_valor_bolsa_publicacao").parent().find('label').addClass('required');
        } else {
            $("#consultar_valor_bolsa_publicacao").removeAttr('required');
            $("#consultar_valor_bolsa_publicacao").parent().find('label').removeClass('required');
        }        
    },
    
    handleDeleteValorBolsa : function() {        
        var id = $(this).attr('data-id');        
        
        if (id) {
            bootbox.confirm(
                { 
                    message : 'Tem certeza que deseja excluir?',
                    buttons : {
                        confirm : {
                            label : 'Sim'
                        },
                        cancel : {
                            label : 'Não'
                        }
                    },                
                    callback : function(result){
                        if (result) {
                            window.location.href = Routing.generate('valor_bolsa_delete', { id : id });
                        }   
                    }
            });
        }
    }
};

valor_bolsa_index.init();