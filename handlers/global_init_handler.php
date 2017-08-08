<?php
/*Initialize Various settings here*/
#set the default timezone
const DEFAULT_TIMEZONE = "Africa/Nairobi";
date_default_timezone_set (DEFAULT_TIMEZONE);

/*CONSTANTS*/
//Active class
const BASE_ACTIVE_CLASS = "active";

//Name of the GET variables used to retrieve the section and tab
const SECTION_GET_VAR = "section";
const TAB_GET_VAR = "tab";

/*FUNCTIONS*/
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

//Get the path to a given section/tab
function GetSectionLink($section_name="",$section_tab="",$prefix="./")
{
    $link = $prefix."?".SECTION_GET_VAR.'='.$section_name;

    if(isset($section_tab) && !empty($section_tab))
    {
        $link .= '&'.TAB_GET_VAR.'='.$section_tab;
    }

    return $link;
}
