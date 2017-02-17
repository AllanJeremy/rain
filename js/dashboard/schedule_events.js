/*global $, jQuery, alert, console*/

var ScheduleEvents = function () {
    'use strict';
    
    //--------------
    
    this.__construct = function () {
        console.log('schedule events created');
        
        //global inits
        //cleanOutModals();
        
        //schedule inits
        openScheduleForm();     //done
        closeScheduleForm();    //done
        submitSchedule();       //done
        editSchedule();
        deleteSchedule();
        markDone();
        markAllDone();
        openSchedule();
        overdueScheduleReminder();
        getPendingSchedules();
        archiveSchedule();
        addObjective();         //done
        removeObjective();      //done
        updateClassroomDropdownExtraInfo();     //done
        updateSubTopicDropdown();               //done
        addObjectiveFromSubtopics();            //done
        tableNavigate();                        //done
        
    };
    
    //----------------------------
    //--------------------------------  SCHEDULE EVENTS
    //--------------------------------
    
    var openScheduleForm = function () {
        
        $('#schedulesTab #scheduleCreateFormContainer').slideUp();
        
        console.log('ScheduleFormHidden');
        
        $('main').on('click', 'a#openScheduleForm', function (e) {
            e.preventDefault();
        
            $('#scheduleCreateFormContainer').slideDown('1800');
            
            $('#createScheduleCont').addClass('z-depth-1');
            $('#createScheduleCont').addClass('blue');
            $('#createScheduleCont').addClass('lighten-5');

            $('a#openScheduleForm').addClass('hide');
            $('a#closeScheduleForm').removeClass('hide');
            
            
        });
        
    };
    
    var closeScheduleForm = function () {
        
        console.log('ScheduleFormHidden');

        $('main').on('click', 'a#closeScheduleForm', function (e) {
            e.preventDefault();
        
            $('#scheduleCreateFormContainer').slideUp('600', function () {
                
                $('#createScheduleCont').removeClass('z-depth-1');
                $('#createScheduleCont').removeClass('blue');
                $('#createScheduleCont').removeClass('lighten-5');

                $('a#closeScheduleForm').addClass('hide');
                $('a#openScheduleForm').removeClass('hide');

            });

        });
        
    };
        
    var submitSchedule = function () {
        
        console.log('Submitting schedule form');

        $('main').on('click', '#scheduleCreateFormContainer a#submitNewSchedule', function (e) {
            e.preventDefault();
        
            console.log('clicked');

            var scheduletitle = $('#scheduleCreateFormContainer input#schedule_title').val();
            var scheduleclassroom = $('#scheduleCreateFormContainer select#schedule_classroom').val();
            var scheduledescription = $('#scheduleCreateFormContainer #descriptionTextarea').val();
            
            $('#scheduleCreateFormContainer ul#objectivesList').children('li').children('span').remove();
            var scheduleobjectives = $('#scheduleCreateFormContainer ul#objectivesList').children('li');
            var scheduleobjectivesformatted = '';
            var scheduledatetime = $('#scheduleCreateFormContainer input#scheduleDate').val() + ' ' + $('#scheduleCreateFormContainer input#scheduleTime').val();
            var scheduledate = $('#scheduleCreateFormContainer .date-picker-container').find('input[type=hidden]').val();
            var scheduletime = $('#scheduleCreateFormContainer .time-picker-container').find('input[type=hidden]').val();
            var scheduleguidid = Materialize.guid();

            scheduleobjectives.each(function (i, e) {
               
                console.log(e.innerHTML);
                
                scheduleobjectivesformatted += e.innerHTML;
                scheduleobjectivesformatted += ',';
                
            });
            
            //scheduleobjectives.join(',');

            console.log(scheduletitle);
            console.log(scheduleobjectivesformatted);
            console.log(scheduleclassroom);
            console.log(scheduledescription);
            console.log(scheduledate);
            console.log(scheduletime);
            console.log(scheduleguidid);
            
            var scheduleformatteddatetime = scheduledate + ' ' + scheduletime;
            
            if (scheduletitle !== '' && scheduleclassroom !== null && scheduledescription !== '' && scheduleobjectivesformatted !== '' && scheduledate !== '' && scheduletime !== '') {
                
                $.post("classes/schedule_class.php", {
                    "action" : "CreateSchedule",
                    "scheduletitle" : scheduletitle,
                    "scheduledescription" : scheduledescription,
                    "scheduleclassroom" : scheduleclassroom,
                    "duedate" : scheduleformatteddatetime,
                    "guidid" : scheduleguidid
                }, function (result) {
                   
                    console.log(result);
                    console.log(typeof result);
                    
                    if(typeof result === 'number') {
                        
                        $.get("handlers/db_info.php", {"action": "ScheduleExistsByGuid", "guid_id" : scheduleguidid }, function (data) {

                            //clear the form inputs

                            $('#scheduleCreateFormContainer input#schedule_title').val('');
                            $('#scheduleCreateFormContainer select#schedule_classroom').val('');
                            $('#scheduleCreateFormContainer #descriptionTextarea').val('');
                            $('#scheduleCreateFormContainer ul#objectivesList').children('li').remove();
                            $('#scheduleCreateFormContainer input#scheduleDate').val('');
                            $('#scheduleCreateFormContainer input#scheduleTime').val('');
                            $('#scheduleCreateFormContainer .date-picker-container').find('input[type=hidden]').val('');
                            $('#scheduleCreateFormContainer .time-picker-container').find('input[type=hidden]').val('');

                            //close the form

                            $('#scheduleCreateFormContainer').slideUp('600', function () {

                                $('#createScheduleCont').removeClass('z-depth-1');
                                $('#createScheduleCont').removeClass('blue');
                                $('#createScheduleCont').removeClass('lighten-5');

                                $('a#closeScheduleForm').addClass('hide');
                                $('a#openScheduleForm').removeClass('hide');

                            });
                            
                            //Prepend the new schedule to Pending schedule list
                            
                            var pendingScheduleHook = $('#schedulesTab table#pendingScheduleTable').find('tbody:first');

                            var scheduledata = {
                                "schedulename": data.schedule_title,
                                "scheduledescription": data.schedule_description,
                                "scheduleclass": $('#scheduleCreateFormContainer #extraClassroomInfo p#ClassroomSubject').find('span').text(),
                                "scheduledatetime": scheduledatetime,
                                "scheduleid": data.schedule_id,
                                "scheduletype": 'pending'
                            };

                            console.log(scheduledata.scheduleclass);

                            console.log(scheduledata);

                            var scheduleData = Lists_Templates.scheduleList(scheduledata);

                            console.log(scheduleData);
                            console.log($('#schedulesTab table#pendingScheduleTable > tbody').length);
                            console.log(pendingScheduleHook.children('tr').length);

//                            $(scheduleData).prependTo("#schedulesTab table#pendingScheduleTable > tbody");
                            pendingScheduleHook.prepend(scheduleData);

                            var paginationthrough = $('#schedulesTab table#pendingScheduleTable').attr('data-paginate-through');

                            console.log(paginationthrough);

                            if (pendingScheduleHook.children('tr').length > paginationthrough) {

                                updatePagination('#schedulesTab table#pendingScheduleTable', 'tbody', '#pendingScheduleTable', paginationthrough, 'forward');

                            }

                        }, 'json');
                        
                    } else {
                        
                        console.log(typeof result);
                        
                    }
                    
                }, 'json');
                
            }

        });
        
    };

    var updatePagination = function (str1, str2, str3, str4, str5) {

        var tablehook = str1,
            child = str2,
            tableid = str3,
            listlimit = str4,
            direction = str5,
            toMove = '';

        if (direction === 'forward') {

            for(var k = 0; k < $(tablehook).children(child).length; k++) {

                var next = k + 1;

                $(tablehook + ' ' + child + '[data-tbody-number=' + k + ']').children('tr').each( function (i,el) {

                    if (i > (listlimit - 1)) {

                        toMove += el.outerHTML;

                        if ($(tablehook + ' ' + child + '[data-tbody-number=' + k + ']').children('tr')[i] !== undefined) {

                            $(tablehook + ' ' + child + '[data-tbody-number=' + k + ']').children('tr')[i].outerHTML = '';

                        } else {

                            $(tablehook + ' ' + child + '[data-tbody-number=' + k + ']').children('tr')[(listlimit - 1)].outerHTML = '';
                        }

                    } else {

                        toMove += '';

                    }

                });
                console.log('data for tbody number : ' + k + ' is => ' + toMove);
                console.log(next + ' : ' + $(tablehook).children(child).length);

                if (toMove !== '') {

                    if(next <= $(tablehook).children(child).length) {

                        $(tablehook + ' ' + child + '[data-tbody-number=' + next + ']').prepend(toMove);

                        toMove = '';

                    }
                }
            };

        } else if (direction === 'backward') {

            for(var k = 0; k < $(tablehook).children(child).length; k++) {

                var next = k + 1,
                    needed = listlimit - $(tablehook + ' ' + child + '[data-tbody-number=' + k + ']').children('tr').length;

                if(next <= $(tablehook).children(child).length) {

                    $(tablehook + ' ' + child + '[data-tbody-number=' + next + ']').children('tr').each( function (i,el) {

                        if (i <= (needed - 1)) {

                            toMove += el.outerHTML;

                            $(tablehook + ' ' + child + '[data-tbody-number=' + k + ']').children('tr')[0].outerHTML = '';

                        } else {

                            toMove += '';

                        }

                    });

                    console.log('data for tbody number : ' + k + ' is => ' + toMove);
                    console.log(next + ' : ' + $(tablehook).children(child).length);

                    if (toMove !== '') {

                        $(tablehook + ' ' + child + '[data-tbody-number=' + k + ']').append(toMove);

                        toMove = '';

                    }
                }
            };

        }
    };

    var tableNavigate = function () {

        console.log('Adding navigation function init');

        $('main').on('click', 'ul.pagination li', function (e) {
        
            e.preventDefault();

            if ($(this).hasClass('active') || $(this).hasClass('disabled')) { //If the clicked li is active or disabled, do nothing
                console.log('is active. do nothing');
                return false;
            } else {

                var targettable = $(this).parent('ul').attr('data-table-target'),
                    paginationcontroltype = $(this).children('a').attr('id'),
                    activepagenumber = $(this).parent('ul').find('li.active').children('a')[0].innerHTML,
                    activepagecontrol = $(this).parent('ul').find('li.active'),
                    clickedpagecontrol = $(this),
                    clickedpagenumber = $(this).children('a')[0].innerHTML,
                    totalpagecontrols = $(this).parent('ul')[0].childElementCount;

                switch (paginationcontroltype) {

                    case 'goToLeftPage' : //Left
                        console.log('left clicked');

                        var previouspage = (parseInt(activepagenumber) - 1),
                            previouspagecontrol = clickedpagecontrol.parent('ul').find('li:nth(' + previouspage + ')');

                        if (previouspage > 0) {

                            $('table#' + targettable).find('tbody[data-tbody-number='+ parseInt(activepagenumber) +']').fadeOut(300, function() {

                                $('table#' + targettable).find('tbody[data-tbody-number='+ parseInt(activepagenumber) +']').removeClass('active');
                                $('table#' + targettable).find('tbody[data-tbody-number='+ parseInt(activepagenumber) +']').addClass('hide');

                                $('table#' + targettable).find('tbody[data-tbody-number='+ previouspage +']')[0].style.opacity = 0;
                                $('table#' + targettable).find('tbody[data-tbody-number='+ previouspage +']').addClass('active');
                                $('table#' + targettable).find('tbody[data-tbody-number='+ previouspage +']').removeClass('hide');

                                $('table#' + targettable).find('tbody[data-tbody-number='+ previouspage +']').animate({
                                    opacity : 1
                                }, 330);

                                activepagecontrol.removeClass('active');
                                console.log(previouspagecontrol);
                                previouspagecontrol.addClass('active');

                                $(this).fadeIn(200);

                                if (parseInt(activepagenumber) === 2) {
                                    clickedpagecontrol.parent('ul').find('li:first-child').addClass('disabled');

                                }

                            });

                        }

                        break;
                    case 'goToRightPage' : //Left
                        console.log('right clicked');

                        var nextpage = (parseInt(activepagenumber) + 1),
                            nextpagecontrol = clickedpagecontrol.parent('ul').find('li:nth(' + nextpage + ')');

                        if (nextpage <= (totalpagecontrols - 2)) {

                            $('table#' + targettable).find('tbody[data-tbody-number='+ parseInt(activepagenumber) +']').fadeOut(300, function() {

                                $('table#' + targettable).find('tbody[data-tbody-number='+ parseInt(activepagenumber) +']').removeClass('active');
                                $('table#' + targettable).find('tbody[data-tbody-number='+ parseInt(activepagenumber) +']').addClass('hide');

                                $('table#' + targettable).find('tbody[data-tbody-number='+ nextpage +']')[0].style.opacity = 0;
                                $('table#' + targettable).find('tbody[data-tbody-number='+ nextpage +']').addClass('active');
                                $('table#' + targettable).find('tbody[data-tbody-number='+ nextpage +']').removeClass('hide');

                                $('table#' + targettable).find('tbody[data-tbody-number='+ nextpage +']').animate({
                                    opacity : 1
                                }, 330);

                                activepagecontrol.removeClass('active');
                                //console.log(nextpagecontrol);
                                nextpagecontrol.addClass('active');

                                $(this).fadeIn(200);

                                if (parseInt(activepagenumber) === 1) {
                                    clickedpagecontrol.parent('ul').find('li:first-child').removeClass('disabled');

                                } else if (parseInt(nextpagecontrol.children('a')[0].innerHTML) === (totalpagecontrols - 2)) {
                                    clickedpagecontrol.parent('ul').find('li:last-child').addClass('disabled');

                                }
                            });
                        }

                        break;
                    default : //normal
                        console.log('others clicked');

                        $('table#' + targettable).find('tbody[data-tbody-number='+ parseInt(activepagenumber) +']').fadeOut(300, function() {

                            $('table#' + targettable).find('tbody[data-tbody-number='+ parseInt(activepagenumber) +']').removeClass('active');
                            $('table#' + targettable).find('tbody[data-tbody-number='+ parseInt(activepagenumber) +']').addClass('hide');

                            $('table#' + targettable).find('tbody[data-tbody-number='+ clickedpagenumber +']')[0].style.opacity = 0;
                            $('table#' + targettable).find('tbody[data-tbody-number='+ clickedpagenumber +']').addClass('active');
                            $('table#' + targettable).find('tbody[data-tbody-number='+ clickedpagenumber +']').removeClass('hide');

                            $('table#' + targettable).find('tbody[data-tbody-number='+ clickedpagenumber +']').animate({
                                opacity : 1
                            }, 330);

                            activepagecontrol.removeClass('active');
                            clickedpagecontrol.addClass('active');

                            $(this).fadeIn(200);

                            if (parseInt(activepagenumber) === 1 && clickedpagecontrol.parent('ul').find('li:first-child').hasClass('disabled')) {
                                clickedpagecontrol.parent('ul').find('li:first-child').removeClass('disabled');

                            } else if (clickedpagecontrol.parent('ul').find('li:last-child').hasClass('disabled') && clickedpagenumber !== (totalpagecontrols - 2)) {
                                clickedpagecontrol.parent('ul').find('li:last-child').removeClass('disabled');

                            }

                            if (parseInt(clickedpagenumber) === (totalpagecontrols - 2)) {
                                clickedpagecontrol.parent('ul').find('li:last-child').addClass('disabled');

                            } else if (parseInt(clickedpagenumber) === 1) {
                                clickedpagecontrol.parent('ul').find('li:first-child').addClass('disabled');

                            }
                        });

                        break;
                }
            }
        });
    };
    
    var addObjective = function () {
        
        console.log('Adding Objectives function init');
        
        $('main').on('click', '#scheduleCreateFormContainer a#addNewScheduleObjective', function (e) {
            
            e.preventDefault();
        
            var objectivetext = $('#scheduleCreateFormContainer input#objectivesInput').val();
            
            if (objectivetext !== '') {

                console.log(objectivetext);

                var newObjectiveData = {
                    "text": objectivetext,
                    "removable": true,
                    "isSubtopic" : false
                };

                newObjectiveData = Lists_Templates.objective(newObjectiveData);

                $('#scheduleCreateFormContainer ul#objectivesList').append(newObjectiveData);
            
            }
            
            $('#scheduleCreateFormContainer input#objectivesInput').val('');
            
        });
        
    };

    var addObjectiveFromSubtopics = function () {
        console.log('Adding objectives from list of topics init');

        var teacherClassroomids = $('#scheduleCreateFormContainer select#schedule_classroom').children('option:not(:disabled)').map(function () {
            return this.value;
        }).get();

        var target = '';
        
        teacherClassroomids.forEach(function (i) {

            target += '#scheduleCreateFormContainer select#schedule_classroom_' + i + ', ';

        });

        console.log(target);

        $('main').on('change', target + '#scheduleCreateFormContainer select#schedule_classroom_default', function (e) {
            e.preventDefault();
            console.log('clicked!!!');
            //Know which select is active;

            var currentActiveSelect = $('#scheduleCreateFormContainer #selectContainerHook').children('.select-wrapper:not(.hide)').find('select:not(.hide) option:selected:not(:disabled)').text();

            var newObjectiveData = {
                "text": currentActiveSelect,
                "removable": true,
                "isSubtopic" : true
            };

            newObjectiveData = Lists_Templates.objective(newObjectiveData);

            $('#scheduleCreateFormContainer ul#objectivesList').append(newObjectiveData);

            console.log(currentActiveSelect);

        });
    };

    var removeObjective = function () {
        
        console.log('Removing Objectives function init');
        
        $('main').on('click', '#scheduleCreateFormContainer ul#objectivesList li a', function (e) {
            
            e.preventDefault();
        
            $(this).parents('li').remove();
            
            console.log('removed');
            
        });
        
    };
        
    var updateClassroomDropdownExtraInfo = function () {
        
        $('main').on('change', '#scheduleCreateFormContainer select#schedule_classroom', function (e) {
            e.preventDefault();
        
            var selectedteacherClassroomid = $('#scheduleCreateFormContainer select#schedule_classroom').find('option:selected:not(:disabled)').val(),
                streamid = '',
                subjectid = '',
                subjectHook = $('#scheduleCreateFormContainer #extraClassroomInfo p#ClassroomSubject'),
                streamHook = $('#scheduleCreateFormContainer #extraClassroomInfo p#ClassroomStream');
                
            console.log(subjectHook.length);
            console.log(streamHook.length);
            
            //gets data about the classroom
            if(selectedteacherClassroomid != 'null') {
                
                $.get('handlers/db_info.php', {"action": "ClassroomExists", "class_id": selectedteacherClassroomid}, function (data) {

                    subjectid = data.subject_id;
                    streamid = data.stream_id;

                    //gets data about the subject of the selected classroom
                    $.get('handlers/db_info.php', {"action": "GetSubjectById", "subject_id": subjectid}, function (subjectdata) {

                        var subjectName = subjectdata.name;

                        subjectHook.find('span').remove();
                        subjectHook.append('<span>' + subjectName + '</span>');

                    }, 'json');

                    //gets data about the stream of the selected classroom
                    $.get('handlers/db_info.php', {"action": "GetStreamById", "stream_id": streamid}, function (streamdata) {

                        var streamName = streamdata.name;
                        console.log(streamName);

                        streamHook.find('span').remove();
                        streamHook.append('<span>' + streamName + '</span>');

                    }, 'json');

                    console.log(selectedteacherClassroomid);

                }, 'json');

            } else {

                subjectHook.find('span').remove();
                subjectHook.append('<span> </span>');

                streamHook.find('span').remove();
                streamHook.append('<span> </span>');

            }

        });
    
    };
    
    var updateSubTopicDropdown = function () {
        
        console.log('Choose a classroom first, for a list of topics to appear');
        
        $('main').on('change', '#scheduleCreateFormContainer select#schedule_classroom', function (e) {
            e.preventDefault();
            var selectedClassroomId = $('#scheduleCreateFormContainer select#schedule_classroom').val();
            var selectHook = $('#scheduleCreateFormContainer #selectContainerHook');
            
            console.log('changed');
            console.log('selected is - ' + selectedClassroomId);
            
            selectHook.children('.select-wrapper').addClass('hide');
            selectHook.children('.select-wrapper').children('select').addClass('hide');
            console.log(selectHook.children('.select-wrapper').length);
            
            selectHook.children('.select-wrapper').children('select#schedule_classroom_' + selectedClassroomId).removeClass('hide');
            selectHook.children('.select-wrapper').children('select#schedule_classroom_' + selectedClassroomId).parent('.select-wrapper').removeClass('hide');
            
            $('select').material_select();
            
        });
    };
    
    var editSchedule = function () {

    };
    
    var deleteSchedule = function () {
        
    };
        
    var markDone = function () {
        
        $('main').on('click', 'a#attendedSchedule', function(e) {
            e.preventDefault();


            console.log('marking done...');
        });
    };

    var openSchedule = function () {

        $('main').on('click', 'a#openSchedule', function(e) {
            e.preventDefault();

            console.log('opening...');

            //variables for the modal
            var template = {
                classes: resultData.classes,
                modalId: 'editClassRoom',
                templateHeader: 'Edit Classroom',
                templateBody: formTemplate,
                modalActionType: 'type="submit"',
                modalActionTypeText: 'Update classroom',
                extraActions: Lists_Templates.editExtraFooterActions({
                    "Delete" : true,
                    "Archive" : true,
                    "Reload" : false
                })
            };
            //Ajax
            //load the modal in the DOM
            $('main').append(Lists_Templates.modalTemplate(template));

            $(this).attr('data-target', template.modalId);

            $('#' + template.modalId).openModal({dismissible:false});

        });
    };
        
    var markAllDone = function () {
        
    };
        
    var overdueScheduleReminder = function () {
        
    };
        
    var getPendingSchedules = function () {
        
    };
        
    var archiveSchedule = function () {
        
    };
    
    //--------------------------------
    //--------------------------------  MODAL EVENTS AND FUNCTIONS
    //--------------------------------

    //----------------------------      FUNCTIONS

    var loadEsomoModal = function (modal_id, modal_header, modal_body, modal_action) {

        var args = {
            modalId: modal_id,
            modalActionType: {
                actionClose: 'close-hivi',
                actionAdd: 'add-hivi'
            },
            modal_action: modal_action,
            templateHeader: modal_header,
            templateBody: modal_body

        };

    var esomoModal = Lists_Templates.esomoModalTemplate(args);

        $('main').append(esomoModal);

    };

    //--------------------------------

    var cleanOutModal = function (str) {

        console.log('cleaning out modal' + str);

        $('.modal' + str).remove();

        console.log($('main').find('.modal' + str).length);

    };

    //----------------------------

    var cleanOutModals = function () {

        console.log('cleaning out classrooms dialogs');

        //$('a#createClassroom').attr('data-target', '');

        $('.modal ').remove();

    };

    //--------------------------------
    //--------------------------------  END OF MODAL EVENTS AND FUNCTIONS
    //--------------------------------

    this.__construct();

};
