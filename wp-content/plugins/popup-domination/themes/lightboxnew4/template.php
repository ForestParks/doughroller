<?php if(isset($fields['video']) && !empty($fields['video'])): ?>
<script>
	$f("ipad", "<?php echo $this->plugin_url; ?>inc/flowplayer/flowplayer-3.2.7.swf").ipad();
</script>	
<?php endif; ?>
<div class="popup-dom-lightbox-wrapper" id="<?php echo $lightbox_id?>"<?php echo $delay_hide ?>>
	<div class="lightbox-overlay"></div>
	<div class="lightbox-main lightbox-color-<?php echo $color ?>">
		<a href="#" class="lightbox-close" id="<?php echo $lightbox_close_id?>"><span>Close</span></a>
		<div class="lightbox-main-content">
			<div class="lightbox-video">
				<?php 
					if(isset($fields['video-embed']) && !empty($fields['video-embed'])){
						echo $fields['video-embed'];
					}
					else if(isset($fields['video']) && !empty($fields['video'])){
						echo '<a href="'.$fields['video'].'" style="display:block;width:700px;height:330px" id="ipad"></a> ';
					}else{
						echo '<img src="'.$theme_url.'/images/video-panel-width.png" alt="video-panel" width="472" height="280" />';
					}
					?>
			</div>
			<div class="lightbox-top">
				<div class="lightbox-top-content cf">
					<p class="heading"><?php echo $fields['title'] ?></p>
					<p class="short-paragraph"><?php echo nl2br($fields['short_paragraph']) ?></p>
					<div class="bullet-listx">
	                    <ul class="bullet-list"><?php
	                        $count = 1;
	                        if(isset($list_items) && count($list_items) > 0):
	                            foreach($list_items as $l):
	                                if($count>4)
	                                    break;?>
	                        <li><?php echo $l ?></li><?php
	                                $count++;
	                            endforeach;
	                        endif;?>
	                    </ul>
						<div class="lightbox-clear"></div>
					</div>
				</div>
			</div>
			<div class="lightbox-clear"></div>
		</div>
		<?php if($provider != 'form' && $provider != 'nm'): ?>
			<div class="lightbox-bottom">
				<div class="lightbox-signup-panel">
					<div class="wait" style="display:none;"><img src="<?php echo $this->plugin_url.'css/images/wait.gif'; ?>" /></div>
		            <div class="form">
		                <div>
		                	<form id="removeme">
		                    	<?php echo $inputs['hidden'].$fstr; ?>
		                    	<input type="submit" value="<?php echo $fields['submit_button'] ?>" src="<?php echo $theme_url?>/images/trans.png" class="<?php echo $button_color?>-button" />
		                    </form>
		                </div>
		            </div>
				</div>
				<div class="lightbox-clear"></div>
			</div>
			<p class="secure"><img src="<?php echo $theme_url?>/images/lightbox-secure.png" alt="lightbox-secure" width="16" height="15" /> <?php echo $fields['footer_note'] ?></p>
		<?php else: ?>
			<div class="lightbox-bottom">
				<div class="lightbox-signup-panel">
		            <form method="post" action="<?php echo $form_action ?>"<?php echo $target ?>>
		                <div>
		                    <?php echo $inputs['hidden'].$fstr ?>
		                    <input type="submit" value="<?php echo $fields['submit_button'] ?>" src="<?php echo $theme_url?>images/trans.png" class="<?php echo $button_color?>-button" />
		                </div>
		            </form>	
	            </div>
	            <div class="lightbox-clear"></div>
			</div>
			<p class="secure"><img src="<?php echo $theme_url.'/images/lightbox-secure.png'; ?>" alt="lightbox-secure" width="16" height="15" />  <?php echo $fields['footer_note'] ?></p>
		<?php endif; ?>
		<div class="lightbox-clear"></div>
		<?php echo $promote_link ?>
	</div>
</div>