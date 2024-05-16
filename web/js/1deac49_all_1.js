(function ($) {
    var all = {
        construct: function () {
            this.events();
        },
        events: function () {
            $("#btnExport").click(function () {
                $("#tblExport").battatech_excelexport({
                    containerid: "tblExport"
                    , datatype: 'table'
                });
            });
            $('.inativar-participante').click(this.handleClickInativarParticipante);
        },
        handleClickInativarParticipante: function () {
            var that = this;
            var participante = $(that).attr('data-participante');
            var eixo = $(that).attr('data-eixo');
            var perfil = parseInt($(that).attr('data-perfil'), 10);

            bootbox.confirm('Deseja realmente remover esse participante?', function (result) {
                if (!result) return;

                if ((perfil === 4)) { // Preceptor
                    bootbox.confirm('Ao remover este Preceptor todos os Estudantes vinculados ao mesmo Curso de Graduação também serão removidos. Deseja realmente remover esse participante?', function (result1) {
                        if (!result1) return;

                        all.realizaInativacao(participante);
                    });
                } else {
                    all.realizaInativacao(participante);
                }
            });
        },
        realizaInativacao: function (participante) {
            $.post(Routing.generate('participante_inativar', {
                projetoPessoa: participante
            }), {}, function (data) {
                bootbox.alert(data.message, function () {
                    if (data.status) {
                        location.reload(true);
                    }
                });
            }, 'json');
        }
    };

    all.construct();
})(jQuery);
