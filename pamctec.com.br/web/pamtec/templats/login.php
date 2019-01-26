<div class="login-form">
	<div class="main-div">
		<div class="panel">
			<h2>Login</h2>
		</div>

		<div style="color: #f00">
		<?php 
			if(isset($msg)) echo $msg;
		?> 
		</div>

		<form id="Login" action="" method="post">
			<div class="form-group">
				<input type="text" class="form-control" id="email" name="email" placeholder="Email">
			</div>
			<div class="form-group">
				<input type="password" class="form-control" id="senha" name="senha" placeholder="Senha">
			</div>
					
			<button type="submit" class="btn btn-primary" name="btnLogar">Logar</button>
		</form>
	</div>
</div>