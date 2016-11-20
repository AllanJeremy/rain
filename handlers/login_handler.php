<?php

//Returns true if the admin login POST variables are set, false otherwise
function AdminLoginSet()
{
    if()
}

//Returns true if the student login POST variables are set, false otherwise
function StudentLoginSet()
{

}

//Check if admin login credentials are valid - returns true if valid and false if not
function AdminInfoValid()
{

}

//Check if student login credentials are valid - returns true if valid and false if not
function StudentInfoValid()
{

}


#RUN THIS CODE WHEN THIS FILE IS REFERENCED - when the user attempts to login

//Check if the student login variables have been set
if(StudentLoginSet())
{
    if(StudentInfoValid())
    {
        //If the info is valid, log them in
    }
    else
    {
        //if the info is invalid deny login
    }
}
else if (AdminLoginSet())//if the student variables have not been set, then check if the admin variables have been set
{

    if(AdminInfoValid())
    {
        //If the info is valid, log them in
    }
    else
    {
        //if the info is invalid deny login
    }
}