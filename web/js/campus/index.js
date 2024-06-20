var campus_index = {

    init: function () {
        this.events();
    },

    events: function () {
        $('.btn-activate-campus').click(campus_index.handleActivate);
    },

    handleActivate: function () {
        var id = $(this).attr('data-id');
        var ativo = $(this).attr('data-ativo');
        var nome = $(this).attr('data-nome');
        var codigo = $(this).attr('data-codigo');

        if (id) {
            bootbox.confirm({
                message: 'Tem certeza que deseja ' + (ativo === 'S' ? 'desativar' : 'ativar') + ' o Campus ' + nome + '?',
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
                        window.location.href = Routing.generate('campus_activate', {id: id});
                    }
                }
            });
        }
    },
};

campus_index.init();