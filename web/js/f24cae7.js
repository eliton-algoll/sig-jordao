(function($){
    var CadastrarSecretaria = {
        init: function(){
            this.events();
        },
        events: function(){
            $('#pesquisar_secretaria_uf').change(this.handleChangeUf);
            $('#pesquisar_secretaria_municipio').change(this.handleChangeMunicipio);
            $('#btn-incluir-secretaria').click(this.handleClickIncluirSecretaria);
            $('#pesquisar_secretaria_nuCnpj').blur(this.handleBlurCnpj);
            $(document).on('click', '.btn-excluir-secretaria', this.handleClickExcluirSecretaria);
        },
        handleBlurCnpj: function(){
            if ($(this).val().length == 18) {
                var cnpj = this.value.replace(/\D/g,'');
                $.get(
                    Routing.generate('projeto_get_secretaria_by_cnpj', {cnpj: cnpj}),
                    {},
                    function(data){
                        if (!data.status) {
                            bootbox.alert(data.message);
                            $('#pesquisar_secretaria_nuCnpj').val('');
                            return;
                        }
                        if (Object.keys(data.result).length > 0) {
                            CadastrarSecretaria.disablePesquisaEndereco();
                            $('#pesquisar_secretaria_uf').empty().append(
                                $('<option>').val(data.result.sgUf).html(data.result.sgUf)
                            );

                            $('#pesquisar_secretaria_municipio').empty().append(
                                $('<option>').val(data.result.nuCnpj).html(data.result.noMunicipio)
                            );

                            $('#pesquisar_secretaria_secretaria').empty().append(
                                $('<option>').val(data.result.nuCnpj).html(data.result.noPessoa)
                            );

                        } else {
                            CadastrarSecretaria.enablePesquisaEndereco();
                            $('#pesquisar_secretaria_secretaria').empty();
                        }
                    },
                    'json'
                );
            } else {
                CadastrarSecretaria.enablePesquisaEndereco();
                $('#pesquisar_secretaria_secretaria').empty();
            }
        },
        disablePesquisaEndereco: function(){
            $('#pesquisar_secretaria_uf').val('').prop('disabled', true);
            $('#pesquisar_secretaria_municipio').empty().prop('disabled', true);            
        },
        enablePesquisaEndereco: function(){
            $('#pesquisar_secretaria_uf').prop('disabled', false);
            $('#pesquisar_secretaria_municipio').prop('disabled', false);            
        },
        resetCampusForm: function(){
            $('#pesquisar_secretaria_nuCnpj').val('');
            $('#pesquisar_secretaria_uf').val('');
            $('#pesquisar_secretaria_municipio,#pesquisar_secretaria_secretaria').empty();
            CadastrarSecretaria.enablePesquisaEndereco();
        },        
        handleChangeUf: function(){
            $('#pesquisar_secretaria_nuCnpj').val('');
            $('#pesquisar_secretaria_municipio,#pesquisar_secretaria_secretaria').empty();
            helper.makeOptions(
                $('#pesquisar_secretaria_municipio'),
                Routing.generate('municipio_get_by_uf', {uf: this.value}),
                {},
                'coMunicipioIbge',
                'noMunicipio',
                true
            );
        },
        handleChangeMunicipio: function(){
            $('#pesquisar_secretaria_secretaria').empty();
            helper.makeOptions(
                $('#pesquisar_secretaria_secretaria'),
                Routing.generate('projeto_get_secretarias_by_municipio', {municipio: this.value}),
                {},
                'nuCnpj',
                'noPessoa',
                true
            );
        },
        checkIncluirSecretaria: function(){
            var $secretaria = $('#pesquisar_secretaria_secretaria');
            if ($secretaria.val() == "" || $secretaria.val() == null) {
                bootbox.alert('Nenhuma secretaria selecionada.');
                return false;
            }
            if ($('input[type="hidden"][value="' + $secretaria.val() + '"]').length > 0) {
                bootbox.alert('Essa secretaria já foi incluída.');
                return false;
            }
            return true;
        },
        handleClickIncluirSecretaria: function(){
            if (!CadastrarSecretaria.checkIncluirSecretaria()) {
                return;
            }            
            var tr = $('<tr>').append(
                $('<input>')
                    .attr('type', 'hidden')
                    .attr('name', 'cadastrar_projeto[secretarias][]')
                    .val($('#pesquisar_secretaria_secretaria').val())
            ).append(
                $('<td>').append($('#pesquisar_secretaria_uf > option:selected').html())
            ).append(
                $('<td>').append($('#pesquisar_secretaria_municipio > option:selected').html())
            ).append(
                $('<td>').append($('#pesquisar_secretaria_secretaria > option:selected').html())
            ).append(
                $('<td>').append(
                    $('<a>')
                        .attr('href', 'javascript:;')
                        .addClass('btn-excluir-secretaria')
                        .append($('<span>').addClass('glyphicon glyphicon-remove').attr('title', 'Remover'))
                )
            );
            $('#table-secretarias > tbody').append(tr);
            $('#dados-secretaria').find('.secretaria-placeholder').remove();
            CadastrarSecretaria.resetCampusForm();
        },
        handleClickExcluirSecretaria: function(){
            $(this).parents('tr').remove();
        }
    };
    
    $(document).ready(function(){
        CadastrarSecretaria.init();
    })
})(jQuery);