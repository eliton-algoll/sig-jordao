(function () {

    function init() {
        events();
    }

    function events() {
        $('.seletor-grupo').on('change', handleChangeGrupo);
        $(document).on('click', '#btn-confimar-grupo-tutorial', handleSubmit);
    }

    function handleChangeGrupo() {
        $('#btn-confimar-grupo-tutorial').attr('disabled', false);
        var id = $(this).val();
        var grid = $('#' + $(this).data('grid'));
        // console.log($(this), $('#' + $(this).data('grid')));

        if (id) {
            $.ajax({
                url: Routing.generate('confirmar_grupo_tutorial_grid', {
                    grupoAtuacao: id
                }),
                success: function (response) {
                    grid.html(response);
                }
            });
        } else {
            grid.html('');
        }
    }

    function handleSubmit() {
        var seletores = $('.seletor-grupo');
        var gruposConfirmados = parseInt($('#grupos-ativos-e-confirmados').val(), 10);

        $.ajax({
            url: Routing.generate('confirmar_grupo_tutorial_confirmar', {}),
            success: function (data) {

                if (data.status) {
                    bootbox.alert(data.message, function () {
                        if (data.status) {
                            location.reload(true);
                        }
                    });
                } else if (data.errors) {
                    var message = '<p>Inconsistências encontradas para confirmação:</p><hr>';
                    for (var i = 0; i < data.errors.length; i++) {
                        message += '<ul>';
                        for (var e = 0; e < data.errors[i].length; e++) {
                            message += '<li>' + data.errors[i][e].msg + '</li>';
                        }
                        message += '</ul>';
                    }
                    console.log('message', message);

                    bootbox.alert(message);
                }
            }
        });
    }

    $(document).ready(init);
})();