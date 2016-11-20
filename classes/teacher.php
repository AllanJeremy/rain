<?php

require_once("admin_account.php");

#HANDLES TEACHER RELATED FUNCTIONS
class Teacher extends AdminAccount
{
    //Variable initialization

    //Constructor
    function __construct()
    {
        $this->accType = "teacher";
    }

    //Other Code here

};