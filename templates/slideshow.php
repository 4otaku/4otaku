<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>Слайдшоу</title>
		<script type="text/javascript" src="<?=$def['site']['dir']?>/jss/m/?b=jss&f=jquery-1.3.2.min.js,slideshow.js"></script>
		<link rel="stylesheet" href="<?=$def['site']['dir']?>/jss/m/?b=jss&f=slideshow.css" type="text/css" media="screen" />		
	</head>
	<body>
		<table width="<?=($def['booru']['resizewidth']*1.1+100);?>px" class="center">
			<tr>
				<td colspan="3">
					<div class="margin10">
						Подгонять изображения под экран: 
						<input type="checkbox"<?=($sets['slideshow']['resize'] ? ' checked' : '');?> id="resize">; 
						Автоматический режим 
						<input type="checkbox"<?=($sets['slideshow']['auto'] ? ' checked' : '');?> id="auto">
						<span class="delay_holder"<?=($sets['slideshow']['auto'] ? '' : ' style="display: none;"');?>>
							 , переключать каждые:
							<input type="text" id="delay" value="<?=$sets['slideshow']['delay'];?>" size="3"> 
							секунд
						</span>
						;
					</div>
				</td>
			</tr>
			<tr>
				<td align="left" width="50px">
					<img src="<?=$def['site']['dir']?>/images/left_arrow.png" class="arrow_left">
					&nbsp;
				</td>
				<td class="body" width="<?=($def['booru']['resizewidth']*1.1);?>">								

				</td>
				<td align="right" width="50px">
					<img src="<?=$def['site']['dir']?>/images/right_arrow.png" class="arrow_right">
					&nbsp;
				</td>
			</tr>
		</table>
	</body>
</html>
