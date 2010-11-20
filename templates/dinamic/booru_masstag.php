<a href="<?=SITE_DIR.'/images'?>/booru/<?=$data['id'];?>" class="with_help3" title="
	<? 
		foreach($data['tag'] as $key => $tag) echo ($key ? " " : "").$tag;
		echo ". Запостил: ".$data['pretty_author'][str_replace('|','',$data['author'])];
		echo ". Рейтинг: ".$data['pretty_rating'][$data['rating']].".";
	  ?>
">
	<img src="<?=SITE_DIR.'/images'?>/booru/thumbs/<?=$data['thumb'].'.'.$data['extension'];?>" id="img-<?=$data['id'];?>">
</a>
