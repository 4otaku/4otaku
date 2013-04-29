<div class="center margin10" width="100%">
	Доски: [
	<?
		$first = 0;
		if (empty($board_list)) {
			$board_list = $data['main']['boards'];
		}

		foreach ($board_list as $one_board) {
			?>
				<?=($first++ ? ' / ' : '');?>
				<a href="<?=$def['site']['dir']?>/board/<? if ($url[2] == 'catalog') { ?>catalog/<? } ?><?=$one_board['alias'];?>/">
					<?=$one_board['name'];?>
				</a>
			<?
		}
	?>
	]
	<? if ($url[2] != 'catalog') { ?>
		 [<a href="/board/catalog/">Каталог</a>]
	<? } else { ?>
		 [<a href="/board/">Треды</a>]
	<? } ?>
</div>
