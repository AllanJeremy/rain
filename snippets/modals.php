<?php

class Modals
{
    //Constants for the different admin account types
    const TEACHER_ACCOUNT = "teacher";
    const PRINCIPAL_ACCOUNT = "principal";
    const SUPERUSER_ACCOUNT = "superuser";

    public static function ModalTemplate($modalId, $modalHeader, $body, $extraActions)#WORKING
    {
        $templateOutput = '';
        
        $templateOutput .= '<div id="'.$modalId.'" class="modal modal-fixed-footer">';
        $templateOutput .= '<div class="modal-content">';
        $templateOutput .= '<h4>'.$modalHeader.'</h4>';
        $templateOutput .= $body;
        $templateOutput .= '</div>';
        $templateOutput .= '<div class="modal-footer">';
        
        if ($extraActions != '') {
            $templateOutput .= $extraActions;
          
        }
        
        $templateOutput .= '<a href="javascript:void(0)" id="modalFooterCloseAction" class=" modal-action modal-close waves-effect waves-green btn-flat">close</a>';
        $templateOutput .= '</div></div>';
        
        return $templateOutput;
    }
    
    public static function EsomoModalTemplate($modalId, $modalHeader, $body, $modalAction, $progress = 0)#WORKING
    {
        $warningClass = 'red';
        $okayClass = 'green';
        $infoClass = 'amber';
        $noneClass = 'grey';
        $colorClass = $noneClass;
        $textColorClass = $noneClass.'-text';
        $templateOutput = '';
        
        if ($progress == 0) {
            $colorClass = $noneClass;
            $textColorClass = $noneClass.'-text text-lighten-1';
        
        } elseif ($progress >= 1 && $progress <= 31) {
            $colorClass = $okayClass.' lighten-2';
            $textColorClass = $okayClass.'-text text-darken-2';
        
        } elseif ($progress >= 31 && $progress <= 80) {
            $colorClass = $okayClass.' lighten-2';
            $textColorClass = $okayClass.'-text text-darken-2';
        
        } elseif ($progress >= 81 && $progress <= 92) {
            $colorClass = $infoClass;
            $textColorClass = $infoClass.'-text';
        
        } elseif ($progress >= 93 && $progress <= 100) {
            $colorClass = $warningClass;
            $textColorClass = $warningClass.'-text';
        
        } else {
            // alternative??
        }
        
        $templateOutput .= '<div id="esomoModal'.$modalId.'" class="esomo-modal modal modal-fixed-footer">';
        $templateOutput .= '<div class="modal-content">';
        $templateOutput .= '<h4>'.$modalHeader.'</h4>';
        $templateOutput .= $body;
        $templateOutput .= '</div>';
        $templateOutput .= '<div class="modal-footer row">';
        $templateOutput .= '<div class="col s12 m6"><div class="progress modal-progress"><div class="determinate '.$colorClass.'" style="width:'.$progress.'%;"><span class=" '.$textColorClass.' ">'.$progress.'%</span></div></div></div>';
        $templateOutput .= '<div class="col m3 s6"><a href="javascript:void(0)" id="modalFooterCloseAction" class="right modal-action modal-close waves-effect waves-red red-text btn-flat">close</a></div>';
        $templateOutput .= '<div class="col m3 s6"><a href="javascript:void(0)" id="modalFooterActionAdd" class="right modal-action waves-effect waves-green btn">'.$modalAction.'</a></div>';
        $templateOutput .= '</div>';
        $templateOutput .= '</div>';
        $templateOutput .= '</div>';
        
        return $templateOutput;
    }

