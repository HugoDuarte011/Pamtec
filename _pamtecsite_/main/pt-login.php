<?php

class Login {
    private $email;
    private $pass;
    private $permission;
    private $conn;

    //construtor da classe
    public function __construct($email, $pass){
        $this->email = $email;
        $this->pass = $pass;
        $this->conn = connection_MySql();
        $this->permission = 0;
    }

    public function getEmai(){
        return $this->email;
    }

    public function getPass(){
        return $this->pass;
    }

    public function getPermission(){
        return $this->permission;
    }

    public function Login_User(){
        $password_encrypt = base64_encode($this->encrypt_password($this->pass));
        
        $query = "SELECT 
                    ID 
                    ,user_login
                    ,user_permission
                FROM 
                    apswp_users 
                WHERE 
                    user_login = ? AND 
                    user_pass = ?";

        $sth = $this->conn->prepare($query);
        $sth->bind_param("ss", $this->email, $password_encrypt);
        $sth->execute();
        $result = $sth->get_result();
        $user = $result->fetch_object();

        /* Verifica se retornou o login*/
        if($result->num_rows > 0){
            $_SESSION['user_id'] = $user->ID;
            $_SESSION['user_permission'] = $user->user_permission;
            $this->permission = $user->user_permission;
        } else
            $this->permission = 2; // RETORNO COM ERRO NO LOGIN
    
        return $this->permission;
    }

    // Criptografia
    public function encrypt_password($pass){
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

    public function decrypt_password($pass){
        if(!isset($pass)) return(false);
        $password_descrypt = openssl_decrypt($pass, 'AES-128-CBC', SECURE_AUTH_KEY, 0, LOGGED_IN_KEY);
        return json_decode($password_descrypt, true);
    }
}

// Verifica se passou o e-mail e senha
if(isset($_POST['btnLogar'])) {
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    
    if($email <> '' || $senha <> '') 
        if(!Acessar($email, $senha)) 
            $msg = "E-mail ou Senha inválidos!";
    else 
        $msg = "E-mail ou Senha não informados!";
}

function Acessar($email, $senha){
    $login = new Login($email, $senha);
    $permission = $login->Login_User(); 
    $retorno = true;

    if(isset($permission)){
        if($permission === 0) header("Location: home");
        if($permission === 1) header("Location: Admin_Page");
        if($permission === 2) $retorno = false;
    }

    return $retorno;
}

// Se existir sessão, verifica qual página deve carregar
if(isset($_SESSION['user_id'])){
    if($_SESSION['user_permission'] === 1){
        header("Location: Admin_Page");
    } else {
        header("Location: home");
    }
}
else
    include(ROUTER . 'login.php');
?>