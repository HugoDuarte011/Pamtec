<?php
// Classe para incluir todos os arquivos do Site

Class Main {

    private $router;
    private $caminho;
    private $pamtec = "_pamtecsite_";

    // Carregamento do ínicio do Sistema
    public function __construct(){

        // Carregamento do banco de dados
        include('pt-db.php');

        session_start();

        // Roteamento
        include('_pamtecsite_/controller/router.php');

        // Controller
        include('_pamtecsite_/controller/Home.php');
        include('_pamtecsite_/controller/Contato.php');
        include('_pamtecsite_/controller/Calibracao.php');
        include('_pamtecsite_/controller/Login.php');
    }

    // Pega os arquivos
    public function getFile($arquive){
        
        if($arquive === ""){
            $arquive = "home";
        }

        $dirs = array_filter(glob('*'), 'is_dir');

        foreach($dirs as $value ) {
            if($value === $this->pamtec){
                $folders = new FileSystemIterator($value);
                
                foreach ($folders as $folder) {
                    $files = new FileSystemIterator($folder);

                    foreach ($files as $file) {
                        $template   = $file->getFilename();
                        $ini        = strpos($template, ".");
                        $template   = substr($template, 0, $ini);

                        if($arquive === $template){
                            $this->caminho = $file;
                            break;
                        }
                    }
                }
            }
        }

        return $this->caminho;
    }

    // Executa as ações do Site
    public function acoes(){
        $this->router = new router();

        // Caminhos do Site 
        $this->router->new_route('/', 'Home', "home");
        $this->router->new_route('/home', 'Home', "home");
        $this->router->new_route('/contato', 'Contato', "contato");
        $this->router->new_route('/calibracao', 'Calibracao', "calibracao");
        $this->router->new_route('/login', 'Login', "login");

        //$this->router->map();
        $class =  $this->router->rotear($this);

        if(isset($class)){
            $class->router();
        }
    }

    // Retorna o Menu
    public function getHeader(){
        $header = $this->getFile("header");
        if(isset($header)) include($header);
    }

    // Retorna as páginas
    public function getPage(){
        $this->acoes();
    }

    // Retorna o Menu
    public function getFooter(){
        $footer = $this->getFile("footer");
        if(isset($footer)) include($footer);
    }

}

?>