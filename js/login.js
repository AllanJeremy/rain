/*jslint browser: true*/
/*global $, jQuery, alert, console*/


function removeErrors() {
    'use strict';
    $('#invalid_student_login').addClass('hide');
    $('#invalid_admin_login').addClass('hide');
    $('.errorMessage').remove();
    $('.successMessage').remove();
}

function validateInputs(type) {
    'use strict';
    var emptyTextInputs, emptyPasswordInputs, emptyUsername, emptyPassword;
    
    removeErrors();
    
    emptyTextInputs = $('#' + type + 'Form input[type=text]').filter(function () {
        return this.value === "";
    });
    emptyPasswordInputs = $('#' + type + 'Form input[type=password]').filter(function () {
        return this.value === "";
    });

    if (emptyTextInputs.length > 0) {
        
        emptyUsername = 'username ';
        emptyPassword = '';
        
        if (emptyPasswordInputs.length > 0) {
            
            emptyPassword = 'and password are ';
            $('#invalid_' + type + '_login').text('The ' + emptyUsername + emptyPassword + 'empty.');
            
            return false;
        } else {
            
            $('#invalid_' + type + '_login').text('The ' + emptyUsername + emptyPassword + 'is empty.');
            
            return false;
        }
        
    } else {
        
        emptyUsername = ' ';
        
        if (emptyPasswordInputs.length > 0) {
            
            emptyPassword = 'password ';
            $('#invalid_' + type + '_login').text('The ' + emptyUsername + emptyPassword + 'is empty.');
            
            return false;
        } else {
            
            return true;
        }
        
    }
    
}

function studentLogin() {
    'use strict';
    var validated, username, password, type, errorMessage, loginData, btnEl;
    
    btnEl = $('#studentForm button')[0].innerHTML;
    type = 'student';
    username = $('#studentForm input#studentUsername').val();
    password = $('#studentForm input#studentPassword').val();
    
    loginData = {
        student_username: username,
        student_password: password
    };
    
    validated = validateInputs(type);//returns false if there are empty fields and true if all fields are populated
    
    console.log(username + '-' + password + '-' + validated);
    
    if (validated === true) {
        //ajax
        errorMessage = 'Invalid username or password.';
        
        $.ajax({
            
            type: 'POST',
            url: 'handlers/login_handler.php', //this should be url to your PHP file
            dataType: 'text',
            data: loginData,
            beforeSend: function () {
                console.log('sending');
                $('#studentForm button')
                    .text('verifying...')
                    .addClass('op-4 disabled btn-loading');
            },
            complete: function () {
                
            },
            success: function (str) {
                //success message
//                console.log('success-' + str);
                
                if (str.length === 1) {
                    //redirect
                    $('#adminForm button').removeAttr('onclick');//preventing bastards who would switch tabs fast to login as admin
                    
                    $('#studentForm button')[0].innerHTML = btnEl;//preventing bastards who would switch tabs fast to login as admin
                    $('#studentForm button').removeClass('op-4 disabled btn-loading');//preventing bastards who would switch tabs fast to login as admin
                    $('#studentForm')
                        .prepend('<div class="new-class card-panel success green white-text text-lighten-2">Success!<br>Logging you in in a few...</div>')
                        .fadeIn(230);
                    
                    window.setTimeout(function () {
                        location.reload();//Better option since a user won't define what page should load
                    }, 2000);
                    
                    window.setTimeout(function () {
                        $('.success').fadeOut(900);
                    }, 1100);
                    
                } else {
                    $('#studentForm').prepend(str);
                    $('#studentForm button')[0].innerHTML = btnEl;
                    $('#studentForm button').removeClass('op-4 disabled btn-loading');
                }
                
            },
            error: function (str) {
                //error messages
                console.log('error-' + str);
                $('#studentForm button')[0].innerHTML = btnEl;
                $('#studentForm button').removeClass('op-4 disabled btn-loading');
                $('#studentForm').prepend(str);
            }
        });
        
    } else {//Error
        $('#invalid_student_login').removeClass('hide');
    }
    
}

function adminLogin() {
    'use strict';
    var validated, accountType, username, btnEl, password, type, errorMessage, loginData;
    
    type = 'admin';
    btnEl = $('#adminForm button')[0].innerHTML;
    
    accountType = $('#adminForm select').val();
    username = $('#adminForm input#staffUsername').val();
    password = $('#adminForm input#staffPassword').val();
    
    loginData = {
        staff_acc_type: accountType,
        staff_username: username,
        staff_password: password
    };
    
    validated = validateInputs(type);//returns false if there are empty fields and true if all fields are populated
    
    console.log(username + '-' + password + '-' + accountType + '-' + validated);
    
    if (validated === true) {
        //ajax
        errorMessage = 'Invalid username or password or you chose the wrong usertype.';
        
        $.ajax({
            
            type: 'POST',
            url: 'handlers/login_handler.php', //this should be url to your PHP file
            dataType: 'text',
            data: loginData,
            beforeSend: function () {
                console.log('sending');
                $('#adminForm button')
                    .text('verifying...')
                    .addClass('op-4 disabled btn-loading');
            },
            complete: function () {
                
            },
            success: function (str) {
                //success message
                //console.log('success-' + str);
                
                if (str.length === 1) {
                    //redirect
                    $('#studentForm button').removeAttr('onclick')//preventing bastards who would switch tabs fast to login as a student
                    
                    $('#adminForm button')[0].innerHTML = btnEl;
                    $('#adminForm button').removeClass('op-4 disabled btn-loading');
                    $('#adminForm')
                        .prepend('<div class="new-class card-panel success green white-text text-lighten-2">Success!<br>Logging you in in a few...</div>')
                        .fadeIn(230);
                    
                    window.setTimeout(function () {
                        location.reload();//Better option since a user won't define what page should load
                    }, 2000);
                    
                    window.setTimeout(function () {
                        $('.success').fadeOut(900);
                    }, 1100);
                    
                } else {
                    $('#adminForm').prepend(str);
                    $('#adminForm button')[0].innerHTML = btnEl;
                    $('#adminForm button').removeClass('op-4 disabled btn-loading');
                }
            },
            error: function (str) {
                //error messages
                console.log('error-' + str);
                
                $('#adminForm').prepend(str);
                $('#adminForm button')[0].innerHTML = btnEl;
                $('#adminForm button').removeClass('op-4 disabled btn-loading');

            }
        });
        
    } else {//error
        $('#invalid_admin_login').removeClass('hide');
    }
}
