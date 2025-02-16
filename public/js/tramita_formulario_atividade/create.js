var tramita_formulario_atividade_create = {
    
    init : function() {
        this.events();        
    },
    
    events : function() {
        $("#cadastrar_envio_formulario_avaliacao_atividade_formularioAvaliacaoAtividade").change(tramita_formulario_atividade_create.handleLoadPerfis);
        $("input[name^='cadastrar_envio_formulario_avaliacao_atividade[publicacoes]']").click(tramita_formulario_atividade_create.handleLoadProjetos);
        $(document).on('click', "input[name^='cadastrar_envio_formulario_avaliacao_atividade[projetos]']", tramita_formulario_atividade_create.handleCheckboxProjeto);
        $("#cadastrar_envio_formulario_avaliacao_atividade_projetoSelecionado").change(tramita_formulario_atividade_create.handleLoadParticipantes);
        $(document).on('click', "input[name^='cadastrar_envio_formulario_avaliacao_atividade[perfis]']", tramita_formulario_atividade_create.handleCheckboxPerfil);
        $("#btn-add-item").click(tramita_formulario_atividade_create.handleAddParticipante);
        $("#btn-remove-item").click(tramita_formulario_atividade_create.handleRemoveParticipante);
        $("input[name='cadastrar_envio_formulario_avaliacao_atividade[stEnviaTodos]']").click(tramita_formulario_atividade_create.handleStEnviaTodos);
        $("#btn-mark-publicacoes").click(tramita_formulario_atividade_create.handleCheckboxAllPublicacoes);
        $("#btn-mark-projetos").click(tramita_formulario_atividade_create.handleCheckboxAllProjetos);
        $("#btn-salvar").click(tramita_formulario_atividade_create.handleSubmit);
        $("#btn-clear-form").click(tramita_formulario_atividade_create.handleClearForm);
    },
    
    handleLoadPerfis : function() {
        var id = $(this).val();
        
        if (!id) {
            $("#cadastrar_envio_formulario_avaliacao_atividade_perfis").html('');
            return;
        }
        
        $.ajax({
            url : Routing.generate('tramita_formulario_atividade_load_perfis_formulario', { id : id }),
            success : function(response) {
                
                $("#cadastrar_envio_formulario_avaliacao_atividade_perfis").html('');
                
                $.each(response, function (key, row) {
                    var input = '<div class="checkbox"><label><input id="cadastrar_envio_formulario_avaliacao_atividade_perfis_'+ (key + 1) +'" name="cadastrar_envio_formulario_avaliacao_atividade[perfis][]" value="'+ row.coSeqPerfil +'" type="checkbox">'+ row.dsPerfil +'</label></div>';
                    $("#cadastrar_envio_formulario_avaliacao_atividade_perfis").append(input);                    
                });                
            }
        });
    },
    
    handleLoadProjetos : function() {
        
        var ids = [];
        var checkboxSize = 0;
        
        $("input[name^='cadastrar_envio_formulario_avaliacao_atividade[publicacoes]'").each(function(key, input){
            if ($(input).is(':checked')) {
                ids.push($(input).val());
            }
            checkboxSize++;
        });
        
        $("#btn-mark-publicacoes").prop('checked', checkboxSize === ids.length);
        
        if (ids.length == 0) {
            $("#checkbox-projetos").html('');
            return;
        }
        
        $.ajax({
            url : Routing.generate('tramita_formulario_atividade_load_projetos_publicacao'),
            data : {
                ids : ids
            },
            success : function(response) {
                
                $("#checkbox-projetos").html('');
                
                $.each(response, function (key, projetos) {
                    $.each(projetos, function (index, projeto) { 
                        var input = '<div class="checkbox"><label><input name="cadastrar_envio_formulario_avaliacao_atividade[projetos][]" value="'+ projeto.coSeqProjeto +'" data-text="'+ projeto.coSeqProjeto +' - ' + projeto.nuSipar +'" type="checkbox">'+ projeto.coSeqProjeto +' - ' + projeto.nuSipar +'</label></div>';
                        $("#checkbox-projetos").append(input);
                    });
                });                
            }
        });
    },
    
    handleCopyProjetos : function() {
        
        $("#cadastrar_envio_formulario_avaliacao_atividade_projetoSelecionado").html('<option value="">Selecione</option>');
        
        var checked = 0;
        var all = 0;
        
        $("input[name^='cadastrar_envio_formulario_avaliacao_atividade[projetos]'").each(function(key, input) {
            if ($(input).is(':checked')) {
                var option = $('<option>');
                option.val($(input).val());
                option.text($(input).attr('data-text'));
                
                $("#cadastrar_envio_formulario_avaliacao_atividade_projetoSelecionado").append(option);
                checked++;
            }
            all++;
        });        
        
        $("#btn-mark-projetos").prop('checked', all === checked && checked !== 0);
    },
    
    handleStEnviaTodos : function () {
        if ($(this).val() == 1) {
            $("#selecionar-participantes").addClass('hidden');
            $("#cadastrar_envio_formulario_avaliacao_atividade_to_participantes option").each(function (key, option) {
                $(option).remove();
            });
        } else {
            $("#selecionar-participantes").removeClass('hidden');
        }
    },
    
    handleLoadParticipantes : function () {
        var id = $("#cadastrar_envio_formulario_avaliacao_atividade_projetoSelecionado").val();
        var perfis = [];
        
        $("input[name^='cadastrar_envio_formulario_avaliacao_atividade[perfis]']").each(function (key, input) {
            if ($(input).is(':checked')) {
                perfis.push($(input).val());
            }
        });
        
        if (!id || perfis.length == 0) {
            return;
        }
        
        $.ajax({
            url : Routing.generate('tramita_formulario_atividade_load_participantes', { id : id }),
            data : {
                perfis : perfis
            },
            success : function(response) {
                
                $("#cadastrar_envio_formulario_avaliacao_atividade_from_participantes").html('');
                
                $.each(response, function (key, row) {
                    var option = $('<option>');
                    option.val(row.coSeqProjetoPessoa);
                    option.text(row.descricaoParticipante);
                    option.attr('id-projeto', row.coProjeto);
                    option.attr('id-perfil', row.coPerfil);
                    
                    $("#cadastrar_envio_formulario_avaliacao_atividade_from_participantes").append(option);
                });                
            }
        });
    },
    
    handleAddParticipante : function () {
        $("#cadastrar_envio_formulario_avaliacao_atividade_from_participantes option:selected").each(function (key, option) {
            
            var add = true;
            
            $("#cadastrar_envio_formulario_avaliacao_atividade_to_participantes option").each(function (k, optionCloned) {                
                if ($(option).val() == $(optionCloned).val()) {
                    add = false;
                }
            });
            
            if (add) {
                var optionClone = $(option).clone();
                $("#cadastrar_envio_formulario_avaliacao_atividade_to_participantes").append(optionClone.prop('selected', true));
                $(option).remove();
            }
        });
    },
    
    handleRemoveParticipante : function () {
        $("#cadastrar_envio_formulario_avaliacao_atividade_to_participantes option:selected").each(function (key, option) {
            $(option).remove();
        });
    },
    
    markAndUnmarkCheckbox : function (inputName, mark, callback) {
        $(inputName).each(function (key, input){            
            $(input).prop('checked', mark);
        });
        
        if (callback) {
            callback();
        }
    },
    
    handleCheckboxProjeto : function () {
        tramita_formulario_atividade_create.handleCopyProjetos();
        tramita_formulario_atividade_create.handleRemoveParticipanteByProjeto();
    },
    
    handleCheckboxPerfil : function () {
        tramita_formulario_atividade_create.handleLoadParticipantes();
        tramita_formulario_atividade_create.handleRemoveParticipanteByPerfil();        
    },
    
    handleCheckboxAllPublicacoes : function () {        
        tramita_formulario_atividade_create.markAndUnmarkCheckbox(
            "input[name^='cadastrar_envio_formulario_avaliacao_atividade[publicacoes]']",
            $(this).is(':checked'),
            tramita_formulario_atividade_create.handleLoadProjetos
        );
    },
    
    handleCheckboxAllProjetos : function () {
        tramita_formulario_atividade_create.markAndUnmarkCheckbox(
            "input[name^='cadastrar_envio_formulario_avaliacao_atividade[projetos]']",
            $(this).is(':checked'),
            tramita_formulario_atividade_create.handleCopyProjetos
        );        
    },
    
    handleRemoveParticipanteByProjeto : function () {
        
        var projetos = [];
        
        $("input[name^='cadastrar_envio_formulario_avaliacao_atividade[projetos]']").each(function (key, input) {
            if ($(input).is(':checked')) {
                projetos.push($(input).val());
            }
        });
        
        $("#cadastrar_envio_formulario_avaliacao_atividade_to_participantes option").each(function (key, input) {
            if (projetos.indexOf($(input).attr('id-projeto')) == -1) {
                $(input).remove();
            }
        });
    },
    
    handleRemoveParticipanteByPerfil : function () {
        
        var perfis = [];
        
        $("input[name^='cadastrar_envio_formulario_avaliacao_atividade[perfis]']").each(function (key, input) {
            if ($(input).is(':checked')) {
                perfis.push($(input).val());
            }
        });
        
        $("#cadastrar_envio_formulario_avaliacao_atividade_to_participantes option").each(function (key, input) {
            if (perfis.indexOf($(input).attr('id-perfil')) == -1) {
                $(input).remove();
            }
        });
    },
    
    handleSubmit : function (e) {
        
        e.preventDefault();
        
        $("#cadastrar_envio_formulario_avaliacao_atividade_to_participantes option").each(function (key, input) {            
            $(input).prop('selected', true);
        });
        
        var form = $("form[name='cadastrar_envio_formulario_avaliacao_atividade']");        
        form.submit();
    },
    
    handleClearForm : function () {
        
        $("select option:first").prop('selected', true);
        $("input[type='text']").val('');
        $("#cadastrar_envio_formulario_avaliacao_atividade_stEnviaTodos_0").prop('selected', true);
        $("#cadastrar_envio_formulario_avaliacao_atividade_formularioAvaliacaoAtividade").trigger('change');        
        
        tramita_formulario_atividade_create.markAndUnmarkCheckbox(
            "input[name^='cadastrar_envio_formulario_avaliacao_atividade[publicacoes]']",
            false,
            tramita_formulario_atividade_create.handleLoadProjetos
        );
        tramita_formulario_atividade_create.markAndUnmarkCheckbox(
            "input[name^='cadastrar_envio_formulario_avaliacao_atividade[projetos]']",
            false,
            tramita_formulario_atividade_create.handleCopyProjetos
        );

        $("#cadastrar_envio_formulario_avaliacao_atividade_stEnviaTodos_0").trigger('click');
    }
    
};

tramita_formulario_atividade_create.init();