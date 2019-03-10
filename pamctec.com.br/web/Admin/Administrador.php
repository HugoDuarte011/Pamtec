<?php 

    class Administrador {
        
        private $user_id;
        private $user_permission;
        private $conn;

        private $user;
        private $certificate;
        
        //construtor da classe
        public function __construct(){
            $this->conn = connection_MySql();
            $this->user_id = @$_SESSION['user_id'];
            $this->user_permission = @$_SESSION['user_permission'];

            $this->router();
        }

        // Verifica a restrição do Site
        public function Verifica_Restricao(){
            $retricao = false;

            $query = "SELECT
                        user_permission
                    FROM
                        apswp_users
                    WHERE
                        ID = ?
            ";

            $sth = $this->conn->prepare($query);
            $sth->bind_param("i", $this->user_id);
            $sth->execute();
            $result = $sth->get_result();
            $user = $result->fetch_object();

            if($result->num_rows > 0){
                if($user->user_permission === 1)
                    $retricao = true;
            }
            
            return $retricao;
        }

        // Carrega a lista dos Certificados
        public function Carregar_Certificados(){
            $sql = "SELECT
                        arq.ID
                        ,coalesce(arq.arquivo, '') as arquivo
                        ,arq.data_inclusao
                        ,coalesce(arq.cliente, 0) as cliente
                        ,coalesce(arq.nome_arquivo, '') nome_arquivo
                        ,coalesce(user.display_name, '') as nome_cliente
                    FROM
                        apswp_arquivos as arq
                    INNER JOIN apswp_users as user on user.ID = arq.cliente
            ";

            $sth = $this->conn->prepare($sql);
            $sth->execute();
            $result = $sth->get_result();
            $certificate = '';
            if ($result->num_rows > 0) {
                while($row = $result->fetch_object()){
                    $certificate[$row->ID]['arquivo'] = utf8_encode($row->arquivo);
                    $certificate[$row->ID]['data_inclusao'] = utf8_encode($row->data_inclusao);
                    $certificate[$row->ID]['cliente'] = utf8_encode($row->cliente);
                    $certificate[$row->ID]['nome_arquivo'] = utf8_encode($row->nome_arquivo);
                    $certificate[$row->ID]['nome_cliente'] = utf8_encode($row->nome_cliente);
                }
            }

            return $certificate;
        }

        // Carrega a lista dos usuários
        public function Carregar_Usuarios(){
            $sql = "SELECT
                        ID
                        ,coalesce(display_name, '') as nome
                        ,coalesce(user_nicename, '') as fantasia
                        ,coalesce(user_cnpj, '') as cnpj
                        ,coalesce(user_email, '') as email
                    FROM
                        apswp_users
                    WHERE
                        user_permission = 0
            ";

            try {
                $sth = $this->conn->prepare($sql);
                $sth->execute();
            } catch(Exception $e){
                echo $e->getMessage();
            }

            $result = $sth->get_result();
            $user = '';
            if ($result->num_rows > 0) {
                while($row = $result->fetch_object()){
                    $user[$row->ID]['fantasia'] = utf8_encode($row->fantasia);
                    $user[$row->ID]['nome'] = utf8_encode($row->nome);
                    $user[$row->ID]['cnpj'] = utf8_encode($row->cnpj);
                    $user[$row->ID]['email'] = utf8_encode($row->email);
                }
            }

            return $user;
        }

        // Criptografia
        public function encrypt_password($pass){
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
            $password_descrypt = openssl_decrypt($pass, 'AES-128-CBC', SECURE_AUTH_KEY, 0, LOGGED_IN_KEY);
            return json_decode($password_descrypt, true);
        }

        /** 
        * @param ID $ID_User do Usuario para carregar os dados do Usuário
        */
        public function Retorna_Usuario($id){
            $sql = "SELECT * FROM apswp_users WHERE ID = ?";

            $sth = $this->conn->prepare($sql);
            $sth->bind_param("i", $id);
            $sth->execute();
            $result = $sth->get_result();
            $new_user = $result->fetch_object();
            
            return $new_user;
        }

        /** 
        * @param ID $certificate_id do Certificado para carregar os dados do Certificado
        */
        public function Retorna_Certificado($certificate_id){
            $sql = "SELECT 
                        arq.*
                        ,COALESCE(user.user_nicename, '') AS nome
                    FROM 
                        apswp_arquivos AS arq 
                    LEFT JOIN apswp_users AS user ON user.ID = arq.cliente
                    WHERE arq.ID = ?";

            $sth = $this->conn->prepare($sql);
            $sth->bind_param("i", $certificate_id);
            $sth->execute();
            $result = $sth->get_result();
            $object = $result->fetch_object();
            return $object;
        }

        /** 
        * @param ID $ID do Usuario para alteração
        * @param user_nicename $fantasia nome Fantasia do usuario 
        * @param user_email $email do usuario
        * @param user_cnpj $cnpj do usuario
        * @param user_login $login do usuario
        * @param user_pass $senha do usuario
        * @param user_permission $permissao do usuario -> 1 = Admin/0 = Cliente
        *
        * Função para Alterar o usuário
        */
        public function Alterar_Usuario($ID, $display_name, $user_nicename, $user_email, $user_cnpj, $user_login, $user_pass, $user_permission){
            $sql = "UPDATE apswp_users SET 
                display_name = ?,
                user_nicename = ?,
                user_email = ?,
                user_cnpj = ?,
                user_login = ?,
                user_pass = ?,
                user_permission = ?
                WHERE ID = ?
            ";

            $password_encrypt = base64_encode($this->encrypt_password($user_pass));
            $sth = $this->conn->prepare($sql);
            $sth->bind_param("ssssssii", $display_name, $user_nicename, $user_email, $user_cnpj, $user_login, $password_encrypt, $user_permission, $ID);
            echo $user_cnpj;
            try {
                $sth->execute();
            } catch (Exception $e) {
                echo $e->getMessage();
            }
            

            return $sth->affected_rows > 0;
        }

        /** 
        * @param display_name $razao do usuario
        * @param user_nicename $fantasia nome Fantasia do usuario 
        * @param user_email $email do usuario
        * @param user_cnpj $cnpj do usuario
        * @param user_login $login do usuario
        * @param user_pass $senha do usuario
        * @param user_permission $permissao do usuario -> 1 = Admin/0 = Cliente
        *
        * Função para Cadastrar o usuário
        */
        public function Incluir_Usuario($display_name, $user_nicename, $user_email, $user_cnpj, $user_login, $user_pass, $user_permission){
            $sql = "INSERT INTO apswp_users (
                display_name,
                user_nicename,
                user_email,
                user_cnpj,
                user_login,
                user_pass,
                user_permission
                )
                VALUES
                (?,?,?,?,?,?,?)
            ";
            $password_encrypt = base64_encode($this->encrypt_password($user_pass));

            $sth = $this->conn->prepare($sql);
            $sth->bind_param("ssssssi", $display_name, $user_nicename, $user_email, $user_cnpj, $user_login, $password_encrypt, $user_permission);
            $sth->execute();
            return $sth->insert_id > 0;
        }
        
        /**
         * @param ID $ID do Usuário para ser Deletado
         */
        public function Delete_Usuario($ID){
            $sql = "DELETE FROM apswp_users WHERE ID = ?";
            $sth = $this->conn->prepare($sql);
            $sth->bind_param("i", $ID);
            $sth->execute();
            return $sth->affected_rows > 0;
        }

        /** 
        * @param nome_arquivo $nome_arquivo do arquivo
        * @param pasta $pasta destino do arquivo
        * @param tamanho_documento $tamanho do arquivo
        * @param cliente $cliente do arquivo
        *
        * Função para Alterar o Certificado
        */
        public function Alterar_Certificado($ID, $nome_arquivo, $pasta, $tamanho, $cliente) {
            $sql = "UPDATE apswp_arquivos SET
                nome_arquivo = ?,
                pasta = ?,
                tamanho_documento = ?,
                cliente = ?
                WHERE ID = ?
            ";

            $sth = $this->conn->prepare($sql);
            $sth->bind_param("ssii", $nome_arquivo, $pasta, $tamanho, $cliente, $ID);
            $sth->execute();

            return $sth->affected_rows > 0;
        }


        /** 
        * @param nome_arquivo $nome_arquivo do arquivo
        * @param pasta $pasta destino do arquivo
        * @param tamanho_documento $tamanho do arquivo 
        * @param cliente $cliente do arquivo
        *
        * Função para Cadastrar o Certificado
        */
        public function Cadastrar_Certificado($nome_arquivo, $pasta, $tamanho, $cliente) {
            $sql = "INSERT INTO apswp_arquivos (
                nome_arquivo,
                pasta,
                tamanho_documento,
                cliente,
                data_inclusao
                )
                VALUES
                (?,?,?,?, NOW())
            ";
            
            $sth = $this->conn->prepare($sql);
            $sth->bind_param("ssii", $nome_arquivo, $pasta, $tamanho, $cliente);
            $sth->execute();
            return $sth->insert_id > 0;
        }

         /**
         * @param ID $ID do Certificado para ser Deletado
         */
        public function Delete_Certificado($ID){
            $sql = "DELETE FROM apswp_arquivos WHERE ID = ?";
            $sth = $this->conn->prepare($sql);
            $sth->bind_param("i", $ID);
            $sth->execute();
            return $sth->affected_rows > 0;
        }

        /**
         * @param fk_cliente $user_id verifica se o cliente passado existe
         */
        public function Verifica_Cliente($user_id){
            $sql = "SELECT * FROM apswp_users WHERE ID = ?";
            $sth = $this->conn->prepare($sql);
            $sth->bind_param("i", $user_id);
            return $sth->num_rows > 0;
        }

        /**
         * @param user_logn $login verifica se o Login já existe no banco de dados
         */
        public function Verifica_Login($login){
            $sql = "SELECT * FROM apswp_users WHERE user_login = ?";
            $sth = $this->conn->prepare($sql);
            $sth->bind_param("s", $login);
            $existe = false; 
            if ($sth->num_rows > 0) {
                $existe = true;
            }
            return $existe;
        }

        public function getUser(){
            return $this->user;
        }

        public function getCertificate(){
            return $this->certificate;
        }

        /**
         * Controle do Usuário
         * @param ecod ID do Usuário para Inclusão/Alterar
         * @param dcod ID do Usuário para ser Deletado
         */
        public function CRUD_Cliente(){
            
            $this->user["razao"] = "";
            $this->user["fantasia"] = "";
            $this->user["email"] = "";
            $this->user["cnpj"] = "";
            $this->user["login"] = "";
            $this->user["senha"] = "";
            $this->user["confirmaSenha"] = "";
            $this->user["validacao"] = "";
            $this->user["titulo"] = "";
            $this->user["acao"] = "";
            $this->user["botao"] = "";
            
            if(isset($_GET["user"])){
                $user_id = $_GET["user"];
                if(is_numeric($user_id)) {
                    $this->user["titulo"] = "Cadastrar Cliente";
                    $this->user["botao"] = "Cadastrar";

                    $new_user = "";

                    // Campos do usuário
                    $razao = "";
                    $fantasia = "";
                    $email = "";
                    $login = "";
                    $senha = "";
                    $cnpj = "";
                    $permissao = 0;

                    // Mensagem de validação
                    $validacao_cliente = "";

                    switch($user_id){
                        case 0:
                            $this->user["acao"] = "A";

                            // Validar com lista branca
                            if(isset($_POST["btnCliente"])){
                                $razao = $_POST['razao'];
                                $fantasia = $_POST['fantasia'];
                                $email = $_POST['email'];
                                $cnpj = $_POST['cnpj'];
                                $login = $_POST['login'];
                                $senha = $_POST['senha'];
                                $confirmaSenha = $_POST['confirmaSenha'];

                                if($this->Verifica_Login($login)){
                                    $this->user["validacao"] = "Login informado já está em uso!";
                                } else if($senha <> $confirmaSenha){
                                    $this->user["validacao"] = "Senhas informadas não conferem"; 
                                } else {
                                    // Cadastro do Usuário
                                    $retorno = $this->Incluir_Usuario($razao, $fantasia, $email, $cnpj, $login, $senha, $permissao);
                                    if($retorno){
                                        header("Location: ../Admin/");
                                    } else {
                                        $this->user["validacao"] = "Não foi possível inserir o Usuário";
                                    }
                                }
                            }

                        case $user_id > 0;
                            // Verifica se o cliente existe
                            $new_user = $this->Retorna_Usuario($user_id);
                            if(isset($new_user)){
                                $this->user["acao"] = "M";

                                $this->user["botao"] = "Alterar";
                                $this->user["titulo"] = "Alterar Cliente";
                                
                                $this->user["razao"] = $new_user->display_name;
                                $this->user["fantasia"] = $new_user->user_nicename;
                                $this->user["email"] = $new_user->user_email;
                                $this->user["login"] = $new_user->user_login;
                                $this->user["cnpj"] = $new_user->user_cnpj;
                                $senha_decode = $this->decrypt_password(base64_decode($new_user->user_pass));
                                $this->user["senha"] = $senha_decode['password'];

                                // Validar com lista branca
                                if(isset($_POST["btnCliente"])){
                                    $razao = $_POST['razao'];
                                    $fantasia = $_POST['fantasia'];
                                    $email = $_POST['email'];
                                    $cnpj = $_POST['cnpj'];
                                    $login = $_POST['login'];
                                    $senha = $_POST['senha'];

                                    if($this->Verifica_Login($login)){
                                        $this->user["validacao"] = "Login informado já está em uso";
                                    } else {
                                        // Alteração do Usuário
                                        $retorno = $this->Alterar_Usuario($user_id, $razao, $fantasia, $email, $cnpj, $login, $senha, $permissao);
                                        if($retorno){
                                            header("Location: ../Admin/");
                                        } else {
                                            $this->user["validacao"] = "Não foi possível alterar o Usuário";
                                        }
                                    }

                                }

                            } else
                                // Dar mensagem de que o usuário informado não existe
                            break;
                        default:
                            break;
                    }
    
                } else {
                    // Página do Admin
                    header("Location: ../Admin/");
                }
                
            } else if(isset($_GET["user_del"])){
                $delete = $_GET["user_del"];
                if(is_numeric($delete)) {
                    if($this->Delete_Usuario($delete)){
                        header("Location: ../Admin/");
                    }
                }
            }

        }

        /**
         * Controle do Usuário
         * @param certificate ID do Certificado para Inclusão/Alterar
         * @param delete_certificate_id ID do Certificado para ser Deletado
         */
        public function CRUD_Certificado(){
            // Controle Alteração/Inclusão do Certificado
            if(isset($_GET["certificate"])){
                $certificate_id = $_GET["certificate"];
                if(!is_numeric($certificate_id)) header("Location: ../Admin/");
                
                $this->certificate["titulo"]        = "Cadastrar Certificado";
                $fomatosPermitidos                  = array("PDF");

                // Carrega os usuários 
                $this->certificate["user"]          = $this->Carregar_Usuarios();

                // Campos do Arquivo
                $this->certificate["arquivo"]       = "";
                $this->certificate["nomeArquivo"]   = "";
                $this->certificate["nome"]          = "";
                $this->certificate["novoNome"]      = "";
                $this->certificate["pasta"]         = "../pamtec/upload/";
                $this->certificate["cliente"]       = 0;
                $this->certificate["tamanho"]       = 0;

                // Mensagem de validação
                $this->certificate["validacao"]     = "";

                switch($certificate_id){
                    case 0:
                        // Requisão de inclusão
                        if(isset($_POST['btnPostarCertificado'])){
                            $cliente        = $_POST['cliente'];

                            $arquivo_temp   = $_FILES["certificado"]["tmp_name"];
                            $nome_arquivo   = $_FILES["certificado"]["name"];
                            $tamanho        = $_FILES["certificado"]["size"];

                            $extensao       = pathinfo($nome_arquivo, PATHINFO_EXTENSION);
                            //$novoNome       = uniqid().".$extensao";
                            $novoNome       = $nome_arquivo;
                            $arquivo        = isset($_FILES["certificado"]) ? $_FILES["certificado"] : FALSE;
                            // verificar o por que não está validando quando vazio
                            if($nome_arquivo === "" || empty($nome_arquivo)){
                                $this->certificate["validacao"] = "Selecione um arquivo";
                            } else if(!in_array(strtoupper($extensao), $fomatosPermitidos)){
                                $this->certificate["validacao"] = "Arquivo selecionado no formato inválido, formato aceito apenas no formato .PDF";
                            } else if($arquivo){
                                // Cadastro do Certificado
                                } if($cliente == 0 || $cliente <= 0){
                                    $this->certificate["validacao"] = "Selecione um cliente para ser vinculado o certificado";
                                } else if($this->Verifica_Cliente($cliente)){
                                    $this->certificate["validacao"] = "Cliente informado não existe"; 
                                } else {
                                    // Upload de arquivo
                                    if(move_uploaded_file($arquivo_temp, $this->certificate["pasta"].$novoNome)){
                                        if($this->Cadastrar_Certificado($novoNome, $this->certificate["pasta"], $tamanho, $cliente)){
                                            header("Location: ../Admin/");
                                        } else {
                                            $this->certificate["validacao"] = "Não foi possível incluir o Certificado";   
                                        } 
                                    } else {
                                        $this->certificate["validacao"] = "Erro, não foi possível fazer o upload.";
                                    }
                                }
                        }

                    case $certificate_id > 0:
                        // Verifica se o cliente existe
                        $new_certificate = $this->Retorna_Certificado($certificate_id);
                        
                        if(isset($new_certificate)){
                            // Ação que o CRUD está realizando
                            $this->certificate["titulo"]        = "Alterar Certificado";

                            $this->certificate["nomeArquivo"]   = $new_certificate->nome_arquivo;
                            $this->certificate["pasta"]         = $new_certificate->pasta;
                            $this->certificate["tamanho"]       = $new_certificate->tamanho_documento;
                            $this->certificate["cliente"]       = $new_certificate->cliente;
                            $this->certificate["nome"]          = $new_certificate->nome;
                            $this->certificate["ID"]            = $new_certificate->ID;

                            if(isset($_POST['btnPostarCertificado'])){
                                $arquivo_temp   = $_FILES["certificado"]["tmp_name"];
                                $nome_arquivo   = $_FILES["certificado"]["name"];
                                $tamanho        = $_FILES["certificado"]["size"];
                                $cliente        = $_POST['cliente'];

                                $extensao       = pathinfo($nome_arquivo, PATHINFO_EXTENSION);
                                //$novoNome       = uniqid().".$extensao";
                                $novoNome       = $nome_arquivo;
                                $arquivo        = isset($_FILES["certificado"]) ? $_FILES["certificado"] : FALSE;

                                if($nome_arquivo === ''){
                                    $this->certificate["validacao"] = "Selecione um arquivo";
                                } else if(!in_array(strtoupper($extensao), $fomatosPermitidos)){
                                    $this->certificate["validacao"] = "Arquivo selecionado no formato inválido, formato aceito apenas no formato .PDF";
                                } else if($arquivo){
                                    // Cadastro do Certificado
                                    if($cliente == 0 || $cliente <= 0){
                                        $this->certificate["validacao"] = "Selecione um cliente para ser vinculado o certificado";
                                    } else if($this->Verifica_Cliente($cliente)){
                                        $this->certificate["validacao"] = "Cliente informado não existe";
                                    } else {
                                        // Upload de arquivo
                                        if(move_uploaded_file($arquivo_temp, $this->certificate["pasta"].$novoNome)){
                                            if($this->Alterar_Certificado($ID, $novoNome, $this->certificate["pasta"], $tamanho, $cliente)){
                                                header("Location: ../Admin/");
                                            } else {
                                                $this->certificate["validacao"] = "Não foi possível Alterar o Certificado";   
                                            }
                                        } else {
                                            $this->certificate["validacao"] = "Erro, não foi possível fazer o upload.";
                                        }
                                    }
                                }
                            }

                        } else {
                            // Avisar que o certificado não existe
                        }

                    default:
                        // Ver depois como informar que o Certificado informado não existe
                        break;
                }


            } else if(isset($_GET["delete_certificate"])){
                $delete = $_GET["delete_certificate"];
                if(is_numeric($delete)) {
                    if($this->Delete_Certificado($delete)){
                        header("Location: ../Admin/");
                    }
                }
            }

        }

        // Controle de carregamento da página
        public function router(){
            // Verifica se tem acesso na página
            if(isset($this->user_id) && isset($this->user_permission)){
                
                if(!$this->Verifica_Restricao())
                    // Volta para página inicial
                    header("Location: ../");
                else {
                    
                    if(isset($_POST["btnCancelarAdmin"]))
                        // Encaminha para a página do Admin
                        header("Location: ../Admin/");

                    // CRUD do Cliente
                    if(isset($_GET['user'])) $this->CRUD_Cliente();
                    else if(isset($_GET['user_del'])) $this->CRUD_Cliente();

                    // CRUD do Certificado
                    else if(isset($_GET['certificate'])) $this->CRUD_Certificado();
                    else if(isset($_GET['delete_certificate'])) $this->CRUD_Certificado();
                }
            } else {
                header("Location: ../Autenticacao/");
            }
        }
    }

    $admin = new Administrador();
    $user = $admin->Carregar_Usuarios();
    $certificate = $admin->Carregar_Certificados();

    if(isset($_GET['user'])){
        $user = $admin->getUser();
    }

    if(isset($_GET['certificate'])) {
        $certificate = $admin->getCertificate();
    }
?>