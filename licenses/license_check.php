<?php
/* Checks license integrity */
require_once(realpath(dirname(__FILE__) . "/../handlers/error_handler.php"));

class RainLicense
{
    const LICENSE_NOT_FOUND_MESSAGE = "Error: License file not found";
    const INVALID_LICENSE_MESSAGE = "Note: Your license could not be verified. All system functions are currently locked. Please contact support for further assistance";

    const LICENSE_FILE_PATH = "./licenses/license.json";

    //Returns true if the license is valid and false if not
    public static function LicenseValid()
    {        
        #If the license could not be updated
        if(!self::UpdateLicenseInfo()){
            return false;
        }
        

        #Check if the license information is valid

        return true;
    }

    //Updates the license information based on the license file ~ returns true on success
    public static function UpdateLicenseInfo()
    {
        #If the license file does not exist, show error and return false (license is invalid)
        if(!file_exists(self::LICENSE_FILE_PATH)){
            ErrorHandler::MsgBoxError(self::LICENSE_NOT_FOUND_MESSAGE,"m-0");
            return false;
        }

        #License file was found
        $license = file_get_contents(self::LICENSE_FILE_PATH);
        $license = json_decode($license,true);

        #API calls to get information
        //$license["key"]
        //$license["integrity"]

        
        return true;
    }
}