    public static function commentsModalTemplate($modalId, $id, $title, $extraInfo, $modalAction, $commentType, $canComment)#WORKING
    {
        $templateOutput = '';
        $comment_type = '';

        switch ($commentType) {

        case 'schedule':
            $comment_type = 'schedule';

            break;
        case 'assignment':
            $comment_type = 'assignment';
            break;
        case 'ass_submission':
            $comment_type = 'assignment submission';
            break;
        default:
            $comment_type = '';
            break;
        }

        $Title = (($comment_type != '') ? $title . " comments " . $extraInfo : 'Comments');
        
        $templateOutput .= '';
        
        return $templateOutput;
        
    }
    
    public static function ResourceModalTemplate($modalId, $modalHeader)#WORKING
    {
        $templateOutput = '';

        $templateOutput .= '<div id="' . $modalId . '" class="modal modal-fixed-footer">';
        $templateOutput .= '<div class="modal-content"><div class="js-drag-drop-area">';
        $templateOutput .= '<h4 class="white-text">' . $modalHeader . '</h4>';
        $templateOutput .= '<div class="row no-margin">';
        $templateOutput .= '<div id="resourcesTotalInfo" class="col m6 s12">';
        $templateOutput .= '<h6 class=" op-4">To upload</h6>';
        $templateOutput .= '<h4 class="white-text"><span id="totalResources">0</span> files</h4>';
        $templateOutput .= '<br><div class="progress" style="width:0%;"><div class="determinate" style="width:0%;"></div></div>';
        $templateOutput .= '<h6 class="num-progress hide rain-theme-primary-text text-lighten-3"><i>Uploading <span class="js-num-progress">0%</span></i></h6>';
        $templateOutput .= '</div>';
        $templateOutput .= '<div class="col m6 s12">';
        $templateOutput .= '<form id="createResourcesForm">';
        $templateOutput .= '<div class=" input-field col s12 file-field ">';
        $templateOutput .= '<div class="btn right">';
        $templateOutput .= '<span>add resources</span>';
        $templateOutput .= '<input type="file" multiple name="resources">';
        $templateOutput .= '</div>';
/*
        $templateOutput .= '<div class="file-path-wrapper">';
        $templateOutput .= '<input class="file-path validate" type="text" placeholder="Upload one or more files">';
        $templateOutput .= '</div>';
*/
        $templateOutput .= '</div>';
        $templateOutput .= '<input type="submit" name="submitBtn" class="hide btn material-icons btn-floating btn-large upload-btn" value="&#xE2C6;" />';
        //$templateOutput .= '<iframe id="upload_target" name="upload_target" src="#" style="width:0;height:0;border:0px solid transparent;"></iframe>';
        $templateOutput .= '</form>';
        $templateOutput .= '<div style="padding-top:20px;margin-top:20px;" class="hide-on-med-and-down"><br><h6 class="right-align op-4">or drag and drop on the colored area.</h6>';
        $templateOutput .= '</div></div></div></div>';
        $templateOutput .= '<div class="row no-margin" id="errorContainer"><ul></ul></div>';
        $templateOutput .= '<div class="row" id="resourcesList"><div class="container" >';
        $templateOutput .= '</div></div>';
        $templateOutput .= '</div>';
        $templateOutput .= '<div class="modal-footer">';
        $templateOutput .= '<a href="javascript:void(0)" id="modalFooterCloseAction" class=" modal-action modal-close waves-effect waves-red btn-flat">close</a>';
        $templateOutput .= '<a href="javascript:void(0)" id="uploadResource" class=" modal-action waves-effect waves-green btn disabled"><i class="material-icons left">&#xE2C6;</i>upload</a>';
        $templateOutput .= '</div>';
        $templateOutput .= '</div>';

        return $templateOutput;
    }
    
