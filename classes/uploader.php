<?php

/*THIS CLASS IS USED TO HANDLE ALL UPLOAD FUNCTIONALITY*/
class EsomoUploader
{
    /*CONSTANTS*/
    //Upload Constants
    public static $base_upload_dir;
    public static $resource_upload_dir;
    public static $ass_upload_dir;
    public static $ass_submission_upload_dir;
    public static $other_upload_dir;
    
    const DEFAULT_MAX_UPLOAD_SIZE = 50;#Default upload size in megabytes
    const MAX_FILE_SIZE = self::DEFAULT_MAX_UPLOAD_SIZE * 1024 * 1024;
    
    //Accepted file types
    const DEFAULT_ACCEPTED_FILE_TYPES = "
        application/pdf,
        image/jpeg,
        image/jpg,
        image/png,
        word,docx,";
    /*
        pdf,word,excel,images,videos
    */

    /*VARIABLES*/
    public $files_found;
    public $can_upload;
    public $file_exists;#boolean ~ true if the file exists and false if the file does not exist

    //Constructor
    function __construct()
    {   
        //Directory init
        self::$base_upload_dir = realpath(dirname(__FILE__)."/../uploads");
        self::$resource_upload_dir = self::$base_upload_dir ."/resources/";
        self::$ass_upload_dir = self::$base_upload_dir ."/assignments/";
        self::$ass_submission_upload_dir = self::$base_upload_dir ."/ass_submissions/";
        self::$other_upload_dir = self::$base_upload_dir ."/other/";

        //Variable Initialization
        $this->can_upload = false;#can upload file, default is false

        $the_file = &$_FILES;
        $this->files_found = null;
        
        $this->file_exists = (isset($the_file) && (!empty($the_file)));#Check if the file name is set in the files section
        if($this->file_exists)
        {
            $this->files_found = $the_file;
        }
    }

    //Determine Upload directory based on what upload type it is ~ returns it | TODO: add error handling
    private static function GetUploadFolder($upload_type)
    {
        $upload_folder=null;
        switch($upload_type)
        {
            case "resource": #resource upload
                $upload_folder = self::$resource_upload_dir;
            break;
            
            case "assignment": #assignment upload
                $upload_folder = self::$ass_upload_dir;
            break;
            
            case "ass_submission": #assignment submission upload
                $upload_folder = self::$ass_submission_upload_dir;
            break;

            default: #uncategorized item upload
                $upload_folder = self::$other_upload_dir;
        }
        // $upload_folder = realpath($upload_folder);
        //Check if the directory exists, if it does not exist, create it
        if(!is_dir($upload_folder))
        {
            #Create the folder
            mkdir($upload_folder);
        }
        return $upload_folder;
    }
    
    //Get Accepted file types as an array
    private static function GetAcceptedFileTypes()
    {
        #Get the accepted file types as an array
        $accepted_file_types = self::DEFAULT_ACCEPTED_FILE_TYPES;
        $accepted_file_types  = explode(",",$accepted_file_types);
        array_pop($accepted_file_types);
        
        return $accepted_file_types;
    }
    
    //Checks if a file is within the valid size range ~ returns true if it is, and false if not
    private static function FileSizeIsValid($file)
    {
        $file_size=@$file["size"];

        //If the file size can be accessed
        if(isset($file_size)&&(!empty($file_size)))
        {
            return ($file_size <= self::MAX_FILE_SIZE);#File size is valid if it is less than or equal to the max_file_size
        }
        else
        {
            return false;
        }
    }

    //Checks if a file is of an accepted file type ~ returns true if it is, and false if not
    private static function FileTypeIsValid($file)
    {
        $file_type = @$file["type"];

        //Check if the file type is valid
        if(isset($file_type)&&(!empty($file_type)))
        {
            $accepted_file_types = self::GetAcceptedFileTypes();
            return (array_search($file_type,$accepted_file_types));
        }
        else
        {
            return false;
        }
    }

