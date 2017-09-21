<?php
include_once("error_handler.php");

//CONNECTS TO THE DATABASE 
//database variables
if (!defined('DB_HOST'))
{	define('DB_HOST','localhost');  }

if (!defined('DB_USERNAME'))
{	define('DB_USERNAME','root');  }

if (!defined('DB_PASSWORD'))
{	define('DB_PASSWORD','');  }

if (!defined('DB_NAME'))
{	define('DB_NAME','rain');  }

if(!isset($dbCon))//makes sure we don't open multiple connections
{
	$dbCon = new mysqli(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_NAME);//create a new conn if no connection exists

	if ($dbCon->error)//check if there is any error when connecting to the database
	{
		ErrorHandler::MsgBoxError("Database Error : ".$dbCon->error);
		exit();//exit the file execution i we did not get a successful connection to the database
	}

}
