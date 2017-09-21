<?php

class ErrorHandler
{
    
    //Prints a json encoded error log
    public static function PrintErrorLog($errors=(array()))
    {
        echo json_encode(
            array("errors"=>$errors)
        );
    }
    
    //Generic print message
    private static function PrintMessage($message,$type)
    {
        $return_val = array("$type"=>(ucfirst($type)." : ".$message));
        echo json_encode($return_val);
        return $return_val;
    }

    public static function PrintError($message)
    {
        return self::PrintMessage($message,"error");
    }

   public static function PrintSuccess($message)
    {
        return self::PrintMessage($message,"message");
    }
}
?>