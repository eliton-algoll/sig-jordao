(function() {

    function init()
    {
        events();
    }

    function events()
    {
        $('#grupos').on('change', handleChangeGrupo);
        $(document).on('click', '#btn-confimar-grupo-tutorial', handleSubmit);
    }

    function handleChangeGrupo()
    {
        var id = $(this).val();

        if (id) {
            loadGrupo(id);
        } else {
            $('#grid-grupo-tutorial').html('');
        }
    }

    function loadGrupo(idGrupo)
    {
        $.ajax({
            url : Routing.generate(
                'confirmar_grupo_tutorial_grid',
                { grupoAtuacao : idGrupo }
            ),
            success : function(response) {
                $('#grid-grupo-tutorial').html(response);
            }
        });
    }

    function handleSubmit()
    {
        var idGrupo = $('#grupos option:selected').val();

        $.ajax({
            url : Routing.generate(
                'confirmar_grupo_tutorial_validade',
                { grupoAtuacao : idGrupo }
            ),
            success : function(response) {
                if (response.status) {
                    $('.loader').removeClass('hidden');
                    location.href = Routing.generate('confirmar_grupo_tutorial_confirmar', { grupoAtuacao : idGrupo });
                } else {
                    bootbox.alert({
                        title : 'Não foi possível confirmar grupo tutorial',
                        message : response.errors.join('</br>')
                    });
                }
            }
        })
    }

    $(document).ready(init);
})();