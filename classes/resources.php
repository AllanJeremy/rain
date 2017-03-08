<?php

require_once(realpath(dirname(__FILE__) . "/../handlers/db_info.php")); #Connection to the database

//GLOBAL VARIABLE CONTAINING THE LIST OF ALL SUBJECTS
$subject_list = DbInfo::GetAllSubjects();

//Functions that the class must implement
interface EsomoResourceFunctions
{
    public static function DisplayResources();
}

//This class will be used to display resources
class EsomoResource
{   

    #Constants
    const RESOURCES_NOT_FOUND_MESSAGE = "No resources were found.<br>Once resources are uploaded they will appear here";

    #Constructor
    public function __construct__()
    {

    }
    
    #Display Message incase resources could not be found
    protected static function DisplayMissingDataMessage($message)
    {
?>
    <div class="section grey lighten-2 center">
        <h3 class="center grey-text text-darken-2"><?php echo $message;?></h3>
    </div>
<?php
    }

    #Base display resources function ~ Displays the cards for the resources. For internal use
    private static function DisplayResourceBase($resources)
    {
?>  
    <div class="row">
<?php
        #Variable declaration
        $res_name = $res_file_type = $res_file_link = $res_description = "";
        
        #Loop through each resource item
        foreach($resources as $res_found):
            #Variable init
            $res_name = $res_found['resource_name'];
            $res_file_type = $res_found['file_type'];
            $res_file_link = $res_found["file_link"];
            $res_description = $res_found['description'];

        //Loop through each resource and add the html below
?>
        <div col s6 m4 l3>
            <div class="card white">
                <div class="card-content">
                    <span class="card-title truncate" title="<?php echo $res_name;?>"><?php echo $res_name;?></span>
                    <div class="section white">
                        <h3 class="red-text text-darken-3 center"><?php echo $res_found['file_type'];?></h3>
                    </div>
                </div>
                <div class="card-action">
                    <!--TODO: Make this display the file regardless of type in a new tab-->
                    <a class="btn" href="<?php echo $res_file_link; ?>" target="_blank"><i class="material-icons">visible</i> VIEW</a>
                </div>
                <a class="btn btn-flat" href="<?php echo $res_file_link; ?>" target="_blank"><i class="material-icons">info_outline</i> DETAILS</a>
            </div>
        </div>  
    </div>
<?php          
        endforeach; #end foreach($resources as $resource_found)
    }#End of DisplayResourceBase()

    #Display Resources 
    public static function DisplayResources()
    {
        global $subject_list;
        $subject_id = $resources = null;
?>
<div class="container"> 
<?php

        //check if subjects are available
        if( isset($subject_list) && (!empty($subject_list)) ):   
?>         
    <?php
            //Loop through the subjects
            foreach($subject_list as $subject):
                $subject_id = &$subject["subject_id"];
                $resources = DbInfo::GetResourcesBySubject($subject_id);

                if($resources):
     ?>
    <h3><?php echo $subject["subject_name"];?></h3>
     <?php
                    self::DisplayResourceBase($resources);
                else:#resources could not be found
                    self::DisplayMissingDataMessage(self::RESOURCES_NOT_FOUND_MESSAGE);
                endif;

            endforeach;
        else:
                $resources = DbInfo::GetAllResources();
                if($resources)
                {
                    self::DisplayResourceBase($resources);
                }
                else
                {
                    self::DisplayMissingDataMessage(self::RESOURCES_NOT_FOUND_MESSAGE);
                }
        endif;
?>
</div>
<?php    
    }#end of DisplayResources function

