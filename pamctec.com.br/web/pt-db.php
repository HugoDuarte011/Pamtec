<?php
/**
 * @param DB_HOST $servername 
 * @param DB_NAME $database 
 * @param DB_USER $username
 * @param DB_PASSWORD $password
 */

require('pt-config.php');

function connection_MySql(){

    /* Database name MySQL */
    $servername = DB_HOST;

    /* Database host MySQL */
    $database = DB_NAME;

    /* Database user MySQL */
    $username = DB_USER;

    /* Database password MySQL */
    $password = DB_PASSWORD;

    try{
        if (mysqli_connect_errno()) die(mysqli_connect_error());
        $conn = mysqli_connect($servername,$username,$password, $database);
    } catch(Exception $e) {
        header("Location: index.php");
    }

    return $conn;
}

?>