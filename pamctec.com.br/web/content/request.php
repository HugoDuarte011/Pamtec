<?php
// Estrutura para carregar a estrutura dinamicamente

//$DIR = (__DIR__);
//$folders = (explode('\\', $DIR));
$DIR = $REQUEST_URI = filter_input(INPUT_SERVER, 'REQUEST_URI');
$folders = (explode('/', $DIR));

// Vairicaveis default
$css        = "css/style.css";
$favicon    = "IMG/FAVICON - Preto Fundo Branco.png";
$logo       = "IMG/Logo-SF-Nome-Branco.png";
$mask       = "js/jquery.mask.js";
$script     = "js/main.js";

$web        = false;

$count      = 0;
$href       = '';
$admin      = '';
$caption    = '';
$url        = '';
$drop       = '';
$option     = 0;

// Menu
$index      = 'index.php';
$contato    = 'contato.php';
$calibr     = 'calibracao.php';
$drop       = "";

// Loop para saber quando não é a pasta WEB
// Se a requisição não for uma pasta WEB, aplicar nessa pasta, se não, da pasta WEB
foreach($folders as $folder) {
    if($folder <> '' && !strpos($folder, ".php")){
        if($folder === 'web') {
            $web = true;    
        }
        // Se existe uma pasta web, verifica quantas páginas foi chamado
        if($web){
            $count++;
        }   
    }
}

// Tira o que não é da WEB
$count--;

if($count >= 1){
    for($i = 0; $i < $count; $i++){
        $href = $href . "../"; 
    }

    $css = $href . $css;
    $favicon = $href . $favicon;
    $logo = $href . $logo;

    // menu
    $index = $href;
    $calibr = $href . $calibr;
    $contato = $href . $contato;

    // Footer
    $mask = $href . $mask;
    $script = $href . $script;
}

// Contrução do Menu para o Admin / Cliente
if(isset($_SESSION['user_id']) && isset($_SESSION['user_permission'])) {

    if($_SESSION['user_permission'] === 1){
        $caption = 'Administrador';
        $url = $href . 'Admin/';
        $option = 1;
    } else {
        $caption = 'Certificados';
        $url = 'Download';
        $option = 2;
    }
}

switch($option) {
    case 1;
        $drop = '<ul>
                    <li class="nav-item"><a href="'. $href . 'Admin/">Painel</a></li>
                    <li class="nav-item"><a href="'. $href . 'Admin/index.php?sair=true">Sair</a></li>
                    <li class="nav-item"><a href="#"></a></li>
                </ul>
                ';
        break;
    case 2;
        $drop = '<ul>
                    <li class="nav-item"><a href="Download">Certificados</a></li>
                    <li class="nav-item"><a href="Sair">Sair</a></li>
                    <li class="nav-item"><a href="#"></a></li>
                </ul>
                ';
        break;
    default:
        $caption = 'Entrar';
        $url = $href . 'Autenticacao/';
        break;
}

?>