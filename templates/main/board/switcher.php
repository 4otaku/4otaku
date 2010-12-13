<div class="center margin10" width="100%">
	<? if(!sets::get('board','allthreads')) { ?>
		<a href="#" class="switch_allboards" rel="1">
			Убрать приветствие, показывать тут ленту всех тредов.
		</a>
	<? } else { ?>
		<a href="#" class="switch_allboards" rel="0">
			Убрать ленту всех тредов, показывать тут приветствие.
		</a>	
	<? } ?>
</div>
