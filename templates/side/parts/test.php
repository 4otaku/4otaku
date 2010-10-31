<style>
ul#navmenu-v,
ul#navmenu-v li,
ul#navmenu-v ul {
  margin: 0;
  border: 0 none;
  padding: 0;
  width: 240px; 
  list-style: none;
}

ul#navmenu-v img {
  margin-top: 6px;
  float: left ;
  padding-right: 4px;
}

ul#navmenu-v li ul img {
  margin-top: 11px;
  margin-left: 5px;
  float: left ;
  padding-right: 4px;
}

ul#navmenu-v:after {
  clear: both;
  display: block;
  font: 1px/0px serif;
  content: ".";
  height: 0;
  visibility: hidden;
}

ul#navmenu-v li {
  float: left; 
  display: block !important; 
  display: inline; 
  position: relative;
  color: #42A3B9;
}
ul#navmenu-v li ul li {
border:none;
}
ul#navmenu-v a {
  padding: 0 6px;
  display: block;
  background: #FFFFFF;
  height: auto !important;
  height: 1%; 
}


ul#navmenu-v a:hover,
ul#navmenu-v li:hover a,
ul#navmenu-v li.iehover a {
  background: #FFFFFF;

}

/* 2nd Menu */
ul#navmenu-v li:hover li a,
ul#navmenu-v li.iehover li a {
  background: #E8F4F7;
  color: #42A3B9;
  margin-right: 5px;
  padding: 4px;
  border: #E7E7E7 1px solid;
}


ul#navmenu-v li:hover li a:hover,
ul#navmenu-v li:hover li:hover a,
ul#navmenu-v li.iehover li a:hover,
ul#navmenu-v li.iehover li.iehover a {
  background: #E8F4F7;
  color: #42A3B9;
  border:#E7E7E7 1px solid;
}



ul#navmenu-v ul,
ul#navmenu-v ul ul,
ul#navmenu-v ul ul ul {
  display: none;
  position: absolute;
  top: 0px;
  right: 236px;
}


ul#navmenu-v li:hover ul ul,
ul#navmenu-v li:hover ul ul ul,
ul#navmenu-v li.iehover ul ul,
ul#navmenu-v li.iehover ul ul ul {
  display: none;
}

ul#navmenu-v li:hover ul,
ul#navmenu-v ul li:hover ul,
ul#navmenu-v ul:hover ul,
ul#navmenu-v ul ul li:hover ul,
ul#navmenu-v ul ul:hover ul,
ul#navmenu-v li.iehover ul,
ul#navmenu-v ul.iehover ul,
ul#navmenu-v ul li.iehover ul,
ul#navmenu-v ul ul.iehover ul,
ul#navmenu-v ul ul li.iehover ul {
  display: block;
}
</style>

<form action="test_baka.php">
		<div class="cats">	
		<br />
		
		Категория: <select name="sCategory" method="post" class="right">
		<option value="none">Не выбрано</option>
	<? 
		foreach ($data['sidebar']['test2']['cats'] as $cat) {
	?>
			<option value="<?=$cat['alias'];?>"><?=$cat['name'];?></option>

	<?		
		}
	?>
	</select>
  
	<br /><br />
		<table align="center">
			<tr>
				<td>
					<a href="#" class="shift-switcher_test" rel="russian" title="<?=($sets['flag']['ru'] ? 'on' : 'off');?>">
						<img src="/images/ru.<?=($sets['flag']['ru'] ? 'png' : 'gif');?>">
					</a>
				</td>
				<td>
					<a href="#" class="shift-switcher_test" rel="english" title="<?=($sets['flag']['en'] ? 'on' : 'off');?>">
						<img src="/images/en.<?=($sets['flag']['en'] ? 'png' : 'gif');?>">
					</a>
				</td>
				<td>
					<a href="#" class="shift-switcher_test" rel="japanese" title="<?=($sets['flag']['jp'] ? 'on' : 'off');?>">
						<img src="/images/jp.<?=($sets['flag']['jp'] ? 'png' : 'gif');?>">
					</a>
				</td>
				<td>
					<a href="#" class="shift-switcher_test" rel="nolanguage" title="<?=($sets['flag']['no'] ? 'on' : 'off');?>">
						<img src="/images/no.<?=($sets['flag']['no'] ? 'png' : 'gif');?>">
					</a>
				</td>
			</tr>
		</table>
		
<? $abc = "<Теги>"?>
<ul id="navmenu-v"> 
<li> <h3 align="center"><?=$a;?></h3>
<ul>
<? 
	foreach ($data['sidebar']['test']['tags'] as $maintag) {
		?>
			<li><a class="baka" rel="abc" href="#?>"><?=$maintag['name'];?></a>
				<ul>
					<?  $i = 0;
						foreach ($maintag['lesser'] as $key => $tag) { $i++;
							?>
							<li><a class="baka" href="#" rel="$abc"><?=$data['sidebar']['test']['alias'][$key];?></a></li>
							<?
						if ($i>10) break;}
					?>
				</ul>
			</li>
		<?
	}
?>
</ul>
</li>
</ul>

<div class="center clear"><input type="button" value="Искать"></div>
<br />
</form>

