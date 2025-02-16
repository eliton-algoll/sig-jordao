var banco_index = {

    init: function () {
        this.events();
    },

    events: function () {
        $('.btn-activate-banco').click(banco_index.handleActivateBanco);
        $('.btn-delete-banco').click(banco_index.handleDeleteBanco);
    },

    handleActivateBanco: function () {
        var id = $(this).attr('data-id');
        var ativo = $(this).attr('data-ativo');
        var nome = $(this).attr('data-nome');
        var codigo = $(this).attr('data-codigo');

        if (id) {
            bootbox.confirm({
                message: 'Tem certeza que deseja ' + (ativo === 'S' ? 'desativar' : 'ativar') + ' o banco ' + nome + ' (' + codigo + ')?',
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
                        window.location.href = Routing.generate('banco_activate', {id: id});
                    }
                }
            });
        }
    },

    handleDeleteBanco: function () {
        var id = $(this).attr('data-id');
        var nome = $(this).attr('data-nome');
        var codigo = $(this).attr('data-codigo');

        if (id) {
            bootbox.confirm({
                message: 'Tem certeza que deseja excluir o banco ' + nome + ' (' + codigo + ')?',
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
                        window.location.href = Routing.generate('banco_delete', {id: id});
                    }
                }
            });
        }
    }
};

banco_index.init();