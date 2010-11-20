<? include_once('templates'.SL.'side'.SL.'head.php'); ?>
<body>	
	<table width="100%">
		<tr>
			<td colspan="2" id="header">
				<? include_once('templates'.SL.'side'.SL.'header.php'); ?>
			</td>
		</tr>
		<tr>
			<td valign="top" id="sidebar">
				<? include_once('templates'.SL.'side'.SL.'sidebar.php'); ?>
			</td>		
			<td valign="top" id="content">
				<div class="post">
					<? include_once('templates'.SL.'side'.SL.'top.php'); ?>
					<table width="100%" id="error">
						<tr>
							<td>
								<img src="/images/booru_404_<?=rand(1,2);?>.jpg">
							</td>
						</tr>			
					</table>
				</div>
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
