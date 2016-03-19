
<form action="" method="post">
	<fieldset>
		<p>
			<label>Jméno <sup>*</sup></label><br>
			<input type="text" class="required">
			<small>Prosíme, vyplňte toto povinné pole..</small>
		</p>
		<p>
			<label>Přijmení <sup>*</sup></label><br>
			<input type="text" class="required">
			<small>Prosíme, vyplňte toto povinné pole..</small>
		</p>
		<p>
			<label>E-mail <sup>*</sup></label><br>
			<input type="email" class="required email">
			<small>Prosíme, vyplňte toto povinné pole..</small>
		</p>
		<h3>Napište nám, proč rušíte své členství. Vyberte odpověď z následující nabídky.</h3>
		<p>
			<select>
				<option value="Tuto službu nemám kde využít, není pro mě">Tuto službu nemám kde využít, není pro mě</option>
				<option value="Momentálně nemám čas se službou zabývat">Momentálně nemám čas se službou zabývat</option>
				<option value="Měsíční poplatek je vysoký">Měsíční poplatek je vysoký</option>
				<option value="Celkově mi služba nevyhovuje">Celkově mi služba nevyhovuje</option>
				<option value="Ani jedna z dostupných aplikací mi nevyhovuje">Ani jedna z dostupných aplikací mi nevyhovuje</option>
				<option value="Efektivita aplikací nesplnila moje očekávání">Efektivita aplikací nesplnila moje očekávání</option>
			</select>
		</p>
		<p>
			<textarea cols="60" rows="6" placeholder="Prostor pro doplňující informace.."></textarea>
		</p>
		<p class="tac">
			<button type="submit" class="btn">Zrušit členství</button>
		</p>
	</fieldset>
</form>