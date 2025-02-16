var arquivo_retorno_pagamento_index = $(function(){
    
    function init()
    {
        events();
    }
    
    function events()
    {
        $("#consultar_retonos_pagamento_publicacao").on('change', handleLoadReferencias);
        $(document).on('click', '.btn-remove-retorno', handleRemoveRetornoPagamento);
    }
    
    function handleLoadReferencias()
    {
        var id = $(this).val();        
        
        if (!id) {
            return;            
        }
        
        helper.makeOptions(
            '#consultar_retonos_pagamento_referencia',
            Routing.generate('arquivo_retorno_pagamento_load_referencias', { publicacao : id }),
            {},
            'nuMesAno',
            'nuMesAno',
            true
        );
    }
    
    function handleRemoveRetornoPagamento()
    {
        var id = $(this).attr('data-id');
        
        bootbox.confirm({
            message : 'Tem certeza que deseja excluir?',
            buttons : {
                confirm : {
                    label : 'Sim',
                    className : 'btn-primary'
                },
                cancel : {
                    label : 'Não',
                    className : 'btn-defaul'
                }
            },
            callback : function (result) {
                if (true === result) {
                    document.location.href = Routing.generate('arquivo_retorno_pagamento_delete', { retornoPagamento : id })
                }
            }
        });
    }
    
    return {
        init : init()
    };
})();

arquivo_retorno_pagamento_index.init();