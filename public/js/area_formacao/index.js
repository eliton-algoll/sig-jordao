var area_formacao = {

    init: function () {
        this.events();
    },

    events: function () {
        $('.btn-activate-area').click(area_formacao.handleActivate);
    },

    handleActivate: function () {
        var id = $(this).attr('data-id');
        var ativo = $(this).attr('data-ativo');
        var nome = $(this).attr('data-nome');
        var codigo = $(this).attr('data-codigo');

        if (id) {
            bootbox.confirm({
                message: 'Tem certeza que deseja ' + (ativo === 'S' ? 'desativar' : 'ativar') + ' o Curso de Formação ' + nome + '?',
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
                        window.location.href = Routing.generate('curso_formacao_activate', {id: id});
                    }
                }
            });
        }
    },
};

area_formacao.init();