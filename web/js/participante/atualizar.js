(function ($) {
    var atualizar = {
        init: function () {
            this.fillUnsavedFields();
        },
        fillUnsavedFields: function () {
            var cpf = $('[name$="nuCpf]"]').val().replace(/\D/g, '');

            $.get(
                Routing.generate('pessoa_get_by_cpf', {cpf: cpf}),
                {},
                function (data) {
                    var unsaveFields = ['pessoa', 'noMae', 'sexo'];
                    $.each(data, function (index, obj) {
                        if ($.inArray(index, unsaveFields) >= 0) {
                            if (index == 'pessoa') {
                                $('[name$="noPessoa]"]').val(obj.noPessoa);
                            }
                            // else if (index == 'sexo') {
                            //     $('[name$="sexo]"] option[value="' + obj.coSexo + '"]').attr('selected', 'selected');
                            // }
                            else {
                                $('[name$="' + index + ']"]').val(obj);
                            }
                        }
                    });
                },
                'json'
            );
        },
    };

    $(document).ready(function () {
        atualizar.init();
    });
})(jQuery);