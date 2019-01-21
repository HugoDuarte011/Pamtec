<?php 

Class Calibracao { 

    private $param;
    private $router;
    private $template;

    public function __construct($param, $router){
        $this->param = $param;
        $this->router = $router;
    }

    public function router(){
        $this->template =  $this->router->getFile($this->param);
        include($this->template);
    }
}

?>