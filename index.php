<?php
    // Carregamento do banco de dados
    include('pt-db.php');

    // Requisição da URL
    $REQUEST_URI = filter_input(INPUT_SERVER, 'REQUEST_URI');

    $INITE = strpos($REQUEST_URI, '?');
    if($INITE) {
        $REQUEST_URI = substr($REQUEST_URI, 0, $INITE);
    }
    $REQUEST_URI_FOLDER = substr($REQUEST_URI, 1);

    $URL = explode('/', $REQUEST_URI_FOLDER);
    $URL[0] = ($URL[0] != '' ? $URL[0] : 'home');

    switch($URL[0]){
        case 'home';
            $title = "Início";
            break;

        case 'calibracao';
            $title = "Calibração";
            break;
        
        case 'contato';
            $title = "Contato";
            break;

        case 'login';
            $title = 'Login';
            break;
    }

    $menu = $title;

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>
        <?php 
            echo $title;
        ?>
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/png" href="IMG/favicon.png"/>

    <!-- PAMTEC CSS -->
    <link rel="stylesheet" type="text/css" media="screen" href="css/style.css" />
</head>
<body>
    <?php
        // Incluindo o cabeçalho da página
        include(ROUTER . 'header.php');
    ?>

    <div class="col-xs-12">
        <div class="col-sm-2 text-center">
            <h2>
                <?php 
                    echo $menu;
                ?>
            </h2>
        </div>
        <div class="col-sm-10">
        </div>
    </div>

    <div class="container">
        
        <?php
            // Estrutura para carregar as páginas dinamicamente
            include('ROUTER.PHP');
        ?>
        
    </div>

    <?php
        // Incluindo o cabeçalho da página
        include(ROUTER . 'footer.html');
    ?>

    <!-- Scripts -->
    <script src="main.js"></script>

    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
</body>
</html>