<div id="popup_domination_tabs" class="tab-menu">
	<a class="icon feel selected" href="#look_and_feel">Look &amp; feel</a>
	<a class="icon fields" href="#template_fields">Content</a>
	<a class="icon list" href="#list_items">Bullet List</a>
	<a class="icon schedule" href="#schedule">Display Settings</a>
	<a class="icon email" href="#submissions">Mailing List</a>
	<?php if(empty($_GET['type']) || $_GET['type'] != "inline") { ?><a class="icon prev" href="#preview">Launch Preview<br>(save changes first)</a><?php } ?>
</div>
