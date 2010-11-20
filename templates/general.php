<? include_once SITE_FDIR._SL.'templates'.SL.'side'.SL.'head.php'; ?>
<body>	
	<table width="100%">
		<tr>
			<td colspan="2" id="header">
				<? include_once(SITE_FDIR._SL.'templates'.SL.'side'.SL.'header.php'); ?>
			</td>
		</tr>
		<tr>
			<td valign="top" id="content">
				<div class="post">
					<? if (isset($output->side_modules['top'])) include_once(SITE_FDIR._SL.'templates'.SL.'side'.SL.'top.php'); ?>
					<? if (is_array($data['main']['display'])) 
						foreach ($data['main']['display'] as $key => $part) 
							include (SITE_FDIR._SL.'templates'.SL.'main/'.str_replace('_',SL,$part).'.php'); 
					?>
				</div>
			</td>
			<? if (isset($output->side_modules['sidebar'])) { ?>
				<td valign="top" id="sidebar">
					<? include_once(SITE_FDIR._SL.'templates'.SL.'side'.SL.'sidebar.php'); ?>
				</td>
			<? } ?>
		</tr>
		<tr>
			<td colspan="2" id="footer">
				<? include_once(SITE_FDIR._SL.'templates'.SL.'side'.SL.'footer.php'); ?>
			</td>
		</tr>
	</table>
</body>
</html>