    #Base display edit resources function ~ Displays the cards for the resources. For internal use
    private static function DisplayEditResourceBase($user_info,$resources)
    {   
?>
    <div class="container">
<?php
        #Account related variable declaration
        $user_id = &$user_info["user_id"];
        $account_type = &$user_info["account_type"];
        
        #Resource related Variable declaration
        $res_edit_btn_class ="btn btn-flat";#classes applied to all edit buttons
        $res_edit_btn_properties = "";#Properties of the edit button
        $res_name = $res_file_type = $res_file_link = $res_description = "";
        $res_id = $res_teacher_id = 0;#resource teacher id
        $res_belongs_to_teacher = false;#resource belongs to teacher, true if it was created by them, false if otherwise

        //Loop through the resources ~ If the resources belong to the teacher, have an edit button, otherwise only view
        foreach($resources as $res_found):
            #Variable init
            $res_id = $res_found["resource_id"];
            $res_name = $res_found['resource_name'];
            $res_file_type = $res_found['file_type'];
            $res_file_link = $res_found["file_link"];
            $res_description = $res_found['description'];
            $res_teacher_id = $res_found["teacher_id"];
            $res_belongs_to_teacher = ($res_teacher_id == $user_id);
            
            if($res_belongs_to_teacher)
            {
                $res_edit_btn_properties = "class='$res_edit_btn_class'";
            }
            else
            {
                $res_edit_btn_properties = "class='$res_edit_btn_class disabled' disabled";
            }
                /*Note : assumption is that only teachers will be using the resource_id, for editing*/
        ?>      
            <div col s6 m4 l3>
                <div class="card white tr_res_container" data-res-id="<?php echo $res_id?>">
                    <div class="card-content">
                        <span class="card-title truncate" title="<?php echo $res_name;?>"><?php echo $res_name;?></span>
                        <div class="section white">
                            <h3 class="red-text text-darken-3 center"><?php echo $res_found['file_type'];?></h3>
                        </div>
                    </div>
                    <div class="card-action">
                        <!--TODO: Make this display the file regardless of type in a new tab-->
                        <a class="btn" href="<?php echo $res_file_link; ?>" target="_blank"><i class="material-icons">visible</i> VIEW</a>
                    </div>
                    <a <?php echo $res_edit_btn_properties?> href="javascript:void(0)"><i class="material-icons" >info_outline</i> EDIT</a>
                </div>
            </div>
        </div>
<?php         
        //^closes div row
            endforeach;
    }#End of DisplayEditResourceBase
    
    #Display Teacher Resources 
    public static function DisplayEditResources($user_info)
    {
        global $subject_list;

        #Account related variable declaration
        $user_id = &$user_info["user_id"];
        $account_type = &$user_info["account_type"];
?>
    <div class="row">
<?php
        //If the teacher is logged in and the account information could be retrieved
        if(isset($user_id)&&(!empty($user_id)) && ($account_type == "teacher")): 

            //If we could find the subjects
            if( isset($subject_list) && (!empty($subject_list)) ):
                //Loop through each subject
                foreach($subject_list as $subject):
                    $subject_id = &$subject["subject_id"];
                    $resources = DbInfo::GetResourcesBySubject($subject_id);  
    ?>
    <?php
                    #if the resources were found for the specific subject
                    if($resources):
    ?>
    
        <div class="divider"></div>
        <h3><?php echo $subject["subject_name"];?></h3>
    <?php
                        self::DisplayEditResourceBase($user_info,$resources);

                    else:#resources not found in the database
                        self::DisplayMissingDataMessage(self::RESOURCES_NOT_FOUND_MESSAGE);
                    endif;
                endforeach;   
        //Close row div
    ?>
        
    <?php
            else:#could not find the subjects in the database
                $resources = DbInfo::GetAllResources();
                if($resources)
                {
                    self::DisplayEditResourceBase($user_info,$resources);#Display the resources without showing the subject header
                }
                else
                {
                    self::DisplayMissingDataMessage(self::RESOURCES_NOT_FOUND_MESSAGE);
                }
            endif;
        else: #teacher account requested could not be found
            $error_message = "<b>Note : Teacher Resource Error</b>.<br>Could not retrieve teacher resources.<br>Reason: <i>we could not find the teacher account associated with the tests stated</i>. Displaying uneditable resources instead.<br>If the problem persists, feel free to <a href='./report.php' target='_blank'>report the problem</a>";
            
            //Print the error message
            ErrorHandler::PrintError($error_message);
            
            echo "<br><div class='divider'></div><br>";#add a divider after the error message
            
            //Try displaying the resources
            try
            {   
                $resources = DbInfo::GetAllResources();#Get resources in the database
                
                #Check if there are any resources in the database
                if($resources)
                {
                    self::DisplayResources();#Display the resources normally
                }
                else #if resources could not be found in the database
                {
                    self::DisplayMissingDataMessage(self::RESOURCES_NOT_FOUND_MESSAGE);
                }
            }
            catch(Exception $error){
                $error_message = "Could not display resources normally either.<br>If the problem persists, feel free to <a href='./report.php' target='_blank'>report the problem</a> ";
                ErrorHandler::PrintError($error_message);
            }
            
        endif;
    ?>
</div>
<?php
    }#End of DisplayEditResources()

};

//Testing
/*$user_info = MySessionHandler::GetLoggedUserInfo();#store the logged in user info anytime an AJAX call is made

echo "<h1>TESTING DisplayEditResources()</h1>";
EsomoResource::DisplayEditResources($user_info);#Display edit resources

echo "<br><hr><br><h1>TESTING DisplayResources()</h1>";
EsomoResource::DisplayResources();*/
