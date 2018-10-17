<h1 class="form-heading">login Form</h1>
<div class="login-form">
	<div class="main-div">
		<div class="panel">
			<h2>Login</h2>
		</div>

		<?php 
			if(isset($msg)){
				echo $msg;
			}
		?> 

		<form id="Login" action="" method="post">
			<div class="form-group">
				<input type="text" class="form-control" id="txtEmail" name="txtEmail" placeholder="Email">
			</div>
			<div class="form-group">
				<input type="password" class="form-control" id="txtSenha" name="txtSenha" placeholder="Senha">
			</div>
					
			<button type="submit" class="btn btn-primary" name="btnLogar">Logar</button>
		</form>
	</div>
</div>