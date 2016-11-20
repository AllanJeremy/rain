<?php

class ErrorHandler
{

    //Print a success message in a green card-panel
    public static function PrintError($message)
    {
?>
    <div class='container'>
    <div class='card-panel red darken-4'>
        <p class='red-text text-lighten-2'><?php echo $message ?></p>
    </div>
    </div>
<?php 
    }

   //Print a success message in a green card-panel
   public static function PrintSuccess($message)
    {
?>
    <div class='container'>
    <div class='card-panel green'>
        <p class='green-text text-darken-4'><?php echo $message ?></p>
    </div>
    </div>
<?php
    }
    //Print a success message in a green card-panel
    public static function PrintSmallError($message)
    {
?>
    <div class='container'>
    <div class="row">
        <div class='col s12 m6 offset-m3 card red darken-4 red-text center text-lighten-2'>
            <?php echo $message ?> 
        </div>
    </div>
    </div>
<?php 
    }

   //Print a success message on a small card strip
   public static function PrintSmallSuccess($message)
    {
?>
    <div class='container'>
    <div class="row">
        <div class='col s12 m6 offset-m3 card green green-text center text-darken-4'>
            <?php echo $message ?> 
        </div>
    </div>
    </div>
<?php
    }
}
?>