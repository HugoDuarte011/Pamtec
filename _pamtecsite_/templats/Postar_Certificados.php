<p><input class="PostFile" name="Certificado" id="updArquivo" type="file" style="width: 75%; margin-left: auto; margin-right: auto; display: block;"></p>
<p style="width:75%;">
	<div class="custom-select" style="width: 100%; ">
		<select style="width: 75%; display: block; margin-left: auto; margin-right: auto; font-size: 12pt;">
			<option value="0" style="font-size: 20pt;">Todos</option>
				<?php
					if(!isset($user) || $user == ''){
						  echo "
							
						  ";
					} else {
						  foreach($user as $campo => $value){
								echo "
									<option value={$value['ID']} style="font-size: 20pt;">{$value['nome']}</option>
								";
						  }
					}
				?>
		</select>
	</div>
	</br>
	<input class="cmdButton" value='Postar Certificado' name='cmdPostar' type='submit' style="width: 75%; margin-left: auto; margin-right: auto; display: block;">
</p>