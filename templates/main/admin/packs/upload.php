<div class="shell" id="mainpart">
	<h2>
		Заливка нового архива (zip, 200 мегабайт макс.)
	</h2>
	<table>
		<? if (query::$post['name']) { ?>
			<tr>
				<td align="center">
					<script type="text/javascript" src="<?=$def['site']['dir']?>/jss/m/?b=jss&f=fileupload.js"></script>
					<div id="upload"></div>
					<script>
						$(document).ready(function(){  
							new qq.FileUploader({
								element: document.getElementById('upload'),
								action: window.config.site_dir+'/ajax.php?upload=pack&name=<?=urlencode(query::$post['name']);?>&text=<?=urlencode(query::$post['text']);?>',
								multiple: false,
								autoSubmit: true,
								onSubmit: function(id, file) {
									$(".processing").show();
									$('#error').html('');
								},
								onComplete: function(id, file, response) {
									$(".processing").hide(); 
									if (response.error == 'error-maxsize') {
										$('#error').html('<b>Ошибка! Выбранный вами файл превышает 200 мегабайт.</b>');
									} else {
										update(response.id);
										create_progressform(response.id,response.file);
									} 
								}
							});				
						});	
						
						function update(id) {
							$.post("/engine/process_pack.php");
							if ($(".progress-"+id).length > 0) {
								$("#progress-"+id+" .updating").show();$(".progress-"+id).hide();
								$(".progress-"+id).load("/engine/status_pack.php?id="+id, function (){
									$("#progress-"+id+" .updating").hide();$(".progress-"+id).show();
									if ($(".progress-"+id).html() != "Готово.") setTimeout("update('"+id+"');",15000);
								});
							} else {
								setTimeout("update('"+id+"');",15000);
							}
						}

						function create_progressform(id,name,input) {
							$("#mainpart").append("<table><tr><td colspan='3' id='progress-"+id+"'>Прогресс файла "+name+" - <div class='progress-"+id+"'>ожидание</div><img class='updating hidden' src='/images/ajax-processing.gif' /></td></tr></table>");
						}
					</script>
				</td>
				<td>
					<img class="processing" src="/images/ajax-processing.gif" />
				</td>
				<td>
					<span class="processing">Архив загружается.</span>
				</td>
			</tr>			
			<tr>
				<td colspan="3" id="error">
				
				</td>
			</tr>	
		<? } else { ?>
		<tr>
			<td colspan="3">
				<form enctype="multipart/form-data" method="post">
					<table class="text">
						<tr>
							<td colspan="2">
								Заголовок: <input type="text" name="name" size="60" value="" class="name">
							</td>
						</tr>
						<tr>
							<td>						
								<textarea id="textfield" name="text" rows="10" cols="100" class="text"></textarea>		
							</td>
							<td valign="top">
								<table cellspacing="3px" class="bbholder">
									<tr>
										<td>
											<img src="/images/bb/bold.png" rel="b" class="bb" title="Выделить жирным" />
										</td>
									</tr>
									<tr>
										<td>
											<img src="/images/bb/italic.png" rel="i" class="bb" title="Выделить курсивом" />
										</td>
									</tr>
									<tr>
										<td>
											<img src="/images/bb/strike.png" rel="s" class="bb" title="Зачеркнутый текст" />
										</td>
									</tr>					
									<tr>
										<td>
											<img src="/images/bb/picture.png" rel="img" class="bb" title="Добавить картинку" />
										</td>
									</tr>
									<tr>
										<td>
											<img src="/images/bb/link.png" rel="url" class="bb" title="Добавить ссылку" />
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
					<input type="submit" value="Перейти к заливке">
				</form>				
			</td>
		</tr>	
		<? } ?>	
	</table>
</div>
