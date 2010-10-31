<? include_once('templates/404/headers.php'); ?>
<? include_once('templates/side/head.php'); ?>
<script type="text/javascript">
	$(document).ready(function(){ 
		$("div#downscroller a.disabled").click();
	});			
</script>
<body>	
	<table width="100%">
		<tr>
			<td colspan="2" id="header">
				<? include_once('templates/side/header.php'); ?>
			</td>
		</tr>
		<tr>
			<td valign="top" id="sidebar">
				<? include_once('templates/side/sidebar.php'); ?>
			</td>		
			<td valign="top" id="content">
				<div class="post">
					<? include_once('templates/side/top.php'); ?>
					<table width="100%">
						<tr>
							<td>
								<div class="post">
									<h2>
										<?
											if ($url['area'] == $def['area'][1]) {
												?>
													Очередь премодерации в настоящий момент пуста.
												<?
											} elseif ($url['area'] == $def['area'][2]) {
												?>
													Барахолка в настоящий момент пуста.
												<?
											} else {
												?>
													Основная лента в настоящий момент пуста и это оооочень странно. Напишите пожалуйста об этом на admin@4otaku.ru
												<?
											}
										?>
									</h2>
								</div>
							</td>
						</tr>			
					</table>
				</div>
			</td>
		</tr>
		<tr>
			<td colspan="2" id="footer">
				<? include_once('templates/side/footer.php'); ?>
			</td>
		</tr>
	</table>
</body>
</html>
