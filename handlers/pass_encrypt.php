<?php

#CONTROLS HOW PASSWORDS ARE ENCRYPTED
class PasswordEncrypt
{   
    //returns encrypted password
    public static function EncryptPass($password)
    {
        return password_hash($password,PASSWORD_DEFAULT);
    }

    public static function Verify($password,$hash)
    {
        return password_verify($password,$hash);
    }
}