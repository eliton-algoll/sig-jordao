var relatorio_gerencial_pagamento = {
    
    init : function() {
        this.events();
        //this.handleCustumizacao();
    },
    
    events : function() {
        $("#btn-add-item").click(relatorio_gerencial_pagamento.addCustomizacaoItem);
        $("#btn-remove-item").click(relatorio_gerencial_pagamento.removeCustomizacaoItem);
        //$("#filtro_relatorio_gerencial_pagamento_nuSipar").blur(relatorio_gerencial_pagamento.handleCustumizacao);
        $("#btn-search").click(relatorio_gerencial_pagamento.handleSearch);
        $("#btn-download").click(relatorio_gerencial_pagamento.handleDownload);
        $("#btn-clear").click(relatorio_gerencial_pagamento.handleClearForm);
        $("#filtro_relatorio_gerencial_pagamento_nuSipar").blur(relatorio_gerencial_pagamento.handleValidaSipar);
    },
    
    isDependsSipar : function(value) {
        return value == 'vw.noGrupoAtuacao' || value == 'vw.noInstituicaoProjeto' || value == 'vw.noSecretariaSaude';
    },
    
    handleCustumizacao : function() {
        $("#filtro_relatorio_gerencial_pagamento_from_customizacao option").each(function(key, input) {            
            if (relatorio_gerencial_pagamento.isDependsSipar($(input).val()) && 
                $("#filtro_relatorio_gerencial_pagamento_nuSipar").val() == ''
            ) {
                $(input).prop('disabled', true);
            } else if (relatorio_gerencial_pagamento.isDependsSipar($(input).val()) && 
                $("#filtro_relatorio_gerencial_pagamento_nuSipar").val() != ''
            ) {
                $(input).prop('disabled', false);
            }
        });
        
        $("#filtro_relatorio_gerencial_pagamento_to_customizacao option").each(function(key, input) {
            if (relatorio_gerencial_pagamento.isDependsSipar($(input).val()) && 
                $("#filtro_relatorio_gerencial_pagamento_nuSipar").val() == ''
            ) {
                var clone = $(input).clone().prop('disabled', true).prop('selected', false);
                $("#filtro_relatorio_gerencial_pagamento_from_customizacao").append(clone);
                $(input).remove();
            }
        });
    },
    
    preventDeselectCustomizacaoTo : function() {
        $("#filtro_relatorio_gerencial_pagamento_to_customizacao option").each(function(key, input) {   
            $(input).prop('selected', true);        
        });
    },
    
    addCustomizacaoItem : function() {
        relatorio_gerencial_pagamento.moveItem('#filtro_relatorio_gerencial_pagamento_from_customizacao', '#filtro_relatorio_gerencial_pagamento_to_customizacao');        
    },
    
    removeCustomizacaoItem : function() {
        relatorio_gerencial_pagamento.moveItem('#filtro_relatorio_gerencial_pagamento_to_customizacao', '#filtro_relatorio_gerencial_pagamento_from_customizacao');        
    },
    
    moveItem : function(selectOrigem, selectDestino) {
        var origem = $(selectOrigem + ' option:selected').clone().prop('selected', true);
        $(selectOrigem + ' option:selected').remove();
        $(selectDestino).append(origem);
    },
    
    handleSearch : function() {
        if ($(this).attr('disabled')) {
            return;
        }
        
        relatorio_gerencial_pagamento.preventDeselectCustomizacaoTo();
        
        $("#stDownload").remove();
        $("form[name='filtro_relatorio_gerencial_pagamento']").submit();
    },
    
    handleDownload : function() {
        if ($(this).attr('disabled')) {
            return;
        }
        
        relatorio_gerencial_pagamento.preventDeselectCustomizacaoTo();
        
        $("form[name='filtro_relatorio_gerencial_pagamento']").append("<input type='hidden' name='stDownload' id='stDownload' value='1'>");
        $("form[name='filtro_relatorio_gerencial_pagamento']").submit();
    },
    
    handleClearForm : function() {
        relatorio_gerencial_pagamento.preventDeselectCustomizacaoTo();
        relatorio_gerencial_pagamento.removeCustomizacaoItem();
        
        $("input[type='text']:not([readonly])").val('');
        $('select option:first-child').prop("selected", "selected");
    },
    
    handleValidaSipar : function() {
        var sipar = $(this);
        
        if (sipar.attr('readonly') || sipar.val() == '') {
            return;
        }
        
        $.ajax({
            url : Routing.generate('projeto_check_sipar_existis') + '?nuSipar=' + sipar.val(),
            type : 'GET',
            success : function(response) {
                if (!response.exists) {
                    sipar.val('');
                    bootbox.alert(response.msg);
                }
                
                relatorio_gerencial_pagamento.handleCustumizacao();
            }
        });
    }  
};

relatorio_gerencial_pagamento.init();