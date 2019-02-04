<?php
    // Carregamento do banco de dados
    session_start();

    require('content/config.php');
    require('content/request.php');
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>
        <?php  
            if(isset($title)){
                echo $title;
            } else {
                echo "Pamtec";
            }
        ?>
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/png" href="<?php echo $favicon; ?>"/>

    <!-- PAMTEC CSS -->
    <link rel="stylesheet" type="text/css" media="screen" href="<?php echo $css; ?>" />
    
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
</head>
<body>
	<div class="grid-container">
		
		<div class="grid-header">
            <div class="col-xs-12">
                <nav class="navbar navbar-expand-sm bg-dark navbar-dark sticky-top">
                    <div class="navbar-collapse collapse w-100 order-1 dual-collapse2">
                        <ul class="navbar-nav mr-auto">
                            <li class="nav-home-logo">
                                <a class="navbar-brand" href="<?php echo $index; ?>">
                                    <img src="<?php echo $logo; ?>" alt="" itemprop="logo" width="100%" height="100%">
                                </a>
                            </li>
                        </ul>
                        <ul id="menu" class="navbar-nav clearfix">
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo $index; ?>">Início</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo $calibr; ?>">Calibração</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo $contato; ?>">Contato</a>
                            </li>

                            <li class="nav-item">
                            <?php
                                if(isset($request) && $request === 1){
                                    echo 
                                        '<div class="dropdown">
                                            <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                '.$caption.'
                                            </a>';
                                            echo $drop;
                                        echo "</div>";
                                    ;
                                } else {
                                    echo '<a class="nav-link" href='.$url.'>'.$caption.'</a>';
                                }
                                ?>
                            </li>

                        </ul>
                    </div>

                </nav>
            </div>
		</div>