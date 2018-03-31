/*global $, jQuery, alert, console*/

var CommentsEvents = function (userInfo) {
    'use strict';
    //--------------

    this.__construct = function (userInfo) {
        console.log('comments events created');
        console.log(userInfo);

        //comments inits
        getComments([userInfo.user_id, userInfo.account_type, userInfo.full_name]);
        chatBoxUI([userInfo.user_id, userInfo.account_type, userInfo.full_name]);
        addComment([userInfo.user_id, userInfo.account_type]);
//        editComment([userInfo.user_id, userInfo.account_type]);
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

    var chatBoxUI = function (user) {
        
        console.log('chatbox');
        
        $('main').on('click', '.js-open-comment-bar', function (e) {
            e.preventDefault();
            
            var $this = $(this),
                commentbar = $('.chatbox-container:not(.hide)'),
                $thisref = $this.attr('data-chat-ref'),
                $thisuserid = $this.attr('data-chat-userid'),
                chatboxId = 'chatRef_' + _.camelCase($thisref) + '_' + $thisuserid;
            
            console.log(chatboxId);
            console.log(commentbar.length);
            //if it exists in the DOM
            if($('.chatbox-container#' + chatboxId).length > 0) {
                ChatBoxUiState($('.chatbox-container#' + chatboxId), 'full-open');
            
            } else {
                var chatboxData = {
                    id : chatboxId,
                    uistate : '',
                    usertype : '',
                    userid : $thisuserid,
                    username : '',
                    chatsectionref : $thisref,
                    chatref : $thisref,
                    chats : '',
                },
                    chatboxEl = Lists_Templates.chatBoxBar(chatboxData);
                
                $('main').append(chatboxEl);
                console.log(commentbar.length);
                if(commentbar.length == 1 && $(window).width() > 912) {
                    $('.chatbox-container#' + chatboxId).css('right', '456px');
                    
                } else if(commentbar.length == 1 && $(window).width() < 912) {
                    ChatBoxUiState($('.chatbox-container:not(#' + chatboxId + ')'));
                }
                
                ChatBoxUiState($('.chatbox-container#' + chatboxId), 'full-open');
            }
        });
        
        $('main').on('click', '.js-close-chatbox', function (e) {
            e.preventDefault();
            var $this = $(this),
                chatboxId = $this.parents('.chatbox-container').attr('id'),
                chatboxEl = $('.chatbox-container#' + chatboxId);
            
            ChatBoxUiState(chatboxEl, 'close');
        });
        
        $('main').on('click', '.box-header', function (e) {
            e.preventDefault();
            var $this = $(this),
                chatboxId = $this.parents('.chatbox-container').attr('id'),
                chatboxEl = $('.chatbox-container#' + chatboxId);
            
            ChatBoxUiState(chatboxEl, 'full-open');
        });
        
        function ChatBoxUiState(El, action) {
            var chatboxUIState = {
                    open : El.hasClass('open'),
                    fullOpen : El.hasClass('full-open'),
                    active : El.hasClass('active')
                };
            
            switch (action) {
                case 'full-open':
                    //if open
                    if(chatboxUIState.open) {
                        El.removeClass('open').addClass('full-open active');
                        
                        //if not open at all
                    } else if (!chatboxUIState.open && !chatboxUIState.fullOpen) {
                        El.addClass('open');
                        
                        _.delay(function () {
                            El.removeClass('open').addClass('full-open active');
                        }, 340, 'opened');

                        //if full open but not active
                    } else if (chatboxUIState.fullOpen && !chatboxUIState.active) {
                        El.addClass('active');
                    }
                    
                    break;
                case 'open':
                        El.removeClass('full-open active').addClass('open');
                    
                    break;
                case 'close':
                    //if open
                    El.removeClass('active');
                    
                    _.delay(function () {
                        El.removeClass('full-open').addClass('open');
                    }, 340, 'opened');
                    
                    break;
                default:
                    El.removeClass('full-open active');
                    
                    _.delay(function () {
                        El.remove();
                    }, 340, 'removed');
                    
                    console.log(chatboxUIState);
            }
        }
    };
    
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

            console.log($_Q);
            Modals_Events.loadCommentModal(modal_id, $_Q, comment_type, title, modal_body, comment_enabled, extrainfo);

            $('.modal#' + modal_id).openModal({dismissible: false});
            console.log('opening modal clicked');

            $.get('handlers/db_info.php', {'action' : call, 'id' : parseInt($_Q)}, function (resultData) {
                //console.log(resultData);

                if (resultData === false) {//No comments found
                        modal_body += '';

                } else {

                    for(var i = 0; i < resultData['comments'].length; i++) {

                        if (user[0] === resultData['comments'][i]['poster_id'] && user[1] === resultData['comments'][i]['poster_type']) {
                            self = true;

                            resultData['comments'][i]['poster_name'] = 'You';
                            resultData['comments'][i]['poster_link'] = 'javascript:void(0)';
                        }

                        //console.log(resultData['comments'][i]);

                            modal_body += Lists_Templates.commentList(resultData['comments'][i], self);

                        if (i === 8) {

                            $('.modal#' + modal_id).children('.modal-content').append(Lists_Templates.commentList(resultData['comments'][i], self));
                            modal_body = '';
                        }
                    }

                    $('.modal#' + modal_id).children('.modal-content').append(modal_body);

                }

                //console.log(modal_body);

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
                $_Q = $this.parents('.input-field.comment').attr('data-id'), //id of the comment
                comment = $this.parents('.input-field.comment').find('input.js-comment-bar').val(),
                date,
                tempCommentId = '__' + $_Q + '_' + Materialize.guid(),//two dashes means it is a temporary value added. The next value is the schedule\assignment id.
                commentEl,
                commentfail_errormessage = 'Commenting failed',
                commentsListHook = $('.modal#' + modalId).find('.modal-content');

            console.log(modalId);
            console.log($_Q, comment + ', , ' + action);
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

            $.post('handlers/comment_handler.php', {'action' : call, 'id' : parseInt($_Q), 'comment' : comment}, function (resultData) {
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

                    if(modalId !== undefined) {

                        commentEl = Lists_Templates.commentList(commentData, true);

                        console.log(commentsListHook.outerHeight(true));
                        //Scroll to the bottom of the div to see the latest comment posted
                        commentsListHook.animate({
                            scrollTop : commentsListHook.outerHeight(true)
                        }, 300);

                        commentsListHook.append(commentEl);

                    }
                } else {
                    Materialize.toast(commentfail_errormessage, 5000, '', function () {
                        console.log('toast on schedule commenting');
                    });
                }
            }, 'json');

            return(false);

        });

    };

    this.__construct(userInfo);

};
