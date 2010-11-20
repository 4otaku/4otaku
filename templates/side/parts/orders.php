<div class="cats">	
	<h2>
		<a href="/order/">
			Стол заказов
		</a>
		 <a href="#" class="bar_arrow" rel="order">
			<? if ($sets['dir']['order']) { ?>
				<img src="/images/text2391.png">
			<? } else { ?>
				<img src="/images/text2387.png">
			<? } ?>
		</a>
	</h2>
	<div id="order_bar"<?=($sets['dir']['order'] ? '' : ' style="display:none;"');?>>
		<? if (is_array($data['sidebar']['orders'])) { ?>
			<? foreach ($data['sidebar']['orders'] as $order) { ?>
				<? if ($nonfirst) { ?>
					<br /><br />
				<? } else { $nonfirst = true; } ?>
				<?=$order['username'];?> заказал 
				<a href="/order/<?=$order['id'];?>" class="with_help2" rel="1" title="<?=strip_tags($order['text']);?>">
					<?=$order['title'];?>
				</a>.
				(Комментариев: <?=$order['comment_count'];?>)
			<? } unset($nonfirst); ?>
		<? } ?> 
	</div>
</div>
