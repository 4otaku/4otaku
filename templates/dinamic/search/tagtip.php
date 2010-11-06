<table class="mini-shell searchbox">
	<tr class="hidden">
		<td>
			<a href="#" class="tag-tip-0" rel="<?urldecode($get['data']);?>">
				&nbsp;
			</a>
		</td>
	</tr>
	<?
		foreach ($data as $variant) {
			?>
				<tr>
					<td>
						<a href="#" class="tag-tip tag-tip-<?=(++$i);?>" rel="<?=$variant['alias'];?>">
							<?=(mb_strlen($variant['name']) < 31 ? $variant['name'] : mb_substr($variant['name'],0,27).'...');?>
						</a>
					</td>
				</tr>
			<?
		}
	?>			
</table>
