<?php
    // session_start inicia a sessão
    session_start();

    // Conexão com o banco de dados MySql
    require( 'pt-db.php' );

    if(isset($_POST['btnLogar'])) {

        if (!$conn) die('Could not connect: ' . mysql_error());

        $user = $_POST['user']);
        $pass = $_POST['pass']);

        $sql = "SELECT `user_login` FROM `apswp_users` WHERE `user_login` = ? AND `user_pass` = ?";
        $sth = $conn->prepare($sql);
        $result = $sth->bind_param("ss", $user, $pass);
        
        if($sth->execute()){
            $_SESSION['user'] = $user;

            echo "Login realizado com sucesso";
        } else {
            unset ($_SESSION['user']);

            echo "Falha no login";
        }

    }

    include('pt-includes/templats/login.php');
?>