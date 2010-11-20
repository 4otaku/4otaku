<?  

	function output_comment($comment,$number) {
		global $url; global $sets;
		
		$where = array('main' => 'оставлен на главной','workshop' => 'оставлен в мастерской', 'flea_market' => 'оставлен в барахолке');
		if (!$comment['area']) $comment['area'] = 'main';
		?>
			<div class="comment" id="comment-<?=$comment['id'];?>"<?=($comment['position'] ? ' style="padding-left: '.($comment['position']*50).'px;"' : '');?>>
				<div class="comment-top">
					<b><?=$number;?>) <?=$comment['username'];?></b>
					<span class="datetime">
						 <?=$comment['pretty_date'].($url[1] != 'news' && $url[1] != 'order' && $comment['area'] != $url['area'] && array_key_exists($comment['area'],$where) ? ' ('.$where[$comment['area']].')' : '');?>
					</span>
					<span class="commentmetadata"><a href="#comment-<?=$comment['id'];?>" title="">#</a></span>
					<?
						if ($sets['user']['rights']) {
							?>
								<div class="right">
									<?=($sets['user']['rights'] > 1 ? $comment['ip'].'&nbsp;' : '');?>
									<img src="<?=SITE_DIR.'/images'?>/comment_edit.png" alt="редактировать" rel="<?=$comment['id'];?>" class="edit_comment">
									&nbsp;&nbsp;&nbsp;
									<img src="<?=SITE_DIR.'/images'?>/comment_delete.png" alt="удалить" rel="<?=$comment['id'];?>" class="delete_comment">
								</div>
							<?
						}
					?>
				</div>
				<div class="comment-content">
					<div class="avatar-n">
						<img alt='' src='http://www.gravatar.com/avatar/<?=md5( strtolower($comment['email']) );?>?s=50&d=identicon&r=G' class='avatar avatar-50 photo' height='50' width='50' />
					</div>
					<div class="comment-text">
					<span><?=$comment['text'];?></span>
					<?
						if ($comment['position'] < 5) {
							?>
								<br /><br />
								<a href="#" rel="<?=$comment['id'];?>" class="disabled reply">Ответить</a>
								<div class="reply-<?=$comment['id'];?>"></div>
							<?
						}
					?>
					<div class="edit-<?=$comment['id'];?>"></div>					
					<div class="c-wrap"></div>
					</div>
				</div>
				<br />
			</div>
		<?
		if (is_array($comment['children'])) {
			if ($sets['dir']['comments_tree']) {		
				$num = count($comment['children']);
				foreach ($comment['children'] as $child) {
					output_comment($child,$number.'.'.$num--);
				}
			}
			else {
				$num = 0;
				foreach ($comment['children'] as $child) output_comment($child,$number.'.'.++$num);
			}
		}
	}
?>
<div id="comments">
<?	
	if (is_array($data['main']['comments']['comments'])) {
		if ($sets['dir']['comments_tree']) {
			if (is_numeric($data['main']['navi']['curr'])) 
				$num = $data['main']['comments']['number'] - ($data['main']['navi']['curr']-1)*$sets['pp']['comment_in_post'];
			else
				$num = $data['main']['comments']['number'];
			foreach ($data['main']['comments']['comments'] as $comment) output_comment($comment,$num--);
		}
		else {
			if (is_numeric($data['main']['navi']['curr'])) 
				$num = ($data['main']['navi']['curr']-1)*$sets['pp']['comment_in_post'];
			else
				$num = 0;
			foreach ($data['main']['comments']['comments'] as $comment) output_comment($comment,++$num);
		}
	}
?>
<? 
	if ($data['main']['comments']['number'] > $sets['pp']['comment_in_post']) {
		?>
			<? include_once(SITE_FDIR._SL.'templates'.SL.'main'.SL.'navi.php'); ?>
		<?
	}				
?>

		
	<div id="comments-field">
		<noscript>
			Для возможности комментировать, включите пожалуйста javascript;
		</noscript>
	</div>
</div>
