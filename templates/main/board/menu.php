<div class="center margin10" width="100%">
	Доски: [
	<?
		$first = 0;
		if (empty($board_list)) {
			$board_list = $data['main']['boards'];	
		}
		
		foreach ($board_list as $alias => $name) {
			?>
				<?=($first++ ? ' / ' : '');?>
				<a href="<?=$def['site']['dir']?>/board/<?=$alias;?>/">
					<?=$name;?>
				</a>
			<?
		}
	?>
	]
</div>
