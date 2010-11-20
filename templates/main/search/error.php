<table width="100%" id="error">
	<tr>
		<td>
			<? if ($url[2] != 'a') { ?>
				<img src="<?=SITE_DIR.'/images'?>/search.gif">
			<? } else { ?>
				<img src="<?=SITE_DIR.'/images'?>/booru_404_<?=rand(1,2);?>.jpg">
			<? } ?>
		</td>
	</tr>			
</table>
