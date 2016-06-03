<?php
/*
 * Pippity Affiliate Page
 */
?>

<?php

global $wpdb, $pty;
$successMessage = '';
$updateMessage = '';
if ($updateSuccess) {
	$successMessage = '
	   <div id="pty_theme_upload_success">
	   		Affiliate Information Updated!
		</div>
	'; 
}
if (!get_option('pty_updatedThemes', false)) {
$updateMessage = '
	<div id="pty_updateThemesMsg">
	You\'re themes have to be updated before an affiliate link can be displayed.
	<form action="' . PTY_ADM . '&pty_page=upgrade" method="post">
		<input type="hidden" name="version" value="' . $pty->latest . '"/>
		' . wp_nonce_field('pty-upgrade') . '
		<input type="submit" class="button-primary" value="Update Themes"/>
	</form>
	</div>
';
}
echo '
<div class="wrap">';
echo '
	<h2>Become a Pippity Affiliate</h2>
	<div id="poststuff" class="metabox-holder has-right-sidebar">
		<div>
			<div id="side-sortables" class="meta-box-sortabless ui-sortable">
				<div class="postbox">
					<div>
						<h3 class="hndle">Become a Pippity Affiliate</h3>
						<div class="inside" id="pty_supportContent"> 
						<div style="width:600px">
							' . $updateMessage . $successMessage . '
							<div class="pty_messageText">
							<p>
								Others in your network will benefit from Pippity as much as you, so why not spread the word
								<strong>and make money while you do?</strong>
							</p>
							<p>
								With our affiliate program, you\'ll earn revenue for every sale you refer! <br/><br/><a class="pty_standoutLink" href="http://www.shareasale.com/shareasale.cfm?merchantID=33043" target="_blank">Click here for more details
								and to sign-up</a>
							</p>
							<p>
								Once you\'re an affiliate, just paste your affiliate below and hit save. A referral link will
								then be placed at the bottom of each of your popups.
							</p>
							</div>
							<form class="pty_aForm" action="' . PTY_ADM . '&pty_page=affiliate" method="post" enctype="multipart/form-data">
								<label>Link Text</label>
								<input type="text" name="pty_afftext" class="pty_textInp" value="' . get_option('pty_afftext', 'Powered by Pippity') . '"/>

								<label>Shareasale Affiliate Link</label>
								<input type="text" name="pty_afflink" class="pty_textInp" value="' . get_option('pty_afflink', '') . '"/>
								' . wp_nonce_field('pty-upload') . '
								<input type="submit" class="button-primary" name="pty_save_aff" value="Save Settings"/>
							</form>
							</div>
					</div>
				</div>
			</div>
		</div>  ';
echo '
</div>
';       
