<?php

//Interface for functions that must be implemented by this class
interface EsomoModalFunctions
{
/*   //Protected template functions ~ used internally
    protected static function AddBasicModal(); #adds a basic modal
    protected static function AddSingleActionModal($btn_name,$btn_id,$btn_class);*/
    
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

// A class for handling adding of modals to the dom
class EsomoModal implements EsomoModalFunctions
{
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