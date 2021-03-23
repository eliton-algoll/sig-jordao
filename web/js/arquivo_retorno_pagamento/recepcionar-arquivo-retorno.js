var recepcionar_arquivo_retorno = $(function(){
    
    function init()
    {
        events();
    }
    
    function events()
    {
        $("#recepcionar_arquivo_retorno_pagamento_publicacao").on('change', handleLoadFolhas);
        $("input[name='recepcionar_arquivo_retorno_pagamento[tpFolhaPagamento]']").click(function() {
            $("#recepcionar_arquivo_retorno_pagamento_publicacao").trigger('change');
        });        
    }
    
    function handleLoadFolhas()
    {
        var id = $(this).val();
        var tpFolhaPagamento = $("input[name='recepcionar_arquivo_retorno_pagamento[tpFolhaPagamento]']:checked").val();
        
        if (!id) {
            return;            
        }
        
        helper.makeOptions(
            '#recepcionar_arquivo_retorno_pagamento_folhaPagamento',
            Routing.generate('arquivo_retorno_pagamento_load_folhas', { publicacao : id }),
            {
                tpFolhaPagamento : tpFolhaPagamento,
            },
            'coSeqFolhaPagamento',
            'nuMesAnoSipar',
            true
        );
    }
    
    return {
        init : init()
    };
})();

recepcionar_arquivo_retorno.init;