<?php 

    class Administrador {
        
        private $user_id;
        private $conn;

        //construtor da classe
        public function __construct($user_id){
            $this->user_id = $user_id;
            $this->conn = connection_MySql();
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
                    $user[$row->ID]['nome'] = utf8_encode($row->nome);
                    $user[$row->ID]['cnpj'] = utf8_encode($row->cnpj);
                    $user[$row->ID]['email'] = utf8_encode($row->email);
                }
            }

            return $user;
        }

        /** 
        * @param ID $ID_User do Usuario para alteração
        */
        public function Alteracao_Usuario(){

        }

        /** 
        * @param ID $ID_User = 0 da Usuario para inclusão
        */
        public function Inclusao_Usuario(){

        }

    }

    // Verifica se tem acesso na página
    if(isset($_SESSION['user_id']) && isset($_SESSION['user_permission'])){
        $user_id = $_SESSION['user_id'];
        
        $administrador = new Administrador($user_id);
        if(!$administrador->Verifica_Restricao())
            header("Location: home");
        else {
            $user = $administrador->Carregar_Usuarios();
            $certificate = $administrador->Carregar_Certificados();
            $conn = connection_MySql();
            $conn->close();
            include(ROUTER . 'Admin_Page.php');
        }
    } else
        header("Location: home");

    
?>