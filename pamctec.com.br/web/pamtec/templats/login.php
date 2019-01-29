<div class="login-form">
	<div class="main-div">
		<div class="panel">
			<h2>Entrar no sistema</h2>
		</div>

		<div style="color: #f00">
		<?php 
			if(isset($msg)) echo $msg;
		?> 
		</div>

		<form id="Login" action="" method="post">
			<div class="form-group">
				<label for="email">Email ou usuário</label>
				<input type="text" class="form-control" id="email" name="email" placeholder="Email">
			</div>
			<div class="form-group">
				<label for="senha">Senha</label>
				<input type="password" class="form-control" id="senha" name="senha" placeholder="Senha">
			</div>
			
			<button type="submit" class="btn btn-primary" name="btnLogar">Entrar</button>
		</form>
	</div>
</div>