    public static function CreateClassroomTemplate($classStreams = '')#WORKING
    {
        $templateOutput = '';
		
		$templateOutput .= '<br><div class="row"><form id="createNewClassroomForm" class="col s12 m10 offset-m1" method="post" action="">';
		$templateOutput .= '<div class="row input-field card-color-list">';
		$templateOutput .= '<p class="col m4 s12" >Choose a color for the classroom</p>';
		$templateOutput .= '<p class="col m1 s2" ><input name="card_color" type="radio" id="cyan_create"/><label for="cyan_create" class="cyan darken-4"></label></p>';
		$templateOutput .= '<p class="col m1 s2" ><input name="card_color" type="radio" id="blue_create"/><label for="blue_create" class="blue darken-4"></label></p>';
		$templateOutput .= '<p class="col m1 s2" ><input name="card_color" type="radio" id="pink_create"/><label for="pink_create" class="pink darken-4"></label></p>';
		$templateOutput .= '<p class="col m1 s2" ><input name="card_color" type="radio" id="orange_create"/><label for="orange_create" class="orange darken-4"></label></p>';
		$templateOutput .= '<p class="col m1 s2" ><input name="card_color" type="radio" id="blueGrey_create"/><label for="blueGrey_create" class="blue-grey darken-4"></label></p>';
		$templateOutput .= '<p class="col m1 s2" ><input name="card_color" type="radio" id="green_create"/><label for="green_create" class="green darken-4"></label></p>';
		$templateOutput .= '<p class="col m1 s2" ><input name="card_color" type="radio" id="purple_create"/><label for="purple_create" class="purple darken-4"></label></p>';
		$templateOutput .= '<p class="col m1 s2" ><input name="card_color" type="radio" id="lime_create"/><label for="lime_create" class="lime darken-4"></label></p>';
		$templateOutput .= '</div>';
		$templateOutput .= '<div class="row"><div class="input-field col s12">';
		$templateOutput .= '<input id="newClassroomName" type="text" class="validate" name="new_classroom_name" required length="21">';
		$templateOutput .= '<label for="newClassroomName">Class name</label>';
		$templateOutput .= '</div></div>';
		$templateOutput .= '<div class="row"><div class="input-field col s12">';
		$templateOutput .= '<select id="newClassroomStream" name="class_stream" required class="grey-text text-lighten-2">';
		//loop through classes the teacher teaches via ajax
		$templateOutput .= '<option value="1">Alpha</option><option value="2">Charlie</option><option value="3">Black</option><option value="4">Indigo</option>';
		$templateOutput .= $classStreams;
		$templateOutput .= '</select>';
		$templateOutput .= '<label>Stream</label>';
		$templateOutput .= '</div></div>';
		$templateOutput .= '<div class="row"><div class="input-field col s12">';
		$templateOutput .= '<select id="newClassroomSubject" name="class_subject" required class="grey-text text-lighten-2">';
		$templateOutput .= '<optgroup label="sciences"><option value="1">Mathematics</option><option value="5">Physics</option><option value="6">Biology</option><option value="7">Chemistry</option></optgroup><optgroup label="languages"><option value="3">Kiswahili</option><option value="4">French</option><option value="9">Literature</option></optgroup><optgroup label="humanities"><option value="8">Religion</option><option value="13">History</option></optgroup><optgroup label="extras"><option value="14">Art and Design</option><option value="15">ICT</option><option value="16">Physical Education</option><option value="17">Music</option><option value="18">Business studies</option></optgroup>';
		$templateOutput .= '</select>';
		$templateOutput .= '<label>Subject</label>';
		$templateOutput .= '</div></div>';
		$templateOutput .= '<div class="row"><div class="input-field col s12"><p>';
		$templateOutput .= '<input type="checkbox" id="addStudentsToClassroom" name="add_students_to_classroom" value="GetAllStudents" />';
		$templateOutput .= '<label for="addStudentsToClassroom">Add students before creating</label>';
		$templateOutput .= '</p></div></div>';
		$templateOutput .= '<div class="row student-list input-field"></div>';
		$templateOutput .= '<div class="row"><div class="input-field col s12">';
		$templateOutput .= '<a class="right btn" id="createNewClassroomCard" type="submit">Create classroom</a>';
		$templateOutput .= '</div></div>';
		$templateOutput .= '</form></div>';
		
		return $templateOutput;
    }
    
