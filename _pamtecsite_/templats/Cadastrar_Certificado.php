<h1 style="text-align: center; padding: 5px 5px; margin: 5px 5px;">Cadastrar Certificado</h1>

<form action="" method="post">
	<input type="file" name="certificado" value="Arquivo" accept="application/pdf">

	<br/>

	<select>
		<option value="0">Todos</option>
			<?php
				if(!isset($user) || $user == ''){
					echo "
						
					";
				} else {
					foreach($user as $campo => $value){
							echo "
								<option value={$campo}>{$value['fantasia']}</option>
							";
					}
				}
			?>
	</select>

	<br/>

	<input type="submit" name="btnPostarCertificado" value="Postar Certificado">
</form>