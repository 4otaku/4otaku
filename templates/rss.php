<?='<?xml version="1.0"?>';?>

<rss version="2.0"
	xmlns:content="http://purl.org/rss/1.0/modules/content/"
	xmlns:wfw="http://wellformedweb.org/CommentAPI/"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	xmlns:atom="http://www.w3.org/2005/Atom"
	xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
	xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
>
	<channel>
		<title>
			4отаку. Материалы для отаку.
		</title>
		<atom:link href="http://<?=$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];?>" rel="self" type="application/rss+xml" />
		<link>
			http://<?=$_SERVER['SERVER_NAME'];?>/
		</link>
		<description>
			Материалы от отаку, для отаку и интересные отаку.
		</description>
		<lastBuildDate>
			<?=date('r',$data['main']['lastbuild']);?>
		</lastBuildDate>
		<sy:updatePeriod>
			hourly
		</sy:updatePeriod>
		<sy:updateFrequency>
			1
		</sy:updateFrequency>

		<?
			foreach ($data['main']['items'] as $key => $item) {
				?>
					<item>
						<title>
							<![CDATA[<?=$item['rss_title'];?>]]>
						</title>
						<link>
							<?=$item['rss_link'];?>
						</link>
						<guid isPermaLink="false">
							<?=$item['guid'];?>
						</guid>
						<comments>
							<?=$item['comments_link'];?>
						</comments>
						<pubDate>
							<?=date('r',ceil($key/1000));?>
						</pubDate>
						<description>
							<![CDATA[<?
								if ($item['type'] == 'post' || $item['type'] == 'update' ||
									$item['type'] == 'video' || $item['type'] == 'news') {

									twig_load_template('main/rss/' . $item['type'], array($item['type'] => $item));
								} else {
									include 'templates'.SL.'main'.SL.'single'.SL.$item['type'].'.php';
								}
							?>]]>
						</description>
					</item>
				<?
			}
		?>
	</channel>
</rss>
