Выберите запись, имеющую обновления:
<br /><br />
<ul>
	<? foreach ($data['main']['posts'] as $id => $post) { ?>
		<li>
			<a href="<?=$def['site']['dir']?>/admin/updates/<?=$id;?>/">
				<?=$post['title'];?>
			</a> 
			(<?=$post['update_count'];?>)
		</li>
	<? } ?>
</ul>
