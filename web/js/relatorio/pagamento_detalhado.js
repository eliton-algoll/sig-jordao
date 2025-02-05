var pagamento_detalhado = (function(){
    
    function init()
    {
        $("#filtro_relatorio_detalhado_folha_pagamento_nuSei").on('blur keyup', handleBehaviorFilter);
        $("#filtro_relatorio_detalhado_folha_pagamento_coordenador").on('change', handleBehaviorFilter);
        $("#filtro_relatorio_detalhado_folha_pagamento_publicacao").on('change', loadCoordenadores);
        $("#filtro_relatorio_detalhado_folha_pagamento_publicacao").on('change', loadFolhas);
        $("#filtro_relatorio_detalhado_folha_pagamento_situacao").on('change', loadFolhas);
        $("#btn-clear-form").on('click', clearForm);        
    }
    
    function handleBehaviorFilter()
    {
        var sei = $("#filtro_relatorio_detalhado_folha_pagamento_nuSei");
        var coordenador = $("#filtro_relatorio_detalhado_folha_pagamento_coordenador");
        
        if (sei.val() !== '') {            
            coordenador.prop('disabled', true).val('');
        } else {
            coordenador.prop('disabled', false);
        }
        if (coordenador.val() !== '') {
            sei.prop('readonly', true).val('');            
        } else {
            sei.prop('readonly', false);
        }
    }
    
    function loadCoordenadores()
    {
        var publicacao = $(this).val();
        
        if (publicacao === '') {
            $("#filtro_relatorio_detalhado_folha_pagamento_coordenador").empty().html('<option value="">Selecione</option>');
        }
        
        helper.makeOptions(
            '#filtro_relatorio_detalhado_folha_pagamento_coordenador',
            Routing.generate('coordenador_list_by_publicacao', { publicacao : publicacao }),
            [],
            'nuCpf',
            'noPessoa',
            true
        );
    }
    
    function loadFolhas()
    {
        var params = {
            'publicacao' : $("#filtro_relatorio_detalhado_folha_pagamento_publicacao").val(),
            'situacao' :  $("#filtro_relatorio_detalhado_folha_pagamento_situacao").val(),
            'order' : {
                'fp.nuAno' : 'DESC',
                'fp.nuMes' : 'DESC',
                'fp.tpFolhaPagamento' : 'ASC'
            }
        };
        
        if (params.publicacao === '' || params.situacao === '') {
            $("#filtro_relatorio_detalhado_folha_pagamento_folhaPagamento").empty().html('<option value="">Selecione</option>');
            return;
        }
        
        helper.makeOptions(
            '#filtro_relatorio_detalhado_folha_pagamento_folhaPagamento',
            Routing.generate('folha_pagamento_list_by_params'),
            params,
            'coFolhaPagamento',
            'referencia',
            true,
            undefined,
            function () {
                $("#filtro_relatorio_detalhado_folha_pagamento_folhaPagamento option").eq(1).prop('selected', true);
            }
        );
    }
    
    function clearForm()
    {
        $("select").each(function (key, input) {
            $(input).val('');
        });
        $("input[type='text']").val('');
        
        handleBehaviorFilter();
    }
    
    return {
        init : init()
    };
})();

pagamento_detalhado.init;