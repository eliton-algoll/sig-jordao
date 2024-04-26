(function ($) {
    var participante = {
        form: $("form").attr('name'),
        coMunicipioIbge: null,

        construct: function () {
            this.events();

            sessionStorage.setItem('participante_pessoa', '');

            $('#stDeclaracaoCursoPenultimo').hide();
            $('[id$="participante_stDeclaracaoCursoPenultimo"]').hide();

            var optionsCategoriaProf = $('[id$="cadastrar_participante_categoriaProfissional"] option');
            var _option = '';
            optionsCategoriaProf.each(function() {
                var area = $(this).attr('data-tp-area-formacao');
                _option += '<option value="'+ $(this).val() +'" data-tp-area-formacao="'+area+'">'+ $(this).text() +'</option>';
            });

            sessionStorage.setItem('optionsCategoriaProf', _option);
            participante.handleChangePerfil($('[id$="participante_perfil"]'), false);

            $('[name$="cursosLecionados][]"]').trigger('change');
            participante.disabledAreaTematicaNaoSelecionado($('[id$="participante_perfil"]'));
            $('input[name$="participante[stVoluntarioProjeto]"]:checked').trigger('click');

            setTimeout(function () {
                // $('[id$="participante_cursoGraduacao"]').trigger('change');

                // Bloqueia a edição do eixo de atuação quando alterando cadastro
                if ($('.nuCpf:first').attr('readonly') == 'readonly') {
                    // $('[id$="participante_coEixoAtuacao"] input')
                    //     .attr('readonly', 'readonly')
                    //     .attr('disabled', 'disabled');

                    var perfil = parseInt($('[id$="participante_perfil"]').val(), 10);

                    if (perfil === 4) { // Preceptor

                        $('[id$="participante_cursoGraduacao"]')
                            .attr('readonly', 'readonly')
                            .attr('disabled', 'disabled');
                    }
                }

                // Aplicar regrar de exibir ou ocultar curso de graduaçõa
                participante.handleChangeGrupoTutorialAoIniciarEditar($('[id$="participante_grupoTutorial"]'))

                $('[id$="hideAreaTematica"]').hide();

            }, 500);

           // $('[id$="participante_sexo"] option:selected').removeAttr('disabled');
        },

        events: function () {
            $('input[name$="[nuSei]"]').on('change', this.onBlurSei);

            $('[id$="participante_perfil"]').on('change', function () {
                if( $(this).val() == 7 ){
                    participante.handleKeyUpHasOrientadorServico();
                }
                participante.eraseAreaTematica();
                participante.handleChangePerfil($(this), true);
            });

            $('[id$="participante_nuCpf"]').on('change', function () {
                participante.handleKeyUpCpf($(this));
            });

            $('[id$="participante_coUf"]').on('change', function () {
                participante.handleChangeUf($(this));
            });

            $('[name$="cursosLecionados][]"]').on('change', function () {
                participante.handleChangeCursosLecionados();
            });

            $('[id$="participante_cursoGraduacao"]').on('change', function () {
                participante.handleChangeCursoGraduacao($(this));
            });

            $('[name$="participante[stVoluntarioProjeto]"]').on('click', this.handleParticipanteBolsista);

            $('[id$="participante_grupoTutorial"]').on('change', function () {
                participante.handleChangeGrupoTutorial($(this));
            });

            $('[name$="participante[coEixoAtuacao]"]').on('click', this.handleEixoAtuacao);

            $('#btn-incluir-telefone').click(this.handleClickBtnIncluirTelefone);
            $(document).on('click', '.btn-excluir-telefone', this.handleClickBtnExcluirTelefone);
            $("#btn-salvar, #btn-salvar-pessoais, #btn-salvar-contato, #btn-salvar-complementares").click(this.handleClickSubmit);
        },

        isAreaAtuacao: function () {
            return $("#prejetoAreaAtuacao").val() == '1';
        },

        validate: function () {
            if ($('#table-telefones').find('input[type="hidden"]').length < 1) {
                bootbox.alert('É obrigatório o preenchimento de pelo menos um Telefone.');
                return false;
            }

            try {
                var perfil = parseInt($('[id$="participante_perfil"]').val(), 10);

                if ((perfil === 4) || (perfil === 6)) { // Preceptor ou Estudante
                    var cursoId = parseInt($('[id$="participante_cursoGraduacao"]').val(), 10);

                    if ((isNaN(cursoId)) || (cursoId <= 0)) {
                        bootbox.alert('É obrigatório o preenchimento do Curso de Graduação.');
                        return false;
                    }
                }
            } catch (e) {
                return false;
            }

            return true;
        },

        handleClickSubmit: function () {
            if (participante.validate()) {
                $('form').submit();
            }
        },

        eraseAreaTematica: function () {
            $('[name$="areaTematica][]"] option:selected').removeAttr("selected");
            $('[name$="areaTematica][]"] option').attr('disabled', true);
        },

        disabledAreaTematicaNaoSelecionado: function (perfil) {
            var preceptor = 4;
            var estudante = 6;

            if (
                (perfil.val() != preceptor && participante.isAreaAtuacao()) ||
                (perfil.val() == estudante && !participante.isAreaAtuacao()) ||
                (perfil.val() == '')
            ) {
                $('[name$="areaTematica][]"] option').attr('disabled', true);

                $('[name$="areaTematica][]"] option').each(function (index, obj) {
                    if ($('[name$="areaTematica][]"] option:selected').val() == $(obj).val()) {
                        $(this).removeAttr('disabled');
                    }
                });
            }
        },

        handleChangePerfil: function (input, onchange = true) {
            var _optionsCategoriaProf = sessionStorage.getItem('optionsCategoriaProf');
            $('[id$="cadastrar_participante_categoriaProfissional"]').html(_optionsCategoriaProf);

           // $('[id$="participante_grupoTutorial"]').val('');
           $('[id$="participante_noDocumentoMatricula"]').parent('div.form-group').hide();

            if (input.val() != '2') {
                $('.nav-tabs').find('li').eq(2).show();
                $('#btn-salvar').appendTo('#dados-complementares');
            }

            participante.disabledAreaTematicaNaoSelecionado(input);

            switch (input.val()) {
                case '2': {
                    this.actionPerfilCordernadorProjeto();
                }
                    break;
                case '3': {
                    this.actionPerfilCordenadorGrupo();
                }
                    break;
                case '4': {
                    this.actionPerfilPreceptor();
                }
                    break;
                case '5': {
                    this.actionPerfilTutor();
                }
                    break;
                case '6' : {
                    this.actionPerfilEstudante();
                }
                    break;
                case '7' : {
                    this.actionPerfilOrientador();
                }
                    break;
            }
        },

        actionPerfilCordernadorProjeto: function () {
            $('.nav-tabs').find('li').eq(2).hide();
            $('#btn-salvar').appendTo('#dados-de-contato');
        },

        actionPerfilCordenadorGrupo: function () {
            $('[id$="participante_categoriaProfissional"]').parent('div.form-group').show();
            $('[id$="participante_cursosLecionados"]').parent('div.form-group').show();
            $('[id$="participante_areaTematica"]').parent('div.form-group').show();

            $('[id$="participante_coCnes"]').parent('div.form-group').hide();
            $('[id$="participante_titulacao"]').parent('div.form-group').hide();
            $('[id$="participante_cursoGraduacao"]').parent('div.form-group').hide();
            $('[id$="participante_noDocumentoMatricula"]').parent('div.form-group').hide();
            $('[id$="participante_nuAnoIngresso"]').parent('div.form-group').hide();
            $('[id$="participante_nuMatriculaIES"]').parent('div.form-group').hide();
            $('[id$="participante_nuSemestreAtual"]').parent('div.form-group').hide();
            $('[id$="participante_stAlunoRegular"]').parent('div.form-group').hide();


            $('[id$="cadastrar_participante_categoriaProfissional"] option[data-tp-area-formacao="2"]').remove();
            $('[id$="cadastrar_participante_categoriaProfissional"] option[data-tp-area-formacao="3"]').remove();

            if (!participante.isAreaAtuacao()) {
                $('[id$="participante_categoriaProfissional"]').parent('div.form-group').find('label').addClass('required');
                $('[name$="areaTematica][]"] option').removeAttr('disabled');
            }

            this.alertQuantidadeDeCoordenadores();
        },

        actionPerfilPreceptor: function () {
            $('[id$="participante_cursoGraduacao"]').parent('div.form-group').show();
            $('[id$="participante_categoriaProfissional"]').parent('div.form-group').show();
            $('[id$="participante_coCnes"]').parent('div.form-group').show();
            $('[id$="participante_areaTematica"]').parent('div.form-group').show();
            $('[name$="areaTematica][]"] option').removeAttr('disabled');

            $('[id$="participante_titulacao"]').parent('div.form-group').hide();
            // $('[id$="participante_cursoGraduacao"]').parent('div.form-group').hide();
            $('[id$="participante_noDocumentoMatricula"]').parent('div.form-group').hide();
            $('[id$="participante_nuAnoIngresso"]').parent('div.form-group').hide();
            $('[id$="participante_nuMatriculaIES"]').parent('div.form-group').hide();
            $('[id$="participante_nuSemestreAtual"]').parent('div.form-group').hide();
            $('[id$="participante_cursosLecionados"]').parent('div.form-group').hide();
            $('[id$="participante_stAlunoRegular"]').parent('div.form-group').hide();

            $('[id$="participante_coCnes"]').parent('div.form-group').find('label').addClass('required');
            $('[id$="participante_coCnes"]').parent('div.form-group').attr('required');

            $('[id$="cadastrar_participante_categoriaProfissional"] option[data-tp-area-formacao="2"]').remove();
            $('[id$="cadastrar_participante_categoriaProfissional"] option[data-tp-area-formacao="3"]').remove();

            if (!participante.isAreaAtuacao()) {
                $('[id$="participante_categoriaProfissional"]').parent('div.form-group').find('label').addClass('required');
            }
        },

        actionPerfilTutor: function () {
            $('[id$="participante_categoriaProfissional"]').parent('div.form-group').show();
            $('[id$="participante_cursosLecionados"]').parent('div.form-group').show();
            $('[id$="participante_areaTematica"]').parent('div.form-group').show();

            $('[id$="participante_coCnes"]').parent('div.form-group').hide();
            $('[id$="participante_titulacao"]').parent('div.form-group').hide();
            $('[id$="participante_cursoGraduacao"]').parent('div.form-group').hide();
            $('[id$="participante_noDocumentoMatricula"]').parent('div.form-group').hide();
            $('[id$="participante_nuAnoIngresso"]').parent('div.form-group').hide();
            $('[id$="participante_nuMatriculaIES"]').parent('div.form-group').hide();
            $('[id$="participante_nuSemestreAtual"]').parent('div.form-group').hide();
            $('[id$="participante_stAlunoRegular"]').parent('div.form-group').hide();

            if (!participante.isAreaAtuacao()) {
                $('[id$="participante_categoriaProfissional"]').parent('div.form-group').find('label').addClass('required');
                $('[name$="areaTematica][]"] option').removeAttr('disabled');
            }
        },

        actionPerfilEstudante: function () {
            $('[id$="participante_cursoGraduacao"]').parent('div.form-group').show();
            $('[id$="participante_noDocumentoMatricula"]').parent('div.form-group').show();
            $('[id$="participante_nuAnoIngresso"]').parent('div.form-group').show();
            $('[id$="participante_nuSemestreAtual"]').parent('div.form-group').show();
            $('[id$="participante_areaTematica"]').parent('div.form-group').show();
            $('[id$="participante_stAlunoRegular"]').parent('div.form-group').show();

            $('[id$="participante_categoriaProfissional"]').parent('div.form-group').hide();
            $('[id$="participante_coCnes"]').parent('div.form-group').hide();
            $('[id$="participante_titulacao"]').parent('div.form-group').hide();
            $('[id$="participante_nuMatriculaIES"]').parent('div.form-group').hide();
            $('[id$="participante_cursosLecionados"]').parent('div.form-group').hide();
        },

        actionPerfilOrientador: function () {
            $('[id$="participante_categoriaProfissional"]').parent('div.form-group').show();
            $('[id$="participante_cursosLecionados"]').parent('div.form-group').show();
            $('[id$="participante_areaTematica"]').parent('div.form-group').show();

            $('[id$="participante_coCnes"]').parent('div.form-group').hide();
            $('[id$="participante_titulacao"]').parent('div.form-group').hide();
            $('[id$="participante_cursoGraduacao"]').parent('div.form-group').hide();
            $('[id$="participante_noDocumentoMatricula"]').parent('div.form-group').hide();
            $('[id$="participante_nuAnoIngresso"]').parent('div.form-group').hide();
            $('[id$="participante_nuMatriculaIES"]').parent('div.form-group').hide();
            $('[id$="participante_nuSemestreAtual"]').parent('div.form-group').hide();
            $('[id$="participante_stAlunoRegular"]').parent('div.form-group').hide();

            if (!participante.isAreaAtuacao()) {
                $('[id$="participante_categoriaProfissional"]').parent('div.form-group').find('label').addClass('required');
                $('[name$="areaTematica][]"] option').removeAttr('disabled');
            }
        },

        handleKeyUpHasQtdPerfil: function (coPerfil, qtdPermitido) {

            $('#btn-salvar').hide();
            var msg = '';
            switch (coPerfil) {
                case '3': {
                    msg = 'Já existe um Coordenador de Grupo cadastrado no projeto';
                }
                    break;
                case '4': {
                    msg = 'Já existem dois Preceptores cadastrado no projeto';
                }
                    break;
                case '5': {
                    msg = 'Já existe um Tutor cadastrado no projeto';
                }
                    break;
                case '6' : {
                    msg = 'Já existem 8 Tutor cadastrado no projeto';
                }
                    break;
                case '7' : {
                    msg = 'Já existe um Orientador de Serviço cadastrado no projeto';
                }
                    break;
            }

            $.ajax({
                url: Routing.generate('get_perfil_limit_qtd_grupo', {perfil: coPerfil}),
                method: 'GET',
                dataType: 'json',
                success: function (response) {
                    if ($.isEmptyObject(response)) {
                        $('#btn-salvar').show();
                        return;
                    } else {
                        if ( response[0]['NR_ORIENTADOR'] != '0' ) {
                            $('#btn-salvar').hide();
                            bootbox.alert(msg);
                            return;
                        }
                        $('#btn-salvar').show();
                        return;
                    }
                }
            });
        },

        handleKeyUpHasOrientadorServico: function () {

            $('#btn-salvar').hide();
            var perfil = 7;
            $.ajax({
                url: Routing.generate('get_orientador_servico_projeto', {perfil: perfil}),
                method: 'GET',
                dataType: 'json',
                success: function (response) {
                    if ($.isEmptyObject(response)) {
                        $('#btn-salvar').show();
                        return;
                    } else {
                        if ( response[0]['NR_ORIENTADOR'] != '0' ) {
                            $('#btn-salvar').hide();
                            bootbox.alert('Já existe um Orientador de Serviço cadastrado no projeto');
                            return;
                        }
                        $('#btn-salvar').show();
                        return;
                    }
                }
            });
        },
        handleKeyUpCpf: function (input) {
            sessionStorage.setItem('participante_pessoa', '');
            // $('[id$="participante_grupoTutorial"]').val('');

            if (input.val().length != 14) return;

            var cpf = input.val().replace(/[^0-9]/g, '');

            $.ajax({
                url: Routing.generate('pessoa_get_by_cpf', {cpf: cpf}),
                method: 'GET',
                dataType: 'json',
                success: function (response) {
                    if ($.isEmptyObject(response)) {
                        bootbox.alert('CPF inválido!');
                    } else {
                        // console.log(response.pessoa);
                        sessionStorage.setItem('participante_pessoa', JSON.stringify(response.pessoa));

                        let dataNas = new Date(response.dtNascimento.date);
                            dataNas = dataNas.toLocaleDateString('pt-BR', {
                                timeZone: 'UTC',
                            });

                        $('[id$="participante_sexo"] option').removeAttr('selected');
                        $('[id$="participante_sexo"] option').attr('disabled', 'disabled');
                        $('[id$="participante_noPessoa"]').val(response.pessoa.noPessoa);
                        $('[id$="participante_dtNascimento"]').val(dataNas);
                      //  $('[id$="participante_sexo"] option[value="' + response.sexo.coSexo + '"]').attr('selected', 'selected');
                      //  $('[id$="participante_sexo"] option[value="' + response.sexo.coSexo + '"]').removeAttr('disabled');
                        $('[id$="participante_noMae"]').val(response.noMae);
                        $('[id$="participante_coCep"]').val(response.pessoa.nuCep);
                        $('[id$="participante_noLogradouro"]').val(response.pessoa.noLogradouro);
                        $('[id$="participante_nuLogradouro"]').val(response.pessoa.nuLogradouro);
                        $('[id$="participante_dsComplemento"]').val(response.pessoa.dsComplemento);
                        $('[id$="participante_noBairro"]').val(response.pessoa.noBairro);
                        $('[id$="participante_coUf"] option:contains(' + response.pessoa.sgUf + ')').attr('selected', 'selected');

                        if (response.pessoa.coMunicipioIbge) {
                            participante.coMunicipioIbge = response.pessoa.coMunicipioIbge.coMunicipioIbge;
                        }

                        participante.handleChangeUf($('[id$="participante_coUf"]'));
                        participante.loadEmail(cpf);
                        participante.loadTelefones(cpf);
                    }
                }
            });
        },

        handleChangeUf: function (input) {
            if (input.val()) {
                $('[id$="participante_coMunicipioIbge"]').empty();

                helper.makeOptions(
                    $('[id$="participante_coMunicipioIbge"]'),
                    Routing.generate('municipio_get_by_uf', {
                        uf: input.val()
                    }),
                    {},
                    'coMunicipioIbge',
                    'noMunicipio',
                    true,
                    participante.coMunicipioIbge
                );
            }
        },

        handleClickBtnIncluirTelefone: function () {
            if (!participante.validateTelefone()) return;

            if (participante.checkTelefoneHasAdd()) return;

            var tr = $('<tr>').append(
                $('<input>').attr('type', 'hidden').attr('name', participante.form + '[telefones][tpTelefone][]').val($('#telefone_tpTelefone > option:selected').val())
            ).append(
                $('<input>').attr('type', 'hidden').attr('name', participante.form + '[telefones][nuDdd][]').val($('#telefone_nuDdd').val())
            ).append(
                $('<input>').attr('type', 'hidden').attr('name', participante.form + '[telefones][nuTelefone][]').val($('#telefone_nuTelefone').val())
            ).append(
                $('<td>').append($('#telefone_nuDdd').val())
            ).append(
                $('<td>').append($('#telefone_nuTelefone').val())
            ).append(
                $('<td>').append($('#telefone_tpTelefone > option:selected').html())
            ).append(
                $('<td>').append($('<a>').attr('href', 'javascript:;').addClass('btn-excluir-telefone').append(
                    $('<span>').addClass('glyphicon glyphicon-remove').attr('title', 'Remover')
                ))
            );

            $('#table-telefones > tbody').append(tr);
            participante.handleTableTelefonesPlaceholder();

            $('input[name^=telefone]').val('');
        },

        handleClickBtnExcluirTelefone: function () {
            $(this).parents('tr').remove();
            participante.handleTableTelefonesPlaceholder();
        },

        validateTelefone: function () {
            var erros = [];

            if ($("#telefone_tpTelefone").val() == '') {
                erros.push('Selecinar o tipo de telefone');
            }
            if ($("#telefone_nuDdd").val().length < 2) {
                erros.push('O DDD deve possuir pelo menos 2 números');
            }
            if ($("#telefone_nuTelefone").val().length < 9) {
                erros.push('O Telefone deve possuir pelo menos 8 números');
            }

            if (erros.length > 0) {
                bootbox.alert('Para incluir novo telefone, é necessário: <br />' + erros.join('<br />'));
                return false;
            }

            return true;
        },

        handleTableTelefonesPlaceholder: function () {
            if ($('#table-telefones tr').length > 2) {
                $('#table-telefones tr.telefones-placeholder').addClass('hide');
            } else {
                $('#table-telefones tr.telefones-placeholder').removeClass('hide');
            }
        },

        checkTelefoneHasAdd: function () {
            var hasAdd = false;

            $("input[name='" + participante.form + "[telefones][nuTelefone][]']").each(function (key, input) {
                if ($('#telefone_nuTelefone').val() == $(input).val()) {
                    hasAdd = true;
                }
            });

            if (hasAdd) {
                bootbox.alert('O telefone já foi adicionado.');
            }

            return hasAdd;
        },

        alertQuantidadeDeCoordenadores: function () {
            var coProjeto = $('[id$="participante_projeto"] option:selected').val();

            if (coProjeto) {
                $.post(
                    Routing.generate("projeto_qtd_perfil_grupo_atuacao", {
                        coProjeto: coProjeto
                    }),
                    {},
                    function (result) {
                        var message = '<table class="table">';
                        message += '<thead>';
                        message += '<tr>';
                        message += '<th>Grupo de atuação</th>';
                        message += '<th>Coordenador de grupo</th>';
                        message += '<th>Voluntário</th>';
                        message += '</tr>';
                        message += '</thead>';
                        message += '<tbody>';

                        $(result).each(function (index, obj) {
                            message += '<tr>';
                            message += '<td>' + obj.coSeqGrupoAtuacao + ' - ' + obj.noGrupoAtuacao + '</td>';
                            message += '<td>' + obj.coordGrupo + '</td>';
                            message += '<td>' + obj.stVoluntarioProjeto + '</td>';
                            message += '</tr>';
                        });
                        message += '</tbody>';
                        message += '</table>';

                        bootbox.dialog({
                            message: message,
                            title: 'Contador',
                            size: 'large'
                        });
                    });
            }
        },

        handleChangeCursosLecionados: function () {
            $('[name$="areaTematica][]"] option').removeAttr('disabled');

            if ($('[id$="participante_perfil"] option:selected').val() == 5 || $('[id$="participante_perfil"] option:selected').val() == 3) { // 3-Coordenador de Grupo 5-Tutor
                $('[id$="participante_cursoGraduacao"]').val('');

                // desabilito todos os grupos de atuação
                $('[name$="areaTematica][]"] option').attr('disabled', true);

                // para cada curso lecionado
                $('[name$="cursosLecionados][]"]:checked').each(function (index, obj) {
                    // filtro todos os grupos de atuação
                    $('[name$="areaTematica][]"] option').filter(function () {
                        // caso o curso lecionado tenha o mesmo nome do grupo de atuação
                        var regex = '/.' + $(obj).parent().text().trim() + '/g';
                        return $(this).text().match(eval(regex));
                        // eu o retorno e removo o atributo disabled
                    }).removeAttr('disabled');
                });
            }
        },

        handleChangeCursoGraduacao: function (curso) {

            // if( $('#salvar-participante').val() == 'add' ) {
                $('[id$="participante_grupoTutorial"]').val('');
            // }
            $('[name$="areaTematica][]"] option').removeAttr('disabled');

            if ($('[id$="participante_perfil"] option:selected').val() == 6 && $(curso).find('option:selected').text() != 'OUTRO') { // Estudante
                $('[name$="cursosLecionados][]"]').removeAttr('checked', false);
                // desabilito todos os grupos de atuação
                $('[name$="areaTematica][]"] option').attr('disabled', true);
                // filtro todos os grupos de atuação
                $('[name$="areaTematica][]"] option').filter(function () {
                    // caso o curso de graduação tenha o mesmo nome do grupo de atuação
                    var regex = '/.' + $(curso).find('option:selected').text().trim() + '/g';

                    if ($(curso).find('option:selected').text().trim() == '') {
                        return false;
                    }

                    return $(this).text().match(eval(regex));
                    // eu o retorno e removo o atributo disabled
                }).removeAttr('disabled');
            }
        },

        loadTelefones: function (cpf) {
            $.ajax({
                url: Routing.generate('pessoa_get_telefones', {
                    pessoa: cpf
                }),
                success: function (response) {
                    if (!$.isEmptyObject(response)) {
                        $.each(response, function (key, value) {
                            $("#telefone_tpTelefone").val(value.tpTelefone.cod);
                            $("#telefone_nuDdd").val(value.nuDdd);
                            $("#telefone_nuTelefone").val(value.nuTelefone);
                            participante.handleClickBtnIncluirTelefone();
                        });
                    }
                }
            });
        },

        loadEmail: function (cpf) {
            $.ajax({
                url: Routing.generate('pessoa_get_email', {
                    pessoa: cpf
                }),
                success: function (response) {
                    if (!$.isEmptyObject(response)) {
                        $("#cadastrar_participante_dsEnderecoWeb").val(response.dsEnderecoWeb);
                    }
                }
            });
        },

        handleParticipanteBolsista: function () {
            if (participante.isAreaAtuacao()) {
                return;
            }

            if ($(this).val() == 'N') {
                participante.handleIsBolsista();
            } else {
                participante.handleIsNotBolsista();
            }
        },

        handleIsBolsista: function () {
            $('[id$="participante_categoriaProfissional"]').parent('div.form-group').find('label').addClass('required');
            $('[id$="participante_areaTematica"]').parent('div.form-group').find('label').addClass('required');
            $('[id$="participante_cursoGraduacao"]').parent('div.form-group').find('label').addClass('required');
        },

        handleIsNotBolsista: function () {
            $('[id$="participante_categoriaProfissional"]').parent('div.form-group').find('label').removeClass('required');
            $('[id$="participante_areaTematica"]').parent('div.form-group').find('label').removeClass('required');
            $('[id$="participante_cursoGraduacao"]').parent('div.form-group').find('label').removeClass('required');
        },

        handleChangeGrupoTutorial: function (input) {
            try {
                var value = input.val();

                if (value) {
                    var projetoSei = $('[id$="participante_nuSei"]').val();
                    var cpfPessoa = $('.nuCpf:first').val(); // $('[id$="participante_nuCpf"]').val();
                    var cursoGraduacao = $('[id$="participante_cursoGraduacao"]').val();
                    var pessoa = sessionStorage.getItem('participante_pessoa');

                    if ((!pessoa) || (pessoa == '')) {
                        pessoa = cpfPessoa;
                    } else {
                        try {
                            pessoa = JSON.parse(pessoa);
                        } catch (e) {
                            pessoa = pessoa;
                        }
                    }

                    var perfil = $('[id$="participante_perfil"]').val();

                    if ((!pessoa) || (pessoa == '')) {
                        bootbox.alert('O CPF precisa ser fornecido.');
                        // $('[id$="participante_grupoTutorial"]').val('');
                        return;
                    }

                    $('[id$="participante_coEixoAtuacao"] input').removeAttr('disabled');
                    $('[name$="participante[coEixoAtuacao]"]').removeAttr('checked');
                    $('[id$="participante_stDeclaracaoCursoPenultimo"]').parent().parent().parent().hide();
                    $('[id$="participante_cursoGraduacao"] option').show();

                    if ((!perfil) || (perfil < 1)) {
                        bootbox.alert('O Perfil do Participante precisa ser selecionado.');
                        // $('[id$="participante_grupoTutorial"]').val('');
                        return;
                    }

                    if (perfil == 6) { // Estudante

                        if ( !cursoGraduacao ) {
                            bootbox.alert('O Curso de Graduação do Participante precisa ser selecionado.');
                            $('[id$="participante_grupoTutorial"]').val('');
                            return;
                        }

                        $('#btn-salvar').hide();
                    }

                    if ((value > 0) && (perfil == 3 || perfil == 5)) { //Não permitir cadastrar mais de um Coordenador de grupo ou tutor

                        $.ajax({
                            url: Routing.generate('get_perfil_limit_qtd_grupo', {
                                coGrupo: value,
                                perfil: perfil,
                                cpf: cpfPessoa.replace(/([^\d])+/gim, '')
                            }),
                            method: 'GET',
                            dataType: 'json',
                            success: function (response) {
                                if ($.isEmptyObject(response)) {
                                    $('#btn-salvar').show();
                                    return;
                                } else {
                                    if ( response[0]['NR_CADASTRADO'] != '0' ) {
                                        var txtPerfil = '';
                                        if( perfil == 3  ) {
                                            txtPerfil = 'Coordenador de grupo';
                                        }

                                        if( perfil == 5  ) {
                                            txtPerfil = 'Tutor';
                                        }

                                        bootbox.alert('Já existe um '+txtPerfil+' cadastrado ao '+ $(input).find('option:selected').text() +'.');
                                        input.val('');
                                        $('#btn-salvar').hide();
                                        return;
                                    }
                                    $('#btn-salvar').show();
                                    return;
                                }
                            }
                        });

                    }

                    if( $('#salvar-participante').val() == 'add' ) {
                        var voluntary = $('.tab-content').find('input[name="cadastrar_participante[stVoluntarioProjeto]"]:checked').val();
                    }

                    if( $('#salvar-participante').val() == undefined || $('#salvar-participante').val() == 'edit' ) {
                        var voluntary = $('.tab-content').find('input[name="atualizar_participante[stVoluntarioProjeto]"]:checked').val();
                    }

                    if (value > 0) {
                        $.ajax({
                            url: Routing.generate('projeto_get_grupo_tutorial_details', {
                                grupoTutorial: value,
                                nuSipar: projetoSei,
                                cpf: cpfPessoa,
                                voluntario: voluntary,
                                cursoGraduacaoCandidato: cursoGraduacao,
                            }),
                            method: 'GET',
                            dataType: 'json',
                            success: function (response) {

                                if ($.isEmptyObject(response)) {
                                    bootbox.alert('Não foi possível obter os detalhes do Grupo Tutorial.');
                                    return false;
                                } else {
                                    if (response.details.eixoAtuacao) {
                                        switch (response.details.eixoAtuacao) {
                                            case 'A': { // Gestão em Saúde
                                                $('[id$="participante_coEixoAtuacao"] input').attr('disabled', 'disabled');
                                                $('[id$="participante_coEixoAtuacao_0"]').attr('checked', true);
                                                $('[name$="participante[coEixoAtuacao]"][value="A"]').prop('checked', true);
                                                $('[id$="participante_stDeclaracaoCursoPenultimo"]').parent().parent().parent().hide();
                                                break;
                                            }
                                            case 'B': { // Assistência à Saúde
                                                $('[id$="participante_coEixoAtuacao"] input').attr('disabled', 'disabled');
                                                $('[id$="participante_coEixoAtuacao_1"]').attr('checked', true);
                                                $('[name$="participante[coEixoAtuacao]"][value="B"]').prop('checked', true);
                                                $('[id$="participante_stDeclaracaoCursoPenultimo"]').parent().parent().parent().hide();
                                                break;
                                            }
                                            case 'C': { // Assistência à Saúde
                                                $('[id$="participante_coEixoAtuacao"] input').attr('disabled', 'disabled');
                                                $('[id$="participante_coEixoAtuacao_2"]').attr('checked', true);
                                                $('[name$="participante[coEixoAtuacao]"][value="C"]').prop('checked', true);
                                                $('[id$="participante_stDeclaracaoCursoPenultimo"]').parent().parent().parent().hide();
                                                break;
                                            }
                                        }
                                    }

                                    if (perfil == 4) { // Preceptor

                                        // Se Preceptor, não deve exibir os Cursos de Graduação que já esteja vinculado
                                        // aos Preceptores

                                        var options = $('[id$="participante_cursoGraduacao"] option');

                                        for (var i = 0; i < response.details.cursosGraduacao.length; i++) {
                                            for (var j = 0; j < options.length; j++) {
                                                if ($(options[j]).val() == response.details.cursosGraduacao[i]) {
                                                    $(options[j]).hide();
                                                }
                                            }
                                        }

                                        // Verifica se já existem dois preceptores para esse curso e não pode cadastrar
                                        // um novo
                                        var preceptores = [];
                                        var preceptorAtual = -1;
                                        var parts = window.location.href.split('/');

                                        if (parts.length === 6) {
                                            try {
                                                preceptorAtual = parseInt(parts[5], 10);
                                            } catch (e) {
                                                // Do nothing.
                                            }
                                        }

                                        for (var _pi = 0; _pi < response.details.preceptores.length; _pi++) {
                                            if (preceptorAtual <= 0) {
                                                preceptores.push(response.details.preceptores[_pi]);
                                            } else {
                                                if (response.details.preceptores[_pi] !== preceptorAtual) {
                                                    preceptores.push(response.details.preceptores[_pi]);
                                                }
                                            }
                                        }

                                        if (preceptores.length >= 2) {
                                            $('#btn-salvar').hide();
                                            bootbox.alert('Este Grupo já possui seus dois preceptores cadastrados, selecione outro grupo para realizar o cadastro.');
                                            input.val('');
                                            return;
                                        }
                                    }

                                    if (perfil == 6) { // Estudante
                                        if (response.details.temDoisPreceptores) {

                                            cursoGraduacao = parseInt(cursoGraduacao);

                                            if( response.details.estudantesEncontrados > 7 ) {
                                                $('#btn-salvar').hide();
                                                bootbox.alert('Este grupo já atingiu o limite de 8 estudantes, favor adicionar o participante em outro Grupo Tutorial.');
                                                input.val('');
                                                return;
                                            }

                                            var estudantesNaoSaude = (response.details.estudantesCienciasHumanas + response.details.estudantesCienciasSociaisEncontrados);

                                            if( (estudantesNaoSaude > 1) && (response.details.cursoCandidatoSaude == false )) {
                                                $('#btn-salvar').hide();
                                                bootbox.alert('Este grupo já atingiu o limite de 2 estudantes de ciências humanas e/ou ciências sociais aplicadas, favor adicionar o participante em outro Grupo Tutorial.');
                                                input.val('');
                                                return;
                                            }

                                            if( (response.details.estudantesSaude == 6) && (response.details.cursoCandidatoSaude == true )) {
                                                $('#btn-salvar').hide();
                                                bootbox.alert('Este grupo já atingiu o limite de 6 estudantes da área da saúde, favor adicionar o participante em outro Grupo Tutorial.');
                                                input.val('');
                                                return;
                                            }

                                            if( (response.details.estudantesSaude == 5) && (response.details.cursoCandidatoSaude == true )) {
                                                var estudantesCursoSaude = response.details.estudantesCursoSaude;
                                                console.log('estudantesCursoSaude', estudantesCursoSaude);
                                                console.log('estudantesCursoSaude', estudantesCursoSaude);
                                                if (estudantesCursoSaude.length < 3) {
                                                    if ( estudantesCursoSaude.includes(cursoGraduacao) ) {
                                                        $('#btn-salvar').hide();
                                                        bootbox.alert('Os estudantes da área da saúde devem ser distribuídos em pelo menos três cursos distintos.');
                                                        input.val('');
                                                        return;
                                                    }
                                                }
                                            }

                                            if( (response.details.estudantesSaude == 4) && (response.details.cursoCandidatoSaude == true )) {
                                                var estudantesCursoSaude = response.details.estudantesCursoSaude;
                                                if (estudantesCursoSaude.length < 2) {
                                                    if ( estudantesCursoSaude.includes(cursoGraduacao) ) {

                                                        $('#btn-salvar').hide();
                                                        bootbox.alert('Os estudantes da área da saúde devem ser distribuídos em pelo menos três cursos distintos.');
                                                        input.val('');
                                                        return;
                                                    }
                                                }
                                            }

                                            $('#btn-salvar').show();
                                        } else {
                                            $('#btn-salvar').hide();
                                            bootbox.alert('É necessário ter no mínimo 2 (dois) Preceptores bolsistas cadastrados. Salvar não é permitido.');
                                            input.val('');
                                            return;
                                        }
                                    } else {
                                        $('#btn-salvar').show();
                                    }

                                    // Verifica se a quantidade de cursos ocultos é igual a quantidade de cursos
                                    var cursos = $('[id$="participante_cursoGraduacao"] option');
                                    var totalCursos = cursos.length;
                                    var totalCursosOcultos = 0;

                                    for (var i = 0; i < totalCursos; i++) {
                                        if (cursos[i].style.display == 'none') {
                                            totalCursosOcultos++;
                                        }
                                    }

                                    // if (totalCursos == totalCursosOcultos) {
                                    //     bootbox.alert('Este grupo já atingiu o limite de 4 alunos neste Curso de Graduação, favor adicionar o participante em outro Curso de Graduação disponível ou outro Grupo Tutorial.');
                                    //     return;
                                    // }
                                }
                            }
                        });
                    }
                }
            } catch (e) {
                // Do nothing.
            }
        },

        handleChangeGrupoTutorialAoIniciarEditar: function (input) {
            try {
                var value = input.val();

                if (value) {
                    var projetoSei = $('[id$="participante_nuSei"]').val();
                    var cursoGraduacao = $('[id$="participante_cursoGraduacao"]').val();
                    var cpfPessoa = $('.nuCpf:first').val(); // $('[id$="participante_nuCpf"]').val();
                    var pessoa = sessionStorage.getItem('participante_pessoa');

                    if ((!pessoa) || (pessoa == '')) {
                        pessoa = cpfPessoa;
                    } else {
                        try {
                            pessoa = JSON.parse(pessoa);
                            pessoa = pessoa.nuCpfCnpjPessoa;
                        } catch (e) {
                            pessoa = pessoa;
                        }
                    }

                    var perfil = $('[id$="participante_perfil"]').val();

                    if ((!pessoa) || (pessoa == '')) {
                        // bootbox.alert('O CPF precisa ser fornecido.');
                        // $('[id$="participante_grupoTutorial"]').val('');
                        return;
                    }

                    // $('[id$="participante_coEixoAtuacao"] input').removeAttr('disabled');
                    // $('[name$="participante[coEixoAtuacao]"]').removeAttr('checked');
                    $('[id$="participante_stDeclaracaoCursoPenultimo"]').parent().parent().parent().hide();
                    $('[id$="participante_cursoGraduacao"] option').show();

                    if ((!perfil) || (perfil < 1)) {
                        // bootbox.alert('O Perfil do Participante precisa ser selecionado.');
                        // $('[id$="participante_grupoTutorial"]').val('');
                        return;
                    }

                    if (perfil == 6) { // Estudante
                        $('#btn-salvar').hide();
                    }

                    if( $('#salvar-participante').val() == undefined || $('#salvar-participante').val() == 'edit' ) {
                        var voluntary = $('.tab-content').find('input[name="atualizar_participante[stVoluntarioProjeto]"]:checked').val();
                    }

                    if( $('#salvar-participante').val() == 'add' ) {
                        var voluntary = $('.tab-content').find('input[name="cadastrar_participante[stVoluntarioProjeto]"]:checked').val();
                    }

                    if (value > 0) {
                        $.ajax({
                            url: Routing.generate('projeto_get_grupo_tutorial_details', {
                                grupoTutorial: value,
                                nuSipar: projetoSei,
                                cpf: pessoa,
                                voluntario: voluntary,
                                cursoGraduacaoCandidato: cursoGraduacao,
                            }),
                            method: 'GET',
                            dataType: 'json',
                            success: function (response) {
                                // console.log(response);

                                if ($.isEmptyObject(response)) {
                                    // bootbox.alert('Não foi possível obter os detalhes do Grupo Tutorial.');
                                    return false;
                                } else {
                                    if (response.details.eixoAtuacao) {
                                        switch (response.details.eixoAtuacao) {
                                            case 'A': { // Gestão em Saúde
                                                $('[id$="participante_coEixoAtuacao"] input').attr('disabled', 'disabled');
                                                $('[id$="participante_coEixoAtuacao_0"]').attr('checked', true);
                                                $('[name$="participante[coEixoAtuacao]"][value="A"]').prop('checked', true);
                                                $('[id$="participante_stDeclaracaoCursoPenultimo"]').parent().parent().parent().hide();
                                                break;
                                            }
                                            case 'B': { // Assistência à Saúde
                                                $('[id$="participante_coEixoAtuacao"] input').attr('disabled', 'disabled');
                                                $('[id$="participante_coEixoAtuacao_1"]').attr('checked', true);
                                                $('[name$="participante[coEixoAtuacao]"][value="B"]').prop('checked', true);
                                                $('[id$="participante_stDeclaracaoCursoPenultimo"]').parent().parent().parent().hide();
                                                break;
                                            }
                                            case 'C': { // Assistência à Saúde
                                                $('[id$="participante_coEixoAtuacao"] input').attr('disabled', 'disabled');
                                                $('[id$="participante_coEixoAtuacao_2"]').attr('checked', true);
                                                $('[name$="participante[coEixoAtuacao]"][value="C"]').prop('checked', true);
                                                $('[id$="participante_stDeclaracaoCursoPenultimo"]').parent().parent().parent().hide();
                                                break;
                                            }
                                        }
                                    }

                                    if (perfil == 4) { // Preceptor

                                        // Se Preceptor, não deve exibir os Cursos de Graduação que já esteja vinculado
                                        // aos Preceptores

                                        var options = $('[id$="participante_cursoGraduacao"] option');

                                        for (var i = 0; i < response.details.cursosGraduacao.length; i++) {
                                            for (var j = 0; j < options.length; j++) {
                                                if ($(options[j]).val() == response.details.cursosGraduacao[i]) {
                                                    $(options[j]).hide();
                                                }
                                            }
                                        }
                                    }

                                    // if ((perfil == 5) || (perfil == 6)) { // Tutor | Estudante
                                    if (perfil == 6) { // Estudante
                                        if (response.details.temDoisPreceptores) {
                                            $('#btn-salvar').show();
                                        } else {
                                            $('#btn-salvar').hide();
                                            // bootbox.alert('É necessário ter no mínimo 2 (dois) Preceptores bolsistas cadastrados. Salvar não é permitido.');
                                            return;
                                        }
                                    } else {
                                        $('#btn-salvar').show();
                                    }

                                    // Verifica se a quantidade de cursos ocultos é igual a quantidade de cursos
                                    var cursos = $('[id$="participante_cursoGraduacao"] option');
                                    var totalCursos = cursos.length;
                                    var totalCursosOcultos = 0;

                                    for (var i = 0; i < totalCursos; i++) {
                                        if (cursos[i].style.display == 'none') {
                                            totalCursosOcultos++;
                                        }
                                    }

                                    if (totalCursos == totalCursosOcultos) {
                                        // bootbox.alert('Este grupo já atingiu o limite de 4 alunos neste Curso de Graduação, favor adicionar o participante em outro Curso de Graduação disponível ou outro Grupo Tutorial.');
                                        return;
                                    }
                                }
                            }
                        });
                    }
                }
            } catch (e) {
                // Do nothing.
            }
        },

        handleEixoAtuacao: function () {
            $('[id$="participante_stDeclaracaoCursoPenultimo"]').parent().parent().parent().hide();

            switch ($(this).val()) {
                case 'A': { // Gestão em Saúde
                    // Do nothing.
                    $('[id$="participante_stDeclaracaoCursoPenultimo"]').parent().parent().parent().hide();
                    break;
                }
                case 'B': { // Assistência à Saúde
                    $('[id$="participante_stDeclaracaoCursoPenultimo"]').parent().parent().parent().hide();
                    break;
                }
                case 'C': { // Assistência à Saúde
                    $('[id$="participante_stDeclaracaoCursoPenultimo"]').parent().parent().parent().hide();
                    break;
                }
            }
        },

        onBlurSei: function () {
            var input = $(this);

            $.ajax({
                url: Routing.generate('projeto_get_by_sipar'),
                data: {
                    nuSipar: input.val()
                },
                success: function (response) {
                    // console.log(response);

                    if (response.status === false) {
                        input.val('');
                        bootbox.alert(response.error);
                    }
                }
            });
        }
    };

    participante.construct();
})(jQuery);
