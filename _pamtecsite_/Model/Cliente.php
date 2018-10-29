<?php 

    class Cliente {
        private $display_name;
        private $user_cnpj;
        private $user_email;
        private $user_login;
        private $user_nicename;
        private $user_pass;
        private $user_permission;

        // Contrutor da classe
        public function __construct($display_name,$user_cnpj,$user_email,$user_login,$user_nicename,$user_pass,$user_permission ){
            $this->display_name = $display_name;
            $this->user_cnpj = $user_cnpj;
            $this->user_email = $user_email;
            $this->user_login = $user_login;
            $this->user_nicename = $user_nicename;
            $this->user_pass = $user_pass;
            $this->user_permission = $user_permission;
        }

        
    } 

?>