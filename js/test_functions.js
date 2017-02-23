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
    var $editTest = $("a.editTest");
    $editTest.addClass("modal-trigger");//Add the class modal trigger to all edit test buttons

    //Edit test button clicked
    $editTest.click(function(){
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
                var edit_data = GetEditTestModalData();
                console.log("edit data");
                console.log(edit_data);
                $.post("handlers/db_handler.php",{"action":"UpdateEditTest","data":edit_data});
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
        $("#editTestMaxGrade").val(data["max_grade"]);        
        $("#editTestPassGrade").val(data["passing_grade"]);
        $("#editTestCompletionTime").val(data["time_to_complete"]);
        $("#editTestInstructions").val(data["test_description"]);

    }

    //Get the data from the edit test modal
    function GetEditTestModalData()
    {
        var data = {};
        data["test_title"] = $("#editTestTitle").val();
        data["subject_id"] = $("#editTestSubject").val();
        data["max_grade"] = $("#editTestMaxGrade").val();
        data["difficulty"] = $("#editTestDifficulty").val();
        data["passing_grade"] = $("#editTestPassGrade").val();
        data["time_to_complete"] = $("#editTestCompletionTime").val();
        data["test_description"] = $("#editTestInstructions").val();

        return data;
    }

 });

