<?php

//Interface for functions that must be implemented by this class
interface EsomoModalFunctions
{    
    /*PUBLICLY CALLABLE MODALS*/
    //Classroom modals
    public static function DisplayCreateClassroomModal(); #Add a Create Classroom modal to the dom
    public static function DisplayEditClassroomModal(); #Add an Edit Classroom modal to the dom
    public static function DisplayAddClassroomStudentsModal(); #Add an Add Classroom Students modal to the dom
    public static function DisplayRemoveClassroomStudentsModal(); #Add a Remove Classroom Students modal to the dom
    public static function DisplayViewClassroomStudentsModal(); #Add a View Classroom Students modal to the dom
    public static function DisplayViewClassroomAssSentModal(); #Add a View Classroom Assignments sent modal to the dom

    //Assignment modals

    //Schedule modals

    //Statistics modals
};
//TODO : Add global variables for getting the subjects and streams array

// A class for handling adding of modals to the dom
class EsomoModal implements EsomoModalFunctions
{

/* PRIVATE FUNCTIONS ~ HELPER FUNCTIONS */
    
    //Begins a generic modal
    private static function T_BeginGenericModal($modal_id,$modal_title=null,$title_tag="h4")
    {

    }

    //Closes a generic modal's divs
    private static function T_EndGenericModal()
    {

    }
    
    //Progress bar ~ ($progress_id = id attribute  and $progress_name is the name attribute) of the progress bar
    private static function T_AddProgressBar($progress_id,$progress_name)
    {

    }

    //Adds a basic footer, only has the close button
    private static function T_AddBasicFooter()
    {

    }

    
    //Add a footer with a single option other than close
    private static function T_AddSingleOptionFooter($btn_info=array("name"=>"","id"=>"","extra_classes"=>""))
    {
        /*
            Add a footer with more than a single action button. The parameters should be passed in as arrays
            $btn1 and corresponding values will be associative arrays containing the properties: name,id,classes
            for example $btn1 = array("name"=>"Button name","id"=>"buttonId","extra_classes"=>"")
        */
        
    }

    //Add a footer with multiple options
    private static function T_AddMultipleOptionFooter($left_footer_buttons,$right_footer_buttons)
    {

    }

/* PUBLIC FUNCTIONS */
    /*Classroom modals*/
    //Add a Create Classroom modal to the dom
    public static function DisplayCreateClassroomModal()
    {

    }

    //Add an Edit Classroom modal to the dom
    public static function DisplayEditClassroomModal()
    {

    }

    //Add an Add Classroom Students modal to the dom
    public static function DisplayAddClassroomStudentsModal()
    {

    }

    //Add a Remove Classroom Students modal to the dom
    public static function DisplayRemoveClassroomStudentsModal()
    {

    }

    //Add a View Classroom Students modal to the dom
    public static function DisplayViewClassroomStudentsModal()
    {

    }

    //Add a View Classroom Assignments sent modal to the dom
    public static function DisplayViewClassroomAssSentModal()
    {

    }
    
};