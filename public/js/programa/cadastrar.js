(function($){

    function init()
    {
        events();
        var select;
        var selectArea;
        select = document.getElementById("cadastrar_programa_tpPrograma");
        selectArea = document.getElementById("cadastrar_programa_tpAreaTematica");
        if( select != null ) {
            for (var i = 0; i < select.options.length; i++) {
                if (select.options[i].value === '1') {
                    select.remove(i);
                    break;
                }
            }
        }

        if( selectArea != null ) {
            for (var i = 0; i < selectArea.options.length; i++) {
                if (selectArea.options[i].value === '2') {
                    selectArea.remove(i);
                    break;
                }
            }
        }
    }

    function events()
    {
        $("form").submit(function(e) {
            submit(e);
        });
    }

    function submit(e)
    {
        if (typeof  e.originalEvent !== 'undefined') {
            e.preventDefault();

            bootbox.confirm(
                'Após salvar não será permitido alterar o tipo do programa. Deseja realmente salvar?',
                function (result) {
                    console.log(result);
                    if (result) {
                        $("form").submit();
                    }
                }
            );
        }
    }

    $(document).ready(init);
})(jQuery);