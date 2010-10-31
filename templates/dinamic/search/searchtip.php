<table class="mini-shell searchbox">
	<tr class="hidden">
		<td>
			<a href="#" class="search-tip-0" rel="<?urldecode($get['data']);?>">
				&nbsp;
			</a>
		</td>
	</tr>
	<?
		foreach ($data as $variant) {
			?>
				<tr>
					<td>
						<a href="#" class="search-tip search-tip-<?=(++$i);?> tip-type-<?=$variant['type'];?>" rel="<?=$variant['alias'];?>">
							<?=(mb_strlen($variant['query']) < 31 || $get['index'] ? $variant['query'] : mb_substr($variant['query'],0,27).'...');?>
						</a>
					</td>
				</tr>
			<?
		}
	?>			
</table>
