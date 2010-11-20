<? 
include_once(SITE_FDIR._SL.'templates'.SL.'dinamic'.SL.'edit'.SL.'top.php');
?>
<script type="text/javascript" src="<?=SITE_DIR?>/jss/m/?b=jss&f=edit_form.js,ajaxupload.js,add/post.js"></script>
<table width="100%">		
	<tr>
		<td width="20%">
			Загрузить картинку
		</td>
		<td>
			<table>
				<tr>
					<td>
						<div id="post-image">
							<img src="<?=SITE_DIR.'/images'?>/upload_button.png">
						</div>
					</td>
					<td>
						<img class="processing-image hidden" src="<?=SITE_DIR.'/images'?>/ajax-processing.gif" />
					</td>
					<td>
						<span class="processing-image hidden">Изображение загружается. (Вы можете загружать несколько изображений одновременно.)</span>
					</td>
				</tr>
			</table>
		</td>
	</tr>			
	<tr>
		<td colspan="2" id="error">
		
		</td>
	</tr>			
	<tr id="transparent" class="post_images">
		<td colspan="2">
			<?
				foreach ($data['value'] as $image) {
					?>
						<div style="background-image: url(<?=SITE_DIR.'/images'?>/thumbs/<?=$image;?>);"><img class="cancel" src="<?=SITE_DIR.'/images'?>/cancel.png"><input type="hidden" name="images[]" value="<?=$image;?>"></div>
					<?
				}
			?>
		</td>
	</tr>
</table>
<? 
include_once(SITE_FDIR._SL.'templates'.SL.'dinamic'.SL.'edit'.SL.'bottom.php');
?>
