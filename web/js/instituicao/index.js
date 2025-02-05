var instituicao_index = {

    init: function () {
        this.events();
    },

    events: function () {
        $('.btn-activate-inst').click(instituicao_index.handleActivate);
    },

    handleActivate: function () {
        var id = $(this).attr('data-id');
        var ativo = $(this).attr('data-ativo');
        var nome = $(this).attr('data-nome');
        var codigo = $(this).attr('data-codigo');

        if (id) {
            bootbox.confirm({
                message: 'Tem certeza que deseja ' + (ativo === 'S' ? 'desativar' : 'ativar') + ' a instituição ' + nome + ' de CNPJ ' + codigo + '?',
                buttons: {
                    confirm: {
                        label: 'Sim'
                    },
                    cancel: {
                        label: 'Não'
                    }
                },
                callback: function (result) {
                    if (result) {
                        window.location.href = Routing.generate('instituicao_activate', {id: id});
                    }
                }
            });
        }
    },
};

instituicao_index.init();