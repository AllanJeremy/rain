<?php

require_once(realpath(dirname(__FILE__) . "/../handlers/db_info.php")); #Connection to the database

//Functions that the class must implement
interface EsomoResourceFunctions
{
    public static function DisplayResources();
}

//This class will be used to display resources
class EsomoResource
{
    public function __construct__()
    {

    }

    #Display Resources 
    public static function DisplayResources()
    {
        
    }

    #Display Teacher Resources 
    public static function DisplayEditResources()
    {

    }
};