<h1 style="text-align: center; padding: 5px 5px; margin: 5px 5px;"><?php echo $titleCertificate ?></h1>

<div class="validacao_certificado" style="color: #f00;">
	<?php
		echo $validacaoCertificado;
	?>
</div>

<form action="" method="post" enctype="multipart/form-data">
	<input type="file" name="certificado" value=<?php echo $nomeArquivo; ?> accept="application/pdf">

	<br/>

	<select name='cliente'>
		<?php 
			if ($cliente > 0){
				echo "<option value=".$cliente.">$nome</option>";
			} else {
				echo "<option value='0'>Todos</option>";
			}
		?>
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