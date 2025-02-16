var gerar_folha_suplementar = (function () {

    function init() {
        $("#cadastar_folha_suplementar_publicacao").on('change', function () {
            loadProjetos($(this).val());
            loadReferencias($(this).val());
            resetGrids();
        });
        $(document).on('click', '#grid-participantes input[name="checkall"]', checkGridAutorizado);
        $(document).on('click', '#grid-selecionados input[name="checkall"]', checkGridSelecionados);
        $(document).on('click', '#grid-participantes input[type="checkbox"]', checkUncheckAllGridAutorizados);
        $(document).on('click', '#grid-selecionados input[type="checkbox"]', checkUncheckAllGridSelecionados);
        $(document).on('click', '#btn-add', addRows);
        $(document).on('click', '#btn-remove', removeRows);
        $("#btn-buscar").on('click', checkSiparBeforeLoadParticipantes);
        $("#btn-salva").on('click', salvar);
        $("#btn-salva-fecha").on('click', salvarFechar);
        $("#btn-voltar").on('click', voltar);
    }

    function loadReferencias(publicacao) {
        if (publicacao === '') {
            $("#cadastar_folha_suplementar_folhaPagamento option:not(:first)").remove();
            return;
        }

        helper.makeOptions(
            '#cadastar_folha_suplementar_folhaPagamento',
            Routing.generate('folha_pgto_suplementar_list_folhas_mensais', {publicacao: publicacao}),
            [],
            'coSeqFolhaPagamento',
            'referencia',
            true,
            null,
            function () {
                if ($('#cadastar_folha_suplementar_folhaPagamento option').length <= 1) {
                    bootbox.alert('Para o programa selecionado, não foi encontrada nenhuma folha de pagamento MENSAL com situação válida para geração de folha de pagamento COMPLEMENTAR. Selecione um novo programa.');
                }
            }
        );
    }

    function loadProjetos(publicacao) {
        if (publicacao === '') {
            $("#cadastar_folha_suplementar_projeto option:not(:first)").remove();
            return;
        }

        helper.makeOptions(
            '#cadastar_folha_suplementar_projeto',
            Routing.generate('projeto_list_by_publicacao', {publicacao: publicacao}),
            [],
            'coSeqProjeto',
            'nuSipar',
            true,
            null
        );
    }

    function checkSiparBeforeLoadParticipantes()
    {
        var publicacao = $("#cadastar_folha_suplementar_publicacao").val();
        var projeto = $("#cadastar_folha_suplementar_projeto").val();

        if (!projeto) {
            loadParticipantes();
            return;
        }

        $.ajax({
            url: Routing.generate('projeto_get_by_sipar'),
            data: {
                publicacao: publicacao,
                nuSipar: projeto,
                ignoreVigencia: 1
            },
            success: function(response) {
                if (response.status) {
                    if (publicacao != response.projeto.publicacao.coSeqPublicacao) {
                        $("#cadastar_folha_suplementar_publicacao option[value='" + response.projeto.publicacao.coSeqPublicacao + "']").prop('selected', true);
                        $("#cadastar_folha_suplementar_publicacao").trigger('change');
                    }
                    loadParticipantes();
                } else {
                    bootbox.alert(response.error);
                }
            }
        });
    }

    function loadParticipantes() {
        var publicacao = $("#cadastar_folha_suplementar_publicacao").val();
        var projeto = $("#cadastar_folha_suplementar_projeto").val();
        var cpf = $("#cadastar_folha_suplementar_cpf").val();
        var folha = $("#folha-suplementar").val();
        var selecionados = [];

        if (projeto === '' && cpf === '') {

            bootbox.alert('Nº SEI ou CPF obrigatório.');

            $("#div-participantes").addClass('hidden');
            $("#grid-participantes").empty();
            return;
        }

        if (folha === '') {
            selecionados = $('#grid-selecionados table tbody input[type="checkbox"]').map(
                function (k, input) {
                    return input.value;
                }
            ).toArray();
        }

        $.ajax({
            url: Routing.generate('folha_pgto_suplementar_grid_participantes'),
            data: {
                publicacao: publicacao,
                nuSipar: projeto,
                folhaPagamento: folha,
                participantes: selecionados,
                cpf: cpf
            },
            datatype: 'html',
            success: function (response) {
                $("#div-participantes").removeClass('hidden');
                $("#grid-participantes").html(response);
            }
        });
    }

    function loadSelecionados() {
        var folha = $("#folha-suplementar").val();

        if (folha === '') {
            $("#grid-selecionados tbody tr").remove();
            return;
        }

        $.ajax({
            url: Routing.generate(
                'folha_pgto_suplementar_grid_autorizacoes',
                {folhaPagamento: folha}
            ),
            datatype: 'html',
            success: function (response) {
                $("#div-selecionados").removeClass('hidden');
                $("#grid-selecionados").html(response);
            }
        });
    }

    function checkGridAutorizado() {
        var check = $(this);

        checkUncheckGrid(check.is(':checked'), '#grid-participantes');
    }

    function checkGridSelecionados() {
        var check = $(this);

        checkUncheckGrid(check.is(':checked'), '#grid-selecionados');
    }

    function checkUncheckGrid(check, selector) {
        $(selector + ' input[type="checkbox"]:not(:disabled)').prop('checked', check);
    }

    function checkUncheckAllGridAutorizados() {
        if ($(this).attr('name') !== 'checkall') {
            checkUncheckAll('#grid-participantes');
        }
    }

    function checkUncheckAllGridSelecionados() {
        if ($(this).attr('name') !== 'checkall') {
            checkUncheckAll('#grid-selecionados');
        }
    }

    function checkUncheckAll(selector) {
        var check = true;

        $(selector + ' input[type="checkbox"]').each(function (key, inputSelector) {
            if (!$(inputSelector).is(':checked') && $(inputSelector).attr('name') !== 'checkall') {
                check = false;
            }
        });

        $(selector + ' input[name="checkall"]').prop('checked', check);
    }

    function addRows() {
        if (0 === $('#grid-participantes table tbody input[type="checkbox"]:checked').length) {
            bootbox.alert('Selecione os participantes que deseja adicionar/remover na Folha de Pagamento Complementar.');
            return;
        }

        if ($("#folha-suplementar").val() === '') {
            moveRows('#grid-participantes', '#grid-selecionados');

            if ($('#grid-participantes table tbody tr').length === 0) {
                $("#div-participantes").addClass('hidden');
            }

            $("#div-selecionados").removeClass('hidden');
        } else {
            addToDataBase();
        }
        checkUncheckAllGridSelecionados();
    }

    function removeRows() {
        if (0 === $('#grid-selecionados table tbody input[type="checkbox"]:checked').length) {
            bootbox.alert('Selecione os participantes que deseja adicionar/remover na Folha de Pagamento Complementar.');
            return;
        }

        bootbox.confirm({
            message: 'Confirma a exclusão dos participantes selecionados da folha complementar?',
            buttons: {
                confirm: {
                    label: 'Sim'
                },
                cancel: {
                    label: 'Não'
                }
            },
            callback: function (result) {
                if (result) {
                    if ($("#folha-suplementar").val() === '') {
                        $('#grid-selecionados table tbody input[type="checkbox"]:checked').each(function (key, inputSelector) {

                            var vlTotal = parseFloat($("#vlTotalSelecionado").text().replace('.', '').replace(',', '.'));
                            var vlBolsa = parseFloat($(inputSelector).closest('tr').find('td').eq(6).text().replace('R\$', '').replace('.', '').replace(',', '.'));

                            $("#vlTotalSelecionado").text((vlTotal - vlBolsa).toFixed(2).replace('.', ',').replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.'));

                            $(inputSelector).closest('tr').remove();
                        });
                        loadParticipantes();
                        if (0 === $('#grid-selecionados table tbody input[type="checkbox"]').length) {
                            $("#div-selecionados").addClass('hidden');
                        }
                    } else {
                        removeFromDataBase();
                    }
                    checkUncheckAllGridAutorizados();
                }
            }
        });
    }

    function moveRows(selectorGrid1, selectorGrid2) {
        $(selectorGrid1 + ' table tbody input[type="checkbox"]').each(function (key, inputSelector) {
            if ($(inputSelector).is(':checked')) {
                $(selectorGrid2 + ' table tbody').append($(inputSelector).prop('checked', false).closest('tr').clone());

                var vlTotal = parseFloat($("#vlTotalSelecionado").text().replace('.', '').replace(',', '.'));
                var vlBolsa = parseFloat($(inputSelector).closest('tr').find('td').eq(6).text().replace('R\$', '').replace('.', '').replace(',', '.'));

                $("#vlTotalSelecionado").text((vlTotal + vlBolsa).toFixed(2).replace('.', ',').replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.'));

                $(inputSelector).closest('tr').remove();
            }
        });
    }

    function fillInputSelecionados() {
        var participantes = [];

        $('#grid-selecionados table tbody input[type="checkbox"]').each(function (key, inputSelector) {
            participantes.push($(inputSelector).val());
        });

        $('#cadastar_folha_suplementar_participantes').val(participantes.join(','));
    }

    function addToDataBase() {
        var folha = $("#folha-suplementar").val();
        var autorizacoes = $('#grid-participantes table tbody input[type="checkbox"]:checked').map(function () {
            return this.value;
        }).get();

        if (folha === '' || autorizacoes.lenght === 0) return;

        $.ajax({
            url: Routing.generate('folha_pgto_suplementar_add', {folhaPagamento: folha}),
            type: 'POST',
            data: {
                autorizacoes: autorizacoes
            },
            success: function (response) {
                if (response.status) {
                    $.each(autorizacoes, function (key, value) {
                        $("#" + value).remove();
                    });

                    loadSelecionados();
                } else {
                    bootbox.alert(response.error);
                }
            }
        });
    }

    function removeFromDataBase() {
        var folha = $("#folha-suplementar").val();
        var selecionados = $('#grid-selecionados table tbody input[type="checkbox"]:checked').map(function () {
            return this.value;
        }).get();

        if (folha === '' || selecionados.lenght === 0) return;

        $.ajax({
            url: Routing.generate('folha_pgto_suplementar_remove', {folhaPagamento: folha}),
            type: 'POST',
            data: {
                autorizacoes: selecionados
            },
            success: function (response) {
                if (response.status) {
                    $.each(selecionados, function (key, value) {
                        $("#" + value).remove();
                    });

                    loadSelecionados();
                } else {
                    bootbox.alert(response.error);
                }
            }
        });
    }

    function resetGrids() {
        $('#div-selecionados').addClass('hidden');
        $('#grid-selecionados table tbody tr').remove();
        $("#div-participantes").addClass('hidden');
        $("#grid-participantes").empty();
    }

    function salvarFechar() {
        bootbox.confirm({
            message: 'Ao confirmar essa opção, o sistema fechará a respectiva Folha de Pagamento Suplementar e NÃO será mais permitido adicionar ou remover participantes dessa folha. Confirma o fechamento da folha suplementar?',
            buttons: {
                confirm: {
                    label: 'Sim'
                },
                cancel: {
                    label: 'Não'
                }
            },
            callback: function (result) {
                if (result) {
                    fillInputSelecionados();
                    $("#cadastar_folha_suplementar_salvaEfecha").val('S');
                    $("form").submit();
                }
            }
        });
    }

    function salvar() {
        fillInputSelecionados();
        $("#cadastar_folha_suplementar_salvaEfecha").val('N');
        $("form").submit();
    }

    function voltar() {
        bootbox.confirm({
            message: 'Ao confirmar a ação VOLTAR, as atualizações que não foram salvas poderão ser perdidas. Deseja realmente VOLTAR?',
            buttons: {
                confirm: {
                    label: 'Sim'
                },
                cancel: {
                    label: 'Não'
                }
            },
            callback: function (result) {
                if (result) {
                    window.location.href = Routing.generate('folha_pagamento');
                }
            }
        });
    }

    return {
        init: init()
    };
})();

gerar_folha_suplementar.init;