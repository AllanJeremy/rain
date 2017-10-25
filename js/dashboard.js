/*global $, jQuery, alert, console*/

var Dashboard = function () {
    'use strict';
    //--------------
    
    this.__construct = function () {
        console.log('dashboard created');
        
        // setActiveSection();

        Events = new Events();
        Result = new Result();
        Tests = new Tests();
        
        //loadTestCard();
        
    };

    var getAllSubjects = function () {
        var Subjects = $.ajax({
            url: "handlers/db_handler.php",
            type: 'GET'
        });

        Subjects.done(function (val) {
            console.log(val);

            return val;
        });
    };

    this.__construct();
    
};
