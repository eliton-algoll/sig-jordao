var helper = {
    init: function() {
        this.events();
        this.masks();
    },
    events: function() {
        $('.header-order-by').click(this.handleHeaderOrderByClick);
        $('.btn-download').click(this.handleDownloadButton);
        $('.btn-clear-form').on('click', this.clearForm);
        $('form').submit(this.handleSubmitForm);
    },
    masks: function(){
        $('.dmY').mask('00/00/0000', { clearIfNotMatch: true });
        $('.nuCpf').mask('999.999.999-99');
        $('.nuCnpj').mask('99.999.999/9999-99');
        $('.nuSipar').mask('99999.999999/9999-99');
        $('.nuDdd').mask('999');
        $('.nuTelefone').mask('9999-99999');
        $('.money').mask('000.000,00', { reverse : true });
    },    
    makeOptions: function(
        targetSelector, 
        url, 
        data, 
        optionValue, 
        optionLabel, 
        hasEmptyFirstOption, 
        selectedOption, 
        callback, 
        requestMethod
    ) {
        $.ajax({
            type: (requestMethod === undefined ? 'GET' : requestMethod),
            url: url,
            data: data,
            async: true,
            success: function(data){
                $(targetSelector).empty();
                if (hasEmptyFirstOption !== undefined && hasEmptyFirstOption === true) {
                    $(targetSelector).append($('<option>').html('').text('Selecione').val(''));
                }
                $.each(data, function(){
                    var newOption = $('<option>').val(this[optionValue]).html(this[optionLabel]);
                    if (selectedOption !== undefined && selectedOption == this[optionValue]) {
                        newOption.prop('selected', true);
                    }
                    $(targetSelector).append(newOption);
                });
                if (callback !== undefined) {
                    callback();
                }
            }
        });
    },
    ajustTableWithScroll: function() {
        // Change the selector if needed
        var $table = $('[class*=scroll]'),
            $bodyCells = $table.find('tbody tr:first').children(),
            colWidth,
            $thead = $table.find('thead tr').hide();
    
        setTimeout(function(){
            // Get the tbody columns width array
            colWidth = $bodyCells.map(function() {
                return $(this).width();
            }).get();

            // Set the width of thead columns
            $table.find('thead tr').hide().children().each(function(i, v) {
                $(v).width(colWidth[i]);
            });
            
            $thead.show("slow");
        }, 500);
    },
    handleHeaderOrderByClick: function() {
        var orderBy = $(this).attr('data-order-by');
        var sort = $(this).attr('data-sort');
        $('form').append('<input type="hidden" name="order-by" value="' + orderBy + '">');
        $('form').append('<input type="hidden" name="sort" value="' + sort + '">');
        $('form').submit();
    },
    handleDownloadButton: function() {
        $('footer').remove('form');
        var $form = $('form').clone(true, true);
        $form.addClass('hidden');
        $form.append('<input type="hidden" name="flag-download" value="1">');
        $('footer').append($form);
        $form.submit();
        
        return false;
    },
    clearForm: function() {
        var form = $(this).closest('form');
        
        form.find('input[type="text"]').val('');
        form.find('select').val('');
    }    
};

$(function(){
   helper.init(); 
});