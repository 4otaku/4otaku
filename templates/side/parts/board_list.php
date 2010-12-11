<?
	if (is_array($data['sidebar']['board_list'])) {
		?>
			<div class="cats">
				<h2>
					<a href="/board/">
						Доски
					</a>
					 <a href="#" class="bar_arrow" rel="board_list">
						<?
							if ($sets['dir']['board_list']) {
								?>
									<img src="/images/text2391.png">
								<?
							}
							else {
								?>
									<img src="/images/text2387.png">
								<?
							}
						?>
					</a>
				</h2>
				<div id="board_list_bar"<?=($sets['dir']['board_list'] ? '' : ' style="display:none;"');?>>
					<ul class="plain_list">
						<?
							foreach ($data['sidebar']['board_list'] as $alias => $name) {
								?>
									<li>
										[<a href="/art/board/<?=$alias;?>/">
											<?=$name;?>
										</a>]
									</li>
								<?
							}
						?>
					</ul>
				</div>
			</div>
		<?
	}
