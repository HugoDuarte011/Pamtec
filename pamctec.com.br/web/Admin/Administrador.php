<?php 

    class Administrador {
        
        private $user_id;
        private $user_permission;
        private $conn;

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
                        ,coalesce(arq.fk_cliente, 0) as cliente
                        ,coalesce(arq.nome_arquivo, '') nome_arquivo
                        ,coalesce(user.display_name, '') as nome_cliente
                    FROM
                        apswp_arquivos as arq
                    INNER JOIN apswp_users as user on user.ID = arq.fk_cliente
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
            ";

            $sth = $this->conn->prepare($sql);
            $sth->execute();
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
                    LEFT JOIN apswp_users AS user ON user.ID = arq.fk_cliente
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
        * @param tamanho_documento $tamanho_documento tamanho do arquivo 
        * @param arquivo $dados do arquivo
        * @param fk_cliente $cliente do arquivo
        *
        * Função para Alterar o Certificado
        */
        public function Alterar_Certificado($ID, $nome_arquivo, $tamanho_documento, $dados, $cliente) {
            $sql = "UPDATE apswp_arquivos SET
                nome_arquivo = ?,
                tamanho_documento = ?,
                arquivo = ?,
                fk_cliente = ?
                WHERE ID = ?
            ";

            $sth = $this->conn->prepare($sql);
            $sth->bind_param("sisii", $nome_arquivo, $tamanho_documento, $dados, $cliente, $ID);
            $sth->execute();

            return $sth->affected_rows > 0;
        }


        /** 
        * @param nome_arquivo $nome_arquivo do arquivo
        * @param tamanho_documento $tamanho_documento tamanho do arquivo 
        * @param arquivo $dados do arquivo
        * @param fk_cliente $cliente do arquivo
        *
        * Função para Cadastrar o Certificado
        */
        public function Cadastrar_Certificado($nome_arquivo, $tamanho_documento, $dados, $cliente) {
            $sql = "INSERT INTO apswp_arquivos (
                nome_arquivo,
                tamanho_documento,
                arquivo,
                fk_cliente,
                data_inclusao
                )
                VALUES
                (?,?,?,?, NOW())
            ";
            
            $sth = $this->conn->prepare($sql);
            $sth->bind_param("sisi", $nome_arquivo, $tamanho_documento, $dados, $cliente);
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

        /**
         * Controle do Usuário
         * @param ecod ID do Usuário para Inclusão/Alterar
         * @param dcod ID do Usuário para ser Deletado
         */
        public function CRUD_Cliente(){
            
            if(isset($_GET["ecod"])){
                $user_id = $_GET["ecod"];
                if(is_numeric($user_id)) {
                    $titleCliente = "Cadastrar Cliente";
                    $btnSubmit = "Cadastrar";
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
                            $acao = "A";

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
                                    $validacao_cliente = "Login informado já está em uso";
                                } else if($senha <> $confirmaSenha){
                                    $validacao_cliente = "Senhas informadas não conferem"; 
                                } else {
                                    // Cadastro do Usuário
                                    $retorno = $this->Incluir_Usuario($razao, $fantasia, $email, $cnpj, $login, $senha, $permissao);
                                    if($retorno){
                                        header("Location: admin.php");
                                    } else {
                                        $validacao_cliente = "Não foi possível inserir o Usuário";
                                    }
                                }
                            }

                            // Página de cadastro de Cliente
                            include(ROUTER . 'Cadastrar_Clientes.php');
                            break;

                        case $user_id > 0;
                            // Verifica se o cliente existe
                            $new_user = $this->Retorna_Usuario($user_id);
                            if(isset($new_user)){
                                $acao = "M";

                                $btnSubmit = "Alterar";
                                $titleCliente = "Alterar Cliente";
                                $razao = $new_user->display_name;
                                $fantasia = $new_user->user_nicename;
                                $email = $new_user->user_email;
                                $login = $new_user->user_login;
                                $senha_decode = $this->decrypt_password(base64_decode($new_user->user_pass));
                                $senha = $senha_decode['password'];
                                $cnpj = $new_user->user_cnpj;
                                $permissao = $new_user->user_permission;

                                // Validar com lista branca
                                if(isset($_POST["btnCliente"])){
                                    $razao = $_POST['razao'];
                                    $fantasia = $_POST['fantasia'];
                                    $email = $_POST['email'];
                                    $cnpj = $_POST['cnpj'];
                                    $login = $_POST['login'];
                                    $senha = $_POST['senha'];

                                    if($this->Verifica_Login($login)){
                                        $validacao_cliente = "Login informado já está em uso";
                                    } else {
                                        // Alteração do Usuário
                                        $retorno = $this->Alterar_Usuario($user_id, $razao, $fantasia, $email, $cnpj, $login, $senha, $permissao);
                                        if($retorno){
                                            header("Location: admin.php");
                                        } else {
                                            $validacao_cliente = "Não foi possível alterar o Usuário";
                                        }
                                    }

                                }

                                // Página de cadastro de Cliente
                                include(ROUTER . 'Cadastrar_Clientes.php');
                                break;
                            } else
                                // Dar mensagem de que o usuário informado não existe
                            break;
                        default:
                            break;
                    }
    
                } else {
                    // Página do Admin
                    header("Location: admin.php");
                }
                
            } else if(isset($_GET["dcod"])){
                $delete = $_GET["dcod"];
                if(is_numeric($delete)) {
                    if($this->Delete_Usuario($delete)){
                        header("Location: admin.php");
                    }
                }
            }

        }

        /**
         * Controle do Usuário
         * @param certificate_id ID do Certificado para Inclusão/Alterar
         * @param delete_certificate_id ID do Certificado para ser Deletado
         */
        public function CRUD_Certificado(){
            // Controle Alteração/Inclusão do Certificado
            if(isset($_GET["certificate_id"])){
                $certificate_id = $_GET["certificate_id"];
                if(!is_numeric($certificate_id)) header("Location: Admin_Page");
                
                $titleCertificate = "Cadastrar Certificado";

                // Campos do Arquivo
                $arquivo = '';
                $tamanho_documento = 0;
                $cliente = 0;
                $nomeArquivo = '';
                $nome = '';

                // Mensagem de validação
                $validacaoCertificado = "";

                switch($certificate_id){
                    case 0:
                        // Ação que o CRUD está realizando
                        $acao = "A";

                        // Requisão de inclusão
                        if(isset($_POST['btnPostarCertificado'])){
                            $arquivo_temp = $_FILES["certificado"]["tmp_name"];
                            $nome_arquivo =  $_FILES["certificado"]["name"];
                            $ext = pathinfo($nome_arquivo, PATHINFO_EXTENSION);
                            $cliente = $_POST['cliente'];
                            $arquivo = isset($_FILES["certificado"]) ? $_FILES["certificado"] : FALSE;
                            
                            if($nome_arquivo === ''){
                                $validacaoCertificado = "Selecione um arquivo";
                            } else if(strtoupper($ext) <> 'PDF'){
                                $validacaoCertificado = "Arquivo selecionado no formato inválido, formato aceito apenas no formato .PDF";
                            } else if($arquivo){
                                $fp = fopen($arquivo_temp,"rb");
                                $dados_documento = fread($fp,filesize($arquivo_temp));
                                fclose($fp); 

                                $tamanho_documento = $arquivo['size'];
                                $dados = bin2hex($dados_documento);
                                
                                // Cadastro do Certificado
                                if($this->Verifica_Cliente($cliente)){
                                    $validacaoCertificado = "Cliente informado não existe";
                                } else if($cliente == 0 || $cliente <= 0){
                                    $validacaoCertificado = "Selecione um cliente para ser vinculado o certificado";
                                } else {
                                    $retorno = $this->Cadastrar_Certificado($nome_arquivo, $tamanho_documento, $dados, $cliente);
                                    if($retorno){
                                        header("Location: admin.php");
                                    } else {
                                        $validacaoCertificado = "Não foi possível incluir o Certificado";   
                                    }
                                }
                                
                            } else {
                                $validacaoCertificado = "Selecione um arquivo válido!";
                            }

                        }
                        
                        // Carrega os usuários 
                        $user = $this->Carregar_Usuarios();

                        // Página de cadastro de Cliente
                        include(ROUTER . 'Cadastrar_Certificado.php');
                        break;
                    case $certificate_id > 0:
                        // Verifica se o cliente existe
                        $new_certificate = $this->Retorna_Certificado($certificate_id);
                        
                        if(isset($new_certificate)){
                            // Ação que o CRUD está realizando
                            $acao = "M";
                            $titleCertificate = "Alterar Certificado";

                            $nomeArquivo = $new_certificate->nome_arquivo;
                            $tamanho_documento = $new_certificate->tamanho_documento;
                            $cliente = $new_certificate->fk_cliente;
                            $arquivo = $new_certificate->arquivo;
                            $nome = $new_certificate->nome;
                            $ID = $new_certificate->ID;

                            if(isset($_POST['btnPostarCertificado'])){
                                $arquivo_temp = $_FILES["certificado"]["tmp_name"];
                                $nome_arquivo =  $_FILES["certificado"]["name"];
                                $ext = pathinfo($nome_arquivo, PATHINFO_EXTENSION);
                                $cliente = $_POST['cliente'];
                                $arquivo = isset($_FILES["certificado"]) ? $_FILES["certificado"] : FALSE;

                                if($nome_arquivo === ''){
                                    $validacaoCertificado = "Selecione um arquivo";
                                } else if(strtoupper($ext) <> 'PDF'){
                                    $validacaoCertificado = "Arquivo selecionado no formato inválido, formato aceito apenas no formato .PDF";
                                } else if($arquivo){
                                    $fp = fopen($arquivo_temp,"rb");
                                    $dados_documento = fread($fp,filesize($arquivo_temp));
                                    fclose($fp); 

                                    $tamanho_documento = $arquivo['size'];
                                    $dados = bin2hex($dados_documento);

                                    // Cadastro do Certificado
                                    if($this->Verifica_Cliente($cliente)){
                                        $validacaoCertificado = "Cliente informado não existe";
                                    } else if($cliente == 0 || $cliente <= 0){
                                        $validacaoCertificado = "Selecione um cliente para ser vinculado o certificado";
                                    } else {
                                        $retorno = $this->Alterar_Certificado($ID, $nome_arquivo, $tamanho_documento, $dados, $cliente);
                                        if($retorno){
                                            header("Location: admin.php");
                                        } else {
                                            $validacaoCertificado = "Não foi possível Alterar o Certificado";   
                                        }
                                    }
                                }
                            }

                        } else {
                            // Avisar que o certificado não existe
                        }

                        // Carrega os usuários 
                        $user = $this->Carregar_Usuarios();

                        // Página de cadastro de Cliente
                        include(ROUTER . 'Cadastrar_Certificado.php');
                        break;

                    default:
                        // Ver depois como informar que o Certificado informado não existe
                        break;
                }


            } else if(isset($_GET["delete_certificate_id"])){
                $delete = $_GET["delete_certificate_id"];
                if(is_numeric($delete)) {
                    if($this->Delete_Certificado($delete)){
                        header("Location: admin.php");
                    }
                }
            }

        }

        // Sair do Sistema
        public function sair(){
            session_start();

            session_destroy();

            header("Location: index.php");
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
                        header("Location: ");

                    // CRUD do Cliente
                    if(isset($_GET['ecod'])) $this->CRUD_Cliente();
                    else if(isset($_GET['dcod'])) $this->CRUD_Cliente();

                    // CRUD do Certificado
                    else if(isset($_GET['certificate_id'])) $this->CRUD_Certificado();
                    else if(isset($_GET['delete_certificate_id'])) $this->CRUD_Certificado();

                    // Sair
                    else if(isset($_GET['sair'])) $this->sair();

                    else {
                        // Lista de Usuários e Certificados
                        $user = $this->Carregar_Usuarios();
                        $certificate = $this->Carregar_Certificados();
                    
                        // Página do Admin
                        header("Location: ");
                    }

                    // Fecha a conexão
                    $this->conn->close();
                }
            } else {
                header("Location: ../Autenticacao/");
            }
        }

        
    }

    $admin = new Administrador();
?>