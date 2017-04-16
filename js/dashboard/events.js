/*global $, jQuery, alert, console*/

var Events = function () {
    'use strict';
    //--------------
    
    this.__construct = function () {
        console.log('global events created');
        
        Modals_Events = new Modals_Events();

        ClassroomEvents = new ClassroomEvents();
        AssignmentEvents = new AssignmentEvents();
        ScheduleEvents = new ScheduleEvents();
        ResourcesEvents = new ResourcesEvents();
        
        //global inits
        Modals_Events.cleanOutModals();
        Modals_Events.closeModalsEvent();
        
    };

    this.__construct();
    
};
