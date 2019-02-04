<?php
    include_once("../header.php");
    include("../pamtec/main/Download-files.php");
?>

<div class="grid-body" style="padding: 10px 10px;">
    <div class="container">
        
        <h1 style="text-align: center; padding: 5px 5px; margin: 5px 5px;">Certificados</h1>

        <div class="well">
            <table class="table">
                <thead>
                    <tr>
                        <th>Arquivo</th>
                        <th>Data Upload</th>
                        <th>Download</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        if(!isset($certificate) || $certificate == ''){
                            echo "
                                <tr>
                                    <td colspan='3'>Nenhum Certificado cadastrado</td>
                                </tr>
                            ";
                        } else {
                            foreach($certificate as $campo => $value){
                                echo "
                                    <tr>
                                            <td>{$value['nome_arquivo']}</td>
                                            <td>{$value['data_inclusao']}</td>
                                            <td>
                                                <a href='index.php?download={$campo}'>
                                                    <img src='../img/download.png' />
                                                </a>
                                            </td>
                                    </tr>";
                                
                            }
                        }
                    ?>
                </tbody>
            </table>
        </div>
        
    </div>
</div>

<?php 
    include_once("../footer.php");
?>