(function($){
    var Pagamento = {
        init: function(){
            this.events();
        },
        events: function(){
            $('[id$=ufSecretaria]').change(this.handleChangeUfSecretaria);
            $('[id$=municipioSecretaria]').change(this.handleChangeMunicipioSecretaria);
            $('[id$=ufCampus]').change(this.handleChangeUfCampus);
            $('[id$=municipioCampus]').change(this.handleChangeMunicipioCampus);
            $('[id$=instituicao]').change(this.handleChangeInstituicaoCampus);
        },       
        handleChangeUfSecretaria: function(){
            $('[id$=municipioSecretaria],[id$=secretaria]').empty();
            if($('[id$=ufSecretaria]').val() != ''){
                helper.makeOptions(
                    $('[id$=municipioSecretaria]'),
                    Routing.generate('municipio_get_by_uf', {uf: this.value}),
                    {},
                    'coMunicipioIbge',
                    'noMunicipio',
                    true
                );
            }
        },
        handleChangeMunicipioSecretaria: function(){
            $('[id$=secretaria]').empty();
            if($('[id$=municipioSecretaria]').val() != ''){
                helper.makeOptions(
                    $('[id$=secretaria]'),
                    Routing.generate('projeto_get_secretarias_by_municipio', {municipio: this.value}),
                    {},
                    'nuCnpj',
                    'noPessoa',
                    true
                );
            }
        },
        handleChangeUfCampus: function(){

            $('[id$=municipioCampus],[id$=instituicao],[id$=campus]').empty();

            if($('[id$=ufCampus]').val() != ''){
                helper.makeOptions(
                    $('[id$=municipioCampus]'),
                    Routing.generate('municipio_get_by_uf', {uf: this.value}),
                    {},
                    'coMunicipioIbge',
                    'noMunicipio',
                    true
                );
            }
        },
        handleChangeMunicipioCampus: function(){
            $('[id$=instituicao],[id$=campus]').empty();
            if($('[id$=municipioCampus]').val() != ''){
                helper.makeOptions(
                    $('[id$=instituicao'),
                    Routing.generate('projeto_get_instituicoes_by_municipio', {municipio: this.value}),
                    {},
                    'coSeqInstituicao',
                    'noInstituicaoProjeto',
                    true
                );
            }
        },
        handleChangeInstituicaoCampus: function(){
            $('[id$=campus]').empty();
            if($('[id$=instituicao').val() != '') {
                helper.makeOptions(
                    $('[id$=campus]'),
                    Routing.generate('projeto_get_campus_by_instituicao', {instituicao: this.value}),
                    {},
                    'coSeqCampusInstituicao',
                    'noCampus',
                    true
                );
            }
        }
    };
    
    $(document).ready(function(){
        Pagamento.init();
    });
})(jQuery);