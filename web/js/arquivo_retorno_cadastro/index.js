var arquivo_retorno_cadastro_index = $(function(){
    
    function init()
    {
        events();
    }
    
    function events()
    {
        $(document).on('click', '.btn-remove-retorno', handleRemoveRetornoCadastro);
    }
    
    function handleRemoveRetornoCadastro()
    {
        var id = $(this).attr('data-id');
        
        bootbox.confirm({
            message : 'Tem certeza que deseja excluir?',
            buttons : {
                confirm : {
                    label : 'Sim',
                    class : 'btn-primary'
                },
                cancel : {
                    label : 'Não',
                    class : 'btn-default'
                }
            },
            callback : function (result) {
                if (true === result) {
                    document.location.href = Routing.generate('arquivo_retorno_cadastro_delete', { retornoCriacaoConta : id })
                }
            }
        });
    }
    
    return {
        init : init()
    };
})();

arquivo_retorno_cadastro_index.init;