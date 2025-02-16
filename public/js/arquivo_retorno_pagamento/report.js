var arquivo_retorno_pagamento_report = $(function() {
    
    var relatorio_completo = 1;
    
    function init()
    {
        events();
    }
    
    function events()
    {
        $("#filtro_relatorio_retorno_pagamento_tpRelatorio").on('change', handleTipoRelatorio);
        $("#btn-consulta-retorno").on('click', onSubmit);
    }
    
    function handleTipoRelatorio()
    {
        if (relatorio_completo != $(this).val()) {
            $("#filtro_relatorio_retorno_pagamento_stCredito").removeClass('hidden');
            $("#filtro_relatorio_retorno_pagamento_stCredito").parent('div').find('label').eq(0).removeClass('hidden').addClass('required');
        } else {
            $("#filtro_relatorio_retorno_pagamento_stCredito").addClass('hidden').val('');
            $("#filtro_relatorio_retorno_pagamento_stCredito").parent('div').find('label').eq(0).addClass('hidden');
        }
    }
    
    function onSubmit(e)
    {
        $('form').submit();
        
        $.ajax({
            url : Routing.generate('arquivo_retorno_pagamento'),
            statusCode : {
                200 : function () {
                    bootbox.alert('Relatório gerado com sucesso!');
                }
            }
        });        
    }
    
    return {
        init : init()
    };
})();

arquivo_retorno_pagamento_report.init;