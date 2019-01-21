<?php 

Class Login { 

    private $param;
    private $router;
    private $template;

    private $email;
    private $pass;
    private $permission;
    private $conn;
    private $msg;

    public function __construct($param, $router){
        $this->param = $param;
        $this->router = $router;

        $this->conn = connection_MySql();
        $this->permission = 0;
        
        $this->msg = $this->Submit();
    }

    public function Submit() {
        // Verifica se passou o e-mail e senha
        if(isset($_POST["btnLogar"])) {
            
            $this->email = $_POST['email'];
            $this->senha = $_POST['senha'];
            if($this->email <> '' || $this->senha <> '') {
                if(!$this->Acessar($this->email, $this->senha)) 
                    $this->msg = "E-mail ou Senha inválidos!";
            }
            else {
                $this->msg = "E-mail ou Senha não informados!";
            }
        }
        
        return $this->msg;
    }

    public function router(){
        $this->template =  $this->router->getFile($this->param);

        // Se existir sessão, verifica qual página deve carregar
        if(isset($_SESSION['user_id']) && isset($_SESSION['user_permission'])){
            if($_SESSION['user_permission'] === 1)
                header("admin");
            else
                header("home");
        }

        $msg = $this->msg;
        include($this->template);
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

    public function Acessar($email, $senha){
        $permission = $this->Login_User(); 
        $retorno = true;
        
        if(isset($permission)){
            if($permission === 0) header("home");
            if($permission === 1) header("admin");
            if($permission === 2) $retorno = false;
        }

        return $retorno;
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
        
        $this->conn->close();
        
        return $this->permission;
    }

}

?>