var banco_index = {

    init: function () {
        this.events();
    },

    events: function () {
        $('.btn-activate-adm').click(banco_index.handleActivateBanco);
    },

    handleActivateBanco: function () {
        var id = $(this).attr('data-id');
        var ativo = $(this).attr('data-ativo');
        var nome = $(this).attr('data-nome');
        var codigo = $(this).attr('data-codigo');

        if (id) {
            bootbox.confirm({
                message: 'Tem certeza que deseja ' + (ativo === 'S' ? 'desativar' : 'ativar') + ' o Administrador ' + nome + ' (' + codigo + ')?',
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
                        window.location.href = Routing.generate('adm_activate', {id: id});
                    }
                }
            });
        }
    },
};

banco_index.init();