$(document).ready(function (z) {


    /*Superuser validation*/
    var db_handler_path = "handlers/db_handler.php";
    var db_info_path = "handlers/db_info.php";

    //Returns true if empty and false if not empty
    function IsEmpty(value)
    {
        return (value=="" || value==null);
    }

    //Returns true if ajax response is valid
    function IsValidResponse(response)
    {
        return(response=="1" || response=="true");
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

    //Superuser staff id
    function SuperuserStaffIdValid($input){
        var staff_id = $input.val();
        ResetInputValidity($input);

        if(IsEmpty(staff_id))
        {
            $input.addClass("invalid");
            return false;
        }
        else
        {
            $.get(db_info_path,{"action":"SuperuserStaffIdExists","staff_id":staff_id},function(response,status){
                return ValidateInputResponse($input,response);
            });
        }
    };

    //Principal staff id
    function PrincipalStaffIdValid($input){
        var staff_id = $input.val();

        ResetInputValidity($input);

        if(IsEmpty(staff_id))
        {
            $input.addClass("invalid");
            return false;
        }
        else
        {
            $.get(db_info_path,{"action":"PrincipalStaffIdExists","staff_id":staff_id},function(response,status){
                return ValidateInputResponse($input,response);
            });
        }
    };

    //Teacher staff id
    function TeacherStaffIdValid($input){
        var staff_id = $input.val();
        ResetInputValidity($input);

        if(IsEmpty(staff_id))
        {
            $input.addClass("invalid");
            return false;
        }
        else
        {
            $.get(db_info_path,{"action":"TeacherStaffIdExists","staff_id":staff_id},function(response,status){
                return ValidateInputResponse($input,response);
            });
        }
    };

    //Student id
    function StudentIdValid($input){
        var student_id = $input.val();

        ResetInputValidity($input);

        if(IsEmpty(student_id))
        {
            $input.addClass("invalid");
            return false;
        }
        else
        {
            $.get(db_info_path,{"action":"StudentIdValidation","student_id":student_id},function(response,status){
                return ValidateInputResponse($input,response);
           });
        }
    };

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

    /*SUPERUSER AJAX REQUESTS*/
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

    //Timeout for toasts
    var timeout_time = 2000;
    //Create student account
    function CreateStudentAccount(data,$form)
    {
        $.post(db_handler_path,{"action":"CreateStudentAccount","data":data},function(response,status){
            //If the account was successfully created
            if(IsValidResponse(response))
            {
                ClearFormInputs($form);
                Materialize.toast("Successfully created the student account",timeout_time);
            }
            else
            {
                Materialize.toast("Failed to create the student account",timeout_time);
            }
        });
    }
    //Create teacher account
    function CreateTeacherAccount(data,$form)
    {
        $.post(db_handler_path,{"action":"CreateTeacherAccount","data":data},function(response,status){
            //If the account was successfully created
            if(IsValidResponse(response))
            {
                ClearFormInputs($form);
                Materialize.toast("Successfully created the teacher account",timeout_time);
            }
            else
            {
                Materialize.toast("Failed to create the teacher account",timeout_time);
            }
        });
    }
    //Create principal account
    function CreatePrincipalAccount(data,$form,$create_teacher_acc)
    {
        $.post(db_handler_path,{"action":"CreatePrincipalAccount","data":data,"create_teacher_acc":$create_teacher_acc},function(response,status){
            //If the account was successfully created
            if(IsValidResponse(response))
            {
                ClearFormInputs($form);
                Materialize.toast("Successfully created the principal account",timeout_time);
            }
            else
            {
                Materialize.toast("Failed to create the principal account",timeout_time);
            }
        });
    }
    //Create superuser account
    function CreateSuperuserAccount(data,$form)
    {
        $.post(db_handler_path,{"action":"CreateSuperuserAccount","data":data},function(response,status){
            //If the account was successfully created
            if(IsValidResponse(response))
            {
                ClearFormInputs($form);
                Materialize.toast("Successfully created the superuser account",timeout_time);
            }
            else
            {
                Materialize.toast("Failed to create the superuser account",timeout_time);
            }
        });
    }

    /*SUPERUSER EVENTS*/
    //Create Student
    $(".btn#createStudentAccount").click(function(){
        //Validate input
        var $form = $(this).parents("form#createStudentForm");

        console.log(IsValidFormData($form));
        if(IsValidFormData($form))
        {
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

            console.log("Create student account");
            CreateStudentAccount(data,$form);
        }
    });
    //Create Teacher
    $(".btn#createTeacherAccount").click(function(){
        //Validate input
        var $form = $(this).parents("form#createTeacherForm");

        if(IsValidFormData($form))
        {
            //Form data
            var first_name = $form.find("#newTeacherFirstName").val();
            var last_name = $form.find("#newTeacherLastName").val();
            var email = $form.find("#newTeacherEmail").val();
            var phone = $form.find("#newTeacherPhone").val();
            var username = $form.find("#newTeacherUsername").val();
            var staff_id = $form.find("#newTeacherStaffId").val();

            //JSON data to send in ajax request
            var data = {
                "first_name":first_name,
                "last_name":last_name,
                "email":email,
                "phone":phone,
                "username":username,
                "staff_id":staff_id
            };

            CreateTeacherAccount(data,$form);
        }
    });
    //Create Principal
    $(".btn#createPrincipalAccount").click(function(){
        //Validate input
        var $form = $(this).parents("form#createPrincipalForm");

        if(IsValidFormData($form))
        {
            //Form data
            var first_name = $form.find("#newPrincipalFirstName").val();
            var last_name = $form.find("#newPrincipalLastName").val();
            var email = $form.find("#newPrincipalEmail").val();
            var phone = $form.find("#newPrincipalPhone").val();
            var username = $form.find("#newPrincipalUsername").val();
            var staff_id = $form.find("#newPrincipalStaffId").val();
            var create_teacher_acc = $form.find("#createTeacherAccountFromPrincipal").is(":checked");

            //JSON data to send in ajax request
            var data = {
                "first_name":first_name,
                "last_name":last_name,
                "email":email,
                "phone":phone,
                "username":username,
                "staff_id":staff_id,
            };

            CreatePrincipalAccount(data,$form,create_teacher_acc);
        }
    });
    //Create Superuser
    $(".btn#createSuperuserAccount").click(function(){
        //Validate input
        var $form = $(this).parents("form#createSuperuserForm");

        if(IsValidFormData($form))
        {
            //Form data
            var first_name = $form.find("#newSuperuserFirstName").val();
            var last_name = $form.find("#newSuperuserLastName").val();
            var email = $form.find("#newSuperuserEmail").val();
            var phone = $form.find("#newSuperuserPhone").val();
            var username = $form.find("#newSuperuserUsername").val();
            var staff_id = $form.find("#newSuperuserStaffId").val();

            //JSON data to send in ajax request
            var data = {
                "first_name":first_name,
                "last_name":last_name,
                "email":email,
                "phone":phone,
                "username":username,
                "staff_id":staff_id,
            };
            CreateSuperuserAccount(data,$form);
        }
    });

    /*Bulk actions superuser section*/

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
        console.log();

        var number_of_accs = data["acc_ids"].length;
        //If some accounts have been selected
        if(number_of_accs > 0)
        {
            console.log("option : "+$option);
            //Delete or reset student account
            switch($option)
            {
                case "super_student_delete":
                console.log("Deleting student");
                    $selected_accounts.each(function(){
                        $(this).addClass("disabled");
                        $(this).attr("disabled","disabled");
                    });
                    $.post(db_handler_path,{"action":"SuperuserDeleteStudents","data":data},function(response,status){
                        is_valid = IsValidResponse(response);
                        if(is_valid)
                        {
                            $selected_accounts.parents("tr").remove();
                            Materialize.toast("Successfully deleted "+number_of_accs+" account(s)",(timeout_time*2));
                        }
                        else
                        {
                            Materialize.toast("Failed to delete 1 or more students accounts, if the problem persists: contact your web administrator",(timeout_time*2));

                            //Re-enable the checkboxes
                            $selected_accounts.each(function(){
                                $(this).removeClass("disabled");
                                $(this).removeAttr("disabled");
                            });
                        }
                    });
                break;
                case "super_student_reset":
                        $.post(db_handler_path,{"action":"SuperuserResetStudents","data":data},function(response,status){
                        is_valid = IsValidResponse(response);
                        if(is_valid)
                        {
                            Materialize.toast("Successfully reset "+number_of_accs+" student account(s)",(timeout_time*2));
                        }
                        else
                        {
                            Materialize.toast("Failed to reset 1 or more student accounts, if the problem persists: contact your web administrator",(timeout_time*2));
                        }
                    });
                break;
            }
        }
        else
        {
            Materialize.toast("Bulk action failed, select accounts to perform action on first",(timeout_time*2));
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
                    $selected_accounts.each(function(){
                        $(this).addClass("disabled");
                        $(this).attr("disabled","disabled");
                    });
                    $.post(db_handler_path,{"action":"SuperuserDeleteTeachers","data":data},function(response,status){
                        is_valid = IsValidResponse(response);
                        if(is_valid)
                        {
                            $selected_accounts.parents("tr").remove();
                            Materialize.toast("Successfully deleted "+number_of_accs+" account(s)",(timeout_time*2));
                        }
                        else
                        {
                            Materialize.toast("Failed to delete 1 or more accounts, if the problem persists: contact your web administrator",(timeout_time*2));

                            //Re-enable the checkboxes
                            $selected_accounts.each(function(){
                                $(this).removeClass("disabled");
                                $(this).removeAttr("disabled");
                            });
                        }
                    });
                break;
                case "super_teacher_reset":
                        $.post(db_handler_path,{"action":"SuperuserResetTeachers","data":data},function(response,status){
                        is_valid = IsValidResponse(response);
                        if(is_valid)
                        {
                            Materialize.toast("Successfully reset "+number_of_accs+" accounts",(timeout_time*2));
                        }
                        else
                        {
                            Materialize.toast("Failed to reset 1 or more accounts, if the problem persists: contact your web administrator",(timeout_time*2));
                        }
                    });
                break;
            }
        }
        else
        {
            Materialize.toast("Bulk action failed, select accounts to perform action on first",(timeout_time*2));
        }
    });

    //Principal delete action
    $("#super_delete_principal_acc").click(function()
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
        console.log();

        var number_of_accs = data["acc_ids"].length;
        //If some accounts have been selected
        if(number_of_accs > 0)
        {
            //Disable each input
            $selected_accounts.each(function(){
                $(this).addClass("disabled");
                $(this).attr("disabled","disabled");
            });

            //Delete principal accounts
            $.post(db_handler_path,{"action":"SuperuserDeletePrincipals","data":data},function(response,status){
                is_valid = IsValidResponse(response);
                if(is_valid)
                {
                    $selected_accounts.parents("tr").remove();
                    Materialize.toast("Successfully deleted "+number_of_accs+" account(s)",(timeout_time*2));
                }
                else
                {
                    Materialize.toast("Failed to delete 1 or more accounts, if the problem persists: contact your web administrator",(timeout_time*2));

                    //Re-enable the checkboxes
                    $selected_accounts.each(function(){
                        $(this).removeClass("disabled");
                        $(this).removeAttr("disabled");
                    });
                }
            });

        }
        else
        {
            Materialize.toast("Bulk action failed, select accounts to perform action on first",(timeout_time*2));
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
                    Materialize.toast("Successfully reset "+number_of_accs+" accounts",(timeout_time*2));
                }
                else
                {
                    Materialize.toast("Failed to reset 1 or more accounts, if the problem persists: contact your web administrator",(timeout_time*2));
                }
            });
        }
        else
        {
            Materialize.toast("Failed to reset accounts, select accounts to perform action on first",(timeout_time*2));
        }

    });
    
    //Minimum password length
    var MIN_PASS_LENGTH = 8;

    //Changing passwords
    $("#btn_change_password").click(function(){
        var toast_time = 4000;
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
}); // end of document ready
