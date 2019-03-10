<?php

class Download {
    private $permission;
    private $conn;

    private $certificate;

    //construtor da classe
    public function __construct(){
        $this->permission = 0;
        $this->conn = connection_MySql();

        $this->user_id = @$_SESSION['user_id'];
        $this->user_permission = @$_SESSION['user_permission'];

        $this->router();
        $this->submit();
    }

    // Verifica se o usuário possuí acesso
    public function Restricao(){
        $retricao = false;

        $query = "SELECT user_permission FROM apswp_users WHERE ID = ?";

        $sth = $this->conn->prepare($query);
        $sth->bind_param("i", $this->user_id);
        $sth->execute();
        $result = $sth->get_result();
        $user = $result->fetch_object();

        if($result->num_rows > 0){
            if($user->user_permission === 1 || $user->user_permission === 0)
                $retricao = true;
        }
        
        return $retricao;
    }

    // Verifica se o usuário possuí acesso
    public function router(){

        // Verifica se tem acesso na página
        if(isset($this->user_id) && isset($this->user_permission)){
            if(!$this->Restricao())
                header("Location: ../");
        } else {
            header("Location: ../Download/");
        }
    }

    /** 
    * @param ID $certificate_id do Certificado para carregar os dados do Certificado
    *  Carrega a lista dos Certificados
    */
    public function Carregar_Certificados(){
        $sql = "SELECT
                    arq.ID
                    ,coalesce(arq.arquivo, '') AS arquivo
                    ,arq.data_inclusao
                    ,coalesce(arq.cliente, 0) AS cliente
                    ,coalesce(arq.nome_arquivo, '') AS nome_arquivo
                    ,coalesce(user.display_name, '') AS nome_cliente
                FROM
                    apswp_arquivos AS arq
                INNER JOIN apswp_users AS user ON user.ID = arq.cliente
                WHERE
                    arq.cliente = ?
        ";

        $sth = $this->conn->prepare($sql);
        $sth->bind_param("i", $this->user_id);
        $sth->execute();
        $result = $sth->get_result();
        $certificate = "";

        if ($result->num_rows > 0) {
            while($row = $result->fetch_object()){
                $certificate[$row->ID]['data_inclusao']   = utf8_encode($row->data_inclusao);
                $certificate[$row->ID]['cliente']         = utf8_encode($row->cliente);
                $certificate[$row->ID]['nome_arquivo']    = utf8_encode($row->nome_arquivo);
                $certificate[$row->ID]['nome_cliente']    = utf8_encode($row->nome_cliente);
            }
        }

        return $certificate;
    }

    public function getCertificados(){
        return $this->certificate = $this->Carregar_Certificados();
    }
    
    /** 
    * @param ID $id do Certificado para o download
    */
    public function Download_Certificate($id){
        if(!is_numeric($id)) return FALSE;

        $sql = "SELECT
                    ID
                    ,coalesce(arquivo, '') AS arquivo
                    ,data_inclusao
                    ,coalesce(nome_arquivo, '') AS nome_arquivo
                    ,coalesce(pasta, '') AS pasta
                    ,coalesce(tamanho_documento, 0) AS tamanho_documento
                FROM
                    apswp_arquivos
                WHERE
                    ID = ?
        ";

        $sth = $this->conn->prepare($sql);
        $sth->bind_param("i", $id);
        $sth->execute();
        $result = $sth->get_result();

        if ($result->num_rows > 0) {
            while($row = $result->fetch_object()){
                $arquivo_nome = "{$row->nome_arquivo}";
                $pasta = $row->pasta;
                $tamanho = $row->tamanho_documento;
                
                $filename = (__DIR__) . "\\..\\upload\\" . basename($arquivo_nome);
                
                if (file_exists($filename)){
                    header('Content-Type: application/octet-stream'); 
                    header('Content-Disposition: attachment; filename=' . $arquivo_nome); 
                    header('Content-Transfer-Encoding: binary'); 
                    header('Expires: 0'); 
                    header('Cache-Control: must-revalidate, post-check=0, pre-check=0'); 
                    header('Pragma: public'); 
                    header('Content-Length: ' . filesize($filename)); 
                    ob_clean();
                    flush();
                    readfile($filename);
                    exit();
                }
                
            }
        }
    }

    // Download do Certificado
    public function submit(){
        if(isset($_GET['download'])){
            $id = filter_input(INPUT_GET, "download", FILTER_DEFAULT);
            $this->Download_Certificate($id);
        }
    }

}

$download = new Download();
$certificate = $download->getCertificados();

?>