    public static function EditClassroomTemplate($classStreams = '')#WORKING
    {
        $templateOutput = '';
		
		$templateOutput .= '<h6 class="grey-text text-darken-2">Choosing different values will update the classroom.</h6>';
		$templateOutput .= '<br><div class="divider"></div>';
		$templateOutput .= '<br><div class="row"><form id="editClassroomForm" class="col s12 m10 offset-m1" method="post" action="">';
		$templateOutput .= '<div class="row input-field card-color-list">';
		$templateOutput .= '<p class="col m4 s12" >Choose a color for the classroom</p>';
		$templateOutput .= '<p class="col m1 s2" ><input name="card_color" type="radio" id="cyan"/><label for="cyan" class="cyan darken-4"></label></p>';
		$templateOutput .= '<p class="col m1 s2" ><input name="card_color" type="radio" id="blue"/><label for="blue" class="blue darken-4"></label></p>';
		$templateOutput .= '<p class="col m1 s2" ><input name="card_color" type="radio" id="pink"/><label for="pink" class="pink darken-4"></label></p>';
		$templateOutput .= '<p class="col m1 s2" ><input name="card_color" type="radio" id="orange"/><label for="orange" class="orange darken-4"></label></p>';
		$templateOutput .= '<p class="col m1 s2" ><input name="card_color" type="radio" id="bluegrey"/><label for="bluegrey" class="blue-grey darken-4"></label></p>';
		$templateOutput .= '<p class="col m1 s2" ><input name="card_color" type="radio" id="green"/><label for="green" class="green darken-4"></label></p>';
		$templateOutput .= '<p class="col m1 s2" ><input name="card_color" type="radio" id="purple"/><label for="purple" class="purple darken-4"></label></p>';
		$templateOutput .= '<p class="col m1 s2" ><input name="card_color" type="radio" id="lime"/><label for="lime" class="lime darken-4"></label></p>';
		$templateOutput .= '</div>';
		$templateOutput .= '<div class="row"><div class="input-field col s12">';
		$templateOutput .= '<input id="editClassroomName" type="text" class="validate" name="edit_classroom_name" required>';
		$templateOutput .= '<label for="editClassroomName">Class name</label>';
		$templateOutput .= '</div></div>';
		$templateOutput .= '<div class="row"><div class="input-field col s12">';
		$templateOutput .= '<select id="editClassroomStream" name="class_stream" required class="grey-text text-lighten-2">';
		//loop through classes the teacher teaches via ajax
		$templateOutput .= '<option value="1">Alpha</option><option value="2">Charlie</option><option value="3">Black</option><option value="4">Indigo</option>';
		$templateOutput .= $classStreams;
		$templateOutput .= '</select>';
		$templateOutput .= '<label>Stream</label>';
		$templateOutput .= '</div></div>';
		$templateOutput .= '<div class="row"><div class="input-field col s12">';
		$templateOutput .= '<select id="editClassroomSubject" name="class_subject" required class="grey-text text-lighten-2">';
		$templateOutput .= '<optgroup label="sciences"><option value="1">Mathematics</option><option value="5">Physics</option><option value="6">Biology</option><option value="7">Chemistry</option></optgroup><optgroup label="languages"><option value="3">Kiswahili</option><option value="4">French</option><option value="9">Literature</option></optgroup><optgroup label="humanities"><option value="8">Religion</option><option value="13">History</option></optgroup><optgroup label="extras"><option value="14">Art and Design</option><option value="15">ICT</option><option value="16">Physical Education</option><option value="17">Music</option><option value="18">Business studies</option></optgroup>';
//		$templateOutput .= obj.subjectoptions;
		$templateOutput .= '</select>';
		$templateOutput .= '<label>Subject</label>';
		$templateOutput .= '</div></div>';
		$templateOutput .= '<div class="row"><div class="input-field col s12"><p>';
		$templateOutput .= '<a class="btn btn-flat" id="addMoreStudentsToClassroom" data-action="GetAllStudentsNotInClass">Add more students</a>';
		$templateOutput .= '</p></div></div>';
		$templateOutput .= '<div class="row student-list input-field"></div>';
		$templateOutput .= '<div class="row"><div class="input-field col s12">';
		$templateOutput .= '<a class="right btn" id="editClassroomCard" type="submit">Update classroom</a>';
		$templateOutput .= '</div></div>';
		$templateOutput .= '</form></div>';
		
		return $templateOutput;
    }
    
