var filters = {
  
    init : function() {
        $('.int').on('keyup', function(){            
            $(this).val(filters.justNumbers($(this).val()));
        });
    },
    
    justNumbers : function(value) {        
        return value.replace(/[^0-9]/g, '');
    }
    
};

filters.init();