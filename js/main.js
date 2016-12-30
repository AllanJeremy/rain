$(function (z) {
    
    //init the dashboard application except on login page
    if (location.pathname.split('/').pop() != 'login.php') {
        
        var dashboard = new Dashboard();
        
    }
    
    $('.mobile-button-collapse').sideNav();
    
    /**************
    TABS SWITCH FUNCTIONALITIES
    ************/
    
    
    /********** ON DOCUMENT LOAD EVENT **********/
    
    //hide all tabs in <main> then remove 'hide' class on the active bar
    $('main .main-tab').addClass('hide');
    $('main .active-bar').removeClass('hide');
    
    
    //make the current #? bar active on page load
    console.log(location.hash);
    
    var currTag = location.hash;//current hash on init
    
    if(!currTag || currTag === '#!name' || currTag === '#!') {//if currTag is undefined or has '#!name' or '#!' value, make it #classroom
        currTag = '#classroom';
        location.hash = currTag;
    }
    
    var activates = $('.side-nav a:not(.collapsible-header)' + currTag).attr('data-activates');//get the 'data-activates' attr of the current hash

    $('main .active-bar').addClass('hide');

    $('main .main-tab').removeClass('active-bar');

    $('main #' + activates).addClass('active-bar');

    $('main .active-bar').removeClass('hide');

    $('.side-nav a:not(.collapsible-header)').parent().removeClass('active');//clean out any active class
    
    $('.side-nav a:not(.collapsible-header)' + currTag).parent('li').addClass('active');// make active the tag that's similar to the location hash
    $('.side-nav a:not(.collapsible-header)' + currTag).parent('li').parent().parent('.collapsible-body').parent().addClass('active');// make active the collapsible header of the tag that's similar to the location hash
    $('.side-nav a:not(.collapsible-header)' + currTag).parent('li').parent().parent().parent().parent('.collapsible').find('.collapsible-header').addClass('active');// make active the collapsible header of the tag that's similar to the location hash
    $('.side-nav a:not(.collapsible-header)' + currTag).parent('li').parent().parent('.collapsible-body').css('display','block');// Open the collapsible body

    var pageTitle = $('.side-nav a:not(.collapsible-header)' + currTag).text();

    $('a#pageTitle').text(pageTitle);

    $(document).on('click', '.side-nav a', function (e) {
        
        //console.log('tag id is -> ' + $(this).attr('id'));
        
        var tag = $(this).attr('id');
        
        if(tag) {
            
            location.hash = tag;//add the id to the url as hash '#'
        
        }
        
    });
    
    //on click none-collapsible-headers side-nav panel actions
    $('.side-nav a.collapsible-header').click(function () {
        //$('.side-nav a:not(.collapsible-header)').parent().removeClass('active');
        console.log('Materialize css collapsible on.');
    });
    
    
    /********** ON CLICK EVENT **********/
    
    //on click none-collapsible-headers side-nav panel actions
    $('.side-nav a:not(.collapsible-header)').click(function (e) {
        
        //console.log($(this).attr('data-activates'));
        
        var activates = $(this).attr('data-activates');
        
        //if it's not the name in the side nav that is clicked, switch tabs
        if ($(this).attr('id') != 'name' && $(this).attr('id') != undefined) {
            
            e.preventDefault();
            
            $('.side-nav a:not(.collapsible-header)').parent().removeClass('active');
            if ($(this).parent().parent().parent().hasClass('collapsible-body') === true) {
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