<div id="login_form" style='float:left;width: 400px;'>
<form method='post' action='/pay/setSettings/' ?>


                                   
                                <?php foreach($settings as $key=>$value){?>
                                    <div class="block">
                                            <div class="caption"><?=$key?><span class='warning hidden identity'></span></div>
                                            <input type='text' value='<?=$value?>' name='<?=$key?>' class = 'field'/>
                                                    <!--<input class="field"  type="text" name="identity">-->
                                    </div>
				                <?php }?>
					<div id="submit_wrap"><input type="submit" id="send" value="Сохранить"></div>

<!-- 				<div id="send"><span id="ololo">Войти</span>

				</div> -->
				

		</form>
</div>