(function($){

    function init()
    {
        events();
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