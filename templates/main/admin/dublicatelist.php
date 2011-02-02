<? 
	foreach ($data['main']['doubles'] as $double) {
		$double = explode('-', $double);
		?>
			<table width="100%" cellspacing="20">
				<tr>					
				<?
				foreach ($double as $one) {
					?>
						<td valign="top" align="center" width="50%">
							<a href="/art/<?=str_replace('main/','',$data['main']['arts'][$one]['area'].'/');?><?=$one;?>"
								class="with_help3"
								title="
									<?
										if (count($data['main']['arts'][$one]['meta']['tag']) > 1) {
											?>
												Теги: 
											<?
										}
										else {
											?>
												Тег: 
											<?
										}
										if (is_array($data['main']['arts'][$one]['meta']['tag'])) {
											foreach ($data['main']['arts'][$one]['meta']['tag'] as &$tag) $tag = $tag['name'];
											echo implode(', ',$data['main']['arts'][$one]['meta']['tag']);
										}
									?>								
								"
							>
								<img src="http://4otaku.ru/images/booru/thumbs/large_<?=$data['main']['arts'][$one]['thumb'];?>.jpg">
							</a>
							<br /><br />
							ТТХ: <?=$data['main']['arts'][$one]['width'];?>x<?=$data['main']['arts'][$one]['height'];?>px; <?=$data['main']['arts'][$one]['extension'];?>; <?=$data['main']['arts'][$one]['size'];?>;
							<br />
							Комментариев: <?=$data['main']['arts'][$one]['comment_count'];?>
						</td>
					<?
				}
				?>
				</tr>
			</table>
		<?		
	}
?>
