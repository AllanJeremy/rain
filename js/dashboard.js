/*global $, jQuery, alert, console*/

var Dashboard = function () {
    'use strict';
    //--------------
    
    this.__construct = function () {
        console.log('dashboard created');
        
        // setActiveSection();

        Lists_Templates = new Lists_Templates();
        Forms_Templates = new Forms_Templates();

        Events = new Events();
        Result = new Result();
//        Tests = new Tests();
        
        //loadTestCard();
        
    };

    //-------------
    
    this.__construct();
    console.log(window.location.search.split('=').pop());
};
