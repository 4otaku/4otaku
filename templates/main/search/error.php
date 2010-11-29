<table width="100%" class="error">
	<tr>
		<td>
			<? if ($url[2] != 'a' && is_array($data['main']['variants']) && !empty($data['main']['variants'])) { ?>
				Возможно вы искали: 
				<? foreach ($data['main']['variants'] as $key => $variant) { ?>
					<? if (!empty($key)) { ?>
						, 
					<? } ?>
					<a href="/search/<?=$url[2];?>/<?=$url[3];?>/<?=urlencode($variant);?>/">
						<?=$variant;?>
					</a>
				<? } ?>
				.
			<? } ?>
		</td>
	</tr>
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
