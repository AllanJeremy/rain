$(function() {

    $('.mobile-button-collapse').sideNav();

/*
    var $container = $('#masonry-grid');
    // initialize
    $container.masonry({
      columnWidth: '.card-col',
      itemSelector: '.col',
    });
*/
    
    /**************
    TABS SWITCH FUNCTIONALITIES
    ************/
    
    //hide all tabs in <main> then remove class on the active bar
    $('main .main-tab').addClass('hide');
    $('main .active-bar').removeClass('hide');
    
    //on click side-nav panel actions
    $('.side-nav a:not(.collapsible-header)').click(function(e) {
        
        //console.log($(this).attr('data-activates'));
        
        var activates = $(this).attr('data-activates');
        
        //if it's not the name in the side nav that is clicked, switch tabs
        if($(this).attr('id') != 'name' && $(this).attr('id') != undefined) {
            
            e.preventDefault();
            
            $('.side-nav a:not(.collapsible-header)').removeClass('active');
            
            $(this).addClass('active');
            
            $('main .active-bar').addClass('hide');
            
            $('main .main-tab').removeClass('active-bar');
            
            $('main #' + activates).addClass('active-bar');

            $('main .active-bar').removeClass('hide');
            
            console.log('bar-clicked');
            
        } else {
            
            console.log('name clicked');
            console.log('undefined---doing nothing');
            
        }
        
    });
    
    /**************
    TABS SWITCH FUNCTIONALITIES END
    ************/
    
}, jQuery); // end of document ready