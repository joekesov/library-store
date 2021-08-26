<?php

class Logger
{
    public static function errorMessage(string $message)
    {
        return self::colorLog($message, 'e');
    }

    public static function successMessage(string $message)
    {
        return self::colorLog($message, 's');
    }

    public static function warningMessage(string $message)
    {
        return self::colorLog($message, 'w');
    }

    public static function infoMessage(string $message)
    {
        return self::colorLog($message, 'i');
    }

    protected static function colorLog(string $str, string $type = 'i')
    {
        switch ($type) {
            case 'e': //error
                echo "\033[31m$str \033[0m\n";
                break;
            case 's': //success
                echo "\033[32m$str \033[0m\n";
                break;
            case 'w': //warning
                echo "\033[33m$str \033[0m\n";
                break;
            case 'i': //info
                echo "\033[36m$str \033[0m\n";
                break;
            default:
                # code...
                break;
        }
    }
}