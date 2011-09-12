<? include_once 'side'.SL.'head.php'; ?>
<body>
	<? include_once('side'.SL.'header.php'); ?>
	<table width="100%">
		<tr>
			<td valign="top" id="content">
				<div class="post">
					<? if (isset($output->side_modules['top'])) include_once('side'.SL.'top.php'); ?>
					<? if (is_array($data['main']['display'])) 
						foreach ($data['main']['display'] as $key => $part) 
							include ('main/'.str_replace('_',SL,$part).'.php'); 
					?>
				</div>
			</td>
			<? if (isset($output->side_modules['sidebar'])) { ?>
				<td valign="top" id="sidebar">
					<? include_once('side'.SL.'sidebar.php'); ?>
				</td>
			<? } ?>
		</tr>
		<tr>
			<td colspan="2" id="footer">
				<? include_once('side'.SL.'footer.php'); ?>
			</td>
		</tr>
	</table>
</body>
</html>
