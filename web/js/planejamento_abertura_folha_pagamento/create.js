var planejamento_abertura_folha_pgto_create = {
  
    init : function() {
        this.events();        
    },
    
    events : function() {
        $("#coPublicacao").change(planejamento_abertura_folha_pgto_create.handleLoadAnoReferencia);
        $("#btn-copy-planejamento").click(planejamento_abertura_folha_pgto_create.handleCopyPlanejamento);        
    },
    
    handleLoadAnoReferencia : function() {
        
        var publicacao = $(this).val();
        
        if (!publicacao) {
            return;
        }
        
        $.ajax({
            url : Routing.generate('planejamento_abertura_folha_get_planejamento', { id : publicacao }),
            method : 'GET',
            success : function(response) {
                if (response.status) {
                    $("#nuAnoReferencia").val(response.nuAnoReferencia);
                    
                    for (i = 1; i <= 12; i ++) {
                        if (response.dtAtual.ano == response.nuAnoReferencia && response.dtAtual.mes > i) {
                            $("input[name='mesesReferencia["+ i +"]']").prop('readonly', true).val('');
                        }
                    }
                } else {
                    $("#nuAnoReferencia").val('');
                }
            }
        });
    },
    
    handleCopyPlanejamento : function() {
        
        var publicacao = $("#coPublicacao").val();
        
        if (!publicacao) {
            bootbox.alert('Selecione um Programa/Publicação para efetuar a cópia.');
            return;
        }
        
        $.ajax({
            url : Routing.generate('planejamento_abertura_folha_get_planejamento', { id : publicacao }),
            method : 'GET',
            success : function(response) {
                if (response.status) {
                    if (typeof response.data != 'undefined') {
                        $.each(response.data, function(key, value) {
                            if (!$("input[name='mesesReferencia["+ parseInt(value.nuMes) +"]']").attr('readonly')) {
                                $("input[name='mesesReferencia["+ parseInt(value.nuMes) +"]']").val(value.nuDiaAbertura);
                            }
                        });
                    } else {
                        bootbox.alert('Não existe planejamento para o ano de referência anterior ao que está sendo cadastrado. Não é possível executar a operação. Para prosseguir, informe os dias de abertura para cada mês do ano em atualização.');
                    }
                } else {
                    bootbox.alert('Ocorreu um erro executar a operação.');
                }
            }
        });
        
    }
    
};

planejamento_abertura_folha_pgto_create.init();