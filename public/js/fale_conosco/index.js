var fale_conosco = {
    
    tipoAssuntoOutros : 4,
    
    init : function() {
        this.events();
    },
    
    events : function() {
        $("#enviar_fale_conosco_tipoAssunto").change(fale_conosco.handleChangeTipoAssunto);
    },
    
    handleChangeTipoAssunto : function() {
        if ($(this).val() == fale_conosco.tipoAssuntoOutros) {
            $("#enviar_fale_conosco_assunto").attr('required', 'required');
            $("#enviar_fale_conosco_assunto").parent().find('label').addClass('required');
        } else {
            $("#enviar_fale_conosco_assunto").removeAttr('required');
            $("#enviar_fale_conosco_assunto").parent().find('label').removeClass('required');
        }        
    }
};

fale_conosco.init();