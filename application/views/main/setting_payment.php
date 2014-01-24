<form method='post' action='/pay/setSettings/' ?>
			
                            <?php print_r($setting); ?>
                                   
                                <?php foreach($setting as $key=>$value){?>
                                    <div class="block">
                                            <div class="caption">Логин<span class='warning hidden identity'></span></div>
                                            <input type='text' value='<?=$value?>' name='<?=$key?>' class = 'field'/>
                                                    <!--<input class="field"  type="text" name="identity">-->
				<?php }?>	
					<div id="submit_wrap"><input type="submit" id="send" value="Войти"></div>
				</div>
<!-- 				<div id="send"><span id="ololo">Войти</span>

				</div> -->
				
			</div>
		</form>