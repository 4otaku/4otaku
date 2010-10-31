<? include_once 'side/head.php'; ?>
<body>	
	<table width="100%">
		<tr>
			<td colspan="2" id="header">
				<? include_once('side/header.php'); ?>
			</td>
		</tr>
		<tr>
			<td valign="top" id="content">
				<div class="post">
					<? if (isset($output->side_modules['top'])) include_once('side/top.php'); ?>
					<? if (is_array($data['main']['display'])) foreach ($data['main']['display'] as $key => $part) include ('main/'.str_replace('_','/',$part).'.php'); ?>
				</div>
			</td>
			<? if (isset($output->side_modules['sidebar'])) { ?>
				<td valign="top" id="sidebar">
					<? include_once('side/sidebar.php'); ?>
				</td>
			<? } ?>
		</tr>
		<tr>
			<td colspan="2" id="footer">
				<? include_once('side/footer.php'); ?>
			</td>
		</tr>
	</table>
</body>
</html>
