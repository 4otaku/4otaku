<? if (!class_exists('Imagick', false)) { ?>
<h2>Imagick не установлен! Юзаю GD!</h2>
<? } ?>

<style>
	.post table {
		border-top: 1px solid #D8D8D8;
		border-left: 1px solid #D8D8D8;
		border-right: 1px solid #D8D8D8;
		width: 100%;
		font-size: 13px;
		margin: 0;
		margin-bottom: 1em;
		border-radius: 3px;
	}
	.post tr {
		border-color: inherit;
	}
	.post th {
		text-align: left;
		background-color: #EAEAEA;
		color: #999;
		padding: .5em .3em;
		border-bottom: 1px solid #D8D8D8;
	}
	.post td {
		background: #F8F8F8;
		padding: .5em .3em;
		color: #484848;
		border-bottom: 1px solid #E1E1E1;
	}
	.slide {
		padding-left: 10px;
		display: inline-block;
		margin-bottom: 5px;
		background: url('<?=$def['site']['dir']?>/images/tb2.gif') no-repeat 0 2px;
	}
	.slide:hover {
		text-decoration: none;
	}
	.closed {
		display: none;
	}
</style>
<script type="text/javascript">
	$(function() {
		$('.slide').click(function (){
			if ($(this).next('div:hidden').length == 0) {
				$(this).text("Развернуть");
			} else {
				$(this).text("Свернуть");
			}
			$(this).next('div').slideToggle();
			return false;
		});
	});
</script>

<h3>
	<a href="<?=$def['site']['dir']?>/post/workshop/">Мастерская</a> (<?=count($data['main']['post']);?>)
</h3>

<a class="car-yearmonth car-post slide" href="#">
	Свернуть
</a>

<div>
	<table class="car-monthlisting car-post" cellspacing="0" cellpadding="0">
		<tr>
			<th></th>
			<th>Запись</th>
			<th>Комментарии</th>
		</tr>

		<?
			if (is_array($data['main']['post'])) foreach ($data['main']['post'] as $item) {
		?>
				<tr>
					<td>
						<?=$item['id'];?>
					</td>
					<td>
						<a href="<?=$def['site']['dir']?>/post/<?=$item['id'];?>">
							<?=$item['title'];?>
						</a>.
					</td>
					<td>
						<?= ($item['comment_count'] ? $item['comment_count'] : '0');?>
					</td>
				</tr>
		<?
			}
		?>
	</table>
</div>
<hr />

<h3>
	<a href="<?=$def['site']['dir']?>/video/workshop/">Видео</a> (<?=count($data['main']['video']);?>)
</h3>

<a class="car-yearmonth car-video slide" href="#">
	Свернуть
</a>
<div>
	<table class="car-monthlisting car-video" cellspacing="0" cellpadding="0">
		<tr>
			<th></th>
			<th>Запись</th>
			<th>Комментарии</th>
		</tr>
	<?
		if (is_array($data['main']['video'])) foreach ($data['main']['video'] as $item) {
	?>
			<tr>
				<td>
					<?=$item['id'];?>
				</td>
				<td>
					<a href="<?=$def['site']['dir']?>/video/<?=$item['id'];?>">
						<?=$item['title'];?>
					</a>.
				</td>
				<td>
					<?= ($item['comment_count'] ? $item['comment_count'] : '0');?>
				</td>
			</tr>
	<?
		}
	?>
	</table>
</div>
<hr />

<h3>
	<a href="<?=$def['site']['dir']?>/art/workshop/">Арт</a> (<?=($data['main']['art']);?>)
</h3>

Предлагаемые теги:
<ul class="car-monthlisting">
	<?
		if (is_array($data['main']['tags'])) foreach ($data['main']['tags'] as $key => $item) {
			?>
				<li>
					<a href="<?=$def['site']['dir']?>/art/tag/<?=$item['data1'];?>/"><?=$item['data1'];?></a>
					сделать "<?=($item['data3'] ? '<span style="color:#'.$item['data3'].';">' : '').$item['data2'].($item['data3'] ? '</span>' : '');?>";
					<? if ($item['data3']) { ?>
						<a href="#" class="admin_color_tag" rel="<?=$item['data3'].'|'.$item['data1'].'|'.$key;?>">
							Одобрить
						</a>.
					<? } else { ?>
						<a href="<?=$def['site']['dir']?>/admin/tags/search/<?=$item['data1'];?>/">
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

<h3>
	<a href="<?=$def['site']['dir']?>/order/">Заказы</a> (<?=count($data['main']['order']);?>)
</h3>

<a class="car-yearmonth car-order slide" href="#">
	Развернуть
</a>

<div class="closed">
	<table class="car-monthlisting car-order" cellspacing="0" cellpadding="0">
		<tr>
			<th></th>
			<th>Запись</th>
			<th>Комментарии</th>
		</tr>
		<?
			if (is_array($data['main']['order'])) foreach ($data['main']['order'] as $item) {
		?>
			<tr>
				<td>
					<?=$item['id'];?>
				</td>
				<td>
					<a href="<?=$def['site']['dir']?>/order/<?=$item['id'];?>">
						<?=$item['title'];?>
					</a>.
				</td>
				<td>
					<?= ($item['comment_count'] ? $item['comment_count'] : '0');?>
				</td>
			</tr>
		<?
			}
		?>
	</table>
</div>
<hr />

<h3 title="За сутки">Новые комментарии (<?=count($data['main']['comment']);?>)</h3>

<a class="car-yearmonth car-order slide" href="#">
	Развернуть
</a>

<div class="closed">
	<table class="car-monthlisting car-order" cellspacing="0" cellpadding="0">
		<tr>
			<th>Автор</th>
			<th>Текст комментария</th>
			<th>Где оставлен</th>
		</tr>
<?
	if (is_array($data['main']['comment'])) foreach ($data['main']['comment'] as $item) {
?>
		<tr>
			<td><?=$item['username'];?></td>
			<td><?=$item['text'];?></td>
			<td>
				<a href="<?=$def['site']['dir']?>/<?=($item['place'] == 'orders' ? 'order' : $item['place']);?>/<?=$item['post_id'];?>/comments/all#comment-<?=$item['id'];?>">
					<?=$item['place'];?> № <?=$item['post_id'];?>
				</a>
			</td>
		</tr>
<?
	}
?>
	</table>
</div>
