var certificado_index = {
    
    participantes : [],
    
    init : function() {
        this.events();
        $("input[type=checkbox]").each(function(index, input){
            if ($(input).is(':checked')) {
                certificado_index.addParticipante($(input).val());
            } else {
                certificado_index.removeParticipante($(input).val());
            }
        });
    },
    
    events : function() {
        $("#btn-gerar-certificado").click(certificado_index.handleDadosEmissao);
        $("input[type=checkbox]").click(certificado_index.handleClickSelecionar);
        $(document).on('change', '#dados_emissao_certificado_uf', certificado_index.handleChangeUf);
        $(document).on('click', '.btn-download', certificado_index.dowload);
        $("#btn-clear-filter").click(certificado_index.handleClearFiltro);
    },
    
    handleClickSelecionar : function() {        
        if (certificado_index.hasParticipante($(this).val())) {
            certificado_index.removeParticipante($(this).val());
        } else {
            certificado_index.addParticipante($(this).val());
        }        
    },
    
    hasParticipante : function(id) {
        return this.participantes.indexOf(id) > -1;
    },
    
    addParticipante : function(id) {        
        if (!certificado_index.hasParticipante(id)) {
            certificado_index.participantes.push(id);
        }        
    },
    
    removeParticipante : function(id) {
        if (certificado_index.hasParticipante(id)) {
            var index = certificado_index.participantes.indexOf(id);
            certificado_index.participantes.splice(index, 1);
        }
    },
    
    handleChangeUf : function() {
        var uf = $(this).val();
        
        helper.makeOptions(
            "#dados_emissao_certificado_municipio",
            Routing.generate('municipio_get_by_uf', { uf : uf }),
            {},
            'coMunicipioIbge',
            'noMunicipio',
            true,
            undefined,
            undefined,
            'GET'
        );
    },
    
    handleDadosEmissao : function(type) {
        
        if (certificado_index.participantes.length == 0) {
            bootbox.alert({
                title : 'Alerta',
                message : 'Você deve selecinar pelo menos um participante.'
            });
            return;
        }
        
        var type = (typeof type === 'undefined' || typeof type === 'object') ? 'GET' : type;
        
        $("#dados_emissao_certificado_participantes").val(certificado_index.participantes.join(','));
        
        $.ajax({
            url : Routing.generate('certificado_dados_emissao'),
            type : type,
            dataType : 'json',
            data : $("form[name='dados_emissao_certificado']").serialize()
        }).success(function(response) {
            
            if (response.isValid) {
                var dialog = {
                    message : response.content,
                    title : 'Download de Certificados',
                    closeButton : true,
                    buttons : {
                        close : {
                            label : 'Fechar',
                            className : 'btn-default'
                        }
                    }
                };
            } else {
                var dialog = {
                    message : response.content,
                    title : 'Dados para emissão do Certificado',
                    closeButton : false,
                    buttons : {
                        cancel : {
                            label : 'Cancelar',
                            className : 'btn-default'
                        },
                        confirm : {
                            label : 'Confirmar',
                            className : 'btn-primary',
                            callback : function() {
                                certificado_index.handleDadosEmissao('POST');
                            }
                        }
                    }
                };
            }
            
            bootbox.dialog(dialog);
        });
    },
    
    dowload : function() {
        var id = $(this).attr('data-id');
        var qtCh = $(this).attr('data-ch');
        var municipio = $(this).attr('data-municipio');
        var stFinalizacaoContrato = $(this).attr('data-is-certificado');
        var options = 'id='+id+'&qtCargaHoraria='+qtCh+'&coMunicipio='+municipio+'&stFinalizacaoContrato='+stFinalizacaoContrato;
        
        window.open(Routing.generate('certificado_generate') + '?' + options);
    },
    
    handleClearFiltro : function() {
        $("input[type='text']:not([readonly])").val('');
        $('select option:first-child').prop("selected", "selected");
    }
    
};

certificado_index.init();

