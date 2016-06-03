<?php
/*
Pippity

Activate Page
*/
?>

<?php

echo '
<div class="wrap">
	<h2>Welcome to Pippity!</h2>
	<div id="poststuff" class="pty_activateBox metabox-holder has-right-sidebar">
		<div>
			<div id="side-sortables" class="meta-box-sortabless ui-sortable">
				<div class="postbox">
					<div>
						<h3 class="hndle">Pippity Sign-in</h3>
						<div class="inside"> 
						<p>Start by signing your blog in with Pippity. Just paste the key you received when you ordered Pippity below.</p>
						<p>This helps us provide the very best support if anything goes wrong.</p>
							<form id="pty_activateForm">
								<input type="text" name="pty_key" id="pty_key" class="pty_keyInp"/>
								<input type="submit" name="submit" id="pty_activateButton" class="button-primary" value="Activate!"/>
							</form>   
						</div>
					</div>
				</div>
			</div>
		</div>  
</div>
';       
