(function($){
    var InstituicaoCreate = {
        init: function(){
            this.events();
            var uf = $('#cadastrar_instituicao_uf').val();

            if( uf != '' ) {
                var municipioIbge = $('#municipioIbge').text();
                var inst = $('#instituicaoCod').text();

                InstituicaoCreate.populateMunicipio(uf, municipioIbge);
                InstituicaoCreate.populateInstituicao(municipioIbge, inst);
            }
        },

        events: function(){
            $('#cadastrar_instituicao_uf').change(this.handleChangeUf);
            $('#cadastrar_instituicao_municipio').change(this.handleChangeMunicipio);
        },
        populateMunicipio: function (value, codMunicipioIbge) {
            let ibge = '';
            if( codMunicipioIbge && codMunicipioIbge != undefined ) {
                ibge = codMunicipioIbge;
            }
            helper.makeOptions(
                $('#cadastrar_instituicao_municipio'),
                Routing.generate('municipio_get_by_uf', {uf: value}),
                {},
                'coMunicipioIbge',
                'noMunicipio',
                true,
                ibge
            );
        },
        populateInstituicao: function (value, codInstituicao) {
            let cod = '';
            if( codInstituicao && codInstituicao != undefined ) {
                cod = codInstituicao;
            }

            helper.makeOptions(
                $('#cadastrar_instituicao_instituicao'),
                Routing.generate('projeto_get_instituicoes_by_municipio', {municipio: value}),
                {},
                'coSeqInstituicao',
                'noInstituicaoProjeto',
                true,
                cod
            );
        },
        handleChangeUf: function(){
            $('#cadastrar_instituicao_municipio,#cadastrar_instituicao_municipio,#cadastrar_instituicao_instituicao').empty();
            if( this.value != '' ) {
                InstituicaoCreate.populateMunicipio(this.value);
            }
        },
        handleChangeMunicipio: function(){
            $('#cadastrar_instituicao_instituicao').empty();
            if( this.value != '' ) {
                InstituicaoCreate.populateInstituicao(this.value);
            }
        },
    };

    $(document).ready(function(){
        InstituicaoCreate.init();
    })
})(jQuery);