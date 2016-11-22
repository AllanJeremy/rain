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
    var validated, username, password, type, errorMessage, loginData;
    
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
            },
            complete: function () {
                
            },
            success: function (str) {
                //success message
                //console.log('success-' + str);
                
                if (str.length === 1) {
                    //redirect
                    $('#studentForm').prepend('<div class="card-panel success green white-text text-lighten-2">Success!<br>Logging you in in a few...</div>');
                    
                    $('#adminForm button').removeAttr('onclick');//preventing bastards who would switch tabs fast to login as admin
                    
                    window.setTimeout(function () {
                        location.reload();//Better option since a user won't define what page should load
                    }, 2000);
                    
                    window.setTimeout(function () {
                        $('.success').fadeOut(900);
                    }, 1100);
                    
                } else {
                    $('#studentForm').prepend(str);
                }
                
            },
            error: function (str) {
                //error messages
                console.log('error-' + str);
                
                $('#studentForm').prepend(str);
            }
        });
        
    } else {//Error
        $('#invalid_student_login').removeClass('hide');
    }
    
}

function adminLogin() {
    'use strict';
    var validated, accountType, username, password, type, errorMessage, loginData;
    
    type = 'admin';
    
    
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
            },
            complete: function () {
                
            },
            success: function (str) {
                //success message
                //console.log('success-' + str);
                
                if (str.length === 1) {
                    //redirect
                    $('#adminForm').prepend('<div class="card-panel success green white-text text-lighten-2">Success!<br>Logging you in in a few...</div>');
                    
                    $('#studentForm button').removeAttr('onclick');//preventing bastards who would switch tabs fast to login as a student
                    
                    window.setTimeout(function () {
                        location.reload();//Better option since a user won't define what page should load
                    }, 2000);
                    
                    window.setTimeout(function () {
                        $('.success').fadeOut(900);
                    }, 1100);
                    
                } else {
                    $('#adminForm').prepend(str);
                }
                
                
            },
            error: function (str) {
                //error messages
                console.log('error-' + str);
                
                $('#adminForm').prepend(str);
            }
        });
        
    } else {//error
        $('#invalid_admin_login').removeClass('hide');
    }
}

