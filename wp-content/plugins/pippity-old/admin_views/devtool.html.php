<?php
/*
Pippity Theme Builder
*/
?>

<?php

echo '
<div class="wrap">';
if (!$needCreds) {
echo '
	<h2>Pippity Theme Tool</h2>
	<div id="poststuff" class="metabox-holder has-right-sidebar">
		<div>
			<div id="side-sortables" style="width:370px;float:right;" class="meta-box-sortabless ui-sortable">
				<div class="postbox">
					<div>
						<h3 class="hndle">Duplicate a Theme</h3>
						<div class="inside" id="pty_supportContent"> 
							<form class="pty_themeForm" method="post" action="' . PTY_ADM .'&pty_page=devtool">
							<label>Existing Theme</label>
							<select class="pty_existing" name="theme_existing">
								<option value="">Select a Theme</option>
								';
foreach ($themes as $file => $name) {
	echo '<option value="' . $file . '">' . $name . '</option>';
}
echo '
							</select>
							<div class="pty_fieldset">
								<label for="name">New Theme Name</label>
								<input type="text" class="theme_name textInp" name="theme_name"/>
							</div>
							<div class="pty_fieldset">
								<label for="name">New Author Name</label>
								<input type="text" class="theme_author textInp" name="theme_author"/>
							</div>
							<div class="pty_fieldset">
								<label for="name">New Short Description</label>
								<textarea type="text" class="theme_descr textInp" name="theme_descr"></textarea>
							</div>
								' . wp_nonce_field('pty-upload') . '
								<input type="submit" name="theme_submit" class="button-primary" value="Duplicate Theme"/>
							</form>
						</div>
					</div>
				</div>
			</div>
			<div id="side-sortables" style="margin-right:400px" class="meta-box-sortabless ui-sortable">
				<div class="postbox">
					<div>
						<h3 class="hndle">Create a Theme</h3>
						<div class="inside" id="pty_supportContent"> 
							<div class="pty_messageText">
								<p>
									This is a tool <strong>for developer\'s and designers</strong> to help configure a new theme. It will create
									a properly setup theme folder in <code>' . PTY_DIR . '/themes</code>.
								</p>
							</div>
							<form class="pty_themeForm" method="post" action="' . PTY_ADM .'&pty_page=devtool">
								<div class="pty_fieldset">
									<label for="name">Theme Name</label>
									<input type="text" class="theme_name textInp" name="theme_name"/>
								</div>
								<div class="pty_fieldset">
									<label for="name">Author Name</label>
									<input type="text" class="theme_author textInp" name="theme_author"/>
								</div>
								<div class="pty_fieldset">
									<label for="name">Short Description</label>
									<textarea type="text" class="theme_descr textInp" name="theme_descr"></textarea>
								</div>
								<div class="pty_fieldset">
									<label>Vertical Position</label>
									<input type="text" class="theme_y textInp" name="theme_y"/>
									<div class="pty_input_info">top, center, bottom, <code>num</code>px or <code>num</code>%</div>
								</div>
								<div class="pty_fieldset">
									<label>Horizontal Position</label>
									<input type="text" class="theme_x textInp" name="theme_x"/>
									<div class="pty_input_info">left, center, right, <code>num</code>px or <code>num</code>%</div>
								</div>
								<h4 class="pty_contentAreasHead">Content Areas</h4>
								<a href="#" class="pty_add_contentArea">Add Another</a>
								<div id="pty_contentAreasShell">
									<div id="pty_contentAreas">
										<div class="pty_contentArea">
											<label>Content Area Type</label>
											<select class="theme_content_type" name="theme_cont[type][]">
												<option value="input">Short Content</option>
												<option value="html">Long Content</option>
												<option value="image">Image</option>
											</select>
											<label>Content Area Label</label>
											<input type="text" name="theme_cont[label][]" class="theme_content_label textInp"/>
											<label class="pty_content_image">Recommended Dimensions</label>
											<input type="text" name="theme_cont[size][]" class="theme_content_dims pty_content_image textInp"/>
											<label class="pty_content_image">Default Image Source</label>
											<input type="text" name="theme_cont[src][]" class="theme_content_src pty_content_image textInp"/>
											<label class="pty_content_text">Default Content</label>
											<textarea type="text" class="pty_content_text theme_content_default textInp" name="theme_cont[default][]"></textarea>
										</div>
									</div>
								</div>
								<div class="clear"></div>
								' . wp_nonce_field('pty-upload') . '
								<input type="submit" name="theme_submit" class="button-primary" value="Setup Theme Folder & Configuration"/>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div> ';
}
echo '
</div>
';       
