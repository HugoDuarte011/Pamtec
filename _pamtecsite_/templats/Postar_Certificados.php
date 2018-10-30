<p><input class="PostFile" name="Certificado" id="updArquivo" type="file" style="width: 100%"></p>
<p>
   <select class="DropDown" name="Destinatario" style="width: 75%; height: 20%; align: center;">
		<?php
			if(!isset($user) || $user == ''){
				  echo "
						
						<option value='1' selected='selected'>
							Todos
						</option>
				  ";
			} else {
				  foreach($user as $campo => $value){
						echo "
							<option value={$value['ID']} selected='selected'>
								{$value['nome']}
							</option>";
				  }
			}
		?>
   </select>
  </br>
   <input class="cmdButton" value='Postar Certificado' name='cmdPostar' type='submit' style="width: 75%; height: 20%; align: center; display: block;">
</p>