(function($){
    var FolhaPagamento = {
        init: function(){
            this.events();
        },
        events: function(){
            $('.fechar-folha').click(this.handleClickFecharFolha);
            $('.enviar-folha-para-fundo').click(this.handleClickEnviarParaFundo);
            $('.informar-ordem-bancaria').click(this.handleClickInformarOrdemBancaria);
            $('.qtd-projetos').click(this.handleClickQtdProjetos);
            $('.historico-tramitacao-status').click(this.handleClickHistoricoTramitacaoStatus);
            $('.btn-cancelar-folha-suplementar').click(this.handleClickCancelarFolha);
        },
        handleClickQtdProjetos: function() {
            var that = this;
            $.post(
                Routing.generate('folha_pagamento_projetos_vinculados', {folha: $(that).attr('data-folha')}),
                {},
                function(data) {
                    bootbox.dialog({
                        size: 'large',
                        title: 'Projeto(s)',
                        message: data,
                    });
                    helper.ajustTableWithScroll();
                }
            );
        },
        handleClickFecharFolha: function(){
            var that = this;
            bootbox.confirm('Deseja realmente fechar essa folha de pagamento?', function(result){
                if (result) {
                    $.post(
                        Routing.generate('folha_pagamento_fechar', {folha: $(that).attr('data-folha')}),
                        {},
                        function(data){
                            bootbox.alert(data.message, function(){
                                if (data.status) {
                                    location.reload(true);
                                }
                            });
                        },
                        'json'
                    );
                }
            });
        },
        handleClickEnviarParaFundo: function(){
            var that = this;
            bootbox.prompt({ 
                className: 'bootbox-enviar-para-fundo',
                title: 'Para enviar essa folha de pagamento para o Fundo Nacional de Saúde, informe o número do SEI abaixo.',
                size: 'big',
                callback: function(nuSipar){
                    if (nuSipar) {
                        $.post(
                            Routing.generate('folha_pagamento_enviar_para_fundo', {folha: $(that).attr('data-folha'), nuSipar: nuSipar}),
                            {},
                            function(data){
                                bootbox.alert(data.message, function(){
                                    if (data.status) {
                                        location.reload(true);
                                    }
                                });
                            },
                            'json'
                        );
                    }
                }
            });
            FolhaPagamento.applyMask();
        },
        handleClickInformarOrdemBancaria: function(){
            var that = this;
            bootbox.prompt({ 
                className: 'bootbox-informar-ordem-bancaria',
                title: 'Informe o número da ordem bancária abaixo.',
                size: 'small',
                callback: function(nuOrdemBancaria){
                    if (nuOrdemBancaria) {
                        $.post(
                            Routing.generate('folha_pagamento_informar_ordem_bancaria', {folha: $(that).attr('data-folha'), nuOrdemBancaria: nuOrdemBancaria}),
                            {},
                            function(data){
                                bootbox.alert(data.message, function(){
                                    if (data.status) {
                                        location.reload(true);
                                    }
                                });
                            },
                            'json'
                        );
                    }
                }
            });
            FolhaPagamento.applyUpperCase();
        },
        applyMask: function() {
            $('.bootbox-enviar-para-fundo .modal-body input').addClass('nuSipar');
        },
        applyUpperCase: function() {
            $('.bootbox-informar-ordem-bancaria .modal-body input').css('text-transform', 'uppercase');
        },
        handleClickHistoricoTramitacaoStatus: function() {
            var that = this;
            $.post(
                Routing.generate('folha_pagamento_historico_tramitacao_status', {folha: $(that).attr('data-folha')}),
                {},
                function(data) {
                    bootbox.dialog({
                        size: 'large',
                        title: 'Histórico',
                        message: data,
                    });
                }
            );
        },
        handleClickCancelarFolha : function () {
            
            var id = $(this).attr('folha-pagamento');
            
            bootbox.confirm({
                title : 'Confirma o cancelamento da folha suplementar?',
                message : '<div class="form-group"><label>Justificativa</label><textarea id="dsJustificativaCancelamento" class="form-control" maxlength="4000"></textarea><span class="help-block hidden">Esse valor não deve ser vazio</span></div>',
                buttons : {
                    confirm : {
                        label : 'Confirmar'
                    },
                    cancel : {
                        label : 'Voltar'
                    }
                },
                callback : function (result) {
                    
                    var justificativa = $("#dsJustificativaCancelamento");
                    
                    if (result && justificativa.val().trim() !== '') {
                        window.location.href = Routing.generate('folha_pgto_suplementar_cancel', { folhaPagamento : id }) + '?dsJustificativa='+justificativa.val();
                    } else if(result && justificativa.val().trim() === '') {
                        justificativa.closest('div').addClass('has-error'); 
                        justificativa.closest('div').find('span').eq(0).removeClass('hidden');
                        return false;
                    }
                }
            });
            
        }
    };
    $(document).ready(function(){
        FolhaPagamento.init();
    });
})(jQuery);
