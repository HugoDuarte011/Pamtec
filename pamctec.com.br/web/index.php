<?php
    include_once("head.php");
?>

<div class="grid-body" style="padding: 10px 10px;">
    <div class="container">
        
        <?php
            // Estrutura para carregar as páginas dinamicamente
            include(ROUTER . "home.php");
        ?>
        
    </div>
</div>

<?php 
    include_once("footer.php");
?>