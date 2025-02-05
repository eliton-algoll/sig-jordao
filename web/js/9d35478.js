(function($){
    var CadastrarInstituicao = {
        init: function(){
            this.events();
        },
        events: function(){
            $('#pesquisar_campus_uf').change(this.handleChangeUf);
            $('#pesquisar_campus_municipio').change(this.handleChangeMunicipio);
            $('#pesquisar_campus_instituicao').change(this.handleChangeInstituicao);
            $('#btn-incluir-campus').click(this.handleClickIncluirCampus);
            $(document).on('click', '.btn-excluir-campus', this.handleClickExcluirCampus);
        },
        handleChangeUf: function(){
            $('#pesquisar_campus_municipio,#pesquisar_campus_instituicao,#pesquisar_campus_campus').empty();
            helper.makeOptions(
                $('#pesquisar_campus_municipio'),
                Routing.generate('municipio_get_by_uf', {uf: this.value}),
                {},
                'coMunicipioIbge',
                'noMunicipio',
                true
            );
        },
        handleChangeMunicipio: function(){
            $('#pesquisar_campus_instituicao,#pesquisar_campus_campus').empty();
            helper.makeOptions(
                $('#pesquisar_campus_instituicao'),
                Routing.generate('projeto_get_instituicoes_by_municipio', {municipio: this.value}),
                {},
                'coSeqInstituicao',
                'noInstituicaoProjeto',
                true
            );
        },
        handleChangeInstituicao: function(){
            $('#pesquisar_campus_campus').empty();
            helper.makeOptions(
                $('#pesquisar_campus_campus'),
                Routing.generate('projeto_get_campus_by_instituicao', {instituicao: this.value}),
                {},
                'coSeqCampusInstituicao',
                'noCampus',
                true
            );
        },
        handleClickExcluirCampus: function(){
            $(this).parents('tr').remove();
        },
        resetCampusForm: function(){
            $('#pesquisar_campus_uf').val('');
            $('#pesquisar_campus_municipio,#pesquisar_campus_instituicao,#pesquisar_campus_campus').empty();
        },
        checkIncluirCampus: function(){
            var $campus = $('#pesquisar_campus_campus');
            if ($campus.val() == "" || $campus.val() == null) {
                bootbox.alert('Nenhum campus selecionado.');
                return false;
            }
            if ($('input[type="hidden"][value="' + $campus.val() + '"]').length > 0) {
                bootbox.alert('Esse campus já foi incluído.');
                return false;
            }
            return true;
        },
        handleClickIncluirCampus: function(){
            if (!CadastrarInstituicao.checkIncluirCampus()) {
                return;
            }
            
            var instituicaoId = $('#pesquisar_campus_instituicao').val();
            
            var tr = $('<tr>').attr('data-instituicao', instituicaoId).append(
                $('<input>').attr('type', 'hidden').attr('name', 'cadastrar_projeto[campus][]').val($('#pesquisar_campus_campus').val())
            ).append(
                $('<td>').append($('#pesquisar_campus_campus > option:selected').html())
            ).append(
                $('<td>').append($('#pesquisar_campus_instituicao > option:selected').html())
            ).append(
                $('<td>').append($('#pesquisar_campus_municipio > option:selected').html())
            ).append(
                $('<td>').append($('#pesquisar_campus_uf > option:selected').html())
            ).append(
                $('<td>').append(
                    $('<a>').attr('href', 'javascript:;').addClass('btn-excluir-campus').append(
                        $('<span>').addClass('glyphicon glyphicon-remove').attr('title', 'Remover')
                    )
                )
            );
    
            $('#table-campus > tbody').append(tr);
            
            $('#dados-instituicao').find('.campus-placeholder').remove();
            
            CadastrarInstituicao.resetCampusForm();
        }
    };
    
    $(document).ready(function(){
        CadastrarInstituicao.init();
    })
})(jQuery);