<?php
    // Carregamento do banco de dados
    include('pt-db.php');

    session_start();
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
    <link rel="shortcut icon" type="image/png" href="IMG/FAVICON - Preto Fundo Branco.png"/>

    <!-- PAMTEC CSS -->
    <link rel="stylesheet" type="text/css" media="screen" href="css/style.css" />
    
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
</head>
<body>
	<div class="grid-container">
		
		<div class="grid-header">
			<?php
				// Incluindo o cabeçalho da página
                include('header.php');
			?>
		</div>