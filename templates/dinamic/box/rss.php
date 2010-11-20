<script type="text/javascript">
	$(document).ready(function(){ 
		$("input.rss_type").change(function(){
			if ($("#"+this.id+":checked").length == 0) {
				$("#rss_link").attr('href',$("#rss_link").attr('href').replace(this.id,''));
			}
			else {
				$("#rss_link").attr('href',$("#rss_link").attr('href')+this.id);
			}
			$.post('/ajax.php?m=cookie&f=set&field=rss.default&val='+$("#rss_link").attr('href').split('=')[1]);			
		});
	});			
</script>

<input type="checkbox" id="p" class="rss_type"<?=(strpos($sets['rss']['default'],'p') !== false ? ' checked="checked"' : '');?>> - Записи <br />
<input type="checkbox" id="v" class="rss_type"<?=(strpos($sets['rss']['default'],'v') !== false ? ' checked="checked"' : '');?>> - Видео <br />
<input type="checkbox" id="a" class="rss_type"<?=(strpos($sets['rss']['default'],'a') !== false ? ' checked="checked"' : '');?>> - Арты (осторожно, возможны вайпы) <br />
<input type="checkbox" id="n" class="rss_type"<?=(strpos($sets['rss']['default'],'n') !== false ? ' checked="checked"' : '');?>> - Новости сайта <br />
<input type="checkbox" id="o" class="rss_type"<?=(strpos($sets['rss']['default'],'o') !== false ? ' checked="checked"' : '');?>> - Заказы <br />
<input type="checkbox" id="u" class="rss_type"<?=(strpos($sets['rss']['default'],'u') !== false ? ' checked="checked"' : '');?>> - Обновления записей <br />
<input type="checkbox" id="c" class="rss_type"<?=(strpos($sets['rss']['default'],'c') !== false ? ' checked="checked"' : '');?>> - Комментарии <br />
<?
if ($sets['user']['rights'])
{
	?>
	<input type="checkbox" id="m" class="rss_type"<?=(strpos($sets['rss']['default'],'m') !== false ? ' checked="checked"' : '');?>> - Очереди премодерации <br />
	<?
}
?>
<div class="center clear">
	<a href="/rss/=<?=$sets['rss']['default'];?>" id="rss_link">Подписаться на выбранное</a>
</div>
