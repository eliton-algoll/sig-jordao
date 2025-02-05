var planejamento_abertura_folha_pgto_index = {
  
    init : function() {
        this.events();
    },
    
    events : function() {
        $(document).on('click', '.btn-detail', planejamento_abertura_folha_pgto_index.handleDetailPlanejamento);
        $(document).on('click', '.btn-remove-planejamento', planejamento_abertura_folha_pgto_index.handleDeletePlanejamento);
    },
    
    handleDetailPlanejamento : function() {
        
        var id = $(this).attr('id-planejamento');
        
        if (!id) {
            return;
        }
        
        $.ajax({
            url : Routing.generate('planejamento_abertura_folha_detail', { id : id }),
            type : 'GET',
            dataType : 'html',
            success : function(response) {
                bootbox.dialog({
                    title : 'Planejamento anual de Abertura de Folha de Pagamento',
                    message : response,
                    buttons : {
                        close : {
                            label : 'Fechar'
                        }
                    }
                });
            }
        });
    },
    
    handleDeletePlanejamento : function() {
        
        var id = $(this).attr('id-planejamento');        
        
        if (id) {
            bootbox.confirm('Tem certeza que deseja excluir?', function(result){
                if (result) {
                    window.location.href = Routing.generate('planejamento_abertura_folha_delete', { id : id });
                }
            });
        }
        
    }
    
};

planejamento_abertura_folha_pgto_index.init();