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
                    grupoTutorial: parseInt(value, 10),
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
            $('.temas-abordados input[type="checkbox"]:checked', grid).each(function() {
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

        // TODO: Enviar

        // if (seletores.length === 1) {
        //     var idGrupo = $('#grupos option:selected').val();
        //
        //     $.ajax({
        //         url: Routing.generate('confirmar_grupo_tutorial_validade', {
        //             grupoAtuacao: idGrupo
        //         }),
        //         success: function (response) {
        //             if (response.status) {
        //                 $('.loader').removeClass('hidden');
        //                 location.href = Routing.generate('confirmar_grupo_tutorial_confirmar', {
        //                     grupoAtuacao: idGrupo
        //                 });
        //             } else {
        //                 bootbox.alert({
        //                     title: 'Não foi possível confirmar grupo tutorial',
        //                     message: response.errors.join('</br>')
        //                 });
        //             }
        //         }
        //     })
        // } else if (seletores.length == 2) {
        //
        // }
    }

    $(document).ready(init);
})();