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
    private static function MsgBoxDark($message, $color_class,$extra_classes="")
    {
?>
   <div class='card-panel errorMessage   <?php echo "$color_class $color_class-text $extra_classes";?> darken-4 center text-lighten-2'>		
        <span><?php echo $message ?></span>		
   </div>
<?php
    }
    private static function MsgBoxLight($message, $color_class,$extra_classes="")
    {
?>
   <div class='card-panel errorMessage   <?php echo "$color_class $color_class-text $extra_classes";?> lighten-4 center text-darken-2'>		
        <span><?php echo $message ?></span>		
   </div>
<?php
    }
    public static function MsgBoxError($message,$extra_classes="")
    {
        self::MsgBoxDark($message,"red",$extra_classes);
    }
    public static function MsgBoxSuccess($message,$extra_classes="")
    {
        self::MsgBoxDark($message,"green",$extra_classes);
    }
    public static function MsgBoxInfo($message,$extra_classes="")
    {
        self::MsgBoxLight($message,"teal",$extra_classes);
    }
}
?>