<?php
/*
Pippity

Activate Page
*/
?>

<?php

global $wpdb;
echo '
<div class="wrap">
	<h2>Let us help you with Pippity!</h2>
	<div id="poststuff" class="metabox-holder has-right-sidebar">
		<div>
			<div id="side-sortables" class="meta-box-sortabless ui-sortable">
				<div class="postbox">
					<div>
						<h3 class="hndle">Pippity Support</h3>
						<div class="inside" id="pty_supportContent"> 
						<div style="width:600px; float:left;">
						<div class="pty_messageText">
							<p style="margin-top:10px">
							<strong> If you\'re having trouble with Pippity, we want to help you right now.</strong>
							</p>
							<p>
							All support requests should begin here, not from any email address or website. This will help us
							gather some basic data about your Pippity setup and get your problem resolved as quickly as possible. 
							</p>
						</div>
						<form class="pty_aForm" id="pty_supportForm">
						 <p>
						<label for="urgency">Urgency</label>
						<select name="urgency" id="pty_urgency" class="pty_supportLong">
							<option value="0">Just a heads up, reply when you get a chance.</option>
							<option value="1">Feeling frustrated, reply as soon as you can.</option>
							<option value="2">My world is falling apart! Help me now!</option>
						</select>
						</p>     
						<div>
						<p class="pty_supportCol1">
							<label for="fname">First Name</label>
							<input type="text" name="fname" class="pty_textInp" id="pty_fname" value="' . $fname . '"/>
						</p>
						<p class="pty_supportCol2">
							<label for="fname">Last Name</label>
							<input type="text" class="pty_textInp" name="lname" id="pty_lname" value="' . $lname . '"/>
						</p>
						</div>
						<div class="clear"></div>
						<p>
							<label for="email">Reply E-Mail Address</label>
							<input type="text" class="pty_textInp" name="email" id="pty_email" value="' . $email . '"/>
						</p>
					   
						<p>
						<label for="problem">What\'s your issue?</label>
						<textarea name="problem" id="pty_problem"i  class="pty_supportLong pty_textInp"></textarea>
						</p>
								<input type="submit" name="submit" id="pty_activateButton" class="button-primary" value="Submit"/>
							</form>   
							</div>
						</div>
						<div id="pty_supportFrameShell">
							<iframe src="http://pippity.com/support.php" id="pty_supportFrame"></iframe>
						</div>
						<div class="clear"></div>
						<br/>
					</div>
				</div>
			</div>
		</div>  
</div>
';       
