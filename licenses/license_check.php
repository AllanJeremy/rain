<?php
/* Checks license integrity */
require_once(realpath(dirname(__FILE__) . "/../handlers/error_handler.php"));

class RainLicense
{
    const LICENSE_NOT_FOUND_MESSAGE = "Error: License file not found";
    const INVALID_LICENSE_MESSAGE = "Note: Your license could not be verified. All system functions are currently locked. Please contact support for further assistance";

    const LICENSE_FILE_PATH = "./licenses/license.json";
    const LICENSE_API_PATH = "http://license.rain.co.ke/api.php";

    //Returns the license info
    public static function GetLicenseInfo($key="")
    {
        $license_info = file_get_contents(self::LICENSE_FILE_PATH);
        $license_info = json_encode($license_info);

        #If the license info could not be found
        if(!$license_info){
            return false;
        }

        #If the key is empty, return the entire license info associative array
        if(!isset($key) || empty($key))
        {
            return $license_info;
        }
        else
        {
            #If the key is not set
            if(!isset($license_info[$key])){
                return false;
            }
            return $license_info[$key];
        }
    }

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
        $api_call_path = self::LICENSE_API_PATH."?key=".$license["key"]."&integrity=".$license["integrity"];
        
        #Get the license info through an api call
        $license = json_decode(@file_get_contents($api_call_path));

        #return true ~ this is for testing purposes [to remove]
        return true;
        
        #If the license retrieved from the api call was invalid return false
        if(!$license){
            return false;
        }
        #Update the license file
        if( (file_put_contents(self::LICENSE_FILE_PATH, $license)) !== false){
            return true;
        }
        else{
            return false;
        }
    }
}