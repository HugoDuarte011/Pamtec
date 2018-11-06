<?php 

    class Administrador {
        
        private $user_id;
        private $conn;

        //construtor da classe
        public function __construct($user_id, $conn){
            $this->user_id = $user_id;
            $this->conn = $conn;
        }

        public function getUser_Id(){
            return $this->user_id;
        }

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
            $sth->bind_param("s", $id);
            $sth->execute();
            $result = $sth->get_result();
            $new_user = $result->fetch_object();
            
            return $new_user;
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
            $sth->execute();

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
                                        header("Location: Admin_Page");
                                    } else {
                                        $validacao_cliente = "Não foi possível inserir o Usuário";
                                    }
                                }
                            }

                            // Página de cadastro de Cliente
                            include(ROUTER . 'Cadastrar_Clientes.php');
                            break;

                        case $id > 0;
                            // Verifica se o cliente existe
                            $new_user = $this->Retorna_Usuario($id);
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
                                        $retorno = $this->Alterar_Usuario($id, $razao, $fantasia, $email, $cnpj, $login, $senha, $permissao);
                                        if($retorno){
                                            header("Location: Admin_Page");
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
                    header("Location: Admin_Page");
                }
                
            } else if(isset($_GET["dcod"])){
                $delete = $_GET["dcod"];
                if(is_numeric($delete)) {
                    $deletado = $this->Delete_Usuario($delete);
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
                
                // Campos do Arquivo
                $arquivo = '';
                $dataInclusao = '';
                $cliente = 0;
                $nomeArquivo = '';

                // Mensagem de validação
                $validacaoCertificado = "";

                switch($certificate_id){
                    case 0:
                        // Ação que o CRUD está realizando
                        $acao = "A";

                        // Requisão de inclusão
                        if(isset($_POST['btnPostarCertificado'])){

                        }

                        // Página de cadastro de Cliente
                        include(ROUTER . 'Cadastrar_Certificado.php');
                        break;
                    case $certificate_id > 0:
                        break;

                    default:
                        // Ver depois como informar que o Certificado informado não existe
                        break;
                }


            } else if(isset($_GET["certificate_id"])){

            }

        }

        
    }

    // Verifica se tem acesso na página
    if(isset($_SESSION['user_id']) && isset($_SESSION['user_permission'])){
        $user_id = $_SESSION['user_id'];
        
        $conn = connection_MySql();
        $administrador = new Administrador($user_id, $conn);
        if(!$administrador->Verifica_Restricao())
            header("Location: home");
        else {
            
            if(isset($_POST["btnCancelarAdmin"]))
                header("Location: Admin_Page");

            // CRUD do Cliente
            if(isset($_GET['ecod'])) $administrador->CRUD_Cliente();
            else if(isset($_GET['dcod'])) $administrador->CRUD_Cliente();

            // CRUD do Certificado
            else if(isset($_GET['certificate_id'])) $administrador->CRUD_Certificado();
            else if(isset($_GET['delete_certificate_id'])) $administrador->CRUD_Certificado();

            else {
                // Lista de Usuários e Certificados
                $user = $administrador->Carregar_Usuarios();
                $certificate = $administrador->Carregar_Certificados();
            
                // Página do Admin
                include(ROUTER . 'Admin_Page.php');
            }

            // Fecha a conexão
            $conn->close();
        }
    } else
        header("Location: home");

?>