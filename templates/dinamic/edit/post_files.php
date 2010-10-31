<? 
include_once('templates/dinamic/edit/top.php');
?>
<script type="text/javascript" src="/jss/m/?b=jss&f=edit_form.js,ajaxupload.js,add/post.js"></script>
<table class="margin20">
	<tbody class="link_file">
	<tr>
		<td width="20%">
			Загрузить файл
		</td>
		<td>
			<table>
				<tr>
					<td>
						<div id="post-file">
							<img src="/images/upload_button.png">
						</div>
					</td>
					<td>
						<img class="processing-file hidden" src="/images/ajax-processing.gif" />
					</td>
					<td>
						<span class="processing-file hidden">Файл загружается. (Вы можете загружать несколько файлов одновременно.)</span>
					</td>
				</tr>
			</table>
		</td>
	</tr>			
	<tr>
		<td colspan="2" id="error">
		
		</td>
	</tr>	
		<?	$i = 1;
			if (is_array($data['value'])) foreach ($data['value'] as $link) {
				if (!is_array($link['url'])) {$link['url'] = array($link['url']); $link['alias'] = array($link['alias']);}
				foreach ($link['url'] as $key => $oneurl) { 					
					?>
						<tr class="link" rel="<?=$i;?>">
							<td colspan="2">
								<input size="24%" name="file[<?=$i;?>][name]" value="<?=$link['name'];?>" type="text">: 
								<input size="24%" readonly name="file[<?=$i;?>][filename]" value="<?=$link['filename'];?>" type="text"> 
								<input type="hidden" name="file[<?=$i;?>][folder]" value="<?=$link['folder'];?>" />
								<input type="hidden" name="file[<?=$i;?>][type]" value="<?=$link['type'];?>" />
								<?
									if ($link['height']) {
										?>
											<input type="hidden" name="file[<?=$i;?>][height]" value="<?=$link['height'];?>" />										
										<?
									}
								?>							
								<input size="4%" name="file[<?=$i;?>][size]" value="<?=$link['size'];?>" type="text">
								<input type="submit" class="disabled remove_link second_button" rel="file" value="-"/>
							</td>
						</tr>
					<?
					$i++;
				} 
			}
		?>
	<tbody>
</table>
<? 
include_once('templates/dinamic/edit/bottom.php');
?>
