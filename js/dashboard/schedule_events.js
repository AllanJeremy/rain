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
        editSchedule();         //Beta - version
        deleteSchedule();
        markAttendedSchedule();             //60%
        unmarkAttendedSchedule();             //60%
        markAllDone();          //Beta - version
        openSchedule();         //done
        overdueScheduleReminder();
        getPendingSchedules();
        archiveSchedule();
        addObjective();         //done
        removeObjective();      //done
        updateClassroomDropdownExtraInfo();     //done
        updateSubTopicDropdown();               //done
        addObjectiveFromSubtopics();            //done
        tableNavigate();                        //done
        nextPrevSchedule();                     //done
        
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
                    "scheduleobjectives" : scheduleobjectivesformatted,
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
                                "scheduleobjectives": data.schedule_objectives,
                                "scheduleclass": $('#scheduleCreateFormContainer #extraClassroomInfo p#ClassroomSubject').find('span').text(),
                                "scheduledatetime": moment(data.due_date).fromNow(),
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
                            if (pendingScheduleHook.attr('data-tbody-number') === 'noData') {

                                pendingScheduleHook.remove();

                                $('#schedulesTab table#pendingScheduleTable').append('<tbody data-tbody-number="1" class="active">' + scheduleData + '</tbody>');

                            } else {

                                pendingScheduleHook.prepend(scheduleData);
                            }

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

        if (direction === 'forward') {//Excess list is carried to the next tbody

            //Loops through tbodies, checking if there are excess
            for (var k = 1; k <= $(tablehook).children(child).length; k++) {

                var next = k + 1;
                //Loop on each tbody, checking number of <tr> per tbody
                $(tablehook + ' ' + child + '[data-' + child + '-number=' + k + ']').children('tr').each( function (i,el) {
                    //i  is the array index.
                    //If i is greater than the list limit, means they are excess
                    if (i > (listlimit - 1)) {
                        //Add the excess <tr> elements in a variable
                        toMove += el.outerHTML;
                        //The array index number of the next <tr> always reduces by one every time, a <tr> is removed,
                        //thus always having a constant number in ...ren('tr')[listlimit]
                        $(tablehook + ' ' + child + '[data-' + child + '-number=' + k + ']').children('tr')[listlimit].outerHTML = '';

                    } else {

                        toMove += '';

                    }

                });
                console.log('data FROM ' + child + ' number : ' + k + ' TO APPEND TO ' + child + ' number : ' + next + ' is => ' + toMove);
                console.log(next + ' : ' + $(tablehook).children(child).length);
                //After the <tr> loop on a tbody is done,
                //And var toMove has elements/data/is not empty,
                if (toMove !== '') {

                    if(next <= $(tablehook).children(child).length) {
                        console.log('less');
                        $(tablehook + ' ' + child + '[data-' + child + '-number=' + next + ']').prepend(toMove);

                        toMove = '';

                    } else if (next > $(tablehook).children(child).length) {

                        console.log('more');
                        console.log('TO APPEND THIS => ' + '<' + child + '[data-' + child + '-number=' + next + '] class="hide">' + toMove + '</' + child + '>');
                        $(tablehook).children(child + ':last').after('<' + child + ' data-' + child + '-number="' + next + '" class="hide">' + toMove + '</' + child + '>');

                        toMove = '';

                        $('ul[data-table-target=' + tableid.substring(1,tableid.length) + ']').children('li:last').before('<li class="waves-effect"><a href="#!">' + next + '</a></li>');

                    }
                }
            };

        } else if (direction === 'backward') {//If list is less, data from the next tbody is pushed to the current

            for(var k = 1; k <= $(tablehook).children(child).length; k++) {

                var next = k + 1,
                    needed = listlimit - $(tablehook + ' ' + child + '[data-' + child + '-number=' + k + ']').children('tr').length;

                console.log(child + ' number ' + k + ' is less ' + needed + ' rows!');


                if(next <= $(tablehook).children(child).length) {

                    $(tablehook + ' ' + child + '[data-' + child + '-number=' + next + ']').children('tr').each( function (i,el) {

                        if (i <= (needed - 1)) {
                            //Get the first tr elements as long as i is less than the needed number of tr rows

                            toMove += el.outerHTML;

                            $(tablehook + ' ' + child + '[data-' + child + '-number=' + next + ']').children('tr')[0].outerHTML = '';

                        } else {

                            toMove += '';

                        }

                    });

                    console.log('data for ' + child + ' number : ' + k + ' from ' + child + ' number : ' + next + ' is => ' + toMove);
                    console.log(next + ' : ' + $(tablehook).children(child).length);
                    console.log($('ul[data-table-target=' + tableid.substring(1,tableid.length) + ']').children('li:last')[0].previousElementSibling);
                    console.log($('ul[data-table-target=' + tableid.substring(1,tableid.length) + ']').children('li:last')[0].previousElementSibling.rowIndex);
                    console.log($('ul[data-table-target=' + tableid.substring(1,tableid.length) + ']').children('li:last')[0].previousElementSibling.classList);

                    if (toMove !== '') {

                        $(tablehook + ' ' + child + '[data-' + child + '-number=' + k + ']').append(toMove);

                        toMove = '';


                    }
                    //If we moved all the tr of the last tbody to the previous tbody, delete the last tbody and its pagination controller
                    //Set the previous tbody/pagination controller active
                    if (next === $(tablehook).children(child).length && $(tablehook + ' ' + child + ':last').children('tr').length === 0 ) {

                        console.log('TUKO NDANI');

                        $(tablehook).children(child + ':last').remove();



                        if($('ul[data-table-target=' + tableid.substring(1,tableid.length) + ']').children('li:last')[0].previousElementSibling.classList[0] === 'active' || $('ul[data-table-target=' + tableid.substring(1,tableid.length) + ']').children('li:last')[0].previousElementSibling.classList[1] === 'active') {

                            console.log('is active');
                            var $this = $('ul[data-table-target=' + tableid.substring(1,tableid.length) + ']').children('li:last')[0].previousElementSibling.previousElementSibling.firstElementChild.text;

                            console.log($this);

                            $this = $('ul[data-table-target=' + tableid.substring(1,tableid.length) + ']').children('li:last')[0].previousElementSibling.childNodes[0].text;

                            console.log($this);

                            $('ul[data-table-target=' + tableid.substring(1,tableid.length) + ']').children('li:nth-child(' + $this + ')').addClass('active');
                            $(tablehook + ' ' + child + ':last').removeClass('active');
                            $(tablehook + ' ' + child + ':last').addClass('hide');
                            $(tablehook + ' ' + child + ':last').addClass('hide');
                            $(tablehook + ' ' + child + '[data-' + child + '-number=' + $('ul[data-table-target=' + tableid.substring(1,tableid.length) + ']').children('li:last')[0].previousElementSibling.previousElementSibling.firstElementChild.text + ']').removeClass('hide');
                            $(tablehook + ' ' + child + '[data-' + child + '-number=' + $('ul[data-table-target=' + tableid.substring(1,tableid.length) + ']').children('li:last')[0].previousElementSibling.previousElementSibling.firstElementChild.text + ']').addClass('active');
                        }

                        $('ul[data-table-target=' + tableid.substring(1,tableid.length) + ']').children('li:last')[0].previousElementSibling.remove();
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
                                if (parseInt(activepagenumber) === (totalpagecontrols - 2)) {
                                    clickedpagecontrol.parent('ul').find('li:last-child').addClass('disabled');

                                }

                            });

                        }

                        break;
                    case 'goToRightPage' : //Left
                        console.log('right clicked');

                        var nextpage = (parseInt(activepagenumber) + 1),
                            nextpagecontrol = clickedpagecontrol.parent('ul').find('li:nth(' + nextpage + ')');

                        console.log(parseInt(nextpagecontrol.children('a')[0].innerHTML));
                        console.log((totalpagecontrols - 2));

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
                                nextpagecontrol.addClass('active');

                                $(this).fadeIn(200);

                                if (parseInt(activepagenumber) === 1) {
                                    clickedpagecontrol.parent('ul').find('li:first-child').removeClass('disabled');

                                }
                                if (parseInt(nextpagecontrol.children('a')[0].innerHTML) === (totalpagecontrols - 2)) {
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
        
        $('main').on('click', '.modal a#moreScheduleCardDelete', function (e) {
            e.preventDefault();

            var self = $(this), re,
                scheduleid = self.parents('.modal').attr('id').split('_').pop(),
                toastMessage = '<p class="white-text" data-ref-schedule-id="' + scheduleid + '">Preparing to delete schedule  <a href="#!" class="bold" id="toastUndoAction" >UNDO</a></p>';

            self.parents('.modal').find('a#attendedScheduleFromModal').length === 0 ? re = 'attendedScheduleTable' : re = 'pendingScheduleTable';

            console.log('schedule id ' + scheduleid + ' to be deleted.');

            //close modal
            $('.modal#' + self.parents('.modal').attr('id') ).closeModal();
            //remove modal from dom
            cleanOutModals();

            $('table#' + re).find('tr[data-schedule-id="' + scheduleid + '"]').addClass('to-remove');

            //3
            var toastCall = Materialize.toast(toastMessage, 7200, '', function (s) {
                //4
                $.post("handlers/db_handler.php", {"action" : "DeleteSchedule", "scheduleid" : scheduleid}, function (result) {

                    //5
                    if(result === '1') {

                        $('table#' + re).find('tr[data-schedule-id="' + scheduleid + '"]').remove();

                        console.log(result);

                        updatePagination('#schedulesTab table#' + re, 'tbody', '#' + re, paginationthrough, 'forward');

                    }

                    //6
                    //cleanOutModals();

                }, 'text');

            });

        });

    };
        
    var markAttendedSchedule = function () {
        
        /*
        *   1. Finds out if it's marked from the modal or the table list
        *   2. Ajax post to mark the schedule as attended
        *   3. If true, replace the done icon with undo
        *   4. prepend the list to attended table
        *   5. Remove the list from the pending table
        *   6. Clean out modal
        *   7. Update pagination
        */

        $('main').on('click', 'a#attendedSchedule, a#attendedScheduleFromModal', function(e) {
            e.preventDefault();

            var parentEl, scheduleid,
                anchor = $(this), modalid,
                attendedScheduleHook = $('#schedulesTab table#attendedScheduleTable').children('tbody:first');

            if (anchor.attr('id') === 'attendedSchedule') {
                parentEl = anchor.parents('tr');
                scheduleid = parentEl.attr('data-schedule-id');

                console.log('marking done...');

            } else {
                modalid = anchor.parents('.modal').attr('id');
                scheduleid = modalid.split('_').pop();
                parentEl = $('#schedulesTab').find('tr[data-schedule-id="' + scheduleid + '"]');

                console.log('marking done from modal...');

            }


            console.log(parentEl);


            $.post("classes/schedule_class.php", {
                    "action" : "MarkAttendedSchedule",
                    "scheduleid" : scheduleid
                }, function (result) {

                    console.log(result);
                    console.log(typeof result);
                if (result === true) {

                    console.log(attendedScheduleHook);

                    //Add .new-class for the animation
                    //replace the done link with udone
                    //Prepend to attended schedule
                    //Remove the schedule from the pending schedules' table.
                    parentEl.addClass('new-class');
                    parentEl.find('a#attendedSchedule').replaceWith('<a class="btn-icon" id="unmarkdoneSchedule" href="#!"><i class="material-icons">undo</i></a>');
                    console.log(parentEl[0].outerHTML);

                    attendedScheduleHook.prepend(parentEl[0].outerHTML);

                    parentEl.remove();

                    if ($('#schedulesTab table#pendingScheduleTable').children('tbody').length === 1 && $('#schedulesTab table#pendingScheduleTable').children('tbody:first').find('tr').length === 0) {
                        //If there're no schedules, append a dummy;
                        $('#schedulesTab table#pendingScheduleTable').children('tbody:first').prepend("<tr><td>There's no pending schedule</td><td>--</td><td>--</td><td>--</td></tr>");
                        $('#schedulesTab table#pendingScheduleTable').children('tbody:first').attr('data-tbody-number', 'noData');

                    }


                    if (modalid !== '') {

                        $('#' + modalid).closeModal();

                        cleanOutModals();

                    }

                    //Forward update for attended table
                    //Backward update for pending table
                    updatePagination('#schedulesTab table#attendedScheduleTable', 'tbody', '#attendedScheduleTable', 6, 'forward');
                    updatePagination('#schedulesTab table#pendingScheduleTable', 'tbody', '#pendingScheduleTable', 6, 'backward');

                }
            }, 'json');
        });
    };

    var unmarkAttendedSchedule = function () {

        $('main').on('click', 'a#unmarkdoneSchedule', function(e) {
            e.preventDefault();

            var parentEl = $(this).parents('tr'),
                scheduleid = parentEl.attr('data-schedule-id'),
                pendingScheduleHook = $('#schedulesTab table#pendingScheduleTable').children('tbody:first').find('tr:first');

            console.log(scheduleid);

            console.log('unmarking done...');

            $.post("classes/schedule_class.php", {
                    "action" : "UnmarkAttendedSchedule",
                    "scheduleid" : scheduleid
                }, function (result) {

                    console.log(result);
                    console.log(typeof result);
                if (result === true) {

                    console.log(pendingScheduleHook);
                    console.log(parentEl);

                    parentEl.addClass('new-class');
                    parentEl.find('a#unmarkdoneSchedule').replaceWith('<a class="btn-icon" id="attendedSchedule" href="#!"><i class="material-icons">done</i></a>');

                    pendingScheduleHook.before(parentEl[0].outerHTML);

                    parentEl.remove();

                    //Forward update for pending table
                    //Backward update for attended table
                    updatePagination('#schedulesTab table#attendedScheduleTable', 'tbody', '#attendedScheduleTable', 6, 'backward');
                    updatePagination('#schedulesTab table#pendingScheduleTable', 'tbody', '#pendingScheduleTable', 6, 'forward');

                }
            }, 'json');
        });
    };

    var openSchedule = function () {

        $('main').on('click', 'a#openSchedule', function(e) {
            e.preventDefault();

            console.log('opening...');
            var scheduleid = $(this).parents('tr').attr('data-schedule-id'),
                hook = $(this),
                tableid = hook.parents('table').attr('id'),
                prev = true, next  = true;

            //variables for the modal

//            Ajax
            $.get("handlers/db_info.php", {"action": "ScheduleExists", "schedule_id" : scheduleid }, function (data) {

                console.log(data);

                data.due_date_formatted = moment(data.due_date).fromNow();

                var body = Lists_Templates.scheduleInfo(data);

                console.log(scheduleid);
                console.log(hook.parents('table').children('tbody:first').find('tr:first').attr('data-schedule-id'));

                $('.modal#viewScheduleInfo_' + data.schedule_id + ' .modal-content').append(body);

            }, 'json');

            if (scheduleid === hook.parents('table#' + tableid).children('tbody:first').find('tr:first').attr('data-schedule-id')) {
                prev = false;
            }
            if (scheduleid === hook.parents('table#' + tableid).children('tbody:last').find('tr:last').attr('data-schedule-id')) {
                next = false;
            }

            var template = {

                modalId: 'viewScheduleInfo_' + scheduleid,
                templateHeader: 'Schedule Info',
                templateBody: '',
                extraActions: Lists_Templates.infoExtraFooterActions({
                    "Delete" : true,
                    "Previous" : prev,
                    "Next" : next,
                })
            };

//            load the modal in the DOM
            $('main').append(Lists_Templates.modalTemplate(template));

            $(this).attr('data-target', template.modalId);

            $('#' + template.modalId).openModal({dismissible:false});

        });
    };
        
    var nextPrevSchedule = function () {
        /*
        *   Function for the next/previous schedule button in the modal.
        *
        *   On click, the current schedule is gotten from the modal id
        *   It is then used to find the current Element
        *   Next/Previous element's schedule id is gotten.
        *
        *   Ajax get by found id.
        *
        *   If the result is null, it means the element was the first/last element
        *       among the tr list, thus modal closes.
        *   But to have a smarter modal, and prevent the sudden unexplained modal
        *       closing, the next/previous element's id is used to see if its
        *       next/previous element exists (for the next/previous tbody if exists).
        *       If not, the next/previous button in the modal is disabled.
        */

        var scheduleid, array,
            previoustbody, nexttbody,
            previousattribute, nextattribute,
            previousattributeid, nextattributeid,
            modalid, tableid;

        $('main').on('click', 'a#moreScheduleCardPrevious.disabled, a#moreScheduleCardNext.disabled', function(e) {
            e.preventDefault();

            console.log('disabled button. Do nothing');

        });

        $('main').on('click', 'a#moreScheduleCardPrevious:not(.disabled)', function(e) {
            e.preventDefault();

            modalid = $(this).parents('.modal').attr('id');
            tableid = $('#schedulesTab').find('tr[data-schedule-id="' + scheduleid + '"]').parents('table').attr('id');
            scheduleid = modalid.split('_').pop();

            console.log('fetching previous schedule...');

            console.log('current schedule id: ' + scheduleid);

            previousattribute = $('#schedulesTab').find('tr[data-schedule-id="' + scheduleid + '"]');

            // $( "tr" ).index( listItems );

            previousattribute = previousattribute[0].previousElementSibling;

            if (previousattribute !== null) {

                previousattributeid = previousattribute.attributes[0].nodeValue;

                console.log('previous schedule id: ' + previousattributeid);

                //Ajax get previous
                $.get("handlers/db_info.php", {"action": "ScheduleExists", "schedule_id" : previousattributeid }, function (data) {

                    console.log(data);

                    data.due_date_formatted = moment(data.due_date).fromNow();

                    var body = Lists_Templates.scheduleInfo(data);

                    //append the body to the modal
                    $('.modal#' + modalid).find('.scheduledata').fadeOut(300, function() {
                        $(this).html(body);

                        $(this).fadeIn();

                    });

                    //If there's a previous schedule. If not, disable the button
                    previousattribute = $('#schedulesTab').find('tr[data-schedule-id="' + previousattributeid + '"]');
                    previousattribute = previousattribute[0].previousElementSibling;

                    if (previousattributeid === $('table#' + tableid).children('tbody:first').find('tr:first').attr('data-schedule-id')) {
                        $('.modal#' + modalid).find('a#moreScheduleCardPrevious').addClass('disabled');

                    }

                    //If the next button was disabled, enable it.
                    if ($('.modal#' + modalid).find('a#moreScheduleCardNext').hasClass('disabled')) {
                        $('.modal#' + modalid).find('a#moreScheduleCardNext').removeClass('disabled');
                    }

                    //Update the modal id
                    $('.modal#' + modalid).attr('id', 'viewScheduleInfo_' + previousattributeid);

                }, 'json');

            } else {

                if (scheduleid !== $('table#' + tableid).children('tbody:first').find('tr:first').attr('data-schedule-id')) {
                    //Get the previous schedule id in the previous tbody

                    previoustbody = $('#schedulesTab').find('tr[data-schedule-id="' + scheduleid + '"]').parent('tbody');

                    previoustbody = previoustbody[0].previousElementSibling;

                    previousattributeid = previoustbody.lastElementChild.attributes[0].nodeValue;

                    console.log(previousattributeid);

                    //Ajax get previous
                    $.get("handlers/db_info.php", {"action": "ScheduleExists", "schedule_id" : previousattributeid }, function (data) {

                        console.log(data);

                        data.due_date_formatted = moment(data.due_date).fromNow();

                        var body = Lists_Templates.scheduleInfo(data);

                        //append the body to the modal
                        $('.modal#' + modalid).find('.scheduledata').fadeOut(300, function() {
                            $(this).html(body);

                            $(this).fadeIn();

                        });

                        //If there's a previous schedule. If not, disable the button
                        previousattribute = $('#schedulesTab').find('tr[data-schedule-id="' + previousattributeid + '"]');
                        previousattribute = previousattribute[0].previousElementSibling;

                        if (previousattributeid === $('table#' + tableid).children('tbody:first').find('tr:first').attr('data-schedule-id')) {
                            $('.modal#' + modalid).find('a#moreScheduleCardPrevious').addClass('disabled');
                            $('.modal#' + modalid).find('a#moreScheduleCardPrevious').addClass('transparent');

                        }

                        //If the next button was disabled, enable it.
                        if ($('.modal#' + modalid).find('a#moreScheduleCardNext').hasClass('disabled')) {
                            $('.modal#' + modalid).find('a#moreScheduleCardNext').removeClass('disabled');
                            $('.modal#' + modalid).find('a#moreScheduleCardNext').removeClass('transparent');
                        }

                        //Update the modal id
                        $('.modal#' + modalid).attr('id', 'viewScheduleInfo_' + previousattributeid);

                    }, 'json');

                } else {

                    $('#' + modalid).closeModal();

                    cleanOutModals();

                    console.log(previousattributeid);
                }
            }
        });

        $('main').on('click', 'a#moreScheduleCardNext:not(.disabled)', function(e) {
            e.preventDefault();

            console.log('fetching next schedule...');

            modalid = $(this).parents('.modal').attr('id');
            tableid = $('#schedulesTab').find('tr[data-schedule-id="' + scheduleid + '"]').parents('table').attr('id');
            scheduleid = modalid.split('_').pop();

            console.log('current schedule id: ' + scheduleid);

            nextattribute = $('#schedulesTab').find('tr[data-schedule-id="' + scheduleid + '"]');

            // $( "tr" ).index( listItems );

            nextattribute = nextattribute[0].nextElementSibling;

            if (nextattribute !== null) {

                nextattributeid = nextattribute.attributes[0].nodeValue;

                console.log('next schedule id: ' + nextattributeid);

                //Ajax get next
                $.get("handlers/db_info.php", {"action": "ScheduleExists", "schedule_id" : nextattributeid }, function (data) {

                    console.log(data);

                    data.due_date_formatted = moment(data.due_date).fromNow();

                    var body = Lists_Templates.scheduleInfo(data);

                    //append the body to the modal
                    $('.modal#' + modalid).find('.scheduledata').fadeOut(300, function() {
                        $(this).html(body);

                        $(this).fadeIn();

                    });

                    //If there's a next schedule. If not, disable the button
                    nextattribute = $('#schedulesTab').find('tr[data-schedule-id="' + nextattributeid + '"]');
                    nextattribute = nextattribute[0].nextElementSibling;

                    if (nextattributeid === $('table#' + tableid).children('tbody:last').find('tr:last').attr('data-schedule-id')) {
                        $('.modal#' + modalid).find('a#moreScheduleCardNext').addClass('disabled');
                        $('.modal#' + modalid).find('a#moreScheduleCardNext').addClass('transparent');

                    }

                    //If the previous button was disabled, enable it.
                    if ($('.modal#' + modalid).find('a#moreScheduleCardPrevious').hasClass('disabled')) {
                        $('.modal#' + modalid).find('a#moreScheduleCardPrevious').removeClass('disabled');
                        $('.modal#' + modalid).find('a#moreScheduleCardPrevious').removeClass('transparent');
                    }

                    //Update the modal id
                    $('.modal#' + modalid).attr('id', 'viewScheduleInfo_' + nextattributeid);

                }, 'json');

            } else {

                //Find if its the last tr in the table
                if (scheduleid !== $('table#' + tableid).children('tbody:last').find('tr:last').attr('data-schedule-id')) {
                    //Get next schedule id in the next tbody;

                    nexttbody = $('#schedulesTab').find('tr[data-schedule-id="' + scheduleid + '"]').parent('tbody');

                    nexttbody = nexttbody[0].nextElementSibling;

                    nextattributeid = nexttbody.firstElementChild.attributes[0].nodeValue;

                    console.log(nextattributeid);

                    //Ajax get next
                    $.get("handlers/db_info.php", {"action": "ScheduleExists", "schedule_id" : nextattributeid }, function (data) {

                        console.log(data);

                        data.due_date_formatted = moment(data.due_date).fromNow();

                        var body = Lists_Templates.scheduleInfo(data);

                        //append the body to the modal
                        $('.modal#' + modalid).find('.scheduledata').fadeOut(300, function() {
                            $(this).html(body);

                            $(this).fadeIn();

                        });

                        //If there's a next schedule. If not, disable the button
                        nextattribute = $('#schedulesTab').find('tr[data-schedule-id="' + nextattributeid + '"]');
                        nextattribute = nextattribute[0].nextElementSibling;

                        if (nextattributeid === $('table#' + tableid).children('tbody:last').find('tr:last').attr('data-schedule-id')) {
                            $('.modal#' + modalid).find('a#moreScheduleCardNext').addClass('disabled');
                            $('.modal#' + modalid).find('a#moreScheduleCardNext').addClass('transparent');

                        }

                        //If the previous button was disabled, enable it.
                        if ($('.modal#' + modalid).find('a#moreScheduleCardPrevious').hasClass('disabled')) {
                            $('.modal#' + modalid).find('a#moreScheduleCardPrevious').removeClass('disabled');
                            $('.modal#' + modalid).find('a#moreScheduleCardPrevious').removeClass('transparent');
                        }

                        //Update the modal id
                        $('.modal#' + modalid).attr('id', 'viewScheduleInfo_' + nextattributeid);

                    }, 'json');

                } else {

                    $('#' + modalid).closeModal();

                    console.log(nextattributeid);

                    cleanOutModals();
                }
            }
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
