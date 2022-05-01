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
        var continuar = true;
        var seletores = $('.seletor-grupo');
        var payload = [];

        // Verifica se os seletores foram selecionados
        for (var i = 0; i < seletores.length; i++) {
            var value = $(seletores[i], 'option:selected').val();

            if ((!value) || (value <= 0)) {
                continuar = false;
                break;
            } else {
                payload.push({
                    id: parseInt(value, 10),
                    temasAbordados: []
                });
            }
        }

        if (!continuar) {
            bootbox.alert('O Grupo Tutorial precisa ser selecionado.');
            return;
        }

        // Verifica se ao menos uma marcação de tema abordado foi realizada
        for (var j = 0; j < seletores.length; j++) {
            var grid = $('#' + $(seletores[j]).data('grid'));

            var selected = [];
            $('.temas-abordados input[type="checkbox"]:checked', grid).each(function () {
                selected.push(parseInt($(this).val(), 10));
            });

            if (selected.length == 0) {
                continuar = false;
                break;
            } else {
                payload[j].temasAbordados = selected;
            }
        }

        if (!continuar) {
            bootbox.alert('O Grupo Tutorial precisa ter ao menos um Tema abordado selecionado.');
            return;
        }

        $.post(Routing.generate('confirmar_grupo_tutorial_confirmar', {}), {
            payload: payload
        }, function (data) {
            console.log(data);

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