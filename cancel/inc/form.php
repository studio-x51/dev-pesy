<form action="" method="post">
	<fieldset>
		<p>
			<label>Jméno <sup>*</sup></label><br />
      <input type="text" class="required" name="cancel_firstname" value="<?php echo $frm->keepValueRet($_POST['cancel_firstname'])?>" />
			<small>Prosíme, vyplňte toto povinné pole..</small>
		</p>
		<p>
			<label>Přijmení <sup>*</sup></label><br />
			<input type="text" class="required" name="cancel_lastname" value="<?php echo $frm->keepValueRet($_POST['cancel_lastname'])?>" />
			<small>Prosíme, vyplňte toto povinné pole..</small>
		</p>
		<p>
			<label>E-mail <sup>*</sup></label><br />
			<input type="email" class="required email" name="cancel_email" value="<?php echo $frm->keepValueRet($_POST['cancel_email'])?>" />
			<small>Prosíme, vyplňte toto povinné pole..</small>
		</p>
		<h3>Napište nám, proč rušíte své členství. Vyberte odpověď z následující nabídky.</h3>
		<p>
      <?php if(is_array($answer_arr)&&(count($answer_arr)>0)) {?>
      <select name="cancel_answer">
        <?php 
          foreach($answer_arr as $keya => $a) {
            $sel_a = ($_POST['cancel_answer'] == intval($keya)) ? 'selected="selected"' : '';
        ?>
          <option <?php echo $sel_a;?> value="<?php echo intval($keya)?>"><?php echo strval($a)?></option>
        <?php }?>
			</select>
      <?php }?>
		</p>
		<p>
      <textarea cols="60" rows="6" name="cancel_notice" placeholder="Prostor pro doplňující informace.."><?php echo $frm->keepValueRet($_POST['cancel_notice'])?></textarea>
		</p>
		<p class="tac">
      <button type="submit" class="btn" name="cancel_send">Zrušit členství</button>
		</p>
	</fieldset>
</form>
