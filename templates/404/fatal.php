<? engine::make_404(); ?>
<? include_once('templates'.SL.'side'.SL.'head.php'); ?>
<body>	
	<table width="100%">
		<tr>
			<td id="header">
				<? include_once('templates'.SL.'side'.SL.'header.php'); ?>
			</td>
		</tr>
		<tr>
			<td valign="top" id="content">
				<table width="100%" id="error">
					<tr>
						<td>
							<img src="<?=$def['site']['dir']?>/images/yuugif.gif">
						</td>
					</tr>			
				</table>
			</td>
		</tr>
		<tr>
			<td id="footer">
				<? include_once('templates'.SL.'side'.SL.'footer.php'); ?>
			</td>
		</tr>
	</table>
</body>
</html>
