<div class="cats art_vote_wrapper">
	<span><?=$data['sidebar']['art_vote']['score'];?></span>
	<img 
		src="/images/vote_down.gif" 
		class="left vote_down with_help
		<?=($data['sidebar']['art_vote']['voted'] ? ' inactive_vote' : '');?>" 
		title="<?=($data['sidebar']['art_vote']['voted'] ? 'Вы уже голосовали' : 'Не понравилось');?>" 
		rel="<?=$data['sidebar']['art_vote']['art_id'];?>"
	/>
	<img 
		src="/images/vote_up.gif" 
		class="right vote_up with_help
		<?=($data['sidebar']['art_vote']['voted'] ? ' inactive_vote' : '');?>"  
		title="<?=($data['sidebar']['art_vote']['voted'] ? 'Вы уже голосовали' : 'Понравилось');?>" 
		rel="<?=$data['sidebar']['art_vote']['art_id'];?>"
	/>
</div>
