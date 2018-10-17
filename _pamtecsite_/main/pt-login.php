<?php

$msg = '';
$login = true;

if(isset($_POST['btnLogar'])) {

    if (!$conn) die('Could not connect: ' . mysql_error());

    $user = $_POST['txtEmail'];
    $pass = $_POST['txtSenha'];

    // Caso a senha estiver vazia retorna para a página
    if($user === '' || $pass === '') {
        $msg = "Email ou senha não informado!";
        $login = false;
    }
    
    // LOGIN no sistema
    if ($login) {
        $password_encrypt = base64_encode(encrypt_password($pass));

        $query = "SELECT user_login, user_pass FROM apswp_users WHERE user_login = ? AND user_pass = ?";
        $sth = $conn->prepare($query);
        $sth->bind_param("ss", $user, $password_encrypt);
        $sth->execute();
        $result = $sth->get_result();

        if($result->num_rows > 0){
            $_SESSION['user'] = $user;
            $msg =  "Login realizado com sucesso";
        } else {
            $msg = "Falha no login";
        }

    }
}

include(ROUTER . 'login.php');

// Criptografia
function encrypt_password($pass){

    if(!isset($pass)){
        return(false);
    }

    $data = ["password" => $pass];

    $password = openssl_encrypt (
        json_encode($data),
        'AES-128-CBC',
        SECURE_AUTH_KEY,
        0,
        LOGGED_IN_KEY
    );

    return $password;
}

function decrypt_password($pass){
    if(!isset($pass)) return(false);
    $password_descrypt = openssl_decrypt($pass, 'AES-128-CBC', SECURE_AUTH_KEY, 0, LOGGED_IN_KEY);
    return json_decode($password_descrypt, true);
}
?>