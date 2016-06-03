<?php
/*
Pippity

Activate Page
*/
?>

<?php

global $wpdb;
$successMessage = '';
if ($uploadSuccess) {
	$successMessage = '
	   <div id="pty_theme_upload_success">
	   		Upload Successful! <a href="' . PTY_ADM . '-edit">Create a Popup</a>
		</div>
	'; 
}
if (count($errors)) {
	$successMessage = '
	   <div id="pty_theme_upload_failure">
	   ' . $errors[0] . '
		</div>
	'; 
}
echo '
<div class="wrap">';
if (!$needCreds) {
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
							' . $successMessage . '
							<p><strong>Upload a ZIP File of one or more themes below</strong></p>
							<form action="' . PTY_ADM . '-upload&pty_up=1" method="post" enctype="multipart/form-data">
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
}
echo '
</div>
';       
