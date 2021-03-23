var relatorio_gerencial_participante = {
    
    init : function() {
        this.events();
        //this.handleCustumizacao();
        this.handleStyleCustomizacaoFrom();
    },
    
    events : function() {
        $("#btn-add-item").click(relatorio_gerencial_participante.addCustomizacaoItem);
        $("#btn-remove-item").click(relatorio_gerencial_participante.removeCustomizacaoItem);
        //$("#filtro_relatorio_gerencial_participante_nuSipar").blur(relatorio_gerencial_participante.handleCustumizacao);
        $("#btn-search").click(relatorio_gerencial_participante.handleSearch);
        $("#btn-download").click(relatorio_gerencial_participante.handleDownload);
        $("#btn-clear").click(relatorio_gerencial_participante.handleClearForm);
        $("#filtro_relatorio_gerencial_participante_nuSipar").blur(relatorio_gerencial_participante.handleValidaSipar);
    },
    
    isDependsSipar : function(value) {
        return value === 'GRUPO_ATUACAO_PROJETO' || value === 'SECRETARIA_SAUDE' || value === 'NO_INSTITUICAO_PROJETO';
    },
    
    handleCustumizacao : function() {
        $("#filtro_relatorio_gerencial_participante_from_customizacao option").each(function(key, input) {            
            if (relatorio_gerencial_participante.isDependsSipar($(input).val()) && 
                $("#filtro_relatorio_gerencial_participante_nuSipar").val() == ''
            ) {
                $(input).prop('disabled', true);
            } else if (relatorio_gerencial_participante.isDependsSipar($(input).val()) && 
                $("#filtro_relatorio_gerencial_participante_nuSipar").val() != ''
            ) {
                $(input).prop('disabled', false);
            }
        });
        
        $("#filtro_relatorio_gerencial_participante_to_customizacao option").each(function(key, input) {
            if (relatorio_gerencial_participante.isDependsSipar($(input).val()) && 
                $("#filtro_relatorio_gerencial_participante_nuSipar").val() == ''
            ) {
                var clone = $(input).clone().prop('disabled', true).prop('selected', false);
                $("#filtro_relatorio_gerencial_participante_from_customizacao").append(clone);
                $(input).remove();
            }
        });
    },
    
    preventDeselectCustomizacaoTo : function() {
        $("#filtro_relatorio_gerencial_participante_to_customizacao option").each(function(key, input) {   
            $(input).prop('selected', true);        
        });
    },
    
    addCustomizacaoItem : function() {
        relatorio_gerencial_participante.moveItem('#filtro_relatorio_gerencial_participante_from_customizacao', '#filtro_relatorio_gerencial_participante_to_customizacao');
        relatorio_gerencial_participante.handleButtons();
    },
    
    removeCustomizacaoItem : function() {
        relatorio_gerencial_participante.moveItem('#filtro_relatorio_gerencial_participante_to_customizacao', '#filtro_relatorio_gerencial_participante_from_customizacao');
        relatorio_gerencial_participante.handleButtons();
    },
    
    moveItem : function(selectOrigem, selectDestino) {
        var origem = $(selectOrigem + ' option:selected').clone().prop('selected', true);
        $(selectOrigem + ' option:selected').remove();
        $(selectDestino).append(origem);
    },
    
    handleButtons : function() {
        if ($("#filtro_relatorio_gerencial_participante_to_customizacao option").length > 8) {
            $("#btn-search").prop('disabled', true);
            $("#btn-download").prop('disabled', true);
        } else {
            $("#btn-search").prop('disabled', false);
            $("#btn-download").prop('disabled', false);
        }
    },
    
    handleSearch : function() {
        if ($(this).attr('disabled')) {
            return;
        }
        
        relatorio_gerencial_participante.preventDeselectCustomizacaoTo();
        
        $("#stDownload").remove();
        $("form[name='filtro_relatorio_gerencial_participante']").submit();
    },
    
    handleDownload : function() {
        if ($(this).attr('disabled')) {
            return;
        }
        
        relatorio_gerencial_participante.preventDeselectCustomizacaoTo();
        
        $("form[name='filtro_relatorio_gerencial_participante']").append("<input type='hidden' name='stDownload' id='stDownload' value='1'>");
        $("form[name='filtro_relatorio_gerencial_participante']").submit();
    },
    
    handleClearForm : function() {
        relatorio_gerencial_participante.preventDeselectCustomizacaoTo();
        relatorio_gerencial_participante.removeCustomizacaoItem();
        
        $("input[type='text']:not([readonly])").val('');
        $('select option:first-child').prop("selected", "selected");
    },
    
    handleStyleCustomizacaoFrom : function() {        
        $("#filtro_relatorio_gerencial_participante_from_customizacao option").each(function(key, input) {            
            if ($(input).text().match(/\*$/m)) {
                $(input).html($(input).text().replace('*', '<span style="color:red;">*</span>'));
            }
        });
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
            }
        });
    }
    
};

relatorio_gerencial_participante.init();