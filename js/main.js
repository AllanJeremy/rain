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
    
    //on click none-collapsible-headers side-nav panel actions
    $('.side-nav a.collapsible-header').click(function() {
        //$('.side-nav a:not(.collapsible-header)').parent().removeClass('active');
    });
    
    //on click none-collapsible-headers side-nav panel actions
    $('.side-nav a:not(.collapsible-header)').click(function(e) {
        
        //console.log($(this).attr('data-activates'));
        
        var activates = $(this).attr('data-activates');
        
        //if it's not the name in the side nav that is clicked, switch tabs
        if($(this).attr('id') != 'name' && $(this).attr('id') != undefined) {
            
            e.preventDefault();
            
            $('.side-nav a:not(.collapsible-header)').parent().removeClass('active');
            if($(this).parent().parent().parent().hasClass('collapsible-body') === true) {
                //console.log('is a collapsible list');
                
            } else {
                //console.log('not a collapsible list');
                
                $('.side-nav a.collapsible-header').parent().removeClass('active');
            }
            $(this).parent().addClass('active');
            
            $('main .active-bar').addClass('hide');
            
            $('main .main-tab').removeClass('active-bar');
            
            $('main #' + activates).addClass('active-bar');

            $('main .active-bar').removeClass('hide');
            
            console.log('bar-clicked');
            console.log('setting header text');
            
            var pageTitle = $(this).text();
            
            $('a#pageTitle').text(pageTitle);
            
        } else {
            
            console.log('name clicked');
            console.log('undefined---doing nothing');
 
        }
        
    });
    
    /**************
    AJAX FUNCTIONS
    ************/


    /**************
    TABS SWITCH FUNCTIONALITIES END
    ************/
    
    /**************
    SEARCHBAR FUNCTIONALITIES
    ************/
    
    function openSearchBar() {
        
    }
    
    function closeSearchBar() {
        
    }
    /**************
    SEARCHBAR FUNCTIONALITIES END
    ************/
    
}, jQuery); // end of document ready