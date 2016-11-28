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
        templateOutput += '<div class="row"><div class="input-field col s12">';
        templateOutput += '<input id="newClassroomName" type="text" class="validate" name="new_classroom_name" required>';
        templateOutput += '<label for="newClassroomName">Class name</label>';
        templateOutput += '</div></div>';
        templateOutput += '<div class="row"><div class="input-field col s12">';
        templateOutput += '<select id="newClassroomStream" name="class_stream" required class="grey-text text-lighten-2">';
        //loop through classes the teacher teaches via ajax
        templateOutput += '<option value="9A" selected>9A</option>';
        templateOutput += '<option value="9B">9B</option>';
        templateOutput += '</select>';
        templateOutput += '<label>Stream</label>';
        templateOutput += '</div></div>';
        templateOutput += '<div class="row"><div class="input-field col s12">';
        templateOutput += '<select id="newClassroomSubject" name="class_subject" required class="grey-text text-lighten-2">';
        templateOutput += '<optgroup label="' + obj.optgroupname + '">';
        templateOutput += '<option value="' + obj.subjectoption1 + '" selected>' + obj.subjectoption1 + '</option>';
        templateOutput += '<option value="' + obj.subjectoption2 + '">' + obj.subjectoption2 + '</option>';
        templateOutput += '</optgroup>';
        templateOutput += '<optgroup label="' + obj.optgroupname2 + '">';
        templateOutput += '<option value="' + obj.subjectoption3 + '">' + obj.subjectoption3 + '</option>';
        templateOutput += '<option value="' + obj.subjectoption4 + '">' + obj.subjectoption4 + '</option>';
        templateOutput += '</optgroup>';
        templateOutput += '</select>';
        templateOutput += '<label>Subject</label>';
        templateOutput += '</div></div>';
        templateOutput += '<div class="row"><div class="input-field col s12"><p>';
        templateOutput += '<input type="checkbox" id="addStudentsToClassroom" name="add_students_to_classroom" />';
        templateOutput += '<label for="addStudentsToClassroom">Add students before creating</label>';
        templateOutput += '</p></div></div>';
        templateOutput += '<div class="row student-list input-field"></div>';
        templateOutput += '<div class="row"><div class="input-field col s12"><p>';
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
    
    this.editClassroomForm = function () {
        var templateOutput = '';
        
        
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
    
    this.createTestForm = function () {
        
        var templateOutput = '';
        
        
        return templateOutput;
    };
    
    //--------------------------
    
    this.makeStudentFormList = function (str) {
        
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
        templateOutput += str;
        //loop end
        templateOutput += '</div></div>';
        
        return templateOutput;
    };
    
    //--------------------------
    
    this.formOptionsTemplate = function (obj) {
        
        var templateOutput = '';
        
        templateOutput += '<p class="col s6 m4">';
        templateOutput += '<input type="checkbox" class="filled-in" id="' + obj.value + '" />';
        templateOutput += '<label for="' + obj.value + '">' + obj.name + '</label>';
        templateOutput += '</p>';
        
        return templateOutput;
        
    }
    
    //--------------------------
    
    this.__construct();
    
};