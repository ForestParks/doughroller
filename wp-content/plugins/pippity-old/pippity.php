<?php	
/*
Plugin Name: Pippity
Plugin URI: http://pippity.com
Description: Pippity is a popup plugin that your readers won't hate!
Version: 2.1.0.0
Author: Pippity LLC
Author URI: http://pippity.com
*/

global $wpdb;
define('PTY_DIR', WP_PLUGIN_DIR . '/pippity');
define('PTY_URL', WP_PLUGIN_URL . '/pippity');
define('PTY_FILE', 'pippity/pippity.php');
define('PTY_ADM', get_bloginfo('wpurl') . '/wp-admin/admin.php?page=pty');
$pty_upload_dir = wp_upload_dir();
define('PTY_TEMP', $pty_upload_dir['basedir'] . '/pty_temp');
require_once(PTY_DIR . '/pty_version.php');
require_once(PTY_DIR . '/Pty_Theme.class.php');
require_once(PTY_DIR . '/Pty_Filterer.class.php');
if(isset($_REQUEST['incoming_ajax'])){
	require_once(PTY_DIR . '/ajax.php');
}  

/*
 * Main Plugin Class
 */
if(!class_exists("Pty")){
	class Pty{
		function Pty()
		{
			$this->allImages = array();
			$this->variantImages = array();
			$this->allFonts = array();
			$this->allFamilies = array();
			$this->domain =  str_replace('https://', '', str_replace('http://', '', site_url()));
			$this->key = get_option('pty_key', false);
			$this->initLogging();
		}

		/*
		 * Load Popup Scripts
		 */
		public function loadDependencies()
		{
			global $wpdb;
			global $wysija_msg;
			define('PTY_WYSIJA', isset($wysija_msg));
			define('PTY_GRAVITY', defined('GF_SUPPORTED_WP_VERSION'));
			$script_url = WP_PLUGIN_URL;
			$chart_url = 'http://pippity.com/google_charts.php';
			if (isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) != 'off'  && strlen($_SERVER['HTTPS'])) {
				$script_url = str_replace('http://', 'https://', $script_url);
				$chart_url = str_replace('http://', 'https://', $chart_url);
			}
			wp_enqueue_script('jquery');
			if (is_admin()) {
				if (isset($_GET['page']) && strpos($_GET['page'], 'pty') === 0) {
					wp_enqueue_style('pty_admin_styles', $script_url . '/pippity/css/pippity_admin.css');
					wp_enqueue_style('pty_admin_gradients', $script_url . '/pippity/css/pippity_admin_gradients.css');
					if ($this->wpNewer('3.2.0')) {
						wp_enqueue_style('pty_admin_styles_3.2', $script_url .'/pippity/css/pippity_admin_3.2.css');
					}
					wp_enqueue_script('jquery');
					wp_enqueue_script( 'jquery-ui' );
					wp_enqueue_script('pty_datepicker', $script_url . '/pippity/js/ui.datepicker.js', array('jquery-ui-core'));
					wp_enqueue_script('google-charts-api', $chart_url);
					wp_enqueue_script('pty_admin', $script_url . '/pippity/js/pty_admin.js', array('jquery-ui-core'));
					wp_enqueue_script('pty_dpl', $script_url . '/pippity/js/dpl.js');
					wp_enqueue_script('pty_jscolor', $script_url . '/pippity/js/jscolor/jscolor.js'); 
					wp_enqueue_style('wp-pointer');
					wp_enqueue_script('wp-pointer');
					wp_enqueue_script('utils');
				}
			}
			else if (!isset($_GET['nopty'])){
					wp_enqueue_script('jquery');
					wp_enqueue_script('pty_popup-styles', $script_url . '/pippity/js/pippity.js', array('jquery'));
			}
		}
		public function loadGlobals()
		{
			$isPost = 'true';
			if (!is_single()) {
				$isPost = 'false';
			}
			$pty_url = PTY_URL;
			if (isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) != 'off'  && strlen($_SERVER['HTTPS'])) {
				$pty_url = str_replace('http://', 'https://', $pty_url);
			}
			$this->jsGlobals(array(
				'$j' => 'jQuery',
				"PTY_URL" => '"' . $pty_url . '"',
				"PTY_DOM" => '"' . str_replace('http://', '', get_bloginfo('wpurl')) . '"',  
				"PTY_AJAX" => '"' . get_bloginfo('wpurl') . '/wp-admin/admin-ajax.php' . '"',  
				"PTY_ADM" => '"' . get_bloginfo('wpurl') . '/wp-admin/admin.php?page=' . '"', 
				"PTY_PAGE" => '""', 
				"PTY_ISPOST" => $isPost,
				"PTY_KEY" => '"' . base64_encode($this->key . sha1($this->key.'oiernst')) . '"',
				"PTY_AFFLINK" => '"' . get_option("pty_afflink", '') . '"',
				"PTY_AFFTEXT" => '"' . get_option("pty_afftext", '') . '"',
				"PTY_NEWPOPUP" => '"' . ( isset($_GET['pty_n']) ? $_GET['pty_n'] : 'false') . '"',
			));
		}
		public function jsGlobals($array)
		{
			echo '<script type="text/javascript">
				pippity_globals = function(jQuery){
				';
			foreach ($array as $i => $v) {
				echo 'window.' . $i . ' = ' . $v . ';
				';
			}
			echo '
				}(jQuery)
			</script> ';
		}												
        public function scrollTrigger($content)
        {
			return $content . '<span id="pty_trigger"></span>';
        }												

		/*
		 * Admin
		 */
		public function addAdmin()
		{
			$this->checkInstall();
			add_thickbox();
			add_menu_page( 'Pippity', 'Pippity', 'edit_pages', 'pty', array(&$this, 'indexAdmin'), PTY_URL . '/images/icon.png',  null);             
			add_submenu_page('pty', 'Pippity | Create a Popup', 'Create Popup', 'edit_pages', 'pty-edit', array(&$this, 'editPage'));
			add_submenu_page('pty', 'Pippity Analytics', 'Analytics', 'edit_pages', 'pty-analytics', array(&$this, 'analyticsPage'));
			add_submenu_page('pty', 'Upload Themes', 'Upload Themes', 'edit_pages', 'pty-upload', array(&$this, 'uploadPage'));
			add_submenu_page('pty', 'Pippity | Support', 'Support', 'edit_pages', 'pty-support', array(&$this, 'supportPage'));
			if (strpos($_SERVER['REQUEST_URI'], 'plugins.php') !== false) {
				$this->pluginsPage();
			}
		}                
		
		/*
		 * Include an admin page's controller, view and other resources
		 */
		public function adminPage($name, $check = true)
		{
			$showPage = true;
			if ($check) {
				
				// Check in with Pippity, ignore if it doesn't respond
				$pty_rsp = $this->callHome();
				if (is_array($pty_rsp) || is_string($pty_rsp)) {

					// If no key or invalid, get their key
					if ( $pty_rsp == 'no-key' || $pty_rsp == 'invalid') {
						return $this->activatePage();
					}   


					// Check to see if it's time for an upgrade
					if (!$this->compareVersions($this->latest)) {
						$ignore = get_option("pty_ignoreVersion", "0.0.0.0");

						// Check to be sure we want this upgrade
						if (!$this->compareVersions($this->latest, $ignore)) {
							$this->showUpgradeBox($pty_rsp['latest']);
						}
					}
				}
			}
			if ( isset($_GET['pty_page']) ) {
				$name = $_GET['pty_page'];
			}
			if ($showPage) {
				include PTY_DIR . '/admin_controllers/' . $name . '.php';
				if (file_exists(PTY_DIR . '/css/' . $name . '.css')) {
					echo '<link rel="stylesheet" href="'. PTY_URL . '/css/' . $name . '.css"/>';
				}
				if (file_exists(PTY_DIR . '/js/' . $name . '.js')) {
					echo '<script type="text/javascript" src="' . PTY_URL . '/js/' . $name . '.js"></script>';
				}
				echo '<div id="PTY" dir="ltr">';
					include PTY_DIR . '/admin_views/' . $name . '.html.php'; 
					$this->footer($name);
				echo '</div>';
			}
		}	                                            
		public function indexAdmin()
		{
			$this->cleanPtyTempDir();
			if ($this->logging) {
				global $wpdb;
				$this->log('Test Prefix', array("smpTable" => $this->t('popups')));
				$this->log('Desc. Pop. Table', array("PopTable" => $wpdb->get_results("DESCRIBE " . $this->t('popups'))));
				$this->log('Desc. Imp. Table', array("ImpTable" => $wpdb->get_results("DESCRIBE " . $this->t('imps'))));
				$this->log('Control Array', array("arraysample" => array("hi", "there", 'friend')));
			}
			$this->adminPage('index');	
		} 
		public function editPage()
		{
			$this->cleanPtyTempDir();
			$this->adminPage('edit');
		}
		public function analyticsPage()
		{
			$this->cleanPtyTempDir();
			$this->adminPage('analytics');
		}												
		public function uploadPage()
		{
			$this->adminPage('upload');
		}												
		public function activatePage()
		{
			$this->adminPage('activate', false); 
		}												
		public function devtoolPage()
		{
			$this->cleanPtyTempDir();
			$this->adminPage('devtool');
		}
		public function footer($page)
		{
			$noFooter = array("activate");
			if (in_array($page, $noFooter)) {
				return true;
			}
			$license = 'Thanks for using Pippity, you\'re awesome!';
			if (!$this->key) {
				$license = 'Your license is out of date so you\'re missing out on support and updates. <a href="http://pippity.com/pricing">Click here to change that!</a>';

			}
			echo '
				<div id="pty_footer" class="pty_footerBox metabox-holder">
					<div>
						<div id="side-sortables" class="meta-box-sortabless ui-sortable">
							<div class="postbox">
								<div>
									<div class="inside">
									<div id="pty_right_footer">
										<ul>
											<li><a href="' . PTY_ADM . '&pty_page=affiliate">Make $ With Pippity</a></li>
											<li><a href="' . PTY_ADM . '&pty_page=devtool">DevTool</a></li>
										</ul>
									</div>
									' . $license . ' | You\'re on version ' . preg_replace('/\.0$/', '', $this->version) . '
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			';
		}	
		public function supportPage()
		{
			$this->adminPage('support');
		}												
		public function pluginsPage()
		{
			// Check in with Pippity, ignore if it doesn't respond
			$pty_rsp = $this->callHome();
			if (is_array($pty_rsp) || is_string($pty_rsp)) {

				// If no key or invalid, ignore
				if ( $pty_rsp == 'no-key' || $pty_rsp == 'invalid') {
					return false;
				}   

				// Check to see if it's time for an upgrade
				if (!$this->compareVersions($this->latest)) {
					$ignore = get_option("pty_ignoreVersion", "0.0.0.0");
					// Check to be sure we want this upgrade
					if (!$this->compareVersions($this->latest, $ignore)) {
						add_action( "after_plugin_row_" . PTY_FILE, array(&$this, 'pluginUpdateRow'), 10, 2 );
					}
				}
			}
		}
		public function pluginUpdateRow($file, $plugin_data)
		{
			$r = new stdClass();
			$r->new_version = $this->latest;
			$plugins_allowedtags = array('a' => array('href' => array(),'title' => array()),'abbr' => array('title' => array()),'acronym' => array('title' => array()),'code' => array(),'em' => array(),'strong' => array());
			$plugin_name = wp_kses( $plugin_data['Name'], $plugins_allowedtags );
			$wp_list_table = _get_list_table('WP_Plugins_List_Table');
			$rsp = $this->fullRsp;
			if ( is_network_admin() || !is_multisite() ) {
				echo '<tr class="plugin-update-tr"><td colspan="' . $wp_list_table->get_column_count() . '" class="plugin-update colspanchange"><div class="update-message">';

				if ( ! current_user_can('update_plugins') )
					printf( __('There is a new version of %1$s available. <a href="%2$s" class="" title="%3$s">View version %4$s details</a>.'), $plugin_name, esc_url($details_url), esc_attr($plugin_name), $r->new_version );
				else
					printf( __('There is a new version of %1$s available. <a href="%2$s" class="" title="%3$s">View version %4$s details</a> or <a href="%5$s">update automatically</a>.'), $plugin_name, 'http://pippity.com/changelog', esc_attr($plugin_name), $r->new_version, wp_nonce_url( self_admin_url('admin.php?page=pty&pty_page=upgrade&version=' . $r->new_version) , 'pty-upgrade') );


				$changes =  json_decode($rsp->latest);
				$changes = strlen($changes->changes) ? (object)json_decode($changes->changes) : array();
				if (is_object($changes) && count($changes)) {
					echo '<div style="color: #c10000; margin:6px 0 -6px 6px;">Here\'s what\'s new:</div>';
					echo '<ul style="list-style: disc; margin-left: 20px; font-weight:normal;">';
					foreach ($changes as $i=> $change) {
						$bold = (isset($change->show) && $change->show) ? 'font-weight: bold; ' : '';
						echo '<li style="width: 300px; margin: 0; ' . $bold . ' float: left; ' . ($i % 2 == 0 ? 'clear: left;' : '') . '">' . $change->text. '</li>';
					}
					echo '</ul><div style="clear: left; margin-bottom:10px;"></div>';
				}
				
				echo '</div></td></tr>';
			}
		}
		public function wmodeFix($content)
		{
			$offset = 0;
			while ($offset < strlen($content)) {
				$offset = strpos($content, '<iframe', $offset);
				if ($offset !== false) {
					$endOffset = strpos($content, '>', $offset);
					$iframe = substr($content, $offset, $endOffset - $offset);
					$bits = explode('src=', $iframe);
					$bits = explode(' ', $bits[1]);
					$src = trim($bits[0], '"' . "'");
					if (strpos($src, 'youtube.') !== false) {
						if (strpos($src, '?') !== false) {
							$pre = '&';
						}
						else {
							$pre = '?';
						}
						$content = str_replace($src, $src . $pre . 'wmode=true', $content);
					}
					$m = $iframe;
					$offset = $endOffset;
				}
				else {
					$offset = strlen($content) + 1;
				}
			}
			return $content;
			
		}
		public function showUpgradeBox($latest)
		{
			if (isset($_GET['pty_page']) && $_GET['pty_page'] == 'upgrade') {
				return false;
			}
			$notable = array();
			$numNotable = 0;
			$numMinor = 0;
			$latest = json_decode($latest);
			if (!is_object($latest)) {
				return false;
			}
			$changes = json_decode($latest->changes);
			$changes = is_array($changes) ? $changes : array();
			$head = "<h2><strong>Version <span id='pty_newVersion'>" . rtrim($latest->version, '.0') . "</span> of Pippity is now available! (You're on version " . rtrim(PTY_VERSION, '.0') . ")</strong></h2>";
			$descr = '';
			$changeList = "<div class='pty_notableChanges'><h4>Notable Changes";
			if ($latest->url) {
				$changeList .= "<a href='{$latest->url}' target='_blank'>Even More Info<span> ➦</span></a>";
			}
			$changeList .= "</h4><ul>";
			foreach ($changes as $c) {
				if ($c->show) {
					$notable[] = $c;
					$numNotable++;
					$changeList .= "<li>{$c->text}</li>";
				}
				else {													
					$numMinor++;
				}
			}
			$changeList .= "</ul></div>";
			$descr .= 'There ';
			if ($numNotable === 1) {
				$descr .= 'is ' . $numNotable . ' notable change ';
			}
			else {													
				$descr .= 'are ' . $numNotable . ' notable changes ';
			}
			if ($numMinor > 0) {
				if ($numMinor === 1) {
					$descr .= " and 1 minor change";
				}
				else {													
					$descr .= " and $numMinor changes";
				}
			}
			$descr .= ' &nbsp;<input id="pty_moreChangeInfo" type="submit" class="button-secondary" value="More Info"/>';
			if(count($changes)) {
				$changesButton = '';
			}
			$impMsg = $latest->msg ? '<div id="pty_importantMessage">' . $latest->msg . '</div>' : ''; 
			echo '
				<div id="pty_updateBox" class="pty_updateBox metabox-holder has-right-sidebar">
					<div>
						<div id="side-sortables" class="meta-box-sortabless ui-sortable">
							<div class="postbox">
								<div>
								<h3 class="hndle">Pippity Upgrade Available!</h3>
								<div class="inside">
									' . $head . '
									' . $impMsg . '
									<p id="pty_numNotableMsg">' . $descr . '</p>
									' . $changeList . '
									<form action="' . PTY_ADM . '&pty_page=upgrade" method="post">
										<input type="hidden" name="version" value="' . $this->latest . '"/>
										' . wp_nonce_field('pty-upgrade') . '
										<input type="submit" class="button-primary" value="Easy Upgrade Now"/>
									</form>
									<form action="http://pippity.com/d/from/' . $this->key . '/' . $this->secret . '/' . $this->latest . '" method="post">
										<input type="submit" class="button-secondary" value="Download Upgrade"/>
									</form>
									';
			
			echo '
									<input id="pty_ignoreUpdate" type="submit" class="button-secondary" value="Ignore This Version"/>
								</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			';
		}												
													
		/*
		 * Analytics
		 */
		public function updateAnalytics()
		{
			global $wpdb;
			// Check if new stats need processing
			$imps = $this->t( 'imps' );
			$stats = $this->t( 'stats' );
			$daily = $this->t( 'daily' );

			// Delete any impressions that are somehow marked as started after NOW
			$wpdb->query("DELETE FROM $imps WHERE startTime > (select UNIX_TIMESTAMP(CONCAT(CURDATE(), ' 23:59:59')))");

			// Delete stats figured out today and any that are somehow after NOW
			$wpdb->query("DELETE FROM $stats WHERE ts >= (select UNIX_TIMESTAMP(CURDATE()))");
			$lowEnd = "(select IF(max(ts),max(ts)+86400,1) from $stats)";
			$q = "SELECT count(*) as n FROM {$imps} where startTime > $lowEnd";
			$r = $wpdb->get_results( $q );
			if ( $r[0]->n ) {
				$wpdb->query( "DROP TABLE $daily" );
				$wpdb->query( "
						CREATE TABLE $daily 
							SELECT 
								# timestamp, popupid, closeTime, blurTime, convertTime, leaveTime
								# ts         id       ct         bt        vt           lt
								@ts:=(UNIX_TIMESTAMP(FROM_UNIXTIME(startTime, '%Y-%m-%d 00:00:00'))) AS ts, # Level to just date
								popupid as id, closeTime as ct, 
								blurTime as bt, 
								IF(convertTime=0,null,convertTime) as vt, # null if not converted
								leaveTime as lt 
							FROM $imps 
							WHERE startTime > $lowEnd
							ORDER BY popupid,ts
						" );
				
				$wpdb->query( "
				INSERT INTO $stats
					# --------------------------------------------------------->
					SELECT # Level 1
						cts AS ts, # timestamps
						cid AS popupid, # popupid
						imps, # number of impressions
						convs, #number of conversions
						onpopup,
						onpage,
						convertdelay

						# From 3 subqueries: tt1, tt2, tt3
						FROM (
							# ------------------------------------------------>>
							# tt1 popupid = cid
							#
							# popupid, popup timestamps, avg on popup, impressions
							# cid      cts               onpopup       imps
							SELECT # 1.0
								t1.id  AS cid, 
								t1.ts  AS cts,
								AVG(x) AS onpopup,
								t2.al  AS imps
								
								# From 2 subqueries: t1, t2
								FROM (
									# --------------------------------------->>>	
									# closetime, timestamp, popupid
									# x			 ts			id
									SELECT  # 1.1
										IF(@cid = id, IF( @cts = ts, @n := @n + 1, @n :=0), @n := 0) AS c, # Popup count ?
										ct                                   AS x, # Closetime
										@cts := ts                           AS ts, # Timestamp
										@cid := id							 AS id # Popupid
										FROM $daily,
										   (SELECT # Level 4
												@n := 0,
												@cts := 0,
												@cid := 0
											) tc
										ORDER BY 
											id,
											ts,
											x
									) AS t1,

									# --------------------------------------->>>
									# This gets the count of popups
									# popupid, timestamp, count
									# id	   ts		  al
									(SELECT # 1.2
										id, 
										ts,
										COUNT(ct) AS al
										FROM $daily 
										GROUP BY 
											id,
											ts
									) AS t2
								WHERE  
									# Match Results of t1 and t2 by id
									t1.id = t2.id
									
									# Match results of t1 and t2 by timestamp
									AND t1.ts = t2.ts
								GROUP BY 
									t1.id,
									t1.ts
							) AS tt1,
							(

							# tt2 - popuid = lid
							# ------------------------------------------------>>
							SELECT # Level 2
								t1.id AS lid,
								t1.ts  AS lts,
								AVG(x) AS onpage
								FROM (
									# --------------------------------------->>>	
									SELECT # Level 3
										IF(@lid = id, IF( @lts = ts, @n := @n + 1, @n :=0), @n := 0) AS c,
										lt                                   x,
										@lts := ts                           AS ts,
										@lid := id							AS id
										FROM 
											$daily,
											(SELECT # Level 4
												@n := 0,
												@lid := 0,
											   @lts := 0
											) tc
										ORDER BY 
											id,
											ts,
											x
									) t1,
									# --------------------------------------->>>	
								   (SELECT # Level 3
										id,
										ts,
										COUNT(lt) AS al
										FROM
											$daily 
										GROUP BY
											id,
											ts
									) t2
								WHERE  
									t1.id = t2.id
									AND t1.ts = t2.ts
								GROUP  BY 
									t1.id,
									t1.ts
							) AS tt2,
						
							# ------------------------------------------------>>
							# tt1 popupid = cid
							#tt3 - popupid = vid
						   (SELECT # Level 2
								ts                                          AS vts,
								id                                          AS vid,
								COUNT(vt)                                   AS convs,
								( SUM(vt) - MAX(vt) + MIN(vt) ) / COUNT(vt) AS convertdelay
								FROM   
									$daily
								GROUP  BY 
									id,
									ts
								ORDER  BY 
									id,
									ts,
									vt DESC
							) AS tt3
							WHERE 
								tt1.cid = tt2.lid 
								AND tt1.cid = tt3.vid 
						" );
//					$wpdb->query( "DROP TABLE $daily" );
			}
		}

		/*
		 * Utilities
		 */
		public function getInstalledTemplates($theme_name = false)
		{
			$themes = array();
			chdir(WP_PLUGIN_DIR . '/pippity/themes/');
			foreach(scandir('./') as $theme){
				$theme = $theme;
				$hasConf = false; 
				$hasHtml = false;
				if (!$theme_name || $theme_name == $theme) {
					if (is_dir($theme) && $theme != '.' && $theme != '..') {
						$json = $this->themeFileToJson($theme);
						if ($json) {
							$themes[$theme] = $json;
						}
					}        
				}
			}
			return $themes;
		}
		public function themeFileToJson($theme, $isVariant = false)
		{
			$hasConf = false;
			$hasHtml = false;
			foreach (scandir($theme) as $file) {
				if(strpos($file, '.html') !== false){
					$hasHtml = true;
				}
				if($file === "$theme.conf"){
					$hasConf = true;
					$f = fopen($theme . '/' . $file, 'r');
					$json = fread($f, filesize($theme . '/' . $file));
					$json = json_decode('{' . $json . '}');  
					fclose($f);
				}
			}
			if ($hasConf && $hasHtml || $isVariant) {
				return $json;
			}
			else {													
				return false;
			}
		}												
		public function callHome()
		{
			$key = $this->key;
			if (!$key) {
				return 'no-key';
			}
			$domain = $this->domain;
			$url = "http://pippity.com/license/check";
			$releaseLim = isset($_GET['pty_rl']) ? $_GET['pty_rl'] : get_option('pty_releaseLim', 'stable');
			$data = array(
				'domain' => $domain, 
				'key' => $key,
				'releaseLim' => $releaseLim,
				'version' => PTY_VERSION
			);
			$req = new WP_Http;
			$rsp = $req->request($url, array( 'method' => 'POST', 'body' => $data, 'timeout' => 10));
			if (!is_wp_error($rsp)) {
				$rsp = $rsp['body'];
				if ($rsp != 'invalid') {
					$rsp = @unserialize($rsp);
					if ($rsp) {

						// Set latest version 
						$this->user = $rsp['user'];
						$this->latest = json_decode( $rsp['latest'] )->version;
						$this->secret = $rsp['secret'];
						$this->fullRsp = (object)$rsp;
						return $rsp;
					}
					else {													
						$this->key = $key;
						return true;
					}
				}
				else{
					return false;
				}    	
			}
			else {													
				return false;
			}
		}												
		public function compareVersions($latest, $existing = false)
		{
			$existing = $existing ? $existing : PTY_VERSION;
			$l = explode('.', $latest);		// Latest
			$e = explode('.', $existing);	// Existing
			for ($i = 0; $i < 4; $i++) {
				$l[$i] = isset($l[$i]) ? $l[$i] : 0;
				$e[$i] = isset($e[$i]) ? $e[$i] : 0;
				if ($l[$i] != $e[$i]) {
					return $l[$i] < $e[$i];
				}
			}
			return true;
		}												
		public function makeSlug($name, $space = '-')
		{
			$name = strtolower($name);
			$bits = explode(' ', $name);
			foreach($bits as $i => $v){
				$bits[$i] = preg_replace("/[^0-9a-zA-Z]/i", '', $v);
			}
			$slug = implode($space, $bits);	
			return $slug;
		}
		public function t($name)
		{
			global $wpdb;
			return $wpdb->prefix . "pty_" . $name;
		}												
		public function ifExists($i, $v, $by = false)
		{
	if ($by == 's') {
		return strlen($i) ? $i : $v;
	}
	else if ($by == 'c') {
		return count($i) ? $i : $v;
	}
	else {													
		return ($i) ? $i : $v;
	}
}												
		public function datetimeToEpoch($str) 
		{
			list($date, $time) = explode(' ', $str);
			list($year, $month, $day) = explode('-', $date);
			list($hour, $minute, $second) = explode(':', $time);
			$timestamp = mktime($hour, $minute, $second, $month, $day, $year);
			return $timestamp;
		}
		public function elmRem($elm)
		{
			$elm->parentNode->removeChild($elm);
		}
		public function elmByC(&$dom, $classname)
		{
			$xpath = new DOMXPath($dom);
			$path = $xpath->query("//*[ contains( normalize-space( @class ), ' $classname ' ) or substring( normalize-space( @class ), 1, string-length( '$classname' ) + 1 ) = '$classname ' or substring( normalize-space( @class ), string-length( @class ) - string-length( '$classname' ) ) = ' $classname' or @class = '$classname' ]");
			return $path;
		}
		public function copydir( $source, $target ) 
		{
			if ( is_dir( $source ) ) {
				@mkdir( $target );
				$d = dir( $source );
				while ( FALSE !== ( $entry = $d->read() ) ) {
					if ( $entry == '.' || $entry == '..' ) {
						continue;
					}
					$Entry = $source . '/' . $entry; 
					if ( is_dir( $Entry ) ) {
						$this->copydir( $Entry, $target . '/' . $entry );
						continue;
					}
					copy( $Entry, $target . '/' . $entry );
				}

				$d->close();
			}else {
				copy( $source, $target );
			}
		} 
		public function movedir($src,$dst, &$fs, $overwrite, $copy = false) 
		{ 
			global $pty;
			$fs->mkdir($dst); 
			foreach (scandir($src) as $file) {
				if (( $file != '.' ) && ( $file != '..' )) { 
					if ( $fs->is_dir($src . '/' . $file) ) { 
					   $pty->movedir($src . '/' . $file, $dst . '/' . $file, $fs, $overwrite, $copy); 
					} 
					else { 
						if ($copy) {
							$fs->copy($src . '/' . $file, $dst . '/' . $file, $overwrite); 
						}
						else {
							$fs->move($src . '/' . $file, $dst . '/' . $file, $overwrite); 
						}
					} 
				} 
			}
		} 
		public function rrmdir($dir) 
		{
			if (is_dir($dir)) {
				$objects = scandir($dir);
				foreach ($objects as $object) {
					if ($object != "." && $object != "..") {
						if (filetype($dir . "/" . $object) == "dir")
							$this->rrmdir($dir . "/" . $object); else
							unlink($dir . "/" . $object);
					}
				}
				reset($objects);
				rmdir($dir);
			}
		}
		public function currentUrl()
		{
			if (!isset($_SERVER['REQUEST_URI'])) {
				$serverrequri = $_SERVER['PHP_SELF'];
			} 
			else {
				$serverrequri = $_SERVER['REQUEST_URI'];
			}
			$s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
			$protocol = strtolower($_SERVER["SERVER_PROTOCOL"]) . $s;
			$protocol = substr($protocol, 0, strpos($protocol, '/'));
			$port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":" . $_SERVER["SERVER_PORT"]);
			return $protocol . "://" . $_SERVER['SERVER_NAME'] . $port . $serverrequri;
		}
		public function cleanPtyTempDir()
		{
			if (file_exists(PTY_TEMP)) {
				$this->rrmdir(PTY_TEMP);
			}
		}
		public function setReleaseLim($release)
		{
    		update_option('pty_releaseLim', $release);
		}
		public function wpNewer($v)
		{
			global $wp_version;
			$newer = true;
			$e = explode('.', $wp_version);
			$v = explode('.', $v);
			foreach ($e as $i => $eV) {
				if ($eV > $v[$i]) {
					$newer = true;
					break;
				}
				else if ( $eV < $v[$i]) {
					$newer = false;
					break;
				}
			}
			return $newer;
		}

		/*
		 * Logging
		 */
		public function initLogging()
		{
			global $wpdb;
			$this->logging = time() - get_option('pty_logging', '0') < 3600;
			$this->logid = time();
		}
		public function log($step, $pkg = array(), $traceShift = 0)
		{
			if ($this->logging) {
				$url = "http://pippity.com/logger";
				$post = new WP_Http;
				if (!is_array($pkg)) {
					$pkg = array($pkg);
				}

				// Add a trace including the function, params, etc
				// $traceShift let's helper functions shift back in the trace
				$trace = debug_backtrace(false); 
				$trace = (isset($trace[1 + $traceShift]))?$trace[1 + $traceShift]:$trace[0 + $traceShift]; 
				$pkg['trace'] = $trace;

				// The log contents
				$log = array(
					"logid" => $this->logid,
					"step" => $step, 
					"pkg" => json_encode($pkg),
					"key" => $this->key,
					"request" => $this->currentUrl(),
					"domain" => $this->domain,
				);
				$rsp = $post->request($url, array('method' => 'POST', 'body' => $log, "blocking" => false));
			}
		}
		public function logSqlErr($step) {
			$err = mysql_error();
			if (strlen($err)) {
				$this->log($step, $err, 1);
			}
			else {
				$this->log($step, 'Good', 1);
			}
		}
		
		
		/*
		 * Import
		 */
		public function checkImport()
		{
			if (file_exists(WP_PLUGIN_DIR . '/popup-domination')) {
				$param = isset($_GET['import']) ? $_GET['import'] : '';
				if ($param == 'later') {
					update_option('pty_import_later', time());
				}
				else if ($param == 'go' && !file_exists(PTY_DIR . '/themes/pd_1')) {
				    $this->doImport();
				}
				else if (!file_exists(PTY_DIR . '/themes/pd_1') && time() - get_option('pty_import_later', '0') > 604800) {
					echo '
						<div id="pty_importBox" class="pty_updateBox metabox-holder has-right-sidebar">
								<div id="side-sortables" class="meta-box-sortabless ui-sortable">
									<div class="postbox">
										<h3 class="hndle">Import from Popup Domination?</h3>
										<div class="inside">
											<p>Pippity can import your Popup Domination themes and popup! Shall we?</p>
											<form action="' . PTY_ADM . '&import=go" method="post">
												<input type="submit" class="button-primary" value="Import Now"/>
											</form>
											<form action="' . PTY_ADM . '&import=later" method="post">
												<input type="submit" class="button-secondary" value="Some Other Time"/>
											</form>
										</div>
									</div>
								</div>
						</div>
					';
				}	
			}            
		}												
		public function doImport()
		{
			$this->pddir = WP_PLUGIN_DIR . '/popup-domination/themes/';
			$this->ppdir = PTY_DIR . '/themes/'; 
			for ($i = 1; $i < 8; $i++) {
				$fileNum = $i === 1 ? '' : ($i + 1); 
				$file = 'lightbox' . $fileNum;
				$newFile = 'pd_' . $i;
				$pdconf = array();
				$this->importConf($file, $newFile, $i, $pdconf);
				$this->importCss($file, $newFile, $pdconf);
				$this->importHtml($file, $newFile, $pdconf);
				$this->importImages($file, $newFile);
			}                                    
			$this->importPopup();
		}												
		public function importConf($file, $newFile, $id, &$pdconf)
		{
			mkdir($this->ppdir . $newFile);
			$pdconf = $this->parseImportedConf(file_get_contents($this->pddir . $file . '/theme.txt'));
			$pdtexts = array(
				"title" => "Are you enjoying this article?",
				"short_paragraph" => ($newFile == 'pd_6' ? 'We are the best in the business.' : "Then you may also be interested in my 6 ways to Change the World series. It's absolutely free. Here's what you get:"),
				"bullet" => "<ul><li class='pty_bullet'>10 Lions</li><li class='pty_bullet'>5 Tigers</li><li class='pty_bullet'>And 2 Bears, Oh my!</li></ul>", 
				"short_paragraph2" => "We are the best in the business.",
				"form_header" => "Don't hesitate any longer!",
				"footer_note" => "We care about your privacy."
			);	
			$conf = '                           
"name"			: "PopDom ' . $id . '",
"file"			: "' . $newFile . '",
"author"		: "Popup Domination", 
"descr"			: "A popup imported from Popup Domination",
"copy"			: {';
$copy = '';
foreach ($pdconf['fields'] as $i => $f) {
	if (strpos($i, '_default') === false && strpos($i, '_image') === false && strpos($i, 'submit') === false) {
		$type = 'input';
		if (isset($f['type']) && $f['type'] == 'textarea') {
			$type = 'html';
		}
		$copy .= '
			"' . $f['id'] . '" : {
				"label" : "' . $f['label'] . '",
				"text" : "' . $pdtexts[$f['id']] . '",
				"type" : "' . $type . '"
			},';
	}
}       
		$copy .= '
			"bullet" : {
				"label" : "Bullet List",
				"text" : "' . $pdtexts['bullet'] . '",
				"type" : "html"
			},'; 
		if (count($pdconf['hasImg'])){
			foreach ($pdconf['hasImg'] as $img) {
				$img = $pdconf['fields'][$img];
			$copy .= '
	"' . $img['id'] . '"		: {
	   	"type"	: "image",
	   	"label" : "' . $img['label'] . '",
	   	"src"	: "/wp-content/plugins/pippity/images/pty_samples/ebook_180w_left.png",
	   	"size"	: "' . $img['max_w'] . 'x' . $img['max_h'] .'"
			},
		';
			}
   	}
	$copy = trim(trim($copy), ',');
	$conf .= $copy . '
},                            
"fieldOrder"	: "heading,mainText,finePrint",
"x"				: "center",
"y"				: "center",
"styleImgs"		: {
	"background": 	"blue",
	"submit":		"red"
}
			';
			$conf = trim(trim($conf), ',');
			file_put_contents($this->ppdir . $newFile . '/' . $newFile . '.conf', $conf);
		}												
		public function parseImportedConf($conf)
		{
			$out = array();
			$conf = explode("\n", $conf);
			foreach ($conf as $c) {
				$colon = strpos($c, ':');
				$key = str_replace(' ', '_', strtolower(substr($c, 0, $colon)));
				$content = trim(substr($c, $colon+1));
				$out[$key] = $content;
			}
			$fields = array();
			$fieldsBits = explode('|', $out['fields']);
			$out['hasImg'] = array();
			foreach ($fieldsBits as $f) {
				$field = array();
				$bits = explode('[', str_replace(']', '', $f));
				$field['label'] = $bits[0];
				$props = explode(',', $bits[1]);
				foreach ($props as $prop) {
					$pbits = explode(':', $prop);
					$field[$pbits[0]] = $pbits[1];
				}
				if (isset($field['max_w'])) {
					$out['hasImg'][] = $field['id'];
				}
				$fields[$field['id']] = $field;
			}
			$colors = array();
			$colorsBits = explode('|', $out['colors']);
			foreach ($colorsBits as $c) {
				$bits = explode('[', $c);
				$bits[0] = trim($bits[0]);
				$colors[str_replace(' ', '-', strtolower($bits[0]))] = $bits[0];
			}
			$buttons = array();
			$buttonBits = explode('|', $out['button_colors']);
			foreach ($buttonBits as $c) {
				$bits = explode('[', $c);
				$bits[0] = trim($bits[0]);
				$buttons[str_replace(' ', '-', strtolower($bits[0]))] = str_replace(' ', '-', strtolower($bits[0]));
			}    

			$out['fields'] = $fields;
			$out['colors'] = $colors;
			$out['buttons'] = $buttons;
			return $out;
		}												
		public function importCss($file, $newFile, $pdconf)
		{
			$css = file_get_contents($this->pddir . $file . '/lightbox.css');
			$css = str_replace('@import url("../popreset.css");', '', $css);
			$css = str_replace('images/', '', $css);
			$css = str_replace('ul.bullet-list', 'ul', $css); 
			$css = preg_replace('/.popup-dom-lightbox-wrapper {(.+?)}/s', '', $css); 
			$addSpans = array(".secure", ".heading", ".heading2", ".lightbox-top p", ".lightbox-grey-panel p", ".lightbox-signup-panel p");
			foreach ($addSpans as $s) {
				$css = str_replace($s . ' {', "$s span {", $css);
			}
			$css .= '
				#pty_overlay {
					opacity:.8;
					background:#000;
				}
				.pty_spanblock{
					display:block;
				 }
				 #pty_right_imageShell{
				 float: ' . ($newFile == 'pd_4' ? 'right' : 'left' ) . '; 
				}
				.popup-dom-lightbox-wrapper{
					background-repeat:no-repeat;
				}
			';
			file_put_contents($this->ppdir . $newFile . '/' . $newFile . '.css', $css);
		}												
		public function importHtml($file, $newFile, $pdconf)
		{
			$reps = array(
				'<?php echo $lightbox_id?>~pty_' . $newFile,
				'<?php echo $delay_hide ?>~',
				'<?php echo $color ?>~%color%',
				'<?php echo $lightbox_close_id?>~pty_close',
				'<?php echo $promote_link ?>~', 
				'<form method="post" action="<?php echo $form_action ?>"<?php echo $target ?>>~<form id="pty_form">', 
				'<?php echo $inputs[\'hidden\'].$fstr ?>~<input type="text" class="pty_input name" id="pty_name" value="Name"/><input type="text" class="pty_input email" id="pty_email" value="E-Mail Address"/>',
				'<input type="submit" value="<?php echo $fields[\'submit_button\'] ?>" src="<?php echo $theme_url?>images/trans.png" class="<?php echo $button_color?>-button" />~<input type="submit" id="pty_submit" class="pty_submit" value="Sign-Up!"/>',    
			);                
			$html = file_get_contents($this->pddir . $file . '/template.php');
			$html = str_replace('; ?>', ' ?>', $html);
			$html = preg_replace('/<\/div>([ \n]+)<\?php/s', '</div><div class="lightbox-product-image">', $html);
		    $html = preg_replace('/\?>([ \r\n\t]+)<div/s', '</div><div', $html); 	
			foreach ($reps as $rep) {
				$bits = explode('~', $rep);
				$html = str_replace($bits[0], $bits[1], $html);
			}
			$dom = new DOMDocument();
			$dom->formatOutput = true;
			@$dom->loadHtml('<body>
				'.$html.'
				</body>');
			foreach ($this->elmByC($dom, 'bullet-list') as $e) {
				$elm = $dom->createElement('div', '');
				$elm->setAttribute("id", "pty_bullet");
				$e->parentNode->insertBefore($elm, $e);
			    $this->elmRem($e);
			}
			$stop = false;
			foreach ($this->elmByC($dom, 'lightbox-product-image')  as $e) {
				if ($stop) {
					break;
				}
				$stop = true;
				$elm = $dom->createElement('div', '');
				$elm->setAttribute("id", "pty_right_imageShell");
				$e->parentNode->insertBefore($elm, $e);
			    $this->elmRem($e);
			}
			$html = $dom->saveHTML();
			$html = str_replace('\';?>', '', $html); 
			foreach ($pdconf['fields'] as $i => $f) {
				$html = str_replace('<?php echo $fields[\'' . $i . '\'] ?>', '<span class="pty_spanblock" id="pty_'. $i . '"></span>', $html);
				$html = str_replace('<?php echo nl2br($fields[\'' . $i . '\']) ?>', '<span class="pty_spanblock" id="pty_'. $i . '"></span>', $html);
			}

			$html = explode("\n", $html);
			$html[0] = "";
			$html[1] = "";
			$html[count($html)-1] = "";
			$html[count($html)-2] = "";
			$html = trim(implode("\n", $html));
			$backgroundReps = array(
				"pd_1" => "top-content", 
				"pd_2" => "signup-panel", 
				"pd_3" => "main", 
				"pd_4" => "middle-bar",
				"pd_5" => "signup-panel",
				"pd_6" => "signup-panel",
			);
			if (isset($backgroundReps[$newFile])) {
				$rep = $backgroundReps[$newFile];
				$html = str_replace("lightbox-$rep", "lightbox-$rep pty_background", $html);
			}
			file_put_contents($this->ppdir . $newFile . '/' . $newFile . '.html', $html);
		}												
		public function importImages($file, $newFile)
		{
			$imgdir = $this->ppdir . $newFile . '/images';
			mkdir($imgdir);
			$this->copydir($this->pddir . $file . '/images/', $imgdir);
			foreach (scandir($imgdir) as $imgfile) {
				$imgfiledir = $imgdir . '/' . $imgfile;
				if (is_dir($imgfiledir) && strpos($imgfile, '.') !== 0) {
					foreach (scandir($imgfiledir) as $inner) {
						if (strpos($inner, '.') !== 0) {
							rename($imgfiledir . '/' . $inner, $imgdir . '/' . $inner);
						}
					}
				}
			}
			foreach (scandir($imgdir) as $imgfile) {
				if (strpos($imgfile, '-panel') !== false) {
					if (strpos($imgfile, 'bottom') === false && strpos($imgfile, 'top') === false && strpos($imgfile, 'grey') === false && strpos($imgfile, 'middlex') === false) {
						$pre = 'background';
						if (strpos($imgfile, 'button') !== false) {
							$imgfile = str_replace('-button', $imgfile);
							$pre = 'submit_';
						}
						$new = $pre . '__' . str_replace('-panel', '', $imgfile);
						rename($imgdir . '/' . $imgfile, $imgdir . '/' . $new);
					}
				}
				if (strpos($imgfile, 'button') !== false && strpos($imgfile, 'buttons') === false) {
					$pre = 'submit';
					$new = $pre . '__' . str_replace('button-', '', str_replace('-button', '', $imgfile));
					rename($imgdir . '/' . $imgfile, $imgdir . '/' . $new);
				}
			}
			copy($this->pddir . $file . '/previews/blue.jpg', $this->ppdir . $newFile . "/$newFile.png");
		}												
		public function importPopup()
		{
			global $wpdb, $pty;
			$pdtheme = get_option("popup_domination_template");
			if (is_string($pdtheme) && strpos($pdtheme, 'lightbox') !== false) {
				$themes = array(
					'lightbox' => 1,
					'lightbox3' => 2,
					'lightbox4' => 3,
					'lightbox5' => 4,
					'lightbox6' => 5,
					'lightbox7' => 6,
					'lightbox8' => 7,
				);
				$file = 'pd_' . $themes[$pdtheme];
				$conf = '{' . file_get_contents(PTY_DIR . '/themes/' . $file . "/$file.conf") . '}';
				$theme = json_decode($conf, true);
				$p = 'popup_domination_';
				$color = get_option($p . 'color');
				$theme['file'] = $theme['file'];
				$theme['styleImgs']['background'] = $color;
				$theme['styleImgs']['submit'] = get_option($p . 'button_color');
				$theme['name'] = 'PopDom ' . $themes[$pdtheme] . ( $color == 'blue' ? '' : ' in ' . $color );
				$theme['copy']['title']['text'] = get_option($p . 'field_title');
				$theme['copy']['form_header']['text'] = get_option($p . 'field_form_header', '');
				$theme['copy']['footer_note']['text'] = get_option($p . 'field_footer_note', '');
				$theme['copy']['short_paragraph']['text'] = get_option($p . 'field_short_paragraph', '');
				$theme['copy']['short_paragraph2']['text'] = get_option($p . 'field_short_paragraph2', '');
				$theme['copy']['bullet']['text'] = '*' . (implode("\n*", unserialize(get_option($p . 'listitems')))); 
				$theme['copy']['right_image']['src'] = get_option($p . 'field_right_image', '');
				$theme['copy']['name'] = json_decode('{"label":"Name Input Label","type":"field","text":"'.get_option($p . 'field_name_default') .'"}');
				$theme['copy']['email'] = json_decode('{"label":"E-Mail Input Label","type":"field","text":"'.get_option($p . 'field_email_default') .'"}');
				$theme['copy']['submit'] = json_decode('{"label":"Submit Button Label","type":"submit","text":"'.get_option($p . 'field_submit_button') .'"}');
				$theme['settings']['delay'] = $this->ifExists(get_option($p . 'delay', 0), 0, 's');
				$theme['settings']['expire'] = get_option($p . 'cookie_time', 7);
				$theme['settings']['visits'] = get_option($p . 'impression_count', 0); 
				$theme['settings']['postOnly'] = strpos(get_option($p . 'show_opt'), 'everywhere') === false ? 1 : 0;
				$theme['settings']['trigger'] = 0;
				$theme['rawForm'] = get_option($p . 'formhtml');
				 $wpdb->insert(
					$pty->t('popups'),
					array('settings' => serialize($theme)),
					array('%s')
				);   
			}
		}												
		
		/*
		 * Install
		 */
		public function checkInstall()
		{
			$v = get_option('pty_version');
			$this->version = $v;
			if (!$v || $v !== PTY_VERSION) {
				$this->install($v);
			}
		}												
		public function install($v)
		{
			global $wpdb;
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

			//Popups Table
			$tbl_popups = $this->t('popups');
			if ($v != PTY_VERSION || ($wpdb->get_var("SHOW TABLES LIKE '$tbl_popups'") != $tbl_popups)) {
				$popupTable = "
					CREATE TABLE `$tbl_popups` (
						popupid int(11) NOT NULL AUTO_INCREMENT,
						label text NOT NULL,
						settings text NOT NULL,
						status tinyint(3) unsigned NOT NULL DEFAULT 0,
						step tinyint(3) unsigned NOT NULL DEFAULT 0,
						stamp timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
						priority int(11) unsigned NOT NULL DEFAULT 0,
						cookie varchar(15) NOT NULL default 'pty_visited',
						PRIMARY KEY  (popupid),
						KEY state (status)
					) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0 ;
				";
				dbDelta($popupTable);
			}

			//Impressions Table
			$tbl_imps = $this->t('imps'); 
			if ($v != PTY_VERSION || ($wpdb->get_var("SHOW TABLES LIKE '$tbl_imps'") != $tbl_imps)) {
				$impTable = "
					CREATE TABLE `$tbl_imps` (
						impid int(10) unsigned NOT NULL AUTO_INCREMENT,
						popupid int(10) unsigned NOT NULL,
						page varchar(999) NOT NULL,
						startTime int(11) unsigned NOT NULL,
						closeTime int(11) unsigned NOT NULL,
						leaveTime int(10) unsigned NOT NULL,
						blurTime int(10) unsigned NOT NULL,
						convertTime int(10) unsigned NOT NULL,
						PRIMARY KEY  (impid),
						KEY popupid (popupid),
						KEY page (page)
					) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0 ;
				";
				dbDelta($impTable);   
			}

			//Filters Table
			$tbl_filts = $this->t('filters'); 
			if ($v != PTY_VERSION || ($wpdb->get_var("SHOW TABLES LIKE '$tbl_filts'") != $tbl_filts)) {
				$filtTable = "
					CREATE TABLE `$tbl_filts` (
						filterid int(10) unsigned NOT NULL AUTO_INCREMENT,
						popupid int(10) unsigned NOT NULL,
						parentid int(10) unsigned NOT NULL,
						isVal int(1) unsigned NOT NULL,
						type varchar(16) NOT NULL,
						matchVal varchar(244) NOT NULL,
					  	matchStr text NOT NULL,
						PRIMARY KEY  (filterid),
						KEY popupid (popupid,parentid)
					) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0 ;
				";
				dbDelta($filtTable);   
			}

			//Stats Table
			$tbl_stats = $this->t('stats'); 
			if ($v != PTY_VERSION || ($wpdb->get_var("SHOW TABLES LIKE '$tbl_stats'") != $tbl_stats)) {
				$statsTable = "
					CREATE TABLE `$tbl_stats` (
						ts int(11) DEFAULT NULL,
						popupid int(10) unsigned NOT NULL,
						imps bigint(21) NOT NULL DEFAULT '0',
						convs bigint(21) NOT NULL DEFAULT '0',
						onpopup decimal(15,4) DEFAULT NULL,
						onpage decimal(15,4) DEFAULT NULL,
						convertdelay decimal(47,4) DEFAULT NULL,
						UNIQUE KEY stats_idx (ts,popupid)
					) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0 ;
				";
				dbDelta($statsTable);
			}
			
			//Save version
			update_option('pty_version', PTY_VERSION);
		}												
		public function upgrade($version)
		{
			global $wp_filesystem, $pty;
			
			// Make sure we can do this
			if (!function_exists('curl_init')) {
				$pty->log('Curl isn\'t installed');
				return array("success" => 0, "error" => "Curl is not installed or enabled, contact your webhost for help.");
			}

			ob_flush();
			flush();			
			ob_end_flush();

			// Setup
			$pty->cleanPtyTempDir();
			mkdir(PTY_TEMP, 0777);
			chmod(PTY_TEMP, 0777);
			$updir = PTY_TEMP . '/upgrade';
			mkdir($updir, 0777);
			chmod($updir, 0777);

			// Download
			echo 'Downloading Pippity...';
			$url = 'http://pippity.com/d/from/' . $this->key. '/' . $this->secret . '/' . $version;
			$pipe = curl_init();
			curl_setopt($pipe, CURLOPT_URL, $url);
			curl_setopt($pipe, CURLOPT_HEADER, 0);
			curl_setopt($pipe, CURLOPT_RETURNTRANSFER, 1);
			$dl = curl_exec($pipe);
			if (curl_getinfo($pipe, CURLINFO_HTTP_CODE) == 200) {

				//Unzip
				echo 'Downloaded!<br/>';
				$zip = $updir . '/pty.zip';
				$folder = $updir . '/';
				$wp_filesystem->put_contents($zip, $dl, 0755);
				unzip_file($zip, $folder);

				// Move Files into Plugin Directory
				$folder .= 'pippity/';
				foreach (scandir($folder) as $file) {
					$wp_filesystem->chmod(PTY_DIR . '/', 0777, true);
					$from = $folder . $file;
					$to = PTY_DIR . '/' . $file;
					if (!in_array($file, array('.', '..', 'themes'))) {
						echo "Updating $file...";
						$upgrade = $this->upgradeFile($folder, $from, $to);
						if (is_array($upgrade)) {
							// Error
							echo "<br/>";
							return $upgrade;
						}
						echo "Done!<br/>";
					}
					if ($file == 'themes') {
						update_option('pty_updatedThemes', 'true');
						foreach (scandir($from) as $theme) {
							if(!in_array($theme, array('.', '..'))) {
								echo 'Updating theme "' . $theme . '...';
								$upgrade = $this->upgradeFile($from, $from . '/' . $theme, $to . '/' . $theme);
								if (is_array($upgrade)) {
									// Error
									return $upgrade;
								}
								echo 'Done!</br>';
							}
						}
					}
				}

				// Reset all Pippity permissions to WP Default
				$wp_filesystem->chmod(PTY_DIR, false, true);
			}
			else {
				$pty->cleanPtyTempDir();
				$pty->log('Problem download upgrade');
				return array("success" => 0, "error" => 'There was a problem downloading the upgrade. Try again in a bit!');
			}
			echo 'Cleaning up...<br/>';
			$pty->cleanPtyTempDir();
			echo '<strong>Success!</strong><br/><br/>Redirecting...';
			return true;
		}
		private function upgradeFile($folder, $from, $to) 
		{
			global $wp_filesystem, $pty;
			$wp_filesystem->chmod($folder, 0777, true);
			if ($wp_filesystem->exists($to)) {
				$wp_filesystem->delete($to, true);
			}
			// Replace Files
			if (!$wp_filesystem->move($from, $to, true) ) {
				$pty->cleanPtyTempDir();
				$pty->log('Problem moving file', array("from" => $folder, "to" => PTY_DIR, "file" => $file, ""));
				return array("success" => 0, "error" => "There was an error moving " . $file);
			}
			else {
				return true;
			}
		}
	}
}

/*
 * Add Hooks
 */
if(class_exists("Pty")){
	$pty = new Pty();
}
if(isset($pty)){
	register_activation_hook(__FILE__,array(&$pty, 'install'));
	add_action('init', array(&$pty, 'loadDependencies'));
	add_action('admin_menu',  array(&$pty, 'addAdmin'));
	add_filter('the_content', array(&$pty, 'scrollTrigger'));
	add_action('wp_head', array(&$pty, 'loadGlobals'));
	add_action('admin_head', array(&$pty, 'loadGlobals'));
}

