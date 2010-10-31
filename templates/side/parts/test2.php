	<div class="cats">	
		Категория: <select class="right"> 
		<option value="none">Не выбрано</option>
<? 
	foreach ($data['sidebar']['test2']['cats'] as $cat) {
		?>
			<option value="<?=$cat['alias'];?>"><?=$cat['name'];?></option>

		<?		
	}
?>
  </select>
  <br /><br />
		Язык: <select class="right"> 
		<option value="none">Не выбрано</option>		
<? 
	foreach ($data['sidebar']['test2']['lang'] as $lng) {
		?>
			<option value="<?=$lng['alias'];?>"><?=$lng['name'];?></option>

		<?		
	}
?>
  </select>
  <br /><br />
 		Тег: <select class="right"> 
 		<option value="none">Не выбрано</option>		
<? 
	foreach ($data['sidebar']['test2']['tags'] as $tag) {
		?>
			<option value="<?=$tag['alias'];?>"><?=$tag['name'];?></option>

		<?		
	}
?>
  </select>
  <br /> <br />
  <div class="center clear"><input type="button" value="Искать"></div>
</div>
<br />
