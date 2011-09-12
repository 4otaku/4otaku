<div>
	<img src="<?=$def['site']['dir']?>/images/thumbs/305122.jpg" align="left" />
	<p>
		Добрый день. Меня зовут Gouf Custom MS-07B-3 и я выполняю тут функции разведчика. В мои задачи входит поиск битых ссылок и отчет по ним в штаб. Информация обновляется раз в сутки, строго по расписанию.		
	</p>
	<p>
		Отчет вы можете увидеть ниже, записи отсортированы по степени критичности ситуации от самых тяжелых к не слишком срочным. Мы будем очень благодарны, если вы поможете нам разобраться с некоторыми из них. 
		Новую ссылку на замену битой можно предоставить в комментариях, модераторы обязательно заменят ей старую. То же самое и если вы просто добавляете зеркало к ссылке. Обсуждение и комментарии моей работы <a href="<?=$def['site']['dir']?>/news/gouf_mk1/">по этой ссылке</a>
	</p>
	<p>
		Gouf Custom MS-07B-3, вступление закончил, приступаю к отчету.
	</p>
</div>
<b>
	<?=$data['main']['total'];?> битых ссылок найдено.
</b>
<? if (!empty($data['main']['total'])) { ?> 
	<b>
		Отчет: 
	</b>
	<br /><br />
	<?
		foreach ($data['main']['posts'] as $key => $post) {
			?>
				<div class="shell">
					<b>
						Запись №<?=$key;?>:
					</b> 
					<a href="<?=$def['site']['dir']?>/post/<?=$key;?>">
						<?=$post['title'];?>
					</a>
					<br /><br />
					<?
						if ($post['critical_errors']) {
							?>
								<div class="mini-shell">
									<span style="color: #FF0033; font-weight: bold;">
										Ссылка битая и нет зеркала:
									</span>
									<br />
									<?
										foreach ($post['critical_errors'] as $output1) {
											?>
												<?=$output1;?><br />
											<?
										}
									?>
								</div>
							<?
						}
						if ($post['errors']) {
							?>
								<div class="mini-shell">
									<span style="color: #FF6633;font-weight: bold;">
										Ссылка битая:
									</span>
									<br />
									<?
										foreach ($post['errors'] as $output2) {
											?>
												<?=$output2;?><br />
											<?
										}
									?>
								</div>
							<?
						}
						if ($post['warnings']) {
							?>
								<div class="mini-shell">
									<span style="color: #CC9900; font-weight: bold;">
										У ссылки нет зеркала:
									</span>
									<br />
									<?
										foreach ($post['warnings'] as $output3) {
											?>
												<?=$output3;?><br />
											<?
										}
									?>
								</div>
							<?
						}
					?>
				</div>
			<?
		}
	?>
<? } else { ?>
	 <b>
		Все чисто!
	</b>
<? } ?>
