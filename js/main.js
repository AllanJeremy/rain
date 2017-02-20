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
