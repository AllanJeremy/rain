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
    const RESOURCES_NOT_FOUND_MESSAGE = "No resources were found.<br><br>Once resources are uploaded they will appear here";

    #Constructor
    public function __construct__()
    {

    }
    
    #Display Message incase resources could not be found
    public static function DisplayMissingDataMessage()
    {
?>
    <div class="section grey lighten-2 center">
        <h5 class="center grey-text text-darken-1"><?php echo self::RESOURCES_NOT_FOUND_MESSAGE;?></h5>
    </div>
<?php
    }

    #Base display resources function ~ Displays the cards for the resources. For internal use
    private static function DisplayResourceBase($resources)
    {
        echo "<div class='row'>";

        #Variable declaration
        $res_name = $res_file_type = $res_file_link = $res_description = "";
        $res_id = 0;#resource id;

        #Loop through each resource item
        foreach($resources as $res_found):
            #Variable init
            $res_id = $res_found["resource_id"];
            $res_name = $res_found['resource_name'];
            $res_file_type = $res_found['file_type'];
            $res_file_link = $res_found["file_link"];
            $res_description = $res_found['description'];

        //Loop through each resource and add the html below
?>
        <div class="col s12 m6 l4">
            <div class="card white res_container" data-res-id="<?php echo $res_id?>">
                <div class="card-content">
                    <span class="card-title truncate" title="<?php echo $res_name;?>"><?php echo $res_name;?></span>
                        <div class="resource-details-container">
                            <p>Description: <?php echo empty($res_description) ? '<span class="grey-text">Not written</span>' : $res_description; ?></p>
                        </div>
                    <h6 class="grey-text uppercase text-lighten-2 "><?php echo $res_found['file_type'];?></h4>
                </div>
                <div class="card-action">
                    <!--TODO: Make this display the file regardless of type in a new tab-->
                    <a class="btn" href="<?php echo $res_file_link; ?>" target="_blank">OPEN</a>

<!--                    <a class="btn btn-flat right viewResourceDetails" href="javascript:void(0)">DETAILS</a>-->
                </div>
            </div>
        </div>  
<?php          
        endforeach; #end foreach($resources as $resource_found)
        echo "</div>";#close row
    }#End of DisplayResourceBase()

    #Display Resources 
    public static function DisplayResources()
    {
        global $subject_list;
        $subject_id = $resources = null;

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
    <h4 class="grey-text text-darken-2"><?php echo $subject["subject_name"];?></h4>
     <?php
                    self::DisplayResourceBase($resources);
                    echo "<br><div class='divider'></div><br>";#Only display <br> if the subject was found
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
                self::DisplayMissingDataMessage();
            }
        endif;  
    }#end of DisplayResources function

    #Base display edit resources function ~ Displays the cards for the resources. For internal use
    private static function DisplayEditResourceBase($user_info,$resources)
    {   

        #Account related variable declaration
        $user_id = &$user_info["user_id"];
        $account_type = &$user_info["account_type"];
        
        #Resource related Variable declaration
        $res_edit_btn_class ="btn btn-flat right js-edit-resource";#classes applied to all edit buttons
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
            $res_subject_id = $res_found["subject_id"];
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
            <div class="col s12 m6 l4">
                <div class="card white tr_res_container" data-res-id="<?php echo $res_id?>" data-subject-id="<?php echo $res_subject_id?>">
                    <div class="card-content">
                        <span class="card-title truncate" title="<?php echo $res_name;?>"><?php echo $res_name;?></span>
                        <div class="">
                            <div class="resource-details-container">
                                <p>Description: <span class="js-res-description"><?php echo empty($res_description) ? '--' : $res_description; ?></span></p>
<!--                                <a class="btn btn-flat viewResourceDetails " href="javascript:void(0)">RESOURCE DETAILS</a>-->
                            </div>
                            <h5 class="grey-text uppercase text-lighten-1"><?php echo $res_found['file_type'];?></h5>
                        </div>
                    </div>
                    <div class="card-action">
                        <!--TODO: Make this display the file regardless of type in a new tab-->
                    <a class="btn" href="<?php echo $res_file_link; ?>" target="_blank">OPEN</a>
                    <a <?php echo $res_edit_btn_properties?> href="javascript:void(0)">EDIT</a>
                    </div>

                </div>
            </div>
<?php         
        endforeach;#end foreach($resources as $res_found)
        
        echo "</div>";#close wrapping row
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
        <div class='row subject-group' data-subject-group="<?php echo $subject_id; ?>">
        <h4 class="grey-text text-darken-2 subject-group-header"><?php echo $subject["subject_name"];?></h4>
    <?php
                        echo "<div class='row subject-group-body'>";
                        self::DisplayEditResourceBase($user_info,$resources);
                        echo "<br><div class='divider'></div><br>";#Only display <br> if the subject was found
                        echo "</div>";#Only display <br> if the subject was found
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
                    self::DisplayMissingDataMessage();
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
                    self::DisplayMissingDataMessage();
                }
            }
            catch(Exception $error){
                $error_message = "Could not display resources normally either.<br>If the problem persists, feel free to <a href='./report.php' target='_blank'>report the problem</a> ";
                ErrorHandler::PrintError($error_message."<br><b>Error Caught : </b>$error");
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
