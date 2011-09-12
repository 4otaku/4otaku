Тредов на страницу: 
<select class="settings" rel="pp.board">
	<option value="3"<?=(sets::pp('board') == 3 ? ' selected="yes"' : '');?>>3</option>
	<option value="4"<?=(sets::pp('board') == 4 ? ' selected="yes"' : '');?>>4</option>
	<option value="5"<?=(sets::pp('board') == 5 ? ' selected="yes"' : '');?>>5</option>
	<option value="7"<?=(sets::pp('board') == 7 ? ' selected="yes"' : '');?>>7</option>
	<option value="10"<?=(sets::pp('board') == 10 ? ' selected="yes"' : '');?>>10</option>
	<option value="15"<?=(sets::pp('board') == 15 ? ' selected="yes"' : '');?>>15</option>
	<option value="20"<?=(sets::pp('board') == 20 ? ' selected="yes"' : '');?>>20</option>
</select>
<br />
Последних постов в треде: 
<select class="settings" rel="pp.board_posts">
	<option value="3"<?=(sets::pp('board_posts') == 3 ? ' selected="yes"' : '');?>>3</option>
	<option value="4"<?=(sets::pp('board_posts') == 4 ? ' selected="yes"' : '');?>>4</option>
	<option value="5"<?=(sets::pp('board_posts') == 5 ? ' selected="yes"' : '');?>>5</option>
	<option value="7"<?=(sets::pp('board_posts') == 7 ? ' selected="yes"' : '');?>>7</option>
	<option value="10"<?=(sets::pp('board_posts') == 10 ? ' selected="yes"' : '');?>>10</option>
	<option value="15"<?=(sets::pp('board_posts') == 15 ? ' selected="yes"' : '');?>>15</option>
	<option value="20"<?=(sets::pp('board_posts') == 20 ? ' selected="yes"' : '');?>>20</option>
</select>
<br />
Все треды на главной: 
<input type="checkbox" class="settings" rel="board.allthreads" value="1"<?=(sets::board('allthreads') ? ' checked' : '');?>>
<br />
Сразу показывать видео: 
<input type="checkbox" class="settings" rel="board.embedvideo" value="1"<?=(sets::board('embedvideo') ? ' checked' : '');?>>