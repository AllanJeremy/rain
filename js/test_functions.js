/*jslint browser: true*/
/*global $, jQuery, alert, console*/

 $(document).ready(function(){
    //Toggles fullscreen
    function toggleFullScreen() {
        console.log('going fullscreen');
        
        if ((document.fullScreenElement && document.fullScreenElement !== null) || (!document.mozFullScreen && !document.webkitIsFullScreen)) {
            if (document.documentElement.requestFullScreen) {
                document.documentElement.requestFullScreen();
            } else if (document.documentElement.mozRequestFullScreen) {
                document.documentElement.mozRequestFullScreen();
            } else if (document.documentElement.webkitRequestFullScreen) {
                console.log('g');
                //document.documentElement.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT);
            }
        } else {
            if (document.cancelFullScreen) {
                document.cancelFullScreen();
            } else if (document.mozCancelFullScreen) {
                document.mozCancelFullScreen();
            } else if (document.webkitCancelFullScreen) {
                document.webkitCancelFullScreen();
            }
        }
    }

    /*EDIT TEST*/
    var $editTestBtn = $("a.editTest");
    $editTestBtn.addClass("modal-trigger");//Add the class modal trigger to all edit test buttons

    //Edit test button clicked
    $editTestBtn.click(function(){
        //Modal
        var $editTestModal = $("#modal_edit_test");
        //Open Modal after it has been populated
        $editTestModal.openModal();

        var $test_id = $(this).attr("data-test-id");//test_id of the currently clicked test

        //Send the ajax request with the clicked test_id to retrieve test information
        $.get("handlers/db_info.php",{"action":"GetTestById","test_id":$test_id},function(data,status){
            data = JSON.parse(data);
            PopulateEditTestModal($editTestModal,data);
            
            //When the Done editing button is clicked, update the test
            $("#UpdateEditTest").click(function(){
                var edit_data = GetEditTestModalData($test_id);
                $.post("handlers/db_handler.php",{"action":"UpdateEditTest","data":edit_data},function(data,status){
                    $editTestModal.closeModal();
                });
            }); 
        });


    });

    //Populate modal
    function PopulateEditTestModal($modal,data)
    {
        if(!data)
        {
            console.log("Failed to populate edit test modal with default values");
            return false;//Do nothing in terms of populating the modal
        }
        /*
        DATA FIELDS IN THE MODAL
            editTestTitle ~ input(text)
            editTestSubject ~ select
            editTestQuestionCount ~ input(number)
            editTestMaxGrade ~ input(number)
            editTestDifficulty ~ selectr
            editTestPassGrade ~ input(number)
            editTestCompletionTime ~ input(number)
            editTestInstructions ~ textarea
        */
        var subject_id = data["subject_id"];
        
        //Selects <select></select>
        $("#editTestSubject").val(subject_id);//Update the value of the subject_id
        $("#editTestDifficulty").val(data["difficulty"]);
        console.log("Difficulty value = ",$("#editTestDifficulty").val());
        
        //Set all input values
        $("#editTestTitle").val(data["test_title"]);
        $("#editTestQuestionCount").val(data["number_of_questions"]);//Number of questions in this test
        $("#editTestMaxGrade").val(data["max_grade"]);        
        $("#editTestPassGrade").val(data["passing_grade"]);
        $("#editTestCompletionTime").val(data["time_to_complete"]);
        $("#editTestInstructions").val(data["test_description"]);

    }

    //Get the data from the edit test modal ~ takes test_id as a parameter, this will be used to set the value of test_id in data
    function GetEditTestModalData($test_id)
    {
        var data = {};
        data["test_id"] = $test_id;
        data["test_title"] = $("#editTestTitle").val();
        data["subject_id"] = $("#editTestSubject").val();
        data["max_grade"] = $("#editTestMaxGrade").val();
        data["number_of_questions"] = $("#editTestQuestionCount").val();//Number of questions in this test
        data["difficulty"] = $("#editTestDifficulty").val();
        data["passing_grade"] = $("#editTestPassGrade").val();
        data["time_to_complete"] = $("#editTestCompletionTime").val();
        data["test_description"] = $("#editTestInstructions").val();

        return data;
    }

    /*DELETE TEST*/
    var $deleteTestBtn = $("a.deleteTest");//Cache the delete test button for easier future reference
    
    //When the delete test button is clicked
    $deleteTestBtn.click(function(){
        var delay_time = 4500;//Time to delay before deleting the test
        
        //Test variables
        var $test_id = $(this).attr("data-test-id");
        var $test_container = $(this).parents(".take_test_container");
        var $test_title = $test_container.find("span.takeTestTitle").text();

        //Deleting test here
        var toastMessage = '<p class="white-text" data-test-id="' + $test_id + '">Preparing to delete the test  <i>'+$test_title+'  </i><a href="#!" class="bold" id="toastUndoAction" >  UNDO</a></p>';
        
        
        var toastCall = Materialize.toast(toastMessage, delay_time, '', function (s) {
                //ajax
                $.post("handlers/db_handler.php", {"action" : "DeleteTest", "test_id" : $test_id}, function (result) {
                   
                    //remove from DOM
                    $test_container.remove();
                    
                    console.log(result);
                    
                }, 'text');
                
            });
            
    });
    
    /*SKIPPED QUESTIONS*/
    $(".skipped_questions_btn").click(function(){
        alert("Show skipped questions");
    });
 });

