var folha_pagamento_homologar = (function() {
    
    function init()
    {
        $(document).on('click', '.btn-relatorio-mensal', showRelatorioMensal);
    }
    
    function showRelatorioMensal()
    {
        var msg = $(this).attr('data');
        
        bootbox.alert({
            title : 'Relatório Mensal de Atividades',
            message : msg            
        });
    }
    
    return {
        init : init()
    };
})();

folha_pagamento_homologar.init;