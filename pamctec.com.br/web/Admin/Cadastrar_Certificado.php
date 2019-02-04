<?php
    include_once("../header.php");
	include("Administrador.php");
?>

<div class="grid-body" style="padding: 10px 10px;">
    <div class="container col-md-8">
		<h1 style="text-align: center; padding: 5px 5px; margin: 5px 5px;"><?php echo $certificate["titulo"]; ?></h1>

		<div class="validacao_certificado" style="color: #f00;">
			<?php
				echo $certificate["validacao"];
			?>
		</div>

		<form action="" method="post" enctype="multipart/form-data">
			<label for="certificado">Arquivo</label>
			<input type="file" name="certificado" value=<?php echo $certificate["nomeArquivo"]; ?> accept="application/pdf" require>

			<br/>
			<br/>

			<label for="cliente">Cliente</label>
			<select name='cliente'>
				<?php 
					if ($certificate["cliente"] > 0){
						echo "<option value=".$certificate["cliente"].">".$certificate["nome"]."</option>";
					} else {
						echo "<option value='0'>Todos</option>";
					}
				?>
					<?php
						if(!isset($certificate["user"]) || $certificate["user"] == ''){
							echo "
								
							";
						} else {
							foreach($certificate["user"] as $campo => $value){
									echo "
										<option value={$campo}>{$value['fantasia']}</option>
									";
							}
						}
					?>
			</select>

			<br/>

			<button type="submit" class="btn btn-primary" style="width: 100% !important;" name="btnPostarCertificado">Postar Certificado</button>
		</form>
	</div>
</div>

<?php 
    include_once("../footer.php");
?>