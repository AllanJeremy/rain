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

    //Messageboxes
    private static function MsgBoxDefault($message, $color_class)
    {
?>
   <div class='card-panel errorMessage   <?php echo "$color_class $color_class-text";?> darken-4 center text-lighten-2'>		
        <span><?php echo $message ?></span>		
   </div>
<?php
    }
    public static function MsgBoxError($message)
    {
        self::MsgBoxDefault($message,"red");
    }
    public static function MsgBoxSuccess($message)
    {
        self::MsgBoxDefault($message,"green");
    }
}
?>