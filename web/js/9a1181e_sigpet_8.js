(function($){
    var Sigpet = {
        init: function(){
            this.datepicker();
            this.bootbox();
            this.globalAjaxEvents();
        },
        globalAjaxEvents: function(){
            $(document).ajaxStart(function(e){
                $('.loader').removeClass('hidden');
            }).ajaxStop(function(){
                $('.loader').addClass('hidden');
            });
        },
        bootbox: function(){
            bootbox.setDefaults({
                locale: 'pt_BR'
            });
            bootbox.addLocale('pt_BR', {
                OK: 'Fechar',
                CANCEL: 'Cancelar',
                CONFIRM: 'Confirmar'
            });
        },
        datepicker: function(){
            $('.datepicker').datepicker({
                format: 'dd/mm/yyyy',
                language: 'pt-BR'
            });
        }
    };
    $(document).ready(function(){
        Sigpet.init();
    });
    
    for(var f=document.forms,i=f.length;i--;)f[i].setAttribute("novalidate",i)
})(jQuery);