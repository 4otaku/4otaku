<a href="/post/workshop/">
	Всего <?=count($data['main']['post']);?> записей в мастерской
</a>
<br /><br />
<img src="/images/tb2.gif">
&nbsp;
<span class="car-yearmonth car-post" rel="open">
	<span title="Всего записей">
		Свернуть.
	</span>
</span>
<br /><br />
<ul class="car-monthlisting car-post">
	<?
		if (is_array($data['main']['post'])) foreach ($data['main']['post'] as $item) {
			?>
				<li>
					<a href="/post/<?=$item['id'];?>">
						<?=$item['title'];?>
					</a>.
					<?
						if ($item['comment_count']) {
							?> 
								<span title="Всего комментариев">
									Комментариев: (<?=$item['comment_count'];?>)
								</span>
							<?
						}
					?>
				</li>
			<?
		}
	?>
</ul>
<hr />
<a href="/video/workshop/">
	Всего <?=count($data['main']['video']);?> видео на премодерации
</a>
<br /><br />
<img src="/images/tb2.gif">
&nbsp;
<span class="car-yearmonth car-video" rel="open">
	<span title="Всего записей">
		Свернуть.
	</span>
</span>
<br /><br />
<ul class="car-monthlisting car-video">
	<?
		if (is_array($data['main']['video'])) foreach ($data['main']['video'] as $item) {
			?>
				<li>
					<a href="/video/<?=$item['id'];?>">
						<?=$item['title'];?>
					</a>.
					<?
						if ($item['comment_count']) {
							?> 
								<span title="Всего комментариев">
									Комментариев: (<?=$item['comment_count'];?>)
								</span>
							<?
						}
					?>
				</li>
			<?
		}
	?>
</ul>
<hr />
<a href="/art/workshop/">
	Всего <?=($data['main']['art']);?> артов на премодерации
</a>
<br /><br />
Предлагаемые теги:
<ul class="car-monthlisting">
	<?
		if (is_array($data['main']['tags'])) foreach ($data['main']['tags'] as $key => $item) {
			?>
				<li>
					<a href="/art/tag/<?=$item['data1'];?>/"><?=$item['data1'];?></a> 
					сделать "<?=($item['data3'] ? '<span style="color:#'.$item['data3'].';">' : '').$item['data2'].($item['data3'] ? '</span>' : '');?>"; 
					<? if ($item['data3']) { ?>
						<a href="#" class="admin_color_tag" rel="<?=$item['data3'].'|'.$item['data1'].'|'.$key;?>">
							Одобрить
						</a>.
					<? } else { ?>
						<a href="/admin/tags/search/<?=$item['data1'];?>/">
							Перейти к тегам
						</a>.
					<? } ?> 
					<a href="#" class="admin_drop_color_tag" rel="<?=$key;?>">
						Отклонить
					</a>.					
				</li>
			<?
		}
	?>
</ul>
<hr />
<a href="/order/">
	Всего <?=count($data['main']['order']);?> заказов не выполнено
</a>
<br /><br />
<img src="/images/tb2.gif">
&nbsp;
<span class="car-yearmonth car-order" rel="closed">
	<span title="Всего записей">
		Развернуть.
	</span>
</span>
<br /><br />
<ul style="display:none;" class="car-monthlisting car-order">
	<?
		if (is_array($data['main']['order'])) foreach ($data['main']['order'] as $item) {
			?>
				<li>
					<a href="/order/<?=$item['id'];?>">
						<?=$item['title'];?>
					</a>.
					<?
						if ($item['comment_count']) {
							?> 
								<span title="Всего комментариев">
									Комментариев: (<?=$item['comment_count'];?>)
								</span>
							<?
						}
					?>
				</li>
			<?
		}
	?>
</ul>
<hr />
	<?
		if (is_array($data['main']['comment'])) foreach ($data['main']['comment'] as $item) {
			?>
				<div class="mini-shell">
					<?=$item['username'];?>: <?=$item['text'];?> (К 
					<a href="/<?=($item['place'] == 'orders' ? 'order' : $item['place']);?>/<?=$item['post_id'];?>/comments/all#comment-<?=$item['id'];?>">
						<?=$item['place'];?> № <?=$item['post_id'];?>
					</a> ).
				</div>
			<?
		}
	?>
