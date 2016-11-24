<?php
ob_start();#enable output buffering, allows for sending of headers within the file, prevents errors

@session_start();

require_once(realpath(dirname(__FILE__) . "/../handlers/db_info.php"));#Used to retrieve information from the database

#HANDLES COMMENTS IN CLASSROOMS | ASSIGNMENTS | SCHEDULES | TESTS
class CommentHandler
{

};    