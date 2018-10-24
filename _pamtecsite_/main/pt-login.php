<?php

if(isset($_POST['btnLogar'])) {

    if (!$conn) die('Could not connect: ' . mysql_error());

    $user = $_POST['txtEmail'];
    $pass = $_POST['txtSenha'];

    // Caso a senha estiver vazia retorna para a página
    if($user === '' || $pass === '') 
        $msg = "Email ou senha não informados!";
    else {
        $password_encrypt = base64_encode(encrypt_password($pass));

        $query = "SELECT ID, user_login, user_permission FROM apswp_users WHERE user_login = ? AND user_pass = ?";
        $sth = $conn->prepare($query);
        $sth->bind_param("ss", $user, $password_encrypt);
        $sth->execute();
        $result = $sth->get_result();
        $user = $result->fetch_object();

        /* Verifica se retornou o login*/
        if($result->num_rows > 0){
            $_SESSION['user_id'] = $user->ID;
            $permission = $user->user_permission;

            //Verifica se é administrador ou cliente
            if($permission === 0) header("Location: home");
            if($permission === 1) header("Location: Admin_Page");

        } else {
            $msg = "Falha no login";
        }

    }
}

// Se existir sessão, verifica qual página deve carregar
if(isset( $_SESSION['user_id']))
    header("Location: Admin_Page");
else
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

function login_user_permission($user){

}
?>