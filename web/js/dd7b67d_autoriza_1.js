(function($){
    
    var autoriza = {
        
        grupos : [],
        
        qtMinimaGrupos : $("#qtMinimaParticipante").val(),
        
        qtMaximaGrupos : $("#qtMaximaParticipante").val(),
        
        qtTotalBolsistas : $("#qtTotalBolsistas").val(),
        
        construct : function() {
            this.events();
            this.loadGrupos();                                                   
        },
        
        events : function() {
            $("#btn-autoriza-pagamento").click(autoriza.handleClickSubmit);   
            $("input[type=checkbox]").click(autoriza.handleClickSelecaoParticipante);
        },
        
        addGrupo : function(grupo) {
            if (!autoriza.hasGrupo(grupo)) {                
                autoriza.grupos.push(grupo);
            } else {                
                autoriza.addParticipanteGrupo(grupo);
            }
        },
        
        hasGrupo : function(grupo) {            
            var has = false;
            
            $.each(autoriza.grupos, function(key, value) {                 
                if (grupo.id == value.id) {                                    
                    has = true;
                }
            });                 
            return has;
        },
        
        addParticipanteGrupo : function(grupo) {
            $.each(autoriza.grupos, function(key, value){
                if (grupo.id == value.id) {                    
                    autoriza.grupos[key].participantes.push(grupo.participantes[0]);
                }
            });
        },
        
        loadGrupos : function() {
            $("input[name^='autorizacao_pagamento_projeto_pessoa']").each(function(key, input){                
                var grupo = {
                    id : $(input).attr('grupo'),
                    participantes : [{ id : $(input).val(), checked : false }],
                    valid : true
                };
                
                autoriza.addGrupo(grupo);
            });
        },
        
        handleClickSelecaoParticipante : function(e) {
            
            var input = $(this);                                    
            
            $.each(autoriza.grupos, function(key, grupo){                                  
                
                var pgc = 0;                
                     
                $.each(grupo.participantes, function(key2, participante){  
                    if (input.attr('grupo') == grupo.id) {                        
                        if (input.val() == participante.id && input.is(':checked')) {
                            autoriza.grupos[key].participantes[key2].checked = true;                                      
                        } else if (input.val() == participante.id && !input.is(':checked')) {
                            autoriza.grupos[key].participantes[key2].checked = false;
                        }
                    }

                    if (participante.checked) {
                        pgc++;
                    }
                });                                
                
                if (pgc > autoriza.qtMaximaGrupos) {
                    autoriza.grupos[key].valid = false;
                } else {                    
                    autoriza.grupos[key].valid = true;
                }
            });
        },
        
        validate : function(e) {
            
            var erros = [];
            var countParticipantes = 0;
            var justificativa = {
                required : false,
                grupos : []
            };
            
            $.each(autoriza.grupos, function(key, grupo){
                
                var countParticipantesGrupo = 0;
                
                if (!grupo.valid) {
                    erros.push('O grupo ' + (key +1) + ' ultrapassou a quantidade máxima.');
                }
                $.each(grupo.participantes, function(key2, participante){                                        
                    if (participante.checked) {                        
                        countParticipantes++;
                        countParticipantesGrupo++;
                    }
                });
                
                if (countParticipantesGrupo < autoriza.qtMinimaGrupos) {
                    justificativa.required = true;
                    justificativa.grupos.push(key + 1);
                }
            });
            
            if (countParticipantes > autoriza.qtTotalBolsistas) {
                erros.push('A quantidade de profissionais selecionados não pode ultrapassar a quantidade máxima de bolsistas.');
            } else if (countParticipantes == 0) {
                erros.push('Selecione pelo menos um participante.');
            }
            
//            if (justificativa.required && $("#autorizacao_pagamento_justificativa").val().trim() == '') {
//                erros.push('Os grupos ' + justificativa.grupos.join(', ') + ' não possui o mínimo, portanto é necessário uma justificativa.');
//            } else if (justificativa.required && $("#autorizacao_pagamento_justificativa").val().trim().length < 10) {
//                erros.push('A justificativa deve possuir pelo menos 10 caracteres.');
//            }
            
            if ($("#autorizacao_pagamento_justificativa").val().trim().length < 10) {
                erros.push('O relatório mensal de atividades deve possuir pelo menos 10 caracteres.');
            }
            if (!$("input[name='stDeclaracao']").is(':checked')) {
                erros.push('É obrigatório o preenchimento do campo Declaração');
            }
            
            if (erros.length > 0) {
                bootbox.alert(erros.join('</br>'));
                return false;
            }
            
            return true;
        },
        
        handleClickSubmit : function(e) {                        
            if (autoriza.validate(e)) {
                var message = 'Tem certeza que deseja autorizar os profissionais selecionados?';
                bootbox.confirm(message, function(result){
                    if (result) {
                        $('#form-autoriza-pagamento').submit();
                    }
                });
            }
            
            
        }        
    };
    
    autoriza.construct();
})(jQuery);
