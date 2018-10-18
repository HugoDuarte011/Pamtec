<?php

// Requisição da URL
$REQUEST_URI = filter_input(INPUT_SERVER, 'REQUEST_URI');

$INITE = strpos($REQUEST_URI, '?');
if($INITE) {
    $REQUEST_URI = substr($REQUEST_URI, 0, $INITE);
}
$REQUEST_URI_FOLDER = substr($REQUEST_URI, 1);

$URL = explode('/', $REQUEST_URI_FOLDER);
$URL[0] = ($URL[0] != '' ? $URL[0] : 'home');

if(file_exists(ROUTER . $URL[0] . '.php')){
    if($URL[0] === 'login')
        require(ROUTER . '../main/pt-login.php' );
    else
        include_once(ROUTER . $URL[0] . '.php');
} elseif(is_dir(ROUTER . $URL[0])){
    if(isset($URL[1]) && file_exists(ROUTER . $URL[0] . '/' . $URL[1] . '.php')){
        include_once(ROUTER . $URL[0] . '/' . $URL[1] . '.php');
    } else {
        include_once(ROUTER . '404.php');
    }
} else {
    include_once(ROUTER . '404.php');
}

?>