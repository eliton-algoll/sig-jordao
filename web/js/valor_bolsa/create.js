var valor_bolsa_crete = {
    
    init : function() {
        this.mask();
    },
    
    mask : function() {
        $("#cadastrar_valor_bolsa_inicioVigencia").mask('99/9999');
    }
    
};

valor_bolsa_crete.init();