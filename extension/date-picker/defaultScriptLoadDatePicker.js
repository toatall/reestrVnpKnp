function loadDatePicker() {    
    /*
    $('input[isdate]').on('click', function() {
        
        $(this).each(function() {               
            $(this).datepicker({
                'format':'dd.mm.yyyy',
                'autoclose':'true',
                'todayBtn':'linked',
                'language':'ru',
                'weekStart':0,
            }).mask(
        		'00.00.0000',
        		{'placeholder':'__.__.____'}
            );
        }); 
    }); 
    */ 
    
    $('input[isdate]').each(function() {               
        $(this).datepicker({
            'format':'dd.mm.yyyy',
            'autoclose':'true',
            'todayBtn':'linked',
            'language':'ru',
            'weekStart':0,
       })/*.mask(
    		'00.00.0000',
    		{'placeholder':'__.__.____'}
    	)*/;
    });                
}
