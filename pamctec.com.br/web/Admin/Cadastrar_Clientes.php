<?php
    include_once("../header.php");
    include("Administrador.php");
?>

<div class="grid-body" style="padding: 10px 10px;">
    <div class="container">
		<h1 class="entry-title"><?php echo $user["titulo"]; ?></h1>

		<div class="validacao_cliente" style="color: #f00;">
			<?php
				echo $user["validacao"];
			?>
		</div>

		<form action="" method="post">
			<div class="form-group">
				<label for="razao">Razão Social</label>
				<input type="text" id="razao" name="razao" class="form-control" value="<?php echo $user["razao"]; ?>" placeholder="Digite a Razão Social" required />
			</div>

			<div class="form-group">
				<label for="fantasia">Fantasia</label>
				<input type="text" id="fantasia" name="fantasia" class="form-control" value="<?php echo $user["fantasia"]; ?>" placeholder="Digite a Fantasia" required />
			</div>

			<div class="form-group">
				<label for="email">Email</label>
				<input type="text" id="email" name="email" class="form-control" value="<?php echo $user["email"]; ?>" placeholder="Digite E-mail" required />
			</div>

			<div class="form-group">
				<label for="cnpj">CNPJ</label>
				<input type="text" id="cnpj" name="cnpj" class="form-control" value="<?php echo $user["cnpj"]; ?>" placeholder="Digite o CNPJ" />
			</div>

			<div class="form-group">
				<label for="login">Login</label>
				<input type="text" id="login" name="login" class="form-control" value="<?php echo $user["login"]; ?>" placeholder="Digite Login" required />
			</div>
			
			<div class="form-group">
				<label for="senha">Senha</label>
				<input type="password" id="senha" name="senha" class="form-control" value="<?php echo $user["senha"]; ?>" placeholder="Digite a Senha" required />
			</div>

			<?php
				if($user["acao"] == "A") {
			?>
				<div class="form-group">
					<label for="confirmaSenha">Confirma Senha</label>
					<input type="password" id="confirmaSenha" name="confirmaSenha" class="form-control" placeholder="Confirma a senha" required />
				</div>
			<?php
				}
			?>

			<button type="submit" name="btnCliente" class="btn btn-primary"><?php echo $user["botao"]; ?></button>
			<button type="submit" name="btnCancelarAdmin" class="btn btn-primary" formnovalidate>Cancelar</button>
		</form>
	</div>
</div>

<?php 
    include_once("../footer.php");
?>