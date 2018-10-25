<h2>Tipo de Registro</h2>
<p>Selecione o tipo de registro que será visualizado: </p>
<div class="tab">
   <button class="tablinks" onclick="tipoRegistro(event, 'Clientes')">Clientes</button>
   <button class="tablinks" onclick="tipoRegistro(event, 'Certificados')">Certificados</button>
</div>
<div id="Clientes" class="tabcontent">
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.min.css" rel="stylesheet" id="bootstrap-css">
	<script src="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/js/bootstrap.min.js"></script>
	<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
	<div class="w3-container">
	  <h2>Lista de Usuários para edição</h2>
		<div class="btn-toolbar">
			<button class="btn btn-primary"> <a href='Cadastrar_Clientes'>Novo Usuário</button>
		</div>
	  <table class="w3-table w3-striped w3-border">
		<tr>
		  <th>Nome</th>
		  <th>CNPJ</th>
		  <th>Email</th>
		</tr>
			<?php 
				$sql = "SELECT * FROM aswp_users"
				$result = mysql_query($sql);
				while($row = mysql_fetch_array($result)) {
					$PK_ID = $row['ID']
			?>
			<tr>
			<td><?php echo $row['display_name'];?></td>
			<td><?php echo $row['user_cnpj'];?></td>
			<td><?php echo $row['user_email'];?></td>
			<td>
				<a href='Cadastrar_Cliente'$PK_ID><i class="icon-pencil"></i></a>
				<a href="#myModal" role="button" data-toggle="modal"><i class="icon-remove"></i></a>
			</td>
		</tr>
		<?php
			}
		?>
	  </table>
	</div>
</div>
<div id="Certificados" class="tabcontent">
   <div class="w3-container">
  <h2>Lista de certificados para edição</h2>
  <table class="w3-table w3-striped w3-border">
  <div class="btn-toolbar">
		<button class="btn btn-primary"><a href='Postar_Certificado'>Novo Usuário</button>
	</div>
	<tr>
	  <th>Nome</th>
	  <th>Destinatário</th>
	  <th>Data de Upload</th>
	</tr>
	<?php 
		$sql = "SELECT * FROM aswp_arquivos"
		$result = mysql_query($sql);
		while($row = mysql_fetch_array($result)) {
			$PK_ID = $row['ID']
	?>
	<tr>
		<td><?php echo $row['nome_arquivo'];?></td>
		<td><?php echo $row['fk_cliente'];?></td>
		<td><?php echo $row['uploaddate'];?></td>
		<td>
			<a href='Postar_Certificado'$PK_ID><i class="icon-pencil"></i></a>
			<a href="#myModal" role="button" data-toggle="modal"><i class="icon-remove"></i></a>
		</td>
	</tr>
	<?php
		}
	?>
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