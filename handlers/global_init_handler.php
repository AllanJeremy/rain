<?php

/*Initialize Various settings here*/

#set the default timezone
const DEFAULT_TIMEZONE = "Africa/Nairobi";
date_default_timezone_set (DEFAULT_TIMEZONE);

//Active class
const BASE_ACTIVE_CLASS = "active";
//Sets the class for a html attribute
function SetClass($class_name = "")
{
    if(isset($class_name) && !empty($class_name))
    {
        return "class='$class_name'";
    }
    else
    {
        return "";
    }
}