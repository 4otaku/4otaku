<table width="100%" id="error">
	<tr>
		<td>
			<? if ($url[2] != 'a') { ?>
				<img src="/images/search.gif">
			<? } else { ?>
				<img src="/images/booru_404_<?=rand(1,2);?>.jpg">
			<? } ?>
		</td>
	</tr>			
</table>
