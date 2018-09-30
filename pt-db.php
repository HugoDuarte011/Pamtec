<?php
/**
 * Configurações da conexão com servidor
 *
 */

require('pt-config.php');

/* Database name MySQL */
$servername = DB_HOST;

/* Database host MySQL */
$database = DB_NAME;

/* Database user MySQL */
$username = DB_USER;

/* Database password MySQL */
$password = DB_PASSWORD;

$conn = mysqli_connect($servername,$username,$password, $database);
if (mysqli_connect_errno()) die(mysqli_connect_error());

?>