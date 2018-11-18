<?php

    class Download {
        private $user_id;
        private $conn;

        //construtor da classe
        public function __construct($user_id, $conn){
            $this->user_id = $user_id;
            $this->conn = $conn;
        }

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


        /** 
        * @param ID $certificate_id do Certificado para carregar os dados do Certificado
        *  Carrega a lista dos Certificados
        */
        public function Carregar_Certificados($id){
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
                    WHERE
                        arq.fk_cliente = ?
            ";

            $sth = $this->conn->prepare($sql);
            $sth->bind_param("i", $id);
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

        public function hex2bin($str) {
            $r='';

            for ($a=0; $a<strlen($h); $a+=2) {
                $r.=chr(hexdec($h{$a}.$h{($a+1)})); 
            }
            
            return $bin;
        }
        
        /** 
        * @param ID $id do Certificado para o download
        */
        public function Download_Certificate($id){
            if(!is_numeric($id)) return false;

            $sql = "SELECT
                        arq.ID
                        ,coalesce(arq.arquivo, '') AS arquivo
                        ,arq.data_inclusao
                        ,coalesce(arq.nome_arquivo, '') AS nome_arquivo
                        ,coalesce(arq.tamanho_documento, 0) AS tamanho_documento
                    FROM
                        apswp_arquivos AS arq
                    WHERE
                        arq.ID = ?
            ";

            $sth = $this->conn->prepare($sql);
            $sth->bind_param("i", $id);
            $sth->execute();
            $result = $sth->get_result();

            if ($result->num_rows > 0) {
                while($row = $result->fetch_object()){
                    $nomeArquivo = utf8_encode($row->nome_arquivo);
                    $dados_documento =  $row->arquivo;

                    $file = fopen($nomeArquivo,"a+");
                    fwrite($file,hex2bin($dados_documento));
                    fclose($file);

                    //Forçando o download...
                    header("Content-type: application/pdf");
                    header("Content-Disposition: attachment; filename=" . $nomeArquivo);
                    header("Content-Length: " . $row->tamanho_documento);
                    header("Content-Transfer-Encoding: binary");
                    readfile($nomeArquivo);

                    //Apagando o arquivo
                    unlink($nomeArquivo); 
                }
            }

        } 

    }

    // Verifica se tem acesso na página
    if(isset($_SESSION['user_id']) && isset($_SESSION['user_permission'])){
        $user_id = $_SESSION['user_id'];

        $conn = connection_MySql();
        $download = new Download($user_id, $conn);

        if(!$download->Restricao()){
            header("Location: home");
        }
        
        // Download do Certificado
        if(isset($_GET['download'])){
            $id = $_GET['download'];
            $download = $download->Download_Certificate($id);
        }

        // Lista dos certificados
        $certificate = $download->Carregar_Certificados($user_id); 

        // Página do Admin
        include(ROUTER . 'Download.php');

        // Fecha a conexão
        $conn->close();
    } else {
        header("Location: home");
    }


?>