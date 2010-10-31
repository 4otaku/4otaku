<?
	$lang['button'] = array(
		$def['type'][0] => 'Записи',
		$def['type'][1] => 'Видео',
		$def['type'][2] => 'Арт'
	);
?>
<table width="100%">
<tr>
	<td>
		<div id="logo">
			<a href="/"><img src="/images/4otakulogos.gif" alt="4otaku" /></a>
		</div>
	</td>
	
	<td>
		<div id="top_buttons">
			<?
				if (is_array($data['header']['top_buttons'])) foreach ($data['header']['top_buttons'] as $button) {
					?>
						<a href="/<?=$button;?>/">
							<img src="/images/<?=$button;?>.png" alt="<?=$lang['button'][$button];?>" />
						</a>
					<?
				}
			?>
		</div>
	</td>
		
	<td>
		<div id="rss" align="center">
			<div class="right">		
				<a href="<?=($sets['rss']['default'] == $def['rss']['default'] ? '/go?http%3A%2F%2Ffeeds.feedburner.com%2F4otaku' : '/rss/='.$sets['rss']['default'].'/');?>" title="RSS записей">
					<img align="middle" src="/images/feed_80x80.png" alt="RSS записей" />
				</a>
			</div>		
			<div class="margin10 box first_box">
				<a href="http://wiki.4otaku.ru/%D0%9A%D0%B0%D1%82%D0%B5%D0%B3%D0%BE%D1%80%D0%B8%D1%8F:FAQ" title="Частые вопросы по сайту">
					Частые вопросы
				</a>
			</div>
			<div class="margin10 box">
				<a href="/ajax.php?m=box&f=rss&width=600&height=240" title="Выберите, что показывать вам в RSS" class="thickbox">
					Выберите свой RSS
				</a>
			</div>
			<div class="margin10 box">
				<a href="/ajax.php?m=box&f=settings&width=500&height=650" title="Ваши личные настройки" class="thickbox">
					Настройки
				</a>
			</div>
		</div>
	</td>
	</tr>
</table>
<div id="hline"></div>
