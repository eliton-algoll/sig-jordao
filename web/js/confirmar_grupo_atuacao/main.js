(function () {

    function init() {
        events();
    }

    function events() {
        $('.seletor-grupo').on('change', handleChangeGrupo);
        $(document).on('click', '#btn-confimar-grupo-tutorial', handleSubmit);
    }

    function handleChangeGrupo() {
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
        var payload = [];

        // Obtém os valores dos seletores selecionados
        for (var i = 0; i < seletores.length; i++) {
            var value = $('option:selected', seletores[i]).val();

            if (!((!value) || (value <= 0))) {
                var grupo = {
                    id: parseInt(value, 10),
                    temasAbordados: []
                };

                // Obtém os temas abordados do grupo selecionado
                // var grid = $('#' + $(seletores[i]).data('grid'));
                // $('.temas-abordados input[type="checkbox"]:checked', grid).each(function () {
                //     grupo.temasAbordados.push(parseInt($(this).val(), 10));
                // });

                payload.push(grupo);
            }
        }

        if (payload.length == 0) {
            bootbox.alert('Um Grupo Tutorial precisa ser selecionado.');
            return;
        }

        var gruposConfirmados = parseInt($('#grupos-ativos-e-confirmados').val(), 10);

        if ((payload.length < 2) && (gruposConfirmados == 0)) {
            bootbox.alert('Este Projeto possui nenhum grupo confirmado. Não é possível confirmar este grupo de maneira avulsa.');
            return;
        }

        if ((payload.length < 2) && (gruposConfirmados == 3)) {
            bootbox.alert('Este Projeto possui 3 grupos confirmados. Não é possível confirmar este grupo de maneira avulsa.');
            return;
        }

        // for (var i = 0; i < payload.length; i++) {
        //     if (payload[i].temasAbordados.length == 0) {
        //         bootbox.alert('O Grupo Tutorial precisa ter ao menos um Tema abordado selecionado.');
        //         return;
        //     }
        // }

        $.post(Routing.generate('confirmar_grupo_tutorial_confirmar', {}), {
            payload: payload
        }, function (data) {
            // console.log(data);

            if (data.status) {
                bootbox.alert(data.message, function () {
                    if (data.status) {
                        location.reload(true);
                    }
                });
            } else if (data.errors) {
                var message = '<ul>';

                for (var i = 0; i < data.errors.length; i++) {
                    message += '<li>' + data.errors[i] + '</li>';
                }

                message += '</ul>';

                bootbox.alert(message);
            }
        }, 'json');
    }

    $(document).ready(init);
})();