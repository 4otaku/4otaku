<? include_once('templates/404/headers.php'); ?>
<? include_once('templates/side/head.php'); ?>
<body>	
	<table width="100%">
		<tr>
			<td colspan="2" id="header">
				<? include_once('templates/side/header.php'); ?>
			</td>
		</tr>
		<tr>
			<td valign="top" id="content">
				<div class="post">
					<? include_once('templates/side/top.php'); ?>
					<table width="100%" id="error">
						<tr>
							<td>
								<img src="/images/yuugif.gif">
							</td>
						</tr>			
					</table>
				</div>
			</td>
			<td valign="top" id="sidebar">
				<? include_once('templates/side/sidebar.php'); ?>
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
