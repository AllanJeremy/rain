$(document).ready(function (z) {
    
    //init the dashboard application except on login page
    if (location.pathname.split('/').pop() != 'login.php') {
        
        var dashboard = new Dashboard();
        
    }
    
    $('.mobile-button-collapse').sideNav();
    
    /**************
    TABS SWITCH FUNCTIONALITIES
    ************/

    /********** ON DOCUMENT LOAD EVENT **********/

    /**************
    SEARCHBAR FUNCTIONALITIES
    ************/
    
    function openSearchBar() {
        
    }
    
    function closeSearchBar() {
        
    }
    /**************
    SEARCHBAR FUNCTIONALITIES END
    ************/

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
    var timeout_time = 2000;
    //Create student account
    function CreateStudentAccount(data,$form)
    {
        $.post(db_handler_path,{"action":"CreateStudentAccount","data":data},function(response,status){
            //If the account was successfully created
            if(IsValidResponse(response))
            {
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
}); // end of document ready
