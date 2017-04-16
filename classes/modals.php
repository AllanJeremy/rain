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
    //Constants
    const DEFAULT_TITLE_TAG = "h4";#default tag used for modal tags ~ must be a valid html tag preferably one of the h tags

/* PRIVATE FUNCTIONS ~ HELPER FUNCTIONS */
    
    //Ensure only valid tags can be parsed
    private static function GetValidTitleTag($title_tag)
    {
        $valid_tag = "";
        switch($title_tag)
        {
            case "h1":
                $valid_tag = "h1";
            break;
            case "h2":
                $valid_tag = "h2";
            break;
            case "h3":
                $valid_tag = "h3";
            break;
            case "h4":
                $valid_tag = "h4";
            break;
            case "h5":
                $valid_tag = "h5";
            break;
            case "h6":
                $valid_tag = "h6";
            break;
            default:
                $valid_tag = self::DEFAULT_TITLE_TAG;
        }

        return $valid_tag;
    }

    //Begins a generic modal 
    private static function T_BeginGenericModal($modal_id,$modal_title=null,$extra_attributes="",$extra_classes="",$title_tag="h4",$title_classes="grey-text")
    {
        $title_tag = self::GetValidTitleTag($title_tag);#Get the valid title tag
?>
        <div class="modal <?php echo $extra_classes;?>" id="<?php echo $modal_id;?>" <?php echo $extra_attributes;?>>
            <div class="modal-content">
        <?php
            //If the modal title has been provided, add the title to the DOM
            if(isset($modal_title)&&(!empty($modal_title))):
        ?>
                <?php echo $title_tag;?> class="<?php echo $title_classes;?>">
                    <?php echo $modal_title?>
                </<?php echo $title_tag;?>>
        <?php
            endif;
        ?>
<?php
    }

    //Closes a generic modal's divs
    private static function T_CloseDiv($footer_content)
    {
        //Close the content div, then close the entire modal div
?>
        </div>
<?php
    echo $footer_content;
?>
    </div>
<?php
    }
    
    //Progress bar ~ ($progress_id = id attribute  and $progress_name is the name attribute) of the progress bar
    private static function T_AddProgressBar($progress_id,$progress_name)
    {
         
    }

    //Adds a basic footer, only has the close button
    private static function T_AddBasicFooter()
    {
?>
        <div class="modal-footer">
            <a href="javascript:void(0)" class="btn btn-flat modal-close">CLOSE</a>
        </div>
<?php
    }

    
    //Add a footer with a single option other than close
    private static function T_AddSingleOptionFooter($btn_info=array("name"=>"DONE","id"=>"","extra_classes"=>""))
    {
        /*
            Add a footer with more than a single action button. The parameters should be passed in as arrays
            $btn1 and corresponding values will be associative arrays containing the properties: name,id,classes
            for example $btn1 = array("name"=>"Button name","id"=>"buttonId","extra_classes"=>"")
        */
?>
        <div class="modal-footer">
            <a href="javascript:void(0)" class="btn btn-flat modal-close">CLOSE</a>
            <a href="javascript:void(0)" class="btn <?php echo $btn_info['extra_classes'];?>" id="<?php echo $btn_info['id'];?>"><?php echo $btn_info["name"];?></a>
        </div>
<?php
    }
    
    #VALIDATION SUB_HELPER : Returns the values of If buttons have been set and are not empty | also ensure that they are arrays
    private static function ModalButtonsValid($btn_input)
    {
        return (isset($btn_input) && (!empty($btn_input)) && is_array($btn_input));
    }
    
    #VALIDATION SUB_HELPER : Returns the values of If buttons have keys set ~ if they have valid keys, means it is an associative array
    private static function ModalButtonHasKeys($btn_input)
    {
        return 
        (
               isset($btn_input["id"]) && (!empty($btn_input["id"])) 
            && isset($btn_input["name"]) && (!empty($btn_input["name"]))
        );
    }
    
    //Add a footer with multiple options ~ Default for left footer buttons is null, meaning no left footer buttons
    private static function T_AddMultipleOptionFooter($right_footer_buttons,$left_footer_buttons=null,$extra_classes)
    { 
        /*
            PROPOSED LEFT FOOTER BUTTON ARRAY STRUCTURE
            $left_btn = array
                        (
                            "id"=>"",
                            "classes"=>"",
                            "name"=>"" # this is the name for the icon that will be used
                        )

        */
        #NOTE: $extra_classes paramater refers to the entire footer's extra classes
?>
        <div class="modal-footer <?php echo $extra_classes?>">
<?php
        //TODO: COMPLETE THIS FUNCTION
        //If right footer buttons have been set and are not empty | also ensure that they are arrays
        if(self::ModalButtonsValid($right_footer_buttons)):
            #If it is an array, check if it is an array of arrays or just a single associative array
            if(self::ModalButtonHasKeys($right_footer_buttons)):
                #add single right side action button
            
            else:#More than one right button provided
                foreach($right_footer_buttons as $right_btn):
                    #add right side buttons
                endforeach;
            endif;
        endif;

        //If left footer buttons have been set and are not empty | also ensure that they are arrays
        if(self::ModalButtonsValid($left_footer_buttons)):
            #If it is an array, check if it is an array of arrays or just a single associative array
            if(self::ModalButtonHasKeys($left_footer_buttons) && $left_footer_buttons["icon"]):
                #add single left side action button
            
            else:#More than one right button provided
                foreach($left_footer_buttons as $left_btn):
                    #add left side buttons
                endforeach;
            endif;
        endif;
?>
        </div>
<?php        
    }

/* PUBLIC FUNCTIONS */
    /*Classroom modals*/
    //Add a Create Classroom modal to the dom
    public static function DisplayCreateClassroomModal()
    {
        self::T_BeginGenericModal("");
        //Other modal content goes here
?>

<?php
        self::T_AddBasicFooter();
        self::T_EndGenericModal($footer_content);
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
        self::T_BeginGenericModal("myModal","","data-schedule-id='12'");  
?>

<?php
            self::T_CloseDiv();
            $footer_btn = array("name"=>"DONE","id"=>"submitScheduleData","extra_classes"=>"");
            self::T_AddSingleOptionFooter($footer_btn);
        self::T_CloseDiv();
    }

    //Add a View Classroom Assignments sent modal to the dom
    public static function DisplayViewClassroomAssSentModal()
    {

    }
    
};
