<?php
class Logout
{
    public static function userLogout()
    {
        session_start();
        session_destroy();
        header('Location:login');
    }
}

Logout::userLogout();
