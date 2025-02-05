(function($){
    var InstituicaoCreate = {
        init: function(){
            this.events();
            var uf = $('#cadastrar_instituicao_uf').val();
            if( uf != '' ) {
                var municipioIbge = $('#municipioIbge').text();
                InstituicaoCreate.populateMunicipio(uf, municipioIbge);
            }
        },
        events: function(){
            $('#cadastrar_instituicao_uf').change(this.handleChangeUf);
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
        handleChangeUf: function(){
            $('#cadastrar_instituicao_municipio').empty();
            if( this.value != '' ) {
                InstituicaoCreate.populateMunicipio(this.value);
            }
        },
    };

    $(document).ready(function(){
        InstituicaoCreate.init();
    })
})(jQuery);