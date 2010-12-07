<div class="shell">
	<table width="100%">
		<tr>
			<td align="left" colspan="2">
				<h2>
					<a href="http://<?=$_SERVER['HTTP_HOST'].'/'.$item['place'].'/'.$item['post_id'];?>/comments/all#comment-<?=$item['id'];?>" title="<?=$item['title'];?>">
						<?=$item['title'];?>
					</a>
				</h2>
			</td>
		</tr>
		<tr>
			<td align="left" width="1">
			<? 	
				if($item['place'] == 'art') {
					if ($item['preview_picture']) {
						?>
						<img src="http://4otaku.ru/images/booru/thumbs/<?=$item['preview_picture'];?>.jpg">
						<?
					} else {
						?>
						<img src="http://w8m.4otaku.ru/image/<?=$item['preview_cg']['gallery'];?>/thumb/<?=$item['preview_cg']['md5'];?>.jpg">
						<?						
					}
				}
				else
				{
					?>
					<img alt='' src='http://www.gravatar.com/avatar/<?=md5( strtolower($item['email']) );?>?s=70&d=identicon&r=G' />
					<?
				}
			?>
			</td>
			<td align="left">
			<? 
				if(($item['place'] == 'art') && ($item['preview_picture'])) { 
					?>
					<img alt='' src='http://www.gravatar.com/avatar/<?=md5( strtolower($item['email']) );?>?s=70&d=identicon&r=G' />
					<?
				} 
				?>
				<div class="comment-top">
					<b><?=$item['username'];?></b>
					<span class="datetime">
						 <?=$item['pretty_date'];?>
					</span>
				</div>
				<div class=comment-text>
					<?=$item['text'];?>
				</div>			
			</td>	
		</tr>
	</table>
</div>



