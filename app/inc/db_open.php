<?php
$port = '';
if (LOCALHOST==0)
{
#    $port = ';port='.DB_SERVER__PORT;
}
try
{
    $db_pdo = new PDO("mysql:host=".DB_SERVER.$port.";dbname=".DB_DATABASE.";charset=utf8", DB_SERVER_USERNAME, DB_SERVER_PASSWORD);
}
catch(PDOException $e)
{
    if (SHOW_ERROR==true)
    {
      echo '<div style="background-color:white;color:darkred;font-weight:bold;width:100%;margin:10px 0px; padding:10px;">';
      echo $e->getMessage();
      echo '</div>';
    }
    else
    {
        echo "DB connection error.";
    }
}
?>