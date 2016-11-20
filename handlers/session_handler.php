<?php

session_start();

require_once("db_connect.php");#Connection to the database

#HANDLES SESSIONS, LOGIN INFORMATION AND OTHER SESSION INFO.
class SessionHandler
{

    //Constructor
    function __construct()
    {

    }

    //Initialize admin session variables soon as they login
    function InitAdmin($username,$acc_type)
    {
        global $dbCon;
        
    }
}