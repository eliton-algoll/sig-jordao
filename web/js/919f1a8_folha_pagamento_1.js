(function() {

    var tipoPgtoPristine = true;

    function init()
    {
        events();
        triggers();
    }

    function triggers()
    {
        $("select[name$='[tpFolhaPagamento]']").trigger('change');
        onChangeMesAno();
    }

    function events()
    {
        $("#filtro_relatorio_folha_pagamento_mes, " +
          "#filtro_relatorio_folha_pagamento_ano, " +
          "#filtro_relatorio_folha_pagamento_publicacao").on('change', loadFolhas);

        $("select[name$='[tpFolhaPagamento]']").on('change', onChangeTipoPagamento);
        $('select[name$="[ano]"]').on('change', onChangeMesAno);
        $('select[name$="[mes]"]').on('change', onChangeMesAno);

        $("#btn-clear-form").on('click', onClearForm);

        // Just to avoid weird behavior
        $('form').submit(function(e) {
            clearFormErrors();
        });
    }

    function onChangeTipoPagamento()
    {
        if ($(this).val() === 'S') {
            $('select[name$="[folhaPagamento]"]').prop('disabled', false);
            if (tipoPgtoPristine === false) {
                $('select[name$="[mes]"]').trigger('change');
            }
        } else {
            $('select[name$="[folhaPagamento]"] option:not(:first)').remove();
            $('select[name$="[folhaPagamento]"]').prop('disabled', true);
            $('select[name$="[folhaPagamento]"]').parent('div').removeClass('has-error');
            $('select[name$="[folhaPagamento]"]').parent('div').find('.help-block').eq(0).addClass('hidden');
        }
        tipoPgtoPristine = false;
    }

    function onChangeMesAno()
    {
        $('select[name$="[mes]"] option').prop('disabled', false);

        var now = new Date();
        var ano = $('select[name$="[ano]"] option:selected').val();

        if (parseInt(ano) == now.getFullYear()) {
            $('select[name$="[mes]"] option').each(function() {
               if ($(this).val() > now.getMonth() + 1) {
                   $(this).prop('disabled', true);
                   if ($(this).is(':selected')) {
                       $('select[name$="[mes]"]').prop('selectedIndex', 0);
                   }
               }
            });
        }
    }

    function onClearForm()
    {
        $('select').prop('selectedIndex', 0);
        $('input[type="text"]').val('');
        $("select[name$='[tpFolhaPagamento]']").trigger('change');
        $("select[name$='[ano]']").trigger('change');
    }

    function getDataToLoadFolhas()
    {
        return {
            publicacao: $("#filtro_relatorio_folha_pagamento_publicacao option:selected").val(),
            ano: $("#filtro_relatorio_folha_pagamento_ano option:selected").val(),
            mes: $("#filtro_relatorio_folha_pagamento_mes option:selected").val(),
            tpFolhaPagamento: $("select[name$='[tpFolhaPagamento]'] option:selected").val()
        }
    }

    function loadFolhas()
    {
        var data = getDataToLoadFolhas();

        if (data.tpFolhaPagamento !== 'S' || data.publicacao === '' || data.ano === '' || data.mes === '') {
            $("#filtro_relatorio_folha_pagamento_folhaPagamento option:not(:first)").remove();
            return;
        }

        var url = Routing.generate('folha_pgto_suplementar_list_by_ano_mes', {publicacao: data.publicacao});
        var fixedUrl = [url, data.ano, data.mes].join('/');

        helper.makeOptions(
            '#filtro_relatorio_folha_pagamento_folhaPagamento',
            fixedUrl,
            {},
            'coSeqFolhaPagamento',
            'dtInclusao',
            true
        );
    }

    function clearFormErrors()
    {
        $('.no-result').addClass('hidden');
        $('.help-block').addClass('hidden');
        $('.form-group').removeClass('has-error');
    }

    $(document).ready(init);
})();