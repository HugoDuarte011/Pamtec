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
			<button class="btn btn-primary">Novo Usuário</button>
		</div>
		<table class="w3-table w3-striped w3-border">
		<tr>
			<th>Nome</th>
			<th>CNPJ</th>
			<th>Email</th>
		</tr>
		<?php 
			$sql = mysqli_query($connection, "SELECT * FROM usuers");
			while ($row = $sql->fetch_assoc()){
		?>
		<tr>
			<td>name</td>
			<td>cnpj</td>
			<td>email</td>
			<td>
				<a href="user.html"><i class="icon-pencil"></i></a>
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
		<button class="btn btn-primary">Novo Usuário</button>
	</div>
	<tr>
		<th>Nome</th>
		<th>Destinatário</th>
		<th>Data de Upload</th>
	</tr>
	<?php 
		$sql = mysqli_query($connection, "SELECT * FROM wp_files");
		while ($row = $sql->fetch_assoc()){
	?>
	<tr>
		<td>filename</td>
		<td>destination</td>
		<td>uploaddate</td>
		<td>
			<a href="user.html"><i class="icon-pencil"></i></a>
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