<table>
	<tr>
		<td colspan="4">
			Вы собираетесь слить тег 
			<font color="#<?=$data['main']['tag']['color'];?>">
				<?=$data['main']['tag']['name'];?>
			</font> 
			(алиас <?=$data['main']['tag']['alias'];?>
			<?=(trim($data['main']['tag']['variants'],'|') ? ', варианты: '.str_replace('|',', ',trim($data['main']['tag']['variants'],'|')) : '');?>)
			<br /><br />
			С тегом который выберете в форме ниже:<br />
			(A =&gt; B - влить тег A в тег B, A &lt;= B - влить тег B в тег A)
		</td>
	</tr>
	<tr>
		<td colspan="4">
			<hr />
		</td>
	</tr>
	<tr>
		<td colspan="4">
			Искать теги: 
			<input type="text" value="" name="searchtags" class="searchtags" size="17"> 
			<input type="submit" value="Искать" class="disabled dinamic_search_tags">
			<br />
			<div id="dinamic_tags" width="100%">
			</div>
			<div id="tag_loader" class="hidden">
				<br /><br /><br />
				<center>
					<img src="/images/ajax-loader.gif">
				</center>
			</div>
		</td>
	</tr>
	<tr>
		<td colspan="4">
			<hr />
		</td>
	</tr>
	<tr class="hidden merge_field">
		<td class="slave_tag first_tag" rel="<?=$data['main']['tag']['id'];?>">
			<b><?=$data['main']['tag']['name'];?></b>
		</td>
		<td>
			<a href="#" class="dinamic_tag_change_direction">
				=&gt;
			</a>
		</td>
		<td class="master_tag second_tag">
			<b></b>
		</td>	
		<td>
			<input type="button" value="Слить" class="merge_tag">
		</td>		
	</tr>
</table>