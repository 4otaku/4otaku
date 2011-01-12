	<div class="left">
		2008-<?=date("Y");?> 4otaku.ru. <br />
		<noindex>E-mail для любых вопросов: <a href="mailto:admin@4otaku.ru" target="_blank">admin@4otaku.ru</a>.</noindex>
		<?
			define('LINKFEED_USER', '710b0d6fa7ec6448eca963128d748c348f259c44');
			require_once($_SERVER['DOCUMENT_ROOT'].'/'.LINKFEED_USER.'/linkfeed.php');
			$linkfeed = new LinkfeedClient();
			echo $linkfeed->return_links();
		?>
	</div>
	<div class="right">
		<div>
			<noindex><!--LiveInternet counter--><script type="text/javascript">document.write("<a href=\'http://www.liveinternet.ru/click\' target=_blank><img src=\'//counter.yadro.ru/hit?t14.14;r" + escape(document.referrer) + ((typeof(screen)=="undefined")?"":";s"+screen.width+"*"+screen.height+"*"+(screen.colorDepth?screen.colorDepth:screen.pixelDepth)) + ";u" + escape(document.URL) + ";" + Math.random() + "\' border=0 width=88 height=31 alt=\'\' title=\'LiveInternet: показано число просмотров за 24 часа, посетителей за 24 часа и за сегодня\'><\/a>")</script><!--/LiveInternet--></noindex>
		</div>
	</div>
