<?php

require_once("admin_account.php");

#HANDLES PRINCIPAL RELATED FUNCTIONS
class Principal extends AdminAccount
{
    //Variable initialization

    //Constructor
    function __construct()
    {
        $this->accType = "principal";
    }

    //Other Code here

};