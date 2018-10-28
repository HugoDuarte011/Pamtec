<?php
/**
 * Configurações da conexão com servidor
 *
 */

require('pt-config.php');


//$conn = mysqli_connect($servername,$username,$password, $database);

function connection_MySql(){

    /* Database name MySQL */
    $servername = DB_HOST;

    /* Database host MySQL */
    $database = DB_NAME;

    /* Database user MySQL */
    $username = DB_USER;

    /* Database password MySQL */
    $password = DB_PASSWORD;

    $conn = mysqli_connect($servername,$username,$password, $database);

    return $conn;
}

if (mysqli_connect_errno()) die(mysqli_connect_error());

// Criar uma página de falha de conexão

?>