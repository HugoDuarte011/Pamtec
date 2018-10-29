<h2>Tipo de Registro</h2>
<p>Selecione o tipo de registro que será visualizado: </p>

<div class="tab">
   <button class="tablinks" onclick="tipoRegistro(event, 'Clientes')">Clientes</button>
   <button class="tablinks" onclick="tipoRegistro(event, 'Certificados')">Certificados</button>
</div>

<div id="Clientes" class="tabcontent">
   <div class="w3-container">
      <h2>Lista de Usuários para edição</h2>
      <div class="btn-toolbar">
         <button class="btn btn-primary">
            <a href='Cadastrar_Clientes'>
               Novo Usuário
            </a>
         </button>
      </div>
      <table class="w3-table w3-striped w3-border">
            <thead>
                  <tr>
                        <th>Nome</th>
                        <th>CNPJ</th>
                        <th>Email</th>
                  </tr>
            </thead>
            <tbody>
                  <?php
                        if(!isset($user) || $user == ''){
                              echo "
                                    <tr>
                                          <td colspan='5'>Nenhum Cliente Cadastrado</td>
                                    </tr>
                              ";
                        } else {
                              foreach($user as $campo => $value){
                                    echo "
                                          <tr>
                                                <td>{$value['nome']}</td>
                                                <td>{$value['cnpj']}</td>
                                                <td>{$value['email']}</td>
                                                <td>
                                                      
                                                      <a href='Cadastrar_Cliente?ecod={$campo}'>
                                                            <i class='fa fa-pencil' aria-hidden='true'></i>
                                                      </a>
                                                      <a href='Cadastrar_Cliente?dcod={$campo}' role='button' data-toggle='modal'>
                                                            <i class='fa fa-trash' aria-hidden='true'></i>
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
      <table class="w3-table w3-striped w3-border">
         <div class="btn-toolbar">
            <button class="btn btn-primary">
                  <a href='Postar_Certificado'>Novo Certificado</a>
            </button>
         </div>
            <thead>
                  <tr>
                        <th>Nome</th>
                        <th>Destinatário</th>
                        <th>Data de Upload</th>
                  </tr>
            <thead>
            <tbody>
            <?php
                        if(!isset($certificate) || $certificate == ''){
                              echo "
                                    <tr>
                                          <td colspan='5'>Nenhum Certificado cadastrado</td>
                                    </tr>
                              ";
                        } else {
                              foreach($certificate as $campo => $value){
                                    echo "
                                          <tr>
                                                <td>{$value['nome_arquivo']}</td>
                                                <td>{$value['nome_cliente']}</td>
                                                <td>{$value['data_inclusao']}</td>
                                                <td>
                                                      <a href='Postar_Certificado'>Alterar</a>
                                                      <a href='#myModal' role='button' data-toggle='modal'>Remover</a>
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
</script>

