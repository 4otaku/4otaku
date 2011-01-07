<? 
include_once('templates'.SL.'dinamic'.SL.'edit'.SL.'top.php');
?>
<script type="text/javascript" src="<?=$def['site']['dir']?>/jss/m/?b=jss&f=edit_form.js,ajaxupload.js,add/post.js"></script>
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
							<img src="<?=$def['site']['dir']?>/images/upload_button.png">
						</div>
					</td>
					<td>
						<img class="processing-image hidden" src="<?=$def['site']['dir']?>/images/ajax-processing.gif" />
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
						<div style="background-image: url(/images/thumbs/<?=$image;?>);"><img class="cancel" src="<?=$def['site']['dir']?>/images/cancel.png"><input type="hidden" name="images[]" value="<?=$image;?>"></div>
					<?
				}
			?>
		</td>
	</tr>
</table>
<? 
include_once('templates'.SL.'dinamic'.SL.'edit'.SL.'bottom.php');
?>
