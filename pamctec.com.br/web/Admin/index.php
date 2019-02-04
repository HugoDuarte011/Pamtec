<?php
    include_once("../header.php");
    include("Administrador.php");
?>

<div class="grid-body" style="padding: 10px 10px;">
    <div class="container">
        
    <h2>Tipo de Registro</h2>
        <p>Selecione o tipo de registro que será visualizado: </p>

        <div class="tab">
        <button id="tabCliente" class="tablinks" onclick="tipoRegistro(event, 'Clientes')">Clientes</button>
        <button class="tablinks" onclick="tipoRegistro(event, 'Certificados')">Certificados</button>
        </div>

        <div id="Clientes" class="tabcontent">
        <div class="w3-container">
            <h2>Lista de Usuários para edição</h2>
            <div class="btn-toolbar">
                    <a href='Cadastrar_Clientes.php?user=0'>
                    <button class="btn btn-primary">Novo Usuário</button>
                </a>
            </div>
            <br />
            <table class="w3-table w3-striped w3-border">
                    <thead>
                        <tr>
                                <th>ID</th>
                                <th>Razão Social</th>
                                <th>Fantasia</th>
                                <th>CNPJ</th>
                                <th>Email</th>
                                <th>Editar</th>
                                <th>Deletar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if(!isset($user) || $user == ''){
                                echo "
                                    <tr>
                                        <td colspan='7'>Nenhum Cliente Cadastrado</td>
                                    </tr>
                                ";
                            } else {
                                foreach($user as $campo => $value){
                                    echo "
                                        <tr>
                                            <td>{$campo}</td>
                                            <td>{$value['nome']}</td>
                                            <td>{$value['fantasia']}</td>
                                            <td>{$value['cnpj']}</td>
                                            <td>{$value['email']}</td>
                                            <td>
                                                <a href='Cadastrar_Clientes.php?user={$campo}'>
                                                    <img src='../img/pen01.png' />
                                                </a>
                                            </td>
                                            <td>
                                                <a href='Cadastrar_Clientes.php?user_del={$campo}'>
                                                    <img src='../img/trash01.png' />
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

        <div id="Certificados" class="tabcontent">
        <div class="w3-container">
                <h2>Lista de certificados para edição</h2>
                <div class="btn-toolbar">
                    <a href='Cadastrar_Certificado.php?certificate=0'>
                        <button class="btn btn-primary">Novo Certificado</button>
                    </a>
                </div>
                <br/>
                <table class="w3-table w3-striped w3-border">
                    <thead>
                        <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Destinatário</th>
                                <th>Data de Upload</th>
                                <th>Editar</th>
                                <th>Deletar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if(!isset($certificate) || $certificate == ''){
                                echo "
                                    <tr>
                                        <td colspan='6'>Nenhum Certificado cadastrado</td>
                                    </tr>
                                ";
                            } else {
                                foreach($certificate as $campo => $value){
                                    echo "
                                        <tr>
                                            <td>{$campo}</td>
                                            <td>{$value['nome_arquivo']}</td>
                                            <td>{$value['nome_cliente']}</td>
                                            <td>{$value['data_inclusao']}</td>
                                            <td>
                                                <a href='Cadastrar_Certificado.php?certificate={$campo}'>
                                                    <img src='../img/pen01.png' />
                                                </a>
                                            </td>
                                            <td>
                                                <a href='Cadastrar_Certificado.php?delete_certificate={$campo}'>
                                                    <img src='../img/trash01.png' />
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

        <script>
        function tipoRegistro(evt, tipoReg) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }
            document.getElementById(tipoReg).style.display = "block";
            evt.currentTarget.className += " active";
        }
                
        document.getElementById("tabCliente").click();
        </script>

    </div>
</div>

<?php 
    include_once("../footer.php");
?>