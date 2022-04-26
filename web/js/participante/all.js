(function ($) {
    var all = {
        construct: function () {
            this.events();
        },
        events: function () {
            $('.inativar-participante').click(this.handleClickInativarParticipante);
        },
        handleClickInativarParticipante: function () {
            var that = this;
            bootbox.confirm('Deseja realmente remover esse participante?', function (result) {
                if (result) {
                    $.post(
                        Routing.generate('participante_inativar', {projetoPessoa: $(that).attr('data-participante')}),
                        {},
                        function (data) {
                            bootbox.alert(data.message, function () {
                                if (data.status) {
                                    location.reload(true);
                                }
                            });
                        },
                        'json'
                    );
                }
            });
        }
    };

    all.construct();
})(jQuery);

