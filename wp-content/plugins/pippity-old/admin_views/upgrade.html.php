<?php
/*
Pippity Upgrade Page
*/
?>

<?php

global $wpdb;
$html = '';
$params = '';
$jsredir = "
	<script type='text/javascript'>
		setTimeout(function(){location.href = '" . PTY_ADM . "'}, 500);
	</script>	
";
if ($success) {
	echo $jsredir;
}
else if ($error) {
echo '
<div class="wrap">';
echo '
	<h2>Upload Themes</h2>
	<div id="poststuff" class="metabox-holder has-right-sidebar">
		<div>
			<div id="side-sortables" class="meta-box-sortabless ui-sortable">
				<div class="postbox">
					<div>
						<h3 class="hndle">Upload Pippity Themes</h3>
						<div class="inside" id="pty_supportContent"> 
						<div style="width:600px">
							Oh darn, an error occured: ' . $error . '
							<p><strong>Upload a ZIP File of one or more themes below</strong></p>
							<form action="' . PTY_ADM . '-upload" method="post" enctype="multipart/form-data">
								<input type="file" name="themePack"/>
								<input type="hidden" name="uploadTheme" value="1"/>
								<input type="hidden" name="justUploaded" value="1"/>
								' . wp_nonce_field('pty-upload') . '
								<input type="submit" class="button-primary" value="Upload Theme Pack"/>
							</form>
							</div>
					</div>
				</div>
			</div>
		</div>  ';
echo '
</div>
';       
}
else if (!isset($_POST['version'])){
	echo $jsredir;
}
