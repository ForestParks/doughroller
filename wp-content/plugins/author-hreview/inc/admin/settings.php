<?php
	
	/**********************************************************************
    settings
    /**********************************************************************/
	
	// Prevent loading this file directly - Busted!
	if ( ! class_exists( 'WP' ) )
	{
		header( 'Status: 403 Forbidden' );
		header( 'HTTP/1.1 403 Forbidden' );
		exit;
	}
	
	$domain = WPAR_LK_CleanTheDomain(site_url());
	function wpar_settings_init() {
        register_setting('wpar_settings_license_group', 'wpar_license_option','wpar_sanitize_license_settings');
        register_setting('wpar_settings_group', 'wpar_options','wpar_sanitize_plugin_settings');
		register_setting('wpar_plugin_options', 'wpar_options', 'wpar_sanitize_validate_options');
    }
    add_action('admin_init', 'wpar_settings_init');
    add_action('init','wpar_check_active');

    function wpar_sanitize_license_settings($options){
        if(isset($options['key'])){            
			global $domain;
            $key = str_replace(" ", "", $options['key']); 
            $email = str_replace(" ", "", $options['email']);
            $args = array('body'=>array('key'=>$key,'email'=>$email,'domain'=>$domain));
            $response = wp_remote_post('http://authorhreview.com/?keycheck=yes',$args);
            $returnvalue = wp_remote_retrieve_header($response,'returnvalue');
            if(is_wp_error($response) || !$returnvalue){
                add_settings_error('validate','validate','Could not validate the plugin. Please check the values again');
                return $options;
            }
            $options['validation_value'] = $returnvalue;
        } 
        return $options;
    }
    
	function wpar_sanitize_plugin_settings($options){
        if(isset($_POST['reset'])){
            delete_option('wpar_license_option');
			delete_option('wpar_options');
        }
        return $options;
    }
    
    function wpar_add_admin_validation_page() {
        
		if (is_admin()) {
			add_menu_page(
							__( 'Author hReview' ),
							__( 'Author hReview' ),
							'administrator',
							'authorhreview',
							'wpar_admin_validation_page',
							plugin_dir_url( 'author-hreview/img/stars_admin.png' , __FILE__ ).'stars_admin.png',
							49
						);
						
			add_submenu_page('authorhreview',
							'Manage License',
							'Manage License',
							'manage_options',
							'authorhreview',
							'wpar_admin_validation_page'
						);
		
			add_submenu_page('authorhreview',
							__('Blog News'),
							__('Blog News'),
							'manage_options',
							'authorhreview-news',
							'wpar_display_news_page'
						);
		}
    }
   
    function wpar_add_plugin_admin_page(){
		
		if (is_admin()) {
			add_menu_page(__( 'Author hReview' ),
							__( 'Author hReview' ),
							'administrator',
							'authorhreview',
							'my_plugin_admin_page',
							plugin_dir_url( 'author-hreview/img/stars_admin.png' , __FILE__ ).'stars_admin.png',
							49
						);
			
			add_submenu_page('authorhreview',
							'Settings',
							'Settings',
							'manage_options',
							'authorhreview',
							'my_plugin_admin_page'
						);
			
			add_submenu_page('authorhreview',
							__('Review Posts'),
							__('Review Posts'),
							'manage_options',
							'authorhreview-list',
							'tt_render_list_page'
						);

			add_submenu_page('authorhreview',
							__('Blog News'),
							__('Blog News'),
							'manage_options',
							'authorhreview-news',
							'wpar_display_news_page'
						);
		}
    }

			
    function wpar_admin_validation_page() {
        
		echo '<div class="wrap">';
		wpar_screen_icon();
		echo '<h2>Author hReview Settings <span style="font-size:10px;">Ver '. WPAR_VER.'</span></h2>';
		echo '<p>Get more control over Google SERP.</p>';
		
		global $domain;
		
		$options = get_option('wpar_license_option');
        if(wpar_compare_validation()){
            echo 'thanks for activting';
        } else {
			?>
    <form method="post" action="options.php">
        <?php
            settings_fields('wpar_settings_license_group');
            settings_errors();
        ?>
        
        <table class="form-table">
		
        <tr>
			<th><label for="licensekey"><?php _e('API key', 'wp-licensekeys'); ?></label></th>
			<td>
            	<input id="wpar_license_option[key]" class="regular-text" type="text" name="wpar_license_option[key]" value="<?php echo $options["key"]; ?>">
            	<br>
				<span class="description"><?php _e('Do not have one? <a href="http://authorhreview.com/profile/" target="_blank">get it here</a>', 'wp-licensekeys'); ?></span>
			</td>
		</tr>
        
        <tr>
			<th><label for="licenseemail"><?php _e('Your email', 'wp-licensekeys'); ?></label></th>
			<td>
            	<input id="wpar_license_option[email]" class="regular-text" type="text" name="wpar_license_option[email]" value="<?php echo $options["email"]; ?>">
            	<br>
				<span class="description"><?php _e('Enter the email you used to register this plugin', 'wp-licensekeys'); ?></span>
			</td>
		</tr>
        
        <tr>
			<th><label for="licensedomain"><?php _e('Your Domain', 'wp-licensekeys'); ?></label></th>
			<td>
            	<input readonly="readonly" id="wpar_license_option[domain]" class="regular-text" type="text" name="wpar_license_option[domain]" value="<?php echo $domain ?>">
            	<br>
				<span class="description"><?php _e('You do not have to enter this, we got it!', 'wp-licensekeys'); ?></span>
			</td>
		</tr>
        
	</table>
        
 
        
        <br />
        <input type="submit" class="button-primary lk_key" value="Validate"> 
    </form> 
    
    
    <?php } ?>
        
	</div><!-- end of admin page wrap -->

<?php
    }
    

    function my_plugin_admin_page(){
		
		$options = get_option('wpar_options');
        echo '<div class="wrap">';
		wpar_screen_icon();
		echo '<h2>Author hReview Settings <span style="font-size:10px;">Ver '. WPAR_VER.'</span></h2>';
		echo '<p>Get more control over Google SERP.</p>';
		echo '<form method="post" action="options.php">';
       
	    settings_fields('wpar_settings_group');
        settings_errors();

		settings_fields('wpar_plugin_options');
		$options = get_option('wpar_options');
		
		echo '<div class="metabox-holder">';
		
		require_once ('settings_social.php');
		require_once ('settings_general.php');
		require_once ('settings_rating_box.php');
		require_once ('settings_technical.php');
		 
		echo '<div style="clear:both;"></div>';
		echo '</div>';
		echo '</div>';
		echo '<div style="clear:both;"></div>';

		
        echo '<p>The plugin is now active</p>';
        echo 'Remove the validation key and options here ';
        echo '<input type="submit" class="button" value="Reset" name="reset"/>';
        echo '</form>';
    }
	
    
    function wpar_check_active(){      
        if(wpar_compare_validation()){
			add_action( 'admin_menu',	'wpar_add_plugin_admin_page' ); 
			add_action( 'admin_menu',	'wpar_documentation_menu' ); 
			add_action( 'admin_menu',	'wpar_support_menu' );
	
			add_action( 'admin_print_styles-post-new.php',			'wpar_enqueue'		);
			add_action( 'admin_print_styles-post.php',				'wpar_enqueue'		);
			add_action( 'admin_menu',								'wpar_add_box'		);
			add_action( 'save_post',								'wpar_save_data'	);

        } else {
            add_action('admin_menu',	'wpar_add_admin_validation_page' );
            add_action('admin_notices'	,create_function(''," echo \"<div class='update-nag'>Please validate the <a href='".admin_url().'admin.php?page=authorhreview'."'>Author hReview</a> plugin!</div>\";"));

        }
    }
    
    function wpar_compare_validation(){
        $options = get_option('wpar_license_option');
        if(isset($options['validation_value'])){
            $register_email = str_replace(" ", "", $options['email']);
            $key = str_replace(" ", "", $options['key']);
			$domain = $options ['domain'];
            $validation_value = $options['validation_value'];
            $checkvalue = md5($register_email . $key . $domain);
            if($checkvalue == $validation_value){
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
	
	function WPAR_LK_CleanTheDomain($url) {
		$nowww = ereg_replace('www\.','',$url);
		$domain = parse_url($nowww);
		if(!empty($domain["host"])) {
			return $domain["host"];
     	}
		else {
			return $domain["path"];
		}
 
	}
	
	function wpar_add_defaults() {
		$tmp= get_option('wpar_options');
    	if((isset($tmp['wpar_chk_default_options_db'])=='1')||(!is_array($tmp))) {
			delete_option('wpar_options');
			$arr = array(	
							
							"wpar_chk_rating_home_display" => "1",
							"wpar_chk_rating_box_hide" => "",
							"wpar_chk_rating_after_post_display" => "1",
							"wpar_chk_rating_box_hide" => "",
							"wpar_drp_rating_box_style" => "default",
							"wpar_box_width" => "300",
							"wpar_drp_box_align" => "right",
							"wpar_drp_button_color" => "orange",
							"wpar_chk_more_snippet" => "",
							"wpar_chk_default_options_db" => ""
			);
			update_option('wpar_options', $arr);
		}
	}


	function wpar_sanitize_validate_options($input) {
		$input['wpar_box_width'] =  wp_filter_nohtml_kses($input['wpar_box_width']);
        	if(isset($_POST['reset'])){
				delete_option('wpar_license_option');
			}
		return $input;
	}


	function wpar_support_menu() {
    	global $submenu;
			$submenu['authorhreview'][504] = array(
													'Support',
													'administrator' ,
													'http://authorhreview.com/support/forums/plugins-support/author-hreview-plugin/'
											);
	}
	
	function wpar_documentation_menu() {
    	global $submenu;
			$submenu['authorhreview'][604] = array(
													'Documentation',
													'administrator' ,
													'http://authorhreview.com/docs/all/'
											);
	}
	
	add_action( 'admin_footer', 'wpar_add_target_blank_to_support_menu_item');
	
	function wpar_add_target_blank_to_support_menu_item() { ?>
    		<script type="text/javascript"> jQuery(document).ready(function($) {
				$('.wp-submenu-wrap li a[href*="authorhreview.com/support"]').attr('target', '_blank');
				$('.wp-submenu-wrap li a[href*="authorhreview.com/docs"]').attr('target', '_blank');});
			</script>
            <?php
	}

			
		function wpar_screen_icon () {
			echo '<div class="icon32 wpar_icon32" id=""></div>';
		}


	function wpar_admin_links() { ?>
	
        <div class="wpar_admin_link">
            <ul>
				<li><p>
                	<a href="http://wordpress.org/extend/plugins/author-hreview/" target="_blank"><p>Rate the plugin 5★ on WordPress.org</p></a></p>
				</li>
                <li class="wpar_blog"><p>
                	<a href="http://authorhreview.com/" title="Author hReview Plugin for WordPress" target="_blank"><p>Blog about it and link to the plugin site</p></a></p>
				</li>
                
                <li class="wpar_testimonials"><p>
                		<a href="http://authorhreview.com/about/send-testimonial/" title="Send a Testimonial" target="_blank">
                    		Send a Testimonial</a> (& probably get featured)
					</p>
				</li>
                
                <li class="wpar_affiliate"><p>
                		<a href="http://authorhreview.com/member/aff/aff" title="Affiliate Program" target="_blank">
                    		Get your affiliate link</a> (earn 50% commission)
					</p>
				</li>
			</ul>              
 		</div>
<?php
	}

