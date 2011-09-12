<? engine::error_headers(); ?>
<? include_once('templates'.SL.'side'.SL.'head.php'); ?>
<body>
	<? include_once('templates'.SL.'side'.SL.'header.php'); ?>
	<table width="100%">
		<tr>
			<td valign="top" id="content">
				<div class="post">
					<? include_once('templates'.SL.'side'.SL.'top.php'); ?>
					<table width="100%" id="error">
						<tr>
							<td>
								<img src="<?=$def['site']['dir']?>/images/board404.jpg">
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
