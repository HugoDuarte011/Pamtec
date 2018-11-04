<div class="col-xs-12">
	<nav class="navbar navbar-expand-sm bg-dark navbar-dark sticky-top">
		<div class="navbar-collapse collapse w-100 order-1 dual-collapse2">
			<ul class="navbar-nav mr-auto">
				<li class="nav-home-logo">
					<a class="navbar-brand" href="home">
						<img src="IMG/Logo-SF-Nome-Branco.png" alt="" itemprop="logo" width="100%" height="100%">
					</a>
				</li>
			</ul>
			<ul id="menu" class="navbar-nav clearfix">
				<li class="nav-item">
					<a class="nav-link" href='home'>Início</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href='calibracao'>Calibração</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href='contato'>Contato</a>
				</li>
				<li class="nav-item">
				<?php
						$menu_session = '';
						$href = '';
						$drop = '';
						$option = 0;
						
						// Contrução do Menu para o Admin / Cliente
						if(isset($_SESSION['user_id']) && isset($_SESSION['user_permission'])) {

							if($_SESSION['user_permission'] === 1){
								$menu_session = 'Admin';
								$href = 'Admin_Page';
								$option = 1;
							} else {
								$menu_session = 'Certificados';
								$href = 'Lista_Arquivos_Download';
								$option = 2;
							}
						}
						
						switch($option) {
							case 1;
								$drop = '<ul>
											<li class="nav-item"><a href="Admin_Page">Configurações</a></li>
											<li class="nav-item"><a href="Sair">Sair</a></li>
											<li class="nav-item"><a href="#"></a></li>
										</ul>
										';
								break;
							case 2;
								$drop = '<ul>
											<li class="nav-item"><a href="Lista_Arquivos_Download">Certificados</a></li>
											<li class="nav-item"><a href="Sair">Sair</a></li>
											<li class="nav-item"><a href="#"></a></li>
										</ul>
										';
								break;
							default:
								$menu_session = 'Login';
								$href = 'login';
								break;
						}

						echo '<a class="nav-link" href='.$href.'>'.$menu_session.'</a>';
						echo $drop;
					?>
				</li>
			</ul>
		</div>

	</nav>
</div>