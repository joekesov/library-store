<?php

// TODO: Do we need Logger here
//require_once ('Logger.php');

class DBConnector
{
    protected static $connection;

    public static function getConnection(): PDO
    {
        if (empty($connection)) {
            self::setConnection();
        }

        return self::$connection;
    }

    protected static function setConnection(): void
    {
        $username = getenv('MYSQL_USER');
        $password = getenv('MYSQL_PASS');
        $dsn = getenv('DATABASE_URL');

        try{
            self::$connection = new PDO($dsn, $username, $password);
            self::$connection->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

//            Logger::successMessage('Connected succesfully');

        } catch(PDOException $e){
            // TODO: Throw new exception
//            Logger::errorMessage("Connection failed: " . $e -> getMessage());
        }
    }
}