    public static function EditResourceTemplate($Subjects = '')#WORKING
    {
        $templateOutput = '';

		$templateOutput .= '<div class="row"><form id="editResourceForm" class="col s12 m10 offset-m1" method="post" action="">';
		$templateOutput .= '<div class="row no-margin">';
		$templateOutput .= '<div class="input-field col s12">';
		$templateOutput .= '<select id="resourceSubjectType" name="resource_subject" required class="browser-default">';
		$templateOutput .= '<optgroup label="sciences"><option value="1">Mathematics</option><option value="5">Physics</option><option value="6">Biology</option><option value="7">Chemistry</option></optgroup><optgroup label="languages"><option value="3">Kiswahili</option><option value="4">French</option><option value="9">Literature</option></optgroup><optgroup label="humanities"><option value="8">Religion</option><option value="13">History</option></optgroup><optgroup label="extras"><option value="14">Art and Design</option><option value="15">ICT</option><option value="16">Physical Education</option><option value="17">Music</option><option value="18">Business studies</option></optgroup>';
		//$templateOutput .= obj.subjectoptions;
		$templateOutput .= '</select>';
		//$templateOutput .= '<label>Subject</label>';
		$templateOutput .= '<br></div>';
		$templateOutput .= '<br><div class="input-field no-margin col s12">';
		$templateOutput .= '<textarea id="resourceDescription" class="materialize-textarea"></textarea>';
		$templateOutput .= '<label for="resourceDescription">Description</label></div>';
		$templateOutput .= '</div>';
		$templateOutput .= '</form></div>';
		$templateOutput .= '<div class="row">';
		$templateOutput .= '<div class="col s12 m10 offset-m1">';
		$templateOutput .= '<a class="btn" id="updateResource" data-res-id="' . id . '">Update</a>';
		$templateOutput .= '</div></div>';

		return $templateOutput;
    }  
    
    public static function studentFormList($formData = '')#WORKING
    {
        $templateOutput = '';
		
		//searchBar
		$templateOutput .= '<div class="row"><div class="input-field col s12">';
		$templateOutput .= '<p class="col s12 m4 no-margin">';
		$templateOutput .= '<input type="checkbox" id="selectAll" />';
		$templateOutput .= '<label for="selectAll">Select all</label>';
		$templateOutput .= '</p>';
		$templateOutput .= '<div class="col s12 m8 search-wrapper">';
		$templateOutput .= '<div class="row"><div class="input-field margin-horiz-8 col s12">';
		$templateOutput .= '<i class="material-icons prefix">search</i>';
		$templateOutput .= '<input type="search" class="transparent autocomplete" id="searchStudentFormList">';
		$templateOutput .= '<label for="searchStudentFormList-input"  class="hide">Student\'s name</label>';
		$templateOutput .= '<i id="cancelSearch" class="mdi-navigation-close material-icons prefix">close</i>';
		$templateOutput .= '<div class="search-results"></div>';
		$templateOutput .= '</div></div>';
		$templateOutput .= '</div>';
		$templateOutput .= '</div></div>';
		$templateOutput .= '<div class="divider"></div>';
		$templateOutput .= '<div class="row" id="formData"><div class="input-field col s12 list">';
		$templateOutput .= '</div></div>';
        
        return $templateOutput;
    }
}

?>