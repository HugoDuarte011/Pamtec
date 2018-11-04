<div class="well">
	<table class="table">
		<thead>
			<tr>
				<th>Arquivo</th>
				<th>Data Upload</th>
				<th>Download</th>
			</tr>
		</thead>
		<tbody>
			<?php
				if(!isset($certificate) || $certificate == ''){
					echo "
						<tr>
							  <td colspan='6'>Nenhum Certificado cadastrado</td>
						</tr>
					";
				} else {
					foreach($certificate as $campo => $value){
						echo "
							  <tr>
									<td>{$value['nome_arquivo']}</td>
									<td>{$value['data_inclusao']}</td>
									<td>
										  <a href='#myModal' role='button' data-toggle='modal'>Remover</a>
									</td>
							  </tr>";
						
					}
				}
			?>
		</tbody>
    </table>
</div>