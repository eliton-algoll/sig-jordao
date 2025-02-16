(function($){

    function init() {
        $('.btn-ativar-modelo').click(ativar);
    }

    function ativar(event) {
        event.preventDefault();
        console.log(arguments);
        bootbox.confirm({
            message : 'Deseja mesmo ativar o registro? O registro ativo atualmente será inativado.',
            callback : function (result) {
                if (result === true) {
                    location.href = event.target.getAttribute('href');
                }
            }
        });
    }

    $(document).ready(init);
})(jQuery);