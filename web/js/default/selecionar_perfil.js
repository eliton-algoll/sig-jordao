(function($){
    var SelecionarPerfil = {
        init: function() {
            this.events();
        },
        events: function() {
            $('#selecionar_perfil_coPessoaPerfil').change(this.handleChangePerfil);
            $(document).on('click', '.btn-entrar', this.handleClickEntrar);
        },
        handleClickEntrar: function(){
            var $form = $('form[name="selecionar_perfil"]');
            var projeto = $(this).attr('data-projeto');
            $form.append(
                $('<input>').attr('type', 'hidden').attr('name', 'selecionar_perfil[coProjeto]').val(projeto)
            ).submit();
        },
        handleChangePerfil: function(){
            $('#container-selecao-projeto').empty();
            if (this.value) {
                if ($(this).children('option:selected').attr('data-admin') == 1) {
                    $('form[name="selecionar_perfil"]').submit();
                    return;
                }
                $.get(
                    Routing.generate('default_get_projetos_autorizados', {pessoaPerfil: this.value}),
                    {},
                    function (data) {
                        if (data.status) {
                            SelecionarPerfil.renderProjetosAutorizados(data.result);
                        }
                    },
                    'json'
                )
            }
        },
        renderProjetosAutorizados: function(projetos){
            $('#container-selecao-projeto').append(
                $('<p>').addClass('lead').html('Projetos')
            );
            $(projetos).each(function(){
                $('#container-selecao-projeto').append(
                    $('<div>').addClass('col-md-3').append(
                        $('<div>').addClass('thumbnail').append(
                            $('<img>').addClass('media-object').attr('src', assetPath + 'images/pet-saude-icon.png')
                        ).append(
                            $('<div>').addClass('caption').append(
                                $('<h4>').html('Nº SEI ' + this.nuSipar)
                            ).append(
                                $('<p>').append($('<strong>').html(this.dsPrograma))
                            ).append(
                                $('<p>').html(this.dsPublicacao)
                            ).append(
                                $('<p>').append(
                                    $('<a>')
                                        .attr('href', 'javascript:;')
                                        .attr('data-projeto', this.coSeqProjeto)
                                        .addClass('btn btn-primary btn-entrar')
                                        .html('Entrar')
                                )
                            )
                        )
                    )
                );
            });
        }
    };
    $(document).ready(function(){
       SelecionarPerfil.init(); 
    });
})(jQuery);