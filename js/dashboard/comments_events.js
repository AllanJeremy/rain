/*global $, jQuery, alert, console*/

var CommentsEvents = function (userInfo) {
    'use strict';
    //--------------

    this.__construct = function (userInfo) {
        console.log('comments events created');
        console.log(userInfo);

        //comments inits
        getComments([userInfo.user_id, userInfo.account_type, userInfo.full_name]);
        addComment([userInfo.user_id, userInfo.account_type]);
        editComment([userInfo.user_id, userInfo.account_type]);
    };
    /*
    **
    **  NOTE
        Every comment has a column for referencing what info the comment is about.
        :call it $_Q

        -   schedules
        -   assignment
        -   ass_submission
    */

    var getComments = function (user) {
        console.log(user);

        /*
        *
        *   DATA NEEDED
            -   action
                1. get from the links `data_root_hook` attr.

            -   $_Q
                1. needs a switch for the action variable
                   to get it from different html design structures
        *
        *
        */

        $('main').on('click', 'a.js-get-comments', function (e) {
            e.preventDefault();


            var $this = $(this),
                action = $(this).attr('data-root-hook'),
                $_Q = '',
                comment_type = action,
                title = '',
                call = '',
                modal_id = 'modal_comments_' + action, //modal id
                data = [{ //Example of how I hope the array is gotten from the ajax
                    'comment_id':5,
                    'id':5,//Schedule id
                    'comment_text':'ah weh!',
                    'poster_name':'Gabriel Muchiri',
                    'poster_link':'accType=null&id=null',
                    'date': 'Yesterday'
                }],
                modal_body = '',
                extrainfo = '',
                self = false,
                comment_enabled = true; //bool /ajax

            console.log('opening ' + action + ' comment modal');

            switch (action) {
                case 'schedule':
                    $_Q = $this.parents('tr').attr('data-schedule-id');
                    title = $this.parents('tr').find('td.js-schedule-title')[0].innerText; //title of the schedule/assignment...
                    call = 'GetScheduleComments';

                    break;
                case 'assignment':
                    $_Q = $this.parents('.card-col').attr('data-assignment-id');
                    title = $this.parents('.card-col').find('span.card-title')[0].innerText;
                    call = 'GetAssComments';

                    break;
                case 'ass_submission':
                    $_Q = $this.parent('.comment').attr('data-submission-id');
                    title = $this.parents('li.js-assignment-collapsible').find('.collapsible-header span')[0].innerText;
                    call = 'GetAssSubmissionComments';
                    console.log($this.parents('.ass-submission-item'));
                    console.log($this.parents('.ass-submission-item').find('.student-name'));
                    extrainfo = '- ' + $this.parents('.ass-submission-item').find('.student-name')[0].innerText.split('|')[0];
                    break;
                default:
                    break;
            }

            Modals_Events.loadCommentModal(modal_id, $_Q, comment_type, title, modal_body, comment_enabled, extrainfo);

            $('.modal#' + modal_id).openModal({dismissible: false});
            console.log('opening modal clicked');

            $.get('handlers/db_info.php', {'action' : call, 'id' : parseInt($_Q)}, function (resultData) {
                console.log(resultData);

                if (resultData === false) {//No comments found
                        modal_body += '';

                } else {

                    for(var i = 0; i < resultData['comments'].length; i++) {

                        if (user[0] === resultData['comments'][i]['poster_id'] && user[1] === resultData['comments'][i]['poster_type']) {
                            self = true;

                            resultData['comments'][i]['poster_name'] = 'You';
                            resultData['comments'][i]['poster_link'] = 'javascript:void(0)';
                        }

                        console.log(resultData['comments'][i]);

                            modal_body += Lists_Templates.commentList(resultData['comments'][i], self);

                        if (i === 8) {

                            $('.modal#' + modal_id).children('.modal-content').append(Lists_Templates.commentList(resultData['comments'][i], self));
                            modal_body = '';
                        }
                    }

                    $('.modal#' + modal_id).children('.modal-content').append(modal_body);

                }

                console.log(modal_body);

            }, 'json');

            return(false);
        });

    };

    var addComment = function (user) {

        $('main').on('click', '.js-add-comment', function (e) {
            e.preventDefault();

            var $this = $(this),
                action = $this.attr('data-root-hook'),
                call = '',
                modalId = $this.parents('.modal').attr('id'),
                id = $this.parents('.input-field.comment').attr('data-id'), //id of the comment
                comment = $this.parents('.input-field.comment').find('input.js-comment-bar').val(),
                date,
                tempCommentId = '__' + id + '_' + Materialize.guid(),//two dashes means it is a temporary value added. The next value is the schedule\assignment id.
                commentEl,
                commentfail_errormessage = 'Commenting failed',
                commentsListHook = $('.modal#' + modalId).find('.modal-content');

            console.log(id, comment + ', , ' + action);
            if (comment === '') {
                console.log('empty input');
                return;
            }

            switch (action) {
                case 'schedule':
                    call = user[1] + 'CommentOnSchedule';
                    break;
                case 'assignment':
                    call = user[1] + 'CommentOnAss';
                    break;
                case 'ass_submission':

                    call = user[1] + 'CommentOnAssSubmission';
                    break;
                default:
                    break;
            }

            call = call.charAt(0).toUpperCase() + call.slice(1);

            console.log(call);

            $.post('handlers/comment_handler.php', {'action' : call, 'id' : id, 'comment' : comment}, function (resultData) {
                console.log(resultData);
                if(resultData) { //if it returns true
                    date = moment().fromNow();

                    var commentData = {
                        'comment_id':tempCommentId,
                        'comment_text':comment,
                        'poster_name':'You',
                        'poster_link':"javascript:void(0)",
                        'date':date
                    };

                    commentEl = Lists_Templates.commentList(commentData, true);

                    console.log(commentsListHook.outerHeight(true));
                    //Scroll to the bottom of the div to see the latest comment posted
                    commentsListHook.animate({
                        scrollTop : commentsListHook.outerHeight(true)
                    }, 300);

                    commentsListHook.append(commentEl);

                } else {
                    Materialize.toast(commentfail_errormessage, 5000, '', function () {
                        console.log('toast on schedule commenting');
                    });
                }
            }, 'json');

            return(false);

        });

    };

    var editComment = function (user) {

        /*
        *
        *   Contains three events
                -   edit (for the edit button)
                -   cancel edit event
                -   submit editted comments
                -   delete comment event
        *
        */

        var currComment, currCommentId, commenttype;

        $('main').on('click', 'a.js-edit-comment', function (e) {
            e.preventDefault();

            if ($(this).hasClass('disabled')) {
                console.log('disabled');

                return (false);
            }

            console.log($(this));
            var $El = $(this).parents('.comment-item'),

                commentid = $El.attr('data-comment-id'),
                modalId = $(this).parents('.modal').attr('id'),
                buttonhook = $El.parents('.modal').find('.js-add-comment'),
                texthook = $El.find('.js-comment'),
                currText = texthook[0].innerHTML,
                commentbar = $El.parents('.modal').find('input.js-comment-bar');

            currComment = currText;
            currCommentId = commentid;
            commenttype = buttonhook.attr('data-root-hook');

            commentbar.val(currText);
            texthook.addClass('z-depth-3 pad-8')[0].innerHTML = commentbar.val() + Lists_Templates.cancelCommentEdit();
            buttonhook.addClass('js-update-comment').removeClass('js-add-comment')[0].innerHTML = 'edit';

            $El.find('a.js-edit-comment').addClass('active');
            $('.modal#' + modalId).find('a.js-edit-comment:not(.active)').addClass('disabled');

            return (false);
        });

        $('main').on('click', 'a.js-cancel-edit-comment', function (e) {
            e.preventDefault();

            if (currComment === '') {
                return (false);
            }

            var $El = $(this),
                modalId = $(this).parents('.modal').attr('id'),
                buttonhook = $El.parents('.modal').find('.js-update-comment'),
                texthook = $El.parent('.js-comment'),
                commentbar = $El.parents('.modal').find('input.js-comment-bar');

            commentbar.val('');
            texthook.removeClass('z-depth-3 pad-8')[0].innerHTML = currComment;
            buttonhook.removeClass('js-update-comment').addClass('js-add-comment')[0].innerHTML = 'comment';

            $('.modal#' + modalId).find('a.js-edit-comment').removeClass('disabled active');

            return (false);
        });

        $('main').on('click', 'a.js-update-comment', function (e) {
            e.preventDefault();

            var $this = $(this),
                modalId = $this.parents('.modal').attr('id'),
                call = '',
                commentfail_errormessage = 'Commenting failed',
                commenttext = $this.parents('.input-field.comment').find('input.js-comment-bar').val(),
                commentsListHook = $('.modal#' + modalId).find('.modal-content'),
                commentEl = $('.modal#' + modalId + ' .modal-content').find('.comment-item[data-comment-id=' + currCommentId + ']');

            console.log('here 2');
            console.log(currCommentId, commenttext);

            if (commenttext === '') {
                return (false);
            }

            switch (commenttype) {
                case 'schedule':
                    call = 'UpdateScheduleComment';
                    break;
                case 'assignment':
                    call = 'UpdateAssComment';
                    break;
                case 'ass_submission':

                    call = 'UpdateAssSubmissionComment';
                    break;
                default:
                    break;
            }

            call = call.charAt(0).toUpperCase() + call.slice(1);

            console.log(call);

            $.post('handlers/db_handler.php', {"action": call, 'id': currCommentId, 'comment_text': commenttext}, function (returnData) {
                console.log(returnData);

                if(returnData === true) {
                    $('.modal#' + modalId).find('a.js-edit-comment').removeClass('disabled active');

                    commentEl.find('p.js-comment').removeClass('z-depth-3 pad-8')[0].innerHTML = commenttext;
                    $this.parents('.input-field.comment').find('input.js-comment-bar').val('');
                    $this.removeClass('js-update-comment').addClass('js-add-comment')[0].innerHTML = 'comment';
                }

            }, 'json');


            return (false);
        });

        $('main').on('click', 'a.js-delete-comment', function (e) {
            e.preventDefault();

            var $this = $(this),
                commenttype = $this.parents('.modal').find('.js-add-comment').attr('data-root-hook'),
                call = '',
                commentElHook = $this.parents('.comment-item'),
                commentId = commentElHook.attr('data-comment-id');

            switch (commenttype) {
                case 'schedule':
                    call = 'DeleteScheduleComment';
                    break;
                case 'assignment':
                    call = 'DeleteAssComment';
                    break;
                case 'ass_submission':

                    call = 'DeleteAssSubmissionComment';
                    break;
                default:
                    break;
            }

            call = call.charAt(0).toUpperCase() + call.slice(1);

            console.log(call);

            commentElHook.removeClass('new-class').addClass('to-remove');

            $.post('handlers/db_handler.php', {'action' : call, 'id' : commentId}, function (resultData) {
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

    this.__construct(userInfo);

};
