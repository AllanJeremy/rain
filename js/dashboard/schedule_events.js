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
        submitSchedule();       //60%
        editSchedule();
        deleteSchedule();
        markDone();
        markAllDone();
        overdueScheduleReminder();
        getPendingSchedules();
        archiveSchedule();
        addObjective();         //done
        removeObjective();      //done
        updateClassroomDropdownExtraInfo();     //done
        updateSubTopicDropdown();               //done
        addObjectiveFromSubtopics();            //done
        
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

                            $('#scheduleCreateFormContainer input#schedule_title').val('');
                            $('#scheduleCreateFormContainer select#schedule_classroom').val('');
                            $('#scheduleCreateFormContainer #descriptionTextarea').val('');
                            $('#scheduleCreateFormContainer ul#objectivesList').children('li').remove();
                            $('#scheduleCreateFormContainer input#scheduleDate').val('');
                            $('#scheduleCreateFormContainer input#scheduleTime').val('');
                            $('#scheduleCreateFormContainer .date-picker-container').find('input[type=hidden]').val('');
                            $('#scheduleCreateFormContainer .time-picker-container').find('input[type=hidden]').val('');

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
                                "scheduleid": data.schedule_id
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

                                updatePagination('#schedulesTab table#pendingScheduleTable > tbody:first', '#pendingScheduleTable');

                            }

                        }, 'json');
                        
                    } else {
                        
                        console.log(typeof result);
                        
                    }
                    
                }, 'json');
                
            }

        });
        
    };

    var updatePagination = function (str1, str2) {

        var hook = str1,
            tableid = str2;

        
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
        
    };
        
    var markAllDone = function () {
        
    };
        
    var overdueScheduleReminder = function () {
        
    };
        
    var getPendingSchedules = function () {
        
    };
        
    var archiveSchedule = function () {
        
    };
    
    this.__construct();

};
