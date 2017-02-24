/*global $, jQuery, alert, console*/

var Forms_Templates = function () {
    'use strict';
    
    //--------------
    
    this.__construct = function () {
        console.log('Forms templates created');
        
    };
    
    //--------------------------
    
    this.createClassroomForm = function (obj) {
        var templateOutput = '';
        
        templateOutput += '<br><div class="row"><form id="createNewClassroomForm" class="col s12 m10 offset-m1" method="post" action="">';
        templateOutput += '<div class="row input-field card-color-list">';
        templateOutput += '<p class="col m4 s12" >Choose a color for the classroom</p>';
        templateOutput += '<p class="col m1 s2" ><input name="card_color" type="radio" id="cyan"/><label for="cyan" class="cyan darken-4"></label></p>';
        templateOutput += '<p class="col m1 s2" ><input name="card_color" type="radio" id="blue"/><label for="blue" class="blue darken-4"></label></p>';
        templateOutput += '<p class="col m1 s2" ><input name="card_color" type="radio" id="pink"/><label for="pink" class="pink darken-4"></label></p>';
        templateOutput += '<p class="col m1 s2" ><input name="card_color" type="radio" id="orange"/><label for="orange" class="orange darken-4"></label></p>';
        templateOutput += '<p class="col m1 s2" ><input name="card_color" type="radio" id="blueGrey"/><label for="blueGrey" class="blue-grey darken-4"></label></p>';
        templateOutput += '<p class="col m1 s2" ><input name="card_color" type="radio" id="green"/><label for="green" class="green darken-4"></label></p>';
        templateOutput += '<p class="col m1 s2" ><input name="card_color" type="radio" id="purple"/><label for="purple" class="purple darken-4"></label></p>';
        templateOutput += '<p class="col m1 s2" ><input name="card_color" type="radio" id="lime"/><label for="lime" class="lime darken-4"></label></p>';
        templateOutput += '</div>';
        templateOutput += '<div class="row"><div class="input-field col s12">';
        templateOutput += '<input id="newClassroomName" type="text" class="validate" name="new_classroom_name" required length="21">';
        templateOutput += '<label for="newClassroomName">Class name</label>';
        templateOutput += '</div></div>';
        templateOutput += '<div class="row"><div class="input-field col s12">';
        templateOutput += '<select id="newClassroomStream" name="class_stream" required class="grey-text text-lighten-2">';
        //loop through classes the teacher teaches via ajax
        templateOutput += obj.streamoptions;
        templateOutput += '</select>';
        templateOutput += '<label>Stream</label>';
        templateOutput += '</div></div>';
        templateOutput += '<div class="row"><div class="input-field col s12">';
        templateOutput += '<select id="newClassroomSubject" name="class_subject" required class="grey-text text-lighten-2">';
        templateOutput += obj.subjectoptions;
        templateOutput += '</select>';
        templateOutput += '<label>Subject</label>';
        templateOutput += '</div></div>';
        templateOutput += '<div class="row"><div class="input-field col s12"><p>';
        templateOutput += '<input type="checkbox" id="addStudentsToClassroom" name="add_students_to_classroom" value="GetAllStudents" />';
        templateOutput += '<label for="addStudentsToClassroom">Add students before creating</label>';
        templateOutput += '</p></div></div>';
        templateOutput += '<div class="row student-list input-field"></div>';
        templateOutput += '<div class="row"><div class="input-field col s12">';
        templateOutput += '<a class="right btn" id="createNewClassroomCard" type="submit">Create classroom</a>';
        templateOutput += '</div></div>';
        templateOutput += '</form></div>';
        
        return templateOutput;
    };
    
    //--------------------------
    
    this.createAssignmentForm = function () {
        var templateOutput = '';
        
        
        return templateOutput;
    };
    
    //--------------------------
    
    this.createCommentForm = function () {
        var templateOutput = '';
        
        templateOutput += '<br><form id="createNewComment" class="col s12 m8 offset-m2" method="post" action="">';
        templateOutput += '<div class="row"><div class="input-field col s8">';
        templateOutput += '<div class="col s4"><a type="submit" class="btn" onclick="submitComment()">Comment</a></div>';
        templateOutput += '</div></div>';
        templateOutput += '</form>';
        
        return templateOutput;
    };
    
    //--------------------------
    
    this.editClassroomForm = function (obj) {
        var templateOutput = '';
        
        templateOutput += '<h6 class="grey-text text-darken-2">Choosing different values will update the classroom.</h6>';
        templateOutput += '<br><div class="divider"></div>';
        templateOutput += '<br><div class="row"><form id="editClassroomForm" class="col s12 m10 offset-m1" method="post" action="">';
        templateOutput += '<div class="row input-field card-color-list">';
        templateOutput += '<p class="col m4 s12" >Choose a color for the classroom</p>';
        templateOutput += '<p class="col m1 s2" ><input name="card_color" type="radio" id="cyan"/><label for="cyan" class="cyan darken-4"></label></p>';
        templateOutput += '<p class="col m1 s2" ><input name="card_color" type="radio" id="blue"/><label for="blue" class="blue darken-4"></label></p>';
        templateOutput += '<p class="col m1 s2" ><input name="card_color" type="radio" id="pink"/><label for="pink" class="pink darken-4"></label></p>';
        templateOutput += '<p class="col m1 s2" ><input name="card_color" type="radio" id="orange"/><label for="orange" class="orange darken-4"></label></p>';
        templateOutput += '<p class="col m1 s2" ><input name="card_color" type="radio" id="blueGrey"/><label for="blueGrey" class="blue-grey darken-4"></label></p>';
        templateOutput += '<p class="col m1 s2" ><input name="card_color" type="radio" id="green"/><label for="green" class="green darken-4"></label></p>';
        templateOutput += '<p class="col m1 s2" ><input name="card_color" type="radio" id="purple"/><label for="purple" class="purple darken-4"></label></p>';
        templateOutput += '<p class="col m1 s2" ><input name="card_color" type="radio" id="lime"/><label for="lime" class="lime darken-4"></label></p>';
        templateOutput += '</div>';
        templateOutput += '<div class="row"><div class="input-field col s12">';
        templateOutput += '<input id="editClassroomName" type="text" class="validate" name="edit_classroom_name" required>';
        templateOutput += '<label for="editClassroomName">Class name</label>';
        templateOutput += '</div></div>';
        templateOutput += '<div class="row"><div class="input-field col s12">';
        templateOutput += '<select id="editClassroomStream" name="class_stream" required class="grey-text text-lighten-2">';
        //loop through classes the teacher teaches via ajax
        templateOutput += obj.streamoptions;
        templateOutput += '</select>';
        templateOutput += '<label>Stream</label>';
        templateOutput += '</div></div>';
        templateOutput += '<div class="row"><div class="input-field col s12">';
        templateOutput += '<select id="editClassroomSubject" name="class_subject" required class="grey-text text-lighten-2">';
        templateOutput += obj.subjectoptions;
        templateOutput += '</select>';
        templateOutput += '<label>Subject</label>';
        templateOutput += '</div></div>';
        templateOutput += '<div class="row"><div class="input-field col s12"><p>';
        templateOutput += '<a class="btn btn-flat" id="addMoreStudentsToClassroom" data-action="GetAllStudentsNotInClass">Add more students</a>';
        templateOutput += '</p></div></div>';
        templateOutput += '<div class="row student-list input-field"></div>';
        templateOutput += '<div class="row"><div class="input-field col s12">';
        templateOutput += '<a class="right btn" id="editClassroomCard" type="submit">Update classroom</a>';
        templateOutput += '</div></div>';
        templateOutput += '</form></div>';
        
        return templateOutput;
    };
    
    //--------------------------
    
    this.editAssignmentForm = function () {
        var templateOutput = '';
        
        
        return templateOutput;
    };
    
    //--------------------------
    
    this.createTestForm = function () {
        
        
        var templateOutput = '';
        
        
        return templateOutput;
    };
    
    //--------------------------
    
    this.editTestForm = function (obj) {
        
        var templateOutput = '';
        
        templateOutput += '<div class="row" id="editTestForm">';
        templateOutput += '<div class=" input-field col s12 m6">';
        templateOutput += '<input type="text" id="editTestTitle" name="edit_test_title" placeholder="Test Title" class="validate" required>';
        templateOutput += '<label for="editTestTitle">Title</label></div>';
        templateOutput += '<div class="input-field col s12 m6">';
        templateOutput += '<select id="editTestSubject">';
        templateOutput += obj.subjectoptions;//Will have done the loop by calling Forms_Templates.formSelectTemplate function from the events function
        templateOutput += '</select><label for="editTestSubject">Subject</label></div>';
        templateOutput += '<div class=" input-field col s12 m6">';
        templateOutput += '<input type="number" id="editTestQuestionCount" name="edit_test_question_count" min="1" max="50" value="10" class="validate" required>';
        templateOutput += '<label for="editTestQuestionCount">No. of questions</label></div>';
        templateOutput += '<div class=" input-field col s12 m6">';
        templateOutput += '<select id="editTestDifficulty" name="edit_test_difficulty" class="validate" required>';
        templateOutput += '<option value="Very Easy">Very Easy</option>';
        templateOutput += '<option value="Easy">Easy</option>';
        templateOutput += '<option value="Moderate">Moderate</option>';
        templateOutput += '<option value="Difficult">Difficult</option>';
        templateOutput += '<option value="Very Difficult">Very Difficult</option>';
        templateOutput += '</select><label for="editTestDifficulty">Difficulty</label>';
        templateOutput += '</div><div class=" input-field col s12 m4">';
        templateOutput += '<input type="number" id="editTestMaxGrade" name="edit_test_max_grade" min="10" max="100" value="100" class="validate" required>';
        templateOutput += '<label for="editTestMaxGrade">Max grade</label></div>';
        templateOutput += '<div class=" input-field col s12 m4">';
        templateOutput += '<input type="number" id="editTestPassGrade" name="edit_test_pass_grade" min="10" max="100" value="50" class="validate" required>';
        templateOutput += '<label for="editTestPassGrade">Passing grade</label></div>';
        templateOutput += '<div class=" input-field col s12 m4">';
        templateOutput += '<input type="number" id="editTestCompletionTime" step="5" name="edit_test_completion_time" class="validate" min="10" max="45" value="30" required>';
        templateOutput += '<label for="editTestCompletionTime">Time (Minutes)</label></div>';
        /*Retake delays*/
        /*
        templateOutput += '<div class=" input-field col s12 m4">';
        templateOutput += '<input type="number" id="editTestRetakeDelay_days" name="edit_test_rDelay_days" min="0" max="100" value="0" class="validate" required>';
        templateOutput += '<label for="editTestRetakeDelay_days">Retake Delay (Days)</label>';
        templateOutput += '</div><div class=" input-field col s12 m4">';
        templateOutput += '<input type="number" id="editTestRetakeDelay_hours" name="edit_test_rDelay_hours" min="0" max="100" value="0" class="validate" required>';
        templateOutput += '<label for="editTestRetakeDelay_hours">Retake Delay (Hours)</label>';
        templateOutput += '</div><div class=" input-field col s12 m4">';
        templateOutput += '<input type="number" id="editTestRetakeDelay_min" name="edit_test_rDelay_min" min="10" max="100" value="30" class="validate" required>';
        templateOutput += '<label for="editTestRetakeDelay_min">Retake Delay (Minutes)</label>';
        templateOutput += '</div>';
        */
        templateOutput += '<div class=" input-field col s12 ">';
        templateOutput += '<textarea id="editTestInstructions" class="materialize-textarea" placeholder="Instructions students will get for the test"></textarea>';
        templateOutput += '<label for="editTestInstructions">Test instructions</label>';
        templateOutput += '</div>';
        templateOutput += '<div class="row"><div class=" input-field col s12 ">';
        templateOutput += '<a href="javascript:void(0)" class="btn" id="UpdateEditTest">DONE</a>';
        templateOutput += '</div></div></div>';

        return templateOutput;
    };
    
    //--------------------------
    
    this.makeStudentFormList = function (obj) {
        
        var templateOutput = '';
        
        //searchBar
        templateOutput += '<div class="row"><div class="input-field col s12">';
        templateOutput += '<p class="col s6 m4">';
        templateOutput += '<input type="checkbox" id="selectAll" />';
        templateOutput += '<label for="selectAll">Select all</label>';
        templateOutput += '</p>';
        templateOutput += '<div class="col s6 m8 search-wrapper">';
        templateOutput += '<i class="material-icons prefix">search</i>';
        templateOutput += '<input type="search" class="transparent autocomplete" id="searchStudentFormList">';
        templateOutput += '<i id="cancelSearch" class="mdi-navigation-close material-icons prefix">close</i>';
        templateOutput += '<div class="search-results"></div>';
        templateOutput += '</div>';
        templateOutput += '</div></div>';
        templateOutput += '<div class="divider"></div>';
        templateOutput += '<div class="row"><div class="input-field col s12 list">';
        //loop
        templateOutput += obj.formData;
        //loop end
        templateOutput += '</div></div>';
        
        return templateOutput;
    };
    
    //--------------------------
    
    this.formOptionsTemplate = function (obj) {
        
        var templateOutput = '';
        
        templateOutput += '<p class="col s6 m4">';
        templateOutput += '<input type="checkbox" class="filled-in" id="' + obj.id + '" />';
        templateOutput += '<label for="' + obj.id + '">' + obj.name + '</label>';
        templateOutput += '</p>';
        
        return templateOutput;
        
    };
     
    //--------------------------
    
    this.formSelectTemplate = function (obj) {
        
        var templateOutput = '';
        
        templateOutput += '<option value="' + obj.value + '">' + obj.name + '</option>';
        
        return templateOutput;
        
    };
    
    //--------------------------
    
    this.formOptgroupTemplate = function (obj) {
        
        var templateOutput = '';
        
        templateOutput += '<optgroup label="' + obj.category + '">' + obj.categorylist + '</optgroup>';
        
        return templateOutput;
        
    };
    
    //--------------------------
    
    this.__construct();
    
};
