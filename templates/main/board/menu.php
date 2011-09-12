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
				<a href="<?=$def['site']['dir']?>/board/<?=$one_board['alias'];?>/">
					<?=$one_board['name'];?>
				</a>
			<?
		}
	?>
	]
</div>
