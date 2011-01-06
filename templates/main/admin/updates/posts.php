Выберите запись, имеющую обновления:
<br /><br />
<ul>
	<? foreach ($data['main']['posts'] as $id => query::$post) { ?>
		<li>
			<a href="/admin/updates/<?=$id;?>/">
				<?=query::$post['title'];?>
			</a> 
			(<?=query::$post['update_count'];?>)
		</li>
	<? } ?>
</ul>
