<h1 class="entry-title"><?php echo $titleCliente ?></h1>

<div class="validacao_cliente" style="color: #f00;">
	<?php
		echo $validacao_cliente;
	?>
</div>

<form action="" method="post">
	<div class="form-group">
		<label for="razao">Razão Social</label>
		<input type="text" id="razao" name="razao" class="form-control" value="<?php echo $razao; ?>" placeholder="Digite a Razão Social" required />
	</div>

	<div class="form-group">
		<label for="fantasia">Fantasia</label>
		<input type="text" id="fantasia" name="fantasia" class="form-control" value="<?php echo $fantasia; ?>" placeholder="Digite a Fantasia" required />
	</div>

	<div class="form-group">
		<label for="email">Email</label>
		<input type="text" id="email" name="email" class="form-control" value="<?php echo $email; ?>" placeholder="Digite E-mail" required />
	</div>

	<div class="form-group">
		<label for="cnpj">CNPJ</label>
		<input type="text" id="cnpj" name="cnpj" class="form-control" value="<?php echo $cnpj; ?>" placeholder="Digite o CNPJ" required />
	</div>

	<div class="form-group">
		<label for="login">Login</label>
		<input type="text" id="login" name="login" class="form-control" value="<?php echo $login; ?>" placeholder="Digite Login" required />
	</div>
	
	<div class="form-group">
		<label for="senha">Senha</label>
		<input type="password" id="senha" name="senha" class="form-control" value="<?php echo $senha; ?>" placeholder="Digite a Senha" required />
	</div>

	<?php
		if($acao == "A") {
	?>
		<div class="form-group">
			<label for="confirmaSenha">Confirma Senha</label>
			<input type="password" id="confirmaSenha" name="confirmaSenha" class="form-control" placeholder="Confirma a senha" required />
		</div>
	<?php
		}
	?>

	<button type="submit" name="btnCliente" class="btn btn-primary"><?php echo $btnSubmit ?></button>
	<button type="submit" name="btnCancelarCliente" class="btn btn-primary">Cancelar</button>
</form>