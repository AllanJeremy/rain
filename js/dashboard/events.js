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
        
        editScheduleComments();
        deleteScheduleComments();
    };


    var editScheduleComments = function () {

        /*
        *
        *   Contains three events
                -   edit (for the edit button)
                -   cancel edit event
                -   submit editted comments
        *
        */

        var currComment, currCommentId;

        $('main').on('click', 'a.js-edit-schedule-comment, a.js-edit-assignment-comment, a.js-edit-ass_submission-comment', function (e) {
            e.preventDefault();

            if ($(this).hasClass('disabled')) {
                console.log('disabled');

                return (false);
            }

            console.log($(this));
            var $El = $(this).parents('.comment-item'),

                commentid = $El.attr('data-comment-id'),
                modalId = $(this).parents('.modal').attr('id'),
                buttonhook = $El.parents('.modal').find('.js-add-schedule-comment'),
                texthook = $El.find('.js-comment'),
                currText = texthook[0].innerHTML,
                commentbar = $El.parents('.modal').find('input.js-comment-bar');

            currComment = currText;
            currCommentId = commentid;

            commentbar.val(currText);
            texthook.addClass('z-depth-3 pad-8')[0].innerHTML = commentbar.val() + Lists_Templates.cancelCommentEdit();
            buttonhook.addClass('js-update-schedule-comment').removeClass('js-add-schedule-comment')[0].innerHTML = 'edit';

            $El.find('a.js-edit-comment').addClass('active');
            $('.modal#' + modalId).find('a.js-edit-schedule-comment:not(.active)').addClass('disabled');

            return (false);
        });

        $('main').on('click', 'a.js-cancel-edit-schedule-comment, a.js-cancel-edit-assignment-comment, a.js-cancel-edit-ass_submission-comment', function (e) {
            e.preventDefault();

            if (currComment === '') {
                return (false);
            }

            var $El = $(this),
                modalId = $(this).parents('.modal').attr('id'),
                buttonhook = $El.parents('.modal').find('.js-update-schedule-comment'),
                texthook = $El.parent('.js-comment'),
                commentbar = $El.parents('.modal').find('input.js-comment-bar');

            commentbar.val('');
            texthook.removeClass('z-depth-3 pad-8')[0].innerHTML = currComment;
            buttonhook.removeClass('js-update-schedule-comment').addClass('js-add-schedule-comment')[0].innerHTML = 'comment';

            $('.modal#' + modalId).find('a.js-edit-schedule-comment').removeClass('disabled active');

            return (false);
        });

        $('main').on('click', 'a.js-update-schedule-comment', function (e) {
            e.preventDefault();

            var $this = $(this),
                modalId = $this.parents('.modal').attr('id'),
                commentfail_errormessage = 'Commenting failed',
                commenttext = $this.parents('.input-field.comment').find('input.js-comment-bar').val(),
                commentsListHook = $('.modal#' + modalId).find('.modal-content'),
                commentEl = $('.modal#' + modalId + ' .modal-content').find('.comment-item[data-comment-id=' + currCommentId + ']');

            console.log('here 2');
            console.log(currCommentId, commenttext);

            if (commenttext === '') {
                return (false);
            }
            $.post('handlers/db_handler.php', {"action": 'UpdateScheduleComment', 'id': currCommentId, 'comment_text': commenttext}, function (returnData) {
                console.log(returnData);

                if(returnData === true) {
                    $('.modal#' + modalId).find('a.js-edit-schedule-comment').removeClass('disabled active');

                    commentEl.find('p.js-comment').removeClass('z-depth-3 pad-8')[0].innerHTML = commenttext;
                    $this.parents('.input-field.comment').find('input.js-comment-bar').val('');
                    $this.removeClass('js-update-schedule-comment').addClass('js-add-schedule-comment')[0].innerHTML = 'comment';
                }

            }, 'json');


            return (false);
        });
    };

    var deleteScheduleComments = function () {
        $('main').on('click', 'a.js-delete-schedule-comment', function (e) {
            e.preventDefault();

            var $this = $(this),
                commentElHook = $this.parents('.comment-item'),
                commentId = commentElHook.attr('data-comment-id');

            commentElHook.removeClass('new-class').addClass('to-remove');

            $.post('handlers/db_handler.php', {'action' : 'DeleteScheduleComment', 'id' : commentId}, function (resultData) {
                console.log(resultData);
                if (resultData === true) {
                    commentElHook.remove();
                } else {
                    commentElHook.removeClass('to-remove').addClass('new-class');

                }
            }, 'json');

            return (false);
        });
    };

    this.__construct();
    
};
