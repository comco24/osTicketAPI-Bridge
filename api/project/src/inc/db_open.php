<?php
try
{
    $db_pdo = new PDO("mysql:host=".DB_SERVER.';port='.DB_SERVER_PORT.";dbname=".DB_DATABASE.";charset=utf8", DB_SERVER_USERNAME, DB_SERVER_PASSWORD);
}
catch(PDOException $e)
{
    if (SHOW_ERRORS==true)
    {
        echo $e->getMessage();
    }
    else
    {
        echo "DB connection error.";
    }
}
?>