<?php

#CONTROLS HOW PASSWORDS ARE ENCRYPTED
class PasswordEncrypt
{   
    //returns encrypted password
    public static function EncryptPass($password)
    {
        return password_hash($password,PASSWORD_DEFAULT);
    }
}