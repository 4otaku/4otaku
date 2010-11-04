<? include_once('templates'.SL.'side'.SL.'head.php'); ?>
<script type="text/javascript">
	window.halt_onbeforeunload = true;
	$(document).ready(function(){ 
		$("div#downscroller a.disabled").click();
	});			
</script>
<body>	
	<table width="100%">
		<tr>
			<td colspan="2" id="header">
				<? include_once('templates'.SL.'side'.SL.'header.php'); ?>
			</td>
		</tr>
		<tr>
			<td valign="top" id="content">
				<div class="post">
					<? include_once('templates'.SL.'side'.SL.'top.php'); ?>
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
			<td valign="top" id="sidebar">
				<? include_once('templates'.SL.'side'.SL.'sidebar.php'); ?>
			</td>
		</tr>
		<tr>
			<td colspan="2" id="footer">
				<? include_once('templates'.SL.'side'.SL.'footer.php'); ?>
			</td>
		</tr>
	</table>
</body>
</html>
