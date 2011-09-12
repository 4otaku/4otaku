<? include_once('templates'.SL.'side'.SL.'head.php'); ?>
<script type="text/javascript">
	window.halt_onbeforeunload = true;
	$(document).ready(function(){ 
		$("div#downscroller a.disabled").click();
	});
</script>
<body>	
	<? include_once('templates'.SL.'side'.SL.'header.php'); ?>
	<table width="100%">
		<tr>
			<td valign="top" id="content">
				<div class="post">
					<? include_once('templates'.SL.'side'.SL.'top.php'); ?>
					<table width="100%">
						<tr>
							<td>
								<div class="post">
									<h2>
										Тредов в этом разделе пока еще нет. Будете первым?
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
