$(document).ready(function (z) {

    var DISABLED_CLASS = "disabled";
    var ACTIVE_CLASS = "active";

    /*Superuser validation*/
    var db_handler_path = "handlers/db_handler.php";
    var db_info_path = "handlers/db_info.php";
    var toast_time = 4000;
    var short_toast_time = 2000;

    //Returns true if empty and false if not empty
    function IsEmpty(value)
    {
        return (value=="" || value==null);
    }

    //Returns true if ajax response is valid (true)
    function IsValidResponse(response)
    {
        return(response=="1" || response=="true");
    }

    //Check if there are any errrors in the var errors ~ return true if there are
    function HasErrors(ajaxResponse)
    {
        try
        {
            var errors = JSON.parse(ajaxResponse);
            return ( ((errors["errors"]).length>0) );  
        }
        catch(e)//If the json could not be parsed, check if the input was blank
        {
            return !(ajaxResponse == "" || IsValidResponse(ajaxResponse));
        }
            
    }

    //Toast all the errors (this is a js array) ~ doesn't have try catch because haserrors is used before using it
    function ToastAjaxErrors(ajaxResponse)
    {
        try
        {
            var errors = JSON.parse(ajaxResponse);
            errors = errors["errors"];
            for(var i=0; i<errors.length; i++)
            {
                Materialize.toast(errors[i],toast_time);
                setTimeout(100);
            } 
        }
        catch(e)//If the json could not be parsed, check if the input was blank
        {
            console.log("Unable to parse error checking json");
        }

    }

    /*Staff id|Student id changes and validation*/
    //Reset the validity of an input
    function ResetInputValidity($input)
    {
        $input.removeClass("valid");
        $input.removeClass("invalid");
    }
    //Validate input and resposne
    function ValidateInputResponse($input,response)
    {
        ResetInputValidity($input);

        //Id exists ~ means invalid input since we don't want conflicts
        if(IsValidResponse(response))
        {
            $input.addClass("invalid");
            return false;
        }
        else
        {
            $input.addClass("valid");
            return true;
        }
    }

    /*Username changes and validation*/
    //Superuser username exists
    function SuperuserUsernameValid($input){
        var username = $input.val();
        ResetInputValidity($input);

        if(IsEmpty(username))
        {
            $input.addClass("invalid");
            return false;
        }
        else
        {
            $.get(db_info_path,{"action":"SuperuserUsernameExists","username":username},function(response,status){
            return ValidateInputResponse($input,response);
            });
        }
    };
    //Principal username exists
    function PrincipalUsernameValid($input){
        var username = $input.val();
        ResetInputValidity($input);

        if(IsEmpty(username))
        {
            $input.addClass("invalid");
            return false;
        }
        else
        {
            $.get(db_info_path,{"action":"PrincipalUsernameExists","username":username},function(response,status){
                return ValidateInputResponse($input,response);
            });
        }
    };
    //Teacher username exists
    function TeacherUsernameValid($input){
        var username = $input.val();
        ResetInputValidity($input);

        if(IsEmpty(username))
        {
            $input.addClass("invalid");
            return false;
        }
        else
        {
            $.get(db_info_path,{"action":"TeacherUsernameExists","username":username},function(response,status){
                return ValidateInputResponse($input,response);
            });
        }
    };
    //Student username exists
    function StudentUsernameValid($input){
        var username = $input.val();
        ResetInputValidity($input);

        if(IsEmpty(username))
        {
            $input.addClass("invalid");
            return false;
        }
        else
        {
            $.get(db_info_path,{"action":"StudentUsernameExists","username":username},function(response,status){
                return ValidateInputResponse($input,response);
            });
        }

    };
    //Checks if form data is valid
    function IsValidFormData($form)
    {
        var is_valid = true;

        //Check for empty required fields
        $inputs = $form.find("input,textarea,select");
        var id = "";

        $inputs.each(function(){
            var $input = $(this);
            id = $input.attr("id");
            $input.removeClass("valid");
            $input.removeClass("invalid");


            //If the input is not empty and is valid
            if($input != "" && $input.is(":valid"))
            {
                $input.addClass("valid");
            }
            else //Input is invalid
            {
                $input.addClass("invalid");
                is_valid = false;
            }

            //Check username and password
            /*switch(id)
            {
                //Superuser staff id
                case "newSuperuserStaffId":
                    is_valid = SuperuserStaffIdValid($input);
                break;
                //Superuser username
                case "newSuperuserUsername":
                    is_valid = SuperuserUsernameValid($input);
                break;

                //Principal staff id
                case "newPrincipalStaffId":
                    is_valid = PrincipalStaffIdValid($input);
                break;
                //Principal username
                case "newSPrincipalsername":
                    is_valid = PrincipalUsernameValid($input);
                break;

                //Teacher staff id
                case "neTeacherStaffId":
                    is_valid = TeacherStaffIdValid($input);
                break;
                //Teacher username
                case "newTeacherUsername":
                    is_valid = TeacherUsernameValid($input);
                break;

                //Student id
                case "newStudentId":
                    is_valid = StudentIdValid($input);
                break;
                //Student username
                case "newStudentUsername":
                    is_valid = StudentUsernameValid($input);
                    console.log(is_valid);
                break;
            }*/
        });

        return is_valid;
    }

    /*SUPERUSER SECTION AJAX REQUESTS*/
    var feedback_timeout = toast_time*2 ;

    var creating_acc_message = "Creating account... Please wait";
    var acc_creation_failed_message = "Account creation failed. Please ensure filled in all the required fields.";

    //Clear form inputs
    function ClearFormInputs($form)
    {
        $form.find("input").each(function(){
            ResetInputValidity($(this));
            $(this).val("");
            Materialize.updateTextFields();
        });
        $form.find("textarea").html("");
    }

    //Show feedback for creating accounts
    function ShowCreateAccountFeedback(acc_type,ajaxResponse,$form)
    {   
        //Possibly unnecessary try
        var has_errors = HasErrors(ajaxResponse);
        //If there were any errors
        if(has_errors)
        {
            Materialize.toast("Failed to create the "+acc_type+" account",short_toast_time);
            ToastAjaxErrors(ajaxResponse);
        }
        else //No errors
        {
            ClearFormInputs($form);
            Materialize.toast("Successfully created the "+acc_type+" account",short_toast_time);
        }

        //In the end ~ re-enable the create account button
        $form.find(".create-acc-btn").removeClass(DISABLED_CLASS);
    }

    //Create student account
    function CreateStudentAccount(data,$form)
    {
        Materialize.toast(creating_acc_message,feedback_timeout);//HACK ~ This should disappear once the post request is done

        $.post(db_handler_path,{"action":"CreateStudentAccount","data":data},function(response,status){
            ShowCreateAccountFeedback("student",response,$form);
        });
    }
    //Create teacher account
    function CreateTeacherAccount(data,$form)
    {
        Materialize.toast(creating_acc_message,feedback_timeout);//HACK ~ This should disappear once the post request is done
        
        $.post(db_handler_path,{"action":"CreateTeacherAccount","data":data},function(response,status){
            ShowCreateAccountFeedback("teacher",response,$form);
        });
    }
    //Create principal account
    function CreatePrincipalAccount(data,$form,$create_teacher_acc)
    {
        Materialize.toast(creating_acc_message,feedback_timeout);//HACK ~ This should disappear once the post request is done

        $.post(db_handler_path,{"action":"CreatePrincipalAccount","data":data,"create_teacher_acc":$create_teacher_acc},function(response,status){
            ShowCreateAccountFeedback("principal",response,$form);
        });
    }
    //Create superuser account
    function CreateSuperuserAccount(data,$form)
    {
        Materialize.toast(creating_acc_message,feedback_timeout);//HACK ~ This should disappear once the post request is done

        $.post(db_handler_path,{"action":"CreateSuperuserAccount","data":data},function(response,status){
            ShowCreateAccountFeedback("superuser",response,$form);
        });
    }

    /*SUPERUSER EVENTS*/
    var acc_wait_msg = "Please wait until the current account is done being created";

    //Show account wait message
    function ShowAccWaitMessage()
    {
        Materialize.toast(acc_wait_msg,short_toast_time);
    }

    //TODO: Consider making buttons re-enable on disappearing of "Create account toasts"
    //Create Student
    $(".btn#createStudentAccount").click(function(){
        $btn = $(this);
        //Validate input
        var $form = $(this).parents("form#createStudentForm");

        //Only create the account if the form data is valid and the button is not disabled
        if(IsValidFormData($form))
        {
            if(!($btn.hasClass(DISABLED_CLASS)))
            {
                $btn.addClass(DISABLED_CLASS);
                //Form data
                var student_id = $form.find("#newStudentId").val();
                var first_name = $form.find("#newStudentFirstName").val();
                var last_name = $form.find("#newStudentLastName").val();
                var username = $form.find("#newStudentUsername").val();

                //JSON data to send in ajax request
                var data = {
                    "student_id":student_id,
                    "first_name":first_name,
                    "last_name":last_name,
                    "username":username
                };
                
                CreateStudentAccount(data,$form);
            }
            else //Account is still being created ~ wait
            {
                ShowAccWaitMessage();
            }
        }
        else
        {
            Materialize.toast(acc_creation_failed_message,short_toast_time);
        }
    });
    //Create Teacher
    $(".btn#createTeacherAccount").click(function(){
        $btn = $(this);
        //Validate input
        var $form = $(this).parents("form#createTeacherForm");

        if(IsValidFormData($form))
        {
            if(!($btn.hasClass(DISABLED_CLASS)))
            {
                $btn.addClass(DISABLED_CLASS);
                //Form data
                var first_name = $form.find("#newTeacherFirstName").val();
                var last_name = $form.find("#newTeacherLastName").val();
                var email = $form.find("#newTeacherEmail").val();
                var phone = $form.find("#newTeacherPhone").val();
                var username = $form.find("#newTeacherUsername").val();

                //JSON data to send in ajax request
                var data = {
                    "first_name":first_name,
                    "last_name":last_name,
                    "email":email,
                    "phone":phone,
                    "username":username
                };

                CreateTeacherAccount(data,$form);
            }
            else
            {
                ShowAccWaitMessage();
            }
        }
        else
        {
            Materialize.toast(acc_creation_failed_message,short_toast_time);
        }
    });
    //Create Principal
    $(".btn#createPrincipalAccount").click(function(){
        $btn = $(this);
        //Validate input
        var $form = $(this).parents("form#createPrincipalForm");

        if(IsValidFormData($form))
        {
            if(!($btn.hasClass(DISABLED_CLASS)))
            {
                $btn.addClass(DISABLED_CLASS);
                //Form data
                var first_name = $form.find("#newPrincipalFirstName").val();
                var last_name = $form.find("#newPrincipalLastName").val();
                var email = $form.find("#newPrincipalEmail").val();
                var phone = $form.find("#newPrincipalPhone").val();
                var username = $form.find("#newPrincipalUsername").val();
                var create_teacher_acc = $form.find("#createTeacherAccountFromPrincipal").is(":checked");

                //JSON data to send in ajax request
                var data = {
                    "first_name":first_name,
                    "last_name":last_name,
                    "email":email,
                    "phone":phone,
                    "username":username,
                };
            
                CreatePrincipalAccount(data,$form,create_teacher_acc);
            }
            else
            {
                ShowAccWaitMessage();
            }
        }
        else
        {
            Materialize.toast(acc_creation_failed_message,short_toast_time);
        }
    });
    //Create Superuser
    $(".btn#createSuperuserAccount").click(function(){
        $btn = $(this);
        //Validate input
        var $form = $(this).parents("form#createSuperuserForm");

        if(IsValidFormData($form))
        {
            if(!($btn.hasClass(DISABLED_CLASS)))
            {
                $btn.addClass(DISABLED_CLASS);
                //Form data
                var first_name = $form.find("#newSuperuserFirstName").val();
                var last_name = $form.find("#newSuperuserLastName").val();
                var email = $form.find("#newSuperuserEmail").val();
                var phone = $form.find("#newSuperuserPhone").val();
                var username = $form.find("#newSuperuserUsername").val();

                //JSON data to send in ajax request
                var data = {
                    "first_name":first_name,
                    "last_name":last_name,
                    "email":email,
                    "phone":phone,
                    "username":username
                };
                CreateSuperuserAccount(data,$form);
            }
            else
            {
                ShowAccWaitMessage();
            }
        }
        else
        {
            Materialize.toast(acc_creation_failed_message,short_toast_time);
        }
    });

    /*Bulk actions superuser section*/
    var info_timeout = short_toast_time*2;
    var delete_message = "Delete in Progress... Please wait";
    var reset_message = "Account Reset in Progress... Please wait";
    var action_failed_message = "Bulk action failed, select accounts to perform action on first";

    //Student bulk action
    $("#student_bulk_action").change(function(){
        var $self = $(this);
        var $option = $self.val();

        var $selected_accounts = $("input.selected_students:checked");

        var acc_id = null;
        var selected_acc_ids = [];
        $selected_accounts.each(function(){
            $acc = $(this);
            acc_id = $acc.val();
            selected_acc_ids.push(acc_id);
        });

        data = {"acc_ids":selected_acc_ids};

        var number_of_accs = data["acc_ids"].length;
        //If some accounts have been selected
        if(number_of_accs > 0)
        {
            console.log("option : "+$option);
            //Delete or reset student account
            switch($option)
            {
                case "super_student_delete":
                    Materialize.toast(delete_message,toast_time);
                    $selected_accounts.each(function(){
                        $(this).addClass(DISABLED_CLASS);
                        $(this).attr(DISABLED_CLASS,DISABLED_CLASS);
                    });
                    $.post(db_handler_path,{"action":"SuperuserDeleteStudents","data":data},function(response,status){
                        is_valid = IsValidResponse(response);
                        if(is_valid)
                        {
                            $selected_accounts.parents("tr").remove();
                            Materialize.toast("Successfully deleted "+number_of_accs+" account(s)",toast_time);
                        }
                        else
                        {
                            Materialize.toast("Failed to delete 1 or more students accounts, if the problem persists: contact your web administrator",toast_time);

                            //Re-enable the checkboxes
                            $selected_accounts.each(function(){
                                $(this).removeClass(DISABLED_CLASS);
                                $(this).removeAttr(DISABLED_CLASS);
                            });
                        }
                    });
                break;
                case "super_student_reset":
                    Materialize.toast(reset_message,toast_time);
                    $.post(db_handler_path,{"action":"SuperuserResetStudents","data":data},function(response,status){
                    is_valid = IsValidResponse(response);
                        if(is_valid)
                        {
                            Materialize.toast("Successfully reset "+number_of_accs+" student account(s)",toast_time);
                        }
                        else
                        {
                            Materialize.toast("Failed to reset 1 or more student accounts, if the problem persists: contact your web administrator",toast_time);
                        }
                    });
                break;
            }
        }
        else
        {
            Materialize.toast(action_failed_message,info_timeout);
        }

    });

    //Teacher bulk action
    $("#teacher_bulk_action").change(function(){
        var $self = $(this);
        var $option = $self.val();

        var $selected_accounts = $("input.selected_teachers:checked");

        var acc_id = null;
        var selected_acc_ids = [];
        $selected_accounts.each(function(){
            $acc = $(this);
            acc_id = $acc.val();
            selected_acc_ids.push(acc_id);
        });

        data = {"acc_ids":selected_acc_ids};

        var number_of_accs = data["acc_ids"].length;
        console.log(number_of_accs);
        //If some accounts have been selected
        if(number_of_accs > 0)
        {
            console.log("option : "+$option);
            //Delete or reset student account
            switch($option)
            {
                case "super_teacher_delete":
                    Materialize.toast(delete_message,toast_time);
                    $selected_accounts.each(function(){
                        $(this).addClass(DISABLED_CLASS);
                        $(this).attr(DISABLED_CLASS,DISABLED_CLASS);
                    });
                    $.post(db_handler_path,{"action":"SuperuserDeleteTeachers","data":data},function(response,status){
                        is_valid = IsValidResponse(response);
                        if(is_valid)
                        {
                            $selected_accounts.parents("tr").remove();
                            Materialize.toast("Successfully deleted "+number_of_accs+" account(s)",toast_time);
                        }
                        else
                        {
                            Materialize.toast("Failed to delete 1 or more accounts, if the problem persists: contact your web administrator",toast_time);

                            //Re-enable the checkboxes
                            $selected_accounts.each(function(){
                                $(this).removeClass(DISABLED_CLASS);
                                $(this).removeAttr(DISABLED_CLASS);
                            });
                        }
                    });
                break;
                case "super_teacher_reset":
                    Materialize.toast(reset_message,toast_time);
                    $.post(db_handler_path,{"action":"SuperuserResetTeachers","data":data},function(response,status){
                        is_valid = IsValidResponse(response);
                        if(is_valid)
                        {
                            Materialize.toast("Successfully reset "+number_of_accs+" accounts",toast_time);
                        }
                        else
                        {
                            Materialize.toast("Failed to reset 1 or more accounts, if the problem persists: contact your web administrator",toast_time);
                        }
                    });
                break;
            }
        }
        else
        {
            Materialize.toast(action_failed_message,info_timeout);
        }
    });

    //Principal delete action
    $("#super_delete_principal_acc").click(function()
    {
        Materialize.toast(delete_message,toast_time);
        var $self = $(this);

        var $selected_accounts = $("input.selected_principals:checked");

        var acc_id = null;
        var selected_acc_ids = [];
        $selected_accounts.each(function(){
            $acc = $(this);
            acc_id = $acc.val();
            selected_acc_ids.push(acc_id);
        });

        data = {"acc_ids":selected_acc_ids};

        var number_of_accs = data["acc_ids"].length;
        //If some accounts have been selected
        if(number_of_accs > 0)
        {
            //Disable each input
            $selected_accounts.each(function(){
                $(this).addClass(DISABLED_CLASS);
                $(this).attr(DISABLED_CLASS,DISABLED_CLASS);
            });

            //Delete principal accounts
            $.post(db_handler_path,{"action":"SuperuserDeletePrincipals","data":data},function(response,status){
                is_valid = IsValidResponse(response);
                if(is_valid)
                {
                    $selected_accounts.parents("tr").remove();
                    Materialize.toast("Successfully deleted "+number_of_accs+" account(s)",toast_time);
                }
                else
                {
                    Materialize.toast("Failed to delete 1 or more accounts, if the problem persists: contact your web administrator",toast_time);

                    //Re-enable the checkboxes
                    $selected_accounts.each(function(){
                        $(this).removeClass(DISABLED_CLASS);
                        $(this).removeAttr(DISABLED_CLASS);
                    });
                }
            });

        }
        else
        {
            Materialize.toast(action_failed_message,toast_time);
        }

    });

    //Principal reset action
    $("#super_reset_principal_acc").click(function()
    {
        var $self = $(this);

        var $selected_accounts = $("input.selected_principals:checked");

        var acc_id = null;
        var selected_acc_ids = [];
        $selected_accounts.each(function(){
            $acc = $(this);
            acc_id = $acc.val();
            selected_acc_ids.push(acc_id);
        });

        data = {"acc_ids":selected_acc_ids};

        var number_of_accs = data["acc_ids"].length;
        //If some accounts have been selected
        if(number_of_accs > 0)
        {
            $.post(db_handler_path,{"action":"SuperuserResetPrincipals","data":data},function(response,status){
                is_valid = IsValidResponse(response);
                if(is_valid)
                {
                    Materialize.toast("Successfully reset "+number_of_accs+" accounts",toast_time);
                }
                else
                {
                    Materialize.toast("Failed to reset 1 or more accounts, if the problem persists: contact your web administrator",toast_time);
                }
            });
        }
        else
        {
            Materialize.toast("Failed to reset accounts, select accounts to perform action on first",toast_time);
        }

    });

    //Minimum password length
    var MIN_PASS_LENGTH = 8;

    //Changing passwords
    $("#btn_change_password").click(function(){
        var $form = $(this).parents(".account_form");

        var $inputs = $form.find(".input-container").children("input");

        var $old_pass_container = $form.find("#oldPassword");
        var $new_pass_container = $form.find("#newPassword");
        var $confirm_pass_container = $form.find("#confirmNewPassword");

        var old_password = $old_pass_container.val();
        var new_password = $new_pass_container.val();
        var confirm_password = $confirm_pass_container.val();

        var is_valid = true;
        $inputs.removeClass("invalid");
        $inputs.removeClass("valid");

        var error_message = false;

        $inputs.each(function(){
            //If the current input field is empty or password length < minimum password length
            if($(this).val() == "")
            {
                is_valid = false;

                $(this).addClass("invalid");
                error_message = "Failed to change password. One or more required fields are empty";
            }
            else
            {
                //New password length is too short
                if(new_password.length < MIN_PASS_LENGTH)
                {
                    is_valid = false;
                    error_message = "Failed to change password. Password provided is too short";
                    $new_pass_container.addClass("invalid");
                }

                //Confirm password length is too short
                if(confirm_password.length < MIN_PASS_LENGTH)
                {
                    is_valid = false;
                    error_message = "Failed to change password. Password provided is too short";
                    $confirm_pass_container.addClass("invalid");
                }
            }

        });

        //If the password information provided is valid ~ perform an ajax request
        if(is_valid)
        {
            if(new_password == confirm_password)
            {
                //Perform ajax request
                var data = {
                    "old_password":old_password,
                    "new_password":new_password
                };

                //Update the account password
                $.post(db_handler_path,{"action":"UpdateAccountPassword","data":data},function(response,status){
                    var feedback_message;
                    if(response == "1" || response == "true")
                    {
                        feedback_message = "Successfully updated the password for your account. You can use your new password to login to your account";
                    }
                    else
                    {
                        feedback_message =  "Failed to update the password. Possible reason: the old password you provided is incorrect";
                    }

                    Materialize.toast(feedback_message,toast_time);
                });

            }
            else //New password and password confirmation do not match
            {
                error_message = "Failed to change password. New password and password confirmation do not match";

                $new_pass_container.addClass("invalid");
                $confirm_pass_container.addClass("invalid");

                Materialize.toast(error_message,toast_time);
            }
        }
        else
        {
            Materialize.toast(error_message,toast_time);
        }
    });

    /*PRINCIPAL SECTION ~ STATISTICS CODE*/
    //Update schedule overview
    function UpdateScheduleOverview(timeframe)
    {
        $.get(db_info_path,{"action":"UpdateScheduleOverview","timeframe":timeframe},function(response,status){
            try
            {
                var json = JSON.parse(response);
                
                //If the JSON is valid
                if(json)
                {
                    $("#stats_total_schedules").text(json["total_schedule_count"]);
                    $("#stats_done_schedules").text(json["done_schedules"]);
                    $("#stats_unattended_schedules").text(json["unattended_schedules"]);
                }
                else
                {
                    console.log("Could not retrieve JSON information for this timeframe");
                }
            }
            catch(e)
            {
                Materialize.toast("Failed to update records to match timeframe",toast_time);
                console.log("Failed to parse JSON in timeframe ajax request.");
            }

        });
    }

    //Stats overview ~ Schedule overview timeframe change
    $("#schedule_overview_timeframe").change(function(){
        var timeframe = $(this).val();
        UpdateScheduleOverview(timeframe);
    });
    
    //Update assignment overview
    function UpdateAssignmentOverview(timeframe)
    {
        $.get(db_info_path,{"action":"UpdateAssignmentOverview","timeframe":timeframe},function(response,status){
            try
            {
                var json = JSON.parse(response);

                //If the JSON is valid
                if(json)
                {
                    $("#stats_total_ass_sent").text(json["total_ass_sent"]);
                    $("#stats_total_ass_subs").text(json["total_ass_subs"]);
                    $("#stats_total_graded_ass_subs").text(json["total_graded_ass_subs"]);
                }
                else
                {
                    console.log("Could not retrieve JSON information for this timeframe");
                }
            }
            catch(e)
            {
                Materialize.toast("Failed to update records to match timeframe",toast_time);
                console.log("Failed to parse JSON in timeframe ajax request.");
            }
        });
    }

    //Stats overview ~ Assignment overview timeframe change
    $("#ass_overview_timeframe").change(function(){
        var timeframe = $(this).val();

        UpdateAssignmentOverview(timeframe);
    });

    //Get the row to be displayed when no assignments are found
    function GetPrincipalMissingScheduleRowHtml()
    {
        var row_html = '';
        
        row_html += '<tr class="schedule-table-list-row"><td colspan="7">No schedules were found for the specified time period</td></tr>';

        return row_html;
    }

    //Get Schedule tab table row data [REFACTOR TO list_templates]
    function GetPrincipalScheduleRowHtml(data)
    {
        /*Expected data indices
            schedule_title,
            schedule_teacher,
            schedule_classroom,
            schedule_date (formatted),
            schedule_due_date (formatted).
            schedule_status
            schedule_id
            comment_count
         */
        var row_html="";

        row_html += '<tr class="schedule-table-list-row" data-schedule-id="'+data["schedule_id"]+'">';
        row_html += '<td>'+data["schedule_title"]+'</td>';
        row_html += '<td>'+data["schedule_teacher"]+'</td>';
        row_html += '<td>'+data["schedule_classroom"]+'</td>';
        row_html += '<td>'+data["schedule_date"]+'</td>';
        row_html += '<td>'+data["schedule_due_date"]+'</td>';
        row_html += '<td>'+data["schedule_status"]+'</td>';
        row_html += '<td>';
        row_html += '<a href="javascript:void(0)" data-schedule-id="'+data["schedule_id"]+'" class="principal_view_schedule" title="View schedule ('+data["schedule_title"]+')"><i class="material-icons">visibility</i></a>';
        row_html += '<a href="javascript:void(0)" data-schedule-id="'+data["schedule_id"]+'" class=" principal_comment_on_schedule" title="Comments for '+data["schedule_title"]+'"><i class="material-icons lime-text">comment</i></a>';
        row_html += '</td>';
        row_html += '<tr>';

        return row_html;
    }

    //Clear all body rows from the principal schedule section table (provided as parameter)
    function ClearPrincipalScheduleRows($table)
    {
        //Clear all the existing table body rows
        $table.find(".schedule-table-list-row").remove();
    }

    //Update schedule tab stats
    function UpdateScheduleTabStats($table,timeframe)
    {

        $.get(db_info_path,{"action":"UpdateScheduleTabStats","timeframe":timeframe},function(response,status){

            //Rows to add ~ will be appended to the end of the table            
            var rows_to_add = '';
            ClearPrincipalScheduleRows($table);
            
            //Try parsing the response as JSON data
            try
            {
                var json = JSON.parse(response);
                
                //If the JSON is valid
                if(json)
                {
                    var cur_row = '';

                    //Get all the rows to be added based on data provided
                    for (var i=0; i<json.length; i++)
                    {
                        cur_row = GetPrincipalScheduleRowHtml(json[i]);
                        rows_to_add += cur_row;//Add the current row to the rows to add
                    }
                }   
                else
                {
                    rows_to_add = GetPrincipalMissingScheduleRowHtml();
                }
            }
            catch(e)//Catch the exception thrown if parsing JSON fails
            {
                rows_to_add = GetPrincipalMissingScheduleRowHtml();
                console.log("Could not retrieve JSON information for this schedule timeframe");
            }
            finally
            {
                //Append the rows to add to the end of the table
                $table.append(rows_to_add); 
            }

        });
    }

    //Schedule tab ~ schedule tab timeframe change
    $("#schedules_tab_timeframe").change(function(){
        var timeframe = $(this).val();
        var $table = $("table#schedules_tab_list");

        UpdateScheduleTabStats($table,timeframe);
    });

    //Get the row to be displayed when no assignments are found
    function GetPrincipalMissingAssRowHtml()
    {
        var row_html = '';
        
        row_html += '<tr class="ass-table-list-row"><td colspan="9">No assignments were found for the specified time period</td></tr>';

        return row_html;
    }

    //Get assignment row html [REFACTOR TO list_templates]
    function GetPrincipalAssRowHtml(data)
    {
        /*Expected data indices
            ass_title,
            ass_teacher,
            ass_classroom,
            ass_date_sent,
            ass_date_due,
            ass_submission_count,
            returned_submission_count,
            unreturned_submission_count,
            ass_id
        */
        var row_html = '';

        row_html += '<tr class="ass-table-list-row" data-ass-id="'+data["ass_id"]+'">';
        row_html += '<td title="Assignment title">'+data["ass_title"]+'</td>';
        row_html += '<td title="Teacher that sent the assignment">'+data["ass_teacher"]+'</td>';
        row_html += '<td title="Classroom the assignment was sent to">'+data["ass_classroom"]+'</td>';
        row_html += '<td title="Date the assignment was sent">'+data["ass_date_sent"]+'</td>';
        row_html += '<td title="Due date of the assignment">'+data["ass_date_due"]+'</td>';
        row_html += '<td title="Number of submissions received from students for this assignment">'+data["ass_submission_count"]+'</td>';
        row_html += '<td title="Number of submissions that were graded and returned by the teacher">'+data["returned_submission_count"]+'</td>';
        row_html += '<td title="Number of submissions that have not yet been graded/returned by the teacher">'+data["unreturned_submission_count"]+'</td>';
        row_html += '<td>';
        row_html += '<a href="javascript:void(0)" data-ass-id="'+data["ass_id"]+'" class="principal_view_ass" title="View assignment ('+data["ass_title"]+')"><i class="material-icons">visibility</i></a>';
        row_html += '</td>';
        row_html += '</tr>';

        return row_html;
    }

    //Clear all body rows from the principal assignment section table (provided as parameter)
    function ClearPrincipalAssRows($table)
    {
        //Clear all the existing table body rows
        $table.find(".ass-table-list-row").remove();
    }

    //Update assignment overview
    function UpdateAssignmentTabStats($table,timeframe)
    {
        $.get(db_info_path,{"action":"UpdateAssignmentTabStats","timeframe":timeframe},function(response,status){
            
            //Rows to add ~ will be appended to the end of the table            
            var rows_to_add = '';
            
            //Clear all the existing table body rows
            ClearPrincipalAssRows($table);

            //Try parsing the response as JSON data
            try
            {
                var json = JSON.parse(response);

                if(json)
                {
                    var cur_row = '';
                    for (var i=0; i<json.length; i++)
                    {
                        cur_row = GetPrincipalAssRowHtml(json[i]);
                        rows_to_add += cur_row;
                    }
                }
                else
                {
                    rows_to_add = GetPrincipalMissingAssRowHtml();
                }
            }
            catch(e)//Catch the exception thrown if parsing JSON fails
            {
                rows_to_add = GetPrincipalMissingAssRowHtml();
                console.log("Could not retrieve JSON information for this assignment timeframe");
            }
            finally
            {
                //Append the rows to add to the end of the table
                $table.append(rows_to_add); 
            }

        });
    }
    //Assignment tab ~ assignment tab timeframe chabnge
    $("#assignments_tab_timeframe").change(function(){
        var timeframe = $(this).val();
        var $table = $("table#ass_tab_list");
        
        UpdateAssignmentTabStats($table,timeframe);
    });

}); // end of document ready
