<?php

/*THIS CLASS IS USED TO HANDLE ALL UPLOAD FUNCTIONALITY*/
class EsomoUploader
{
    /*CONSTANTS*/
    //Upload Constants
    const BASE_UPLOAD_DIR = "./uploads";#Root upload folder, every upload will be in here
    const RESOURCE_UPLOAD_DIR = self::BASE_UPLOAD_DIR ."/resources";#Upload folder for resources
    const ASS_UPLOAD_DIR = self::BASE_UPLOAD_DIR ."/assignments";#Upload folder for assignments
    const ASS_SUBMISSION_UPLOAD_DIR = self::BASE_UPLOAD_DIR ."/ass_submissions";#Upload folder for assignment submissions
    const OTHER_UPLOAD_DIR = self::BASE_UPLOAD_DIR ."/other";#Upload folder for uncategorized files (other files)
    
    const DEFAULT_MAX_UPLOAD_SIZE = 50;#Default upload size in megabytes
    const DEFAULT_ACCEPTED_FILE_TYPES = "pdf,jpeg,jpg,png,word,docx,";

    /*VARIABLES*/
    public $file_info;
    public $max_file_size;
    public $accepted_file_types;
    public $can_upload;

    //Constructor
    function __construct__($file_path)
    {   
        //Variable Initialization
        $this->file_info = array("file_path"=>$file_path);#file info
        $this->max_file_size = self::DEFAULT_MAX_UPLOAD_SIZE;#default maximum upload size in megabytes
        $this->accepted_file_types = self::DEFAULT_ACCEPTED_FILE_TYPES; #default accepted file types
        $this->can_upload = false;#can upload file, default is false
    }

    //Determine Upload directory based on what upload type it is ~ returns it | TODO: add error handling
    private static function GetUploadFolder($upload_type)
    {
        $upload_folder=null;
        switch($upload_type)
        {
            case "resource": #resource upload
                $upload_folder = self::RESOURCE_UPLOAD_DIR;
            break;
            
            case "assignment": #assignment upload
                $upload_folder = self::ASS_UPLOAD_DIR;
            break;
            
            case "ass_submission": #assignment submission upload
                $upload_folder = self::ASS_SUBMISSION_UPLOAD_DIR;
            break;

            default: #uncategorized item upload
                $upload_folder = self::OTHER_UPLOAD_DIR;
        }
        return $upload_folder;
    }
    
    //Checks if a file is within the valid size range ~ returns true if it is, and false if not
    private static function FileSizeIsValid($source_path)
    {
        //Check if the file size is valid
        return true;
    }

    //Checks if a file is of an accepted file type ~ returns true if it is, and false if not
    private static function FileTypeIsValid($source_path)
    {
        //Check if the file type is valid
        return true;
    }

    //Checks if a file's name is valid ~ returns true if it is, and false if not
    private static function FileNameIsValid($source_path)
    {   
        //Check if the file name is valid
        return true;
    }

    //Upload file function
    public function UploadFile($upload_type)
    {   
        $source_path = &$this->file_info["file_path"]; #Path to the local source file to be uploaded
        $upload_path = self::GetUploadFolder($upload_type); #Path the file will be uploaded to
        $file_size_valid = &self::FileSizeIsValid($source_path); #Boolean indicating if the file(to be uploaded) size is valid
        $file_type_valid = &self::FileTypeIsValid($source_path); #Boolean indicating if the file(to be uploaded) type is valid
        $file_name_valid = &self::FileNameIsValid($source_path); #Boolean indicating if the file(to be uploaded) name is valid

        $this->can_upload = ($file_size_valid && $file_type_valid && $file_name_valid);

        //Upload file here
        if($this->can_upload)
        {

        }
        else #cannot upload file
        {
            return false;
        }
    }

};