    //Checks if a file's name is valid ~ returns true if it is, and false if not
    private static function FileNameIsValid($file)
    {   
        $file_name = @$file["name"];

        //Check if the file name is valid
        if(isset($file_name)&&(!empty($file_name)))
        {
            #Check if the file name is valid
            return true;
        }
        else
        {
            return false;
        }
    }

    //Upload file function
    public function UploadFile($upload_type)
    {   
        $source_path = &$this->file_info["file_path"]; #Path to the local source file to be uploaded
        $upload_path = self::GetUploadFolder($upload_type); #Path the file will be uploaded to
        $folder_exists = is_dir($upload_path);
        
        echo "<br><br><br>";
        
        

        //Upload file here

            $files_found = $this->files_found;

            foreach($files_found as $file)
            {
                $file_size_valid = self::FileSizeIsValid($file); #Boolean indicating if the file(to be uploaded) size is valid
                $file_type_valid = self::FileTypeIsValid($file); #Boolean indicating if the file(to be uploaded) type is valid
                $file_name_valid = self::FileNameIsValid($file); #Boolean indicating if the file(to be uploaded) name is valid

                $file_name = &$file["name"];
                $tmp_name = &$file["tmp_name"];
                $file_size = &$file["size"];

                $this->can_upload = ($file_size_valid && $file_type_valid && $file_name_valid && $folder_exists);
                if($this->can_upload)
                {
                    $upload_destination = $upload_path . $file_name ;
                    if(move_uploaded_file($tmp_name,$upload_destination))
                    {
                        echo "<p>Succeeded uploading <b>".$file_size." bytes</b> of the file <b>".$file_name."</b></p>";
                        return true;
                    }
                    else
                    {
                        echo "<p>Failed to upload  the file : <b>".$file_name."</b></p>";
                        return false;
                    }
                }
                else #cannot upload file
                {
                    echo "<h3>UPLOAD FAILED</h3>";
                    #if the file size is invalid
                    if(!$file_size_valid)
                    {
                        $file_size_in_mb = $file_size/(1024*1024);

                        echo "<p><b>$file_name</b> (".(round($file_size_in_mb,2))."MB) is larger than the maximum accepted size of ".self::DEFAULT_MAX_UPLOAD_SIZE."MB";
                    }
                    
                    #if the file name is invalid
                    if(!$file_name_valid)
                    {
                        echo "<p><b>$file_name</b> has an invalid file name.</p>";
                    }

                    #if the file type is invalid
                    if(!$file_type_valid)
                    {
                        echo "<p><b>$file_name</b> has a file format that is not accepted.</p>";
                    }

                    return false;
                }
            }
            unset($_FILES);
    }
    
    /*DELETING FILES*/
    //[HELPER FUNCTION] Generic Delete file function ~ returns true on success and false on fail
    private static function DeleteFile($file_name,$upload_type)
    {
        $upload_folder = self::GetUploadFolder($upload_type); #Path the file was uploaded to
        $file_path = $upload_folder.$file_name;

        #If the file exists
        if(file_exists($file_path))
        {
            #Attempt to delete the file
            if(@unlink($file_path))
            {
                return true;
            }
            else#failed to delete file
            {
                return false;
            }
        }
        else #file does not exist
        {
            return false;
        }
    }

    //Delete a resource file
    public function DeleteResourceFile($file_name)
    {
        return self::DeleteFile($file_name,"resource");
    }

    //Delete an assignment file
    public function DeleteAssignmentFile($file_name)
    {
        return self::DeleteFile($file_name,"assignment");
    }

    //Delete an assignment submission file
    public function DeleteAssSubmissionFile($file_name)
    {
        return self::DeleteFile($file_name,"ass_submission");
    }

    //Delete 'other' file
    public function DeleteOtherFile($file_name)
    {
        return self::DeleteFile($file_name,"other");
    }
};
