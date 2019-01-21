<?php
// Inicialização do Sistema
include_once('_pamtecsite_/controller/main.php');        
$main = new Main();
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
				$main->getHeader();
			?>
		</div>

		<div class="grid-body" style="padding: 10px 10px;">
			<div class="container">
				
				<?php
					// Estrutura para carregar as páginas dinamicamente
					$main->getPage();
				?>
				
			</div>
		</div>
		
		<div class="grid-footer">
			<?php
                // Incluindo o rodapé da página
				$main->getFooter();
			?>
		</div>
	</div>

    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>

    <!-- Scripts -->
    <script src="js/jquery.mask.js"></script>
    <script src="js/main.js"></script>
</body>
</html>