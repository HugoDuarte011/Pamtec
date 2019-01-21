<?php

    Class router {

        private $_rotas = array();
        private $_acoes = array();
        private $_param = array();
        private $class;

        public function new_route($uri, $action, $param){
            $this->_rotas[] = trim($uri, '/');
            $this->_acoes[] = $action;
            $this->_param[] = $param;
        }

        public function map(){
            echo '<pre>';
            print_r($this->_rotas);
            print_r($this->_acoes);
        }

        public function rotear($router){
            // Requisição da URL
            $uri = filter_input(INPUT_SERVER, 'REQUEST_URI');
            
            $REQUEST_URI_FOLDER = substr($uri, 1);

            $chave = array_search($REQUEST_URI_FOLDER, $this->_rotas);
            
            if($chave === false)
                echo "não achou";
            else {
                @$this->class = new $this->_acoes[$chave]($this->_param[$chave], $router);
            }

            return $this->class;
        }

    }

?>