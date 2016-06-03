<?php
if ($_REQUEST['action'] == 'pty_getActive') {
	@ini_set('display_errors', 0);
}
if ( !class_exists( "TmbAjax" ) ) {

	class TmbAjax
	{

		public function __construct()
		{
			$actions = get_class_methods( $this );
			$public = array( "wysija_submit", "notify", "getGravityForm", "markImp", "addImp", "getTheme", "setLogging", "getActive", "updateAnalytics", 'recordImpression' );
			foreach ( $actions as $a ) {
				if ( $a != 'finish' && $a != '__construct' ) {
					$act = "pty_$a";
					add_action( 'wp_ajax_' . $act, array( &$this, $a ) );
					if ( in_array( $a, $public ) ) {
						add_action( 'wp_ajax_nopriv_' . $act, array( &$this, $a ) );
					}
				}
			}
			foreach ( $_REQUEST as $i => $v ) {
				$this->data[$i] = $v;
			}
		}

		public function finish()
		{
			$rsp = array( 'msg' => $this->msg );
			if ( isset( $this->rsp ) ) {
				foreach ( $this->rsp as $i => $v ) {
					$rsp[$i] = $v;
				}
			}
			if ( isset( $this->suc ) ) {
				$rsp['suc'] = $this->suc;
			}
			if ( isset( $this->err ) ) {
				$rsp['err'] = $this->err;
			}
			$rsp = json_encode( $rsp );
			die( $rsp );
		}

		/******************************************************
		 * AJAX ACTIONS
		 * ****************************************************/

		/**
		 * Create New Popup
		 */
		public function getActive()
		{
			global $wpdb, $pty, $wp_query;
			$popupid = false;
			$popup = false;
			$isPopup = false;
			$wp = new WP();
			$href = isset($_REQUEST['href']) ? explode('?', $_REQUEST['href']) : '';
			$uri = str_replace('https://', '', str_replace('http://', '', $href[0]));
			$uri = trim(substr($uri, strpos($uri, '/')), '/');
			$_SERVER['REQUEST_URI'] = $uri . '?' . $href[1];
			$query = isset($href[1]) ? $href[1] : '';
			$_SERVER['PHP_SELF'] = 'index.php';
			$wp->main($query);
			$wp_query->parse_query();
			$ref = isset($_REQUEST['ref']) ? 'http://' . $_REQUEST['ref'] : '';
			if ( !( $wp_query->is_singular || $wp_query->is_archive || $wp_query->is_search || $wp_query->is_feed || $wp_query->is_trackback || $wp_query->is_404 || $wp_query->is_comments_popup || $wp_query->is_robots ) ) {
				$wp_query->is_home = true;
			}
			if (( 0 == count( $wp_query->posts ) ) && !is_robots() && !is_search() && !is_home() ) {
				// Don't 404 for these queries if they matched an object.
				if ( !(( is_tag() || is_category() || is_tax() || is_author() || is_post_type_archive() ) && $wp_query->get_queried_object() && !is_paged() )) {
					$wp_query->set_404();
					nocache_headers();
				}
			}
			$filterer = new Pty_Filterer(array("referrer" => $ref));

			$js = file_get_contents( PTY_DIR . '/js/pippity_worker.js' );
			$themejs = '
				pty.themes = [];';
			if ( substr_count( $this->data['url'], '_' ) > 1 ) {
				$popupid = (int) end( explode( '_', $this->data['url'] ) );
			}
			else if ( $filterer ) {
				$active = $filterer->run_filters();
				if (is_object($active)) {
					$popupid = $active->popupid;
				}
			}
			if ( $popupid ) {
				$popup = $wpdb->get_row( "SELECT * FROM " . $pty->t( 'popups' ) . " WHERE popupid = '$popupid'" );
				if ( $popup  && strlen($popup->popupid)) {
					$popup = new Pty_Theme( false, $popup, null, array( "active" => true ) );
					$json = $popup->getJson();
					$themejs .= '
						pty.popupid = ' . $popup->popupid . ';
						pty.autoid = ' . $popup->popupid . ';
						pty.theme = pty.themes[' . $popup->popupid . '] = ' . $json . ';
						';
				}
				$isPopup = true;
			}
			if ( isset( $this->data['loadPopups'] ) && strlen( $this->data['loadPopups'] ) ) {
				$ids = explode( ',', $this->data['loadPopups'] );
				foreach ( $ids as $id ) {
					$popup = $wpdb->get_row( "SELECT * FROM " . $pty->t( 'popups' ) . " WHERE popupid = '$id'" );
					if ( is_object( $popup ) ) {
						$popup = new Pty_Theme( false, $popup, null, array( "active" => true ) );
						$themejs .= 'pty.themes[' . $popup->popupid . '] = ' . $popup->getJson() . ';';
					}
				}
				$isPopup = true;
			}
			if ($isPopup) {
				$js = $themejs . ' 
				' . $js;
			}
			else {
				$js = '';
			}
			header( "Content-type: text/javascript" );
			echo trim($js);
			exit;
		}

		public function getGravityForm()
		{
			$post = new WP_Http;
			$body = array(
				"html" => $this->data['html'],
				"do" => 'gravity'
			);
			$res = $post->request(PTY_URL . '/ajax_external.php', array("method" => "POST", "body" => $body));
			$form = $res['body'];
			$this->msg = "Got Gravity Form";
			$this->rsp = array( "form" => $form);
			$this->suc = 1;
			$this->finish();
		}

		public function getTribulantForm()
		{
			$form = do_shortcode($this->data['html']);
			$this->msg = "Got Tribulant Form";
			$this->rsp = array( "form" => $form);
			$this->suc = 1;
			$this->finish();
		}

		public function wysija_submit()
		{
			global $wpdb;
			$wysija_form_tbl = $wpdb->prefix . 'wysija_form';
			$form_id = $this->data['form_id'];
			$raw_form = $wpdb->get_row("SELECT * FROM $wysija_form_tbl WHERE form_id = '$form_id'");
			$form_data = unserialize(base64_decode($raw_form->data));
			$body = json_encode($form_data['body']);
			$post_body = array(
				"action" => 'wysija_ajax',
				"controller" => 'subscribers',
				"ajaxurl" => '',
				"loadingTrans" => 'Loading...',
				"task" => 'save', 
				"formid" => 'form-wysija-' . $form_id, 
				"data" => array(
				)
			);
			$lastname = false;
			$firstname = false;
			if (strpos($body, "firstname") !== false) {
				$firstname = true;
			}
			if (strpos($body, "lastname") !== false) {
				$lastname = true;
			}
			if ($firstname && !$lastname) {
				$firstname = $this->data['name'];
				$post_body['data'][] = array(
					"name" => 'wysija[user][firstname]',
					"value" => $firstname
				);
				$post_body['data'][] = array(
					"name" => 'wysija[user][abs][firstname]',
					"value" => ''
				);
			}
			else if ($firstname && $lastname) {
				$names = explode(' ', $this->data['name']);
				$firstname = $names[0];
				$lastname = '';
				for($x = 1; $x < count($names); $x++) {
					$lastname .= $names[$x] . ' ';
				}
				$lastname = trim($lastname);
				$post_body['data'][] = array(
					"name" => 'wysija[user][firstname]',
					"value" => stripSlashes($firstname)
				);
				$post_body['data'][] = array(
					"name" => 'wysija[user][abs][firstname]',
					"value" => ''
				);
				$post_body['data'][] = array(
					"name" => 'wysija[user][lastname]',
					"value" => stripSlashes($lastname)
				);
				$post_body['data'][] = array(
					"name" => 'wysija[user][abs][lastname]',
					"value" => ''
				);

			}
			$post_body['data'][] = array(
				"name" => 'wysija[user][email]',
				"value" => $this->data['email']
			);
			$post_body['data'][] = array(
				"name" => 'wysija[user][abs][email]',
				"value" => ''
			);
			$post_body['data'][] = array(
				"name" => 'form_id',
				"value" => $form_id
			);
			$post_body['data'][] = array(
				"name" => 'action',
				"value" => 'save'
			);
			$post_body['data'][] = array(
				"name" => 'controller',
				"value" => 'subscribers'
			);
			$post_body['data'][] = array(
				"name" => 'wysija[user_list][list_ids]',
				"value" => implode(',', $form_data['settings']['lists'])
			);
			$post = new WP_Http;
			$res = $post->request(get_bloginfo('wpurl') . '/wp-admin/admin-ajax.php', array('method' => 'POST', 'body' => $post_body));
			$this->msg = "Wysija Subscriber Added";
			$this->suc = 1;
			$this->finish();

		}

		public function updPopup()
		{
			global $wpdb, $pty;
			$tbl = $pty->t( 'popups' );
			$pty->log( 'Start Updating a Popup', $this->data );
			if ( isset( $this->data['popupid'] ) ) {
				$popupid = $this->data['popupid'];
				$settings = json_encode($this->data['settings']);
				$settings = preg_replace("/(\\\+)\'/", "'", $settings);
				$settings = preg_replace('/(\\+)"/', '\"', $settings);
				$settings = '_____enc______' . base64_encode(serialize( json_decode($settings) ));
				$cookie = 'pty_visited';
				$priority = '0';
				if ( isset( $this->data['settings']['settings'] ) ) {
					$cookie = !isset($this->data['settings']['settings']['customCookie']) ? 'pty_visited' : 'pty_' . $this->data['settings']['settings']['customCookie'];
					$priority = !isset($this->data['settings']['settings']['priority']) ? 0 : $this->data['settings']['settings']['priority'];
				}
				$wpdb->update( $tbl, array( "settings" => $settings, "cookie" => $cookie, "priority" => $priority ), array( "popupid" => $popupid ), array( '%s', '%s', '%d' ), '%d'
				);
				$pty->logSqlErr( 'Updating a Popup SQL Query' );
				$popup = new Pty_Theme( null, null, $popupid );
				$popup->initFiles( array( "active" => true ) );
				$popup->reactivate();
				$this->msg = "Popup saved!";
				$this->rsp = array( "popupid" => $popupid );
				$this->suc = 1;
			}
			else {
				$wpdb->insert(
						$tbl, array( 'label' => '', 'status' => 0, 'step' => 1, 'settings' => serialize( $this->data['settings'] ) ), array( '%s' )
				);
				$pty->logSqlErr( 'Adding a New Popup SQL Query' );
				$popupid = $wpdb->insert_id;
				if ( is_numeric( $popupid ) ) {
					$this->msg = "Popup saved!";
					$this->rsp = array( "popupid" => $popupid );
					$this->suc = 1;
				}
				else {
					$this->msg = "Problem adding popup.";
				}
			}
			if ( isset( $this->data['step'] ) ) {
				$pty->log( 'Popupid at Stepper', $popupid );
				$wpdb->update( $tbl, array( "step" => $this->data["step"] ), array( "popupid" => $popupid ), '%d', '%d' );
				$pty->logSqlErr( 'Updating a Popup\'s Step Query' );
			}
			$this->finish();
		}

		public function changeStatus()
		{
			global $wpdb, $pty;
			$popup = new Pty_Theme( null, null, $this->data['popupid'], array( "active" => true ) );
			$pty->log( 'Start Changing Popup Status', $this->data );
			switch ( $this->data['status'] ) {
				case 'activate':
					$pty->log( 'Activating Popup' );
					$popup->activate();
					$this->msg = "Popup activated!";
					$this->suc = 1;
				break;
				case 'deactivate':
					$pty->log( 'Deactivating Popup' );
					$popup->deactivate();
					$this->msg = "Popup deactivated!";
					$this->suc = 1;
				break;
			}
			$this->finish();
		}

		public function makeClone()
		{
			global $wpdb, $pty;
			$pty->log( 'Start Making a Clone', $this->data );
			$popup = new Pty_Theme( null, null, $this->data['popupid'] );
			$cloneOf = $this->data['cloneOf'] . ' (Clone)';
			$filters = $popup->getFilters();
			$wpdb->insert(
					$pty->t( 'popups' ), array( 'settings' => $popup->raw, 'label' => $cloneOf, 'step' => 4, 'status' => 0 ), array( '%s' )
			);
			$id = $wpdb->insert_id;
			$popup = new Pty_Theme( null, null, $id );
			$popup->addFilters($filters);
			$this->rsp = array( "id" => $id, "theme" => $popup->getJson() );
			$this->msg = "Popup Cloned!";
			$this->suc = 1;
			$this->finish();
		}

		public function rename()
		{
			global $wpdb, $pty;
			$pty->log( 'Start Renaming a Popup', $this->data );
			$label = $this->data['label'];
			if ( $label != '' ) {
				$wpdb->query(
						$wpdb->prepare(
								"UPDATE " . $pty->t( 'popups' ) . " SET label = %s WHERE popupid = %s", $this->data['label'], $this->data['popupid']
						)
				);
				$this->msg = "Popup Renamed!";
				$this->suc = 1;
			}
			else {
				$this->msg = "You need to enter something for the label!";
				$this->err = 1;
			}
			$this->finish();
		}

		public function delete()
		{
			global $wpdb, $pty;
			$pty->log( 'Start Deleting a Popup', $this->data );
			$popup = new Pty_Theme( null, null, $this->data['popupid'] );
			$popup->deactivate();
			$wpdb->query( "DELETE FROM " . $pty->t( 'popups' ) . " WHERE popupid = '{$popup->popupid}'" );
			$this->msg = "Popup Deleted!";
			$this->suc = 1;
			$this->finish();
		}

		public function activate()
		{
			global $pty;
			$pty->log( 'Start Activating a Popup', $this->data );
			$key = $this->data['key'];
			$domain = str_replace('https://', '', str_replace( 'http://', '', site_url() ));
			$url = "http://pippity.com/license/activate?blaz=$key&domain=$domain";
			$req = new WP_Http;
			$rsp = $req->request( $url, array( 'method' => 'POST', 'body' => array( ), 'timeout' => 10 ) );
			if ( !is_wp_error( $rsp ) ) {
				$rsp = $rsp['body'];
				if ( $rsp == 'Success' ) {
					update_option( 'pty_key', $key );
					$this->msg = "Activated!";
					$this->suc = 1;
				}
				else if ( $rsp == "Key Locked" ) {
					$this->msg = "This key has already been registered - e-mail help@pippity.com!";
					$this->err = 1;
				}
				else {
					$this->msg = "You submitted an invalid key - email help@pippity.com!";
					$this->err = 1;
				}
			}
			$this->rsp = array( "rsp" => $rsp, "url" => $url );
			$this->finish();
		}

		public function supportRequest()
		{
			global $pty;
			$pty->log( 'Start a Support Request', $this->data );
			$this->data['details']['wp_version'] = get_bloginfo( 'version' );
			$this->data['details']['pty_version'] = PTY_VERSION;
			$supData = array(
				'details' => json_encode( $this->data['details'] ),
				'email' => $this->data['email'],
				'fname' => $this->data['fname'],
				'lname' => $this->data['lname'],
				'domain' => $this->data['domain'],
				'urgency' => $this->data['urgency'],
				'problem' => $this->data['problem'],
				'blaz' => get_option( 'pty_key' ),
			);
			$url = "http://pippity.com/support/request";
			$req = new WP_Http;
			$rsp = $req->request( $url, array( 'method' => 'POST', 'body' => $supData ) );
			$rsp = $rsp['body'];
			$this->rsp['arst'] = $rsp;
			if ( $rsp == 'Success' ) {
				$this->msg = "Request submitted successfully!";
				$this->suc = 1;
			}
			else {
				$this->msg = "There was a problem submitting your request - email help@pippity.com";
				$this->err = 1;
			}
			$this->finish();
		}

		public function notify()
		{
			if (isset($this->data['fields']) && is_array($this->data['fields']) && isset($this->data['popupid'])) {
				$popup = new Pty_Theme( null, null, $this->data['popupid'] );
				if (isset($popup->notify) && is_array($popup->notify) && count($popup->notify)) {

					// We have required fields and we know we have people to email
					add_filter( 'wp_mail_content_type', array($this, 'set_html_content_type') );
					$fields = $this->data['fields'];
					$subject = '[Pippity] You Got a New Sign-up!';
					$hostname = $_SERVER['HTTP_HOST'];
					$message = "
						Hey, good news!
						<br><br>
						Someone just signup up via your Pippity Popup! Their submission is below.
						<br><br>
						<table>
					";
					foreach ($fields as $field => $val) {
						$message .= '
						<tr>
							<td>' . ucfirst($field) . ': </td>
							<td>' . $val . '</td>
						<tr/>
						';
					}
					$message .= '</table><br><br>Thanks, see you next time!';
					$headers = array(
						"From: Pippity <pippity_nosend@" . $hostname
					);
					if (isset($fields['email'])) {
						$name = isset($fields['name']) ? $fields['name'] : 'New Subscriber';
						$headers[] = 'Reply-To: ' . $name . ' <' . $fields['email']. '>';
					}
					if (isset($this->data['subject'])) {
						$subject = $this->data['subject'];
					}
					foreach ($popup->notify as $addr) {
						wp_mail($addr, $subject, $message, $headers);
					}
				}
			}
			$this->msg = '';
			$this->suc = 1;
			$this->finish();
		}

		public function set_html_content_type()
		{
			return 'text/html';
		}

		public function getLastPost()
		{
			//This is for the post beneath our sample popup
			global $pty;
			$recent_args = $pty->wpNewer('3.1.0') ? array("number_posts" => 1, "post_status" => "publish") : 1;
			$post = current(wp_get_recent_posts($recent_args));
			$postUrl = get_permalink($post['ID']);
			$postUrl .= (strpos($postUrl, '?') === false ? '?' : '&') . 'nopty=true';
			$get= new WP_Http;
			$res = $get->request($postUrl, array("method" => "GET"));
			$page = $res['body'];
			echo $page;
			exit;
		}

		public function addImp()
		{
			global $wpdb, $pty;
			$pty->log( 'Start a Adding an Impression', $this->data );
			$wpdb->insert( $pty->t( 'imps' ), array( "popupid" => $this->data['popupid'], "page" => str_replace( get_bloginfo( 'wpurl' ), '', $this->data['page'] ), "startTime" => time(), "closeTime" => 0, "leaveTime" => 0, "blurTime" => 0, "convertTime" => 0 ), array( '%d', '%s', '%d', '%d', '%d', '%d' ) );
			$this->rsp = array( 'id' => $wpdb->insert_id );
			$this->msg = "Impressed.";
			$this->suc = 1;
			$this->finish();
		}

		public function recordImpression()
		{
			global $wpdb, $pty;
			if ( !isset( $this->data['imp'] ) )
				return;

			$pty->log( 'Adding Impression Statistic', $this->data );

			$imp = json_decode(stripSlashes($this->data['imp']));
			$wpdb->insert($pty->t('imps'), 
				array(
					'popupid' => $imp->popupid,
					'page' => $imp->page,
					'startTime' => $imp->startTime, 
					'closeTime' => $imp->closeTime, 
					'leaveTime' => $imp->leaveTime, 
					'blurTime' => $imp->blurTime, 
					'convertTime' => $imp->convertTime
				)
			, array('%d', '%s', '%d', '%d', '%d', '%d', '%d'));
			$this->msg = "Success.";
			$this->suc = 1;
			$this->finish();
		}

		public function markImp()
		{
			global $wpdb, $pty;
			if ( !isset( $this->data['type'] ) || !in_array( $this->data['type'], array( 'closeTime', 'leaveTime', 'blurTime', 'focusTime', 'convertTime' ) ) )
				return;


			$pty->log( 'Adding Impression Statistic', $this->data );
			$row = $wpdb->get_row(
					$wpdb->prepare( "SELECT startTime, blurTime, leaveTime, closeTime, convertTime FROM " . $pty->t( 'imps' ) . " WHERE impid = %d", $this->data['id'] )
			);
			if ( !isset( $row ) )
				return;

			$col = $this->data['type'];
			$time = time() - $row->startTime;
			$blur = $row->blurTime;
			$leave = $row->leaveTime;
			$close = $row->closeTime;
			$convert = $row->convertTime;

			if ( $leave == 0 && $col == 'blurTime' ) {
				$leave = $time;
			}
			else if ( $leave > 0 ) {
				$blur += $time - $leave;
				$leave = 0;
			}


			if ( $col == 'closeTime' ) {
				$close = $time;
			}
			else if ( $col == 'convertTime' ) {
				$convert = $time;
			}
			else if ( $col == 'leaveTime' ) {
				$leave = $time;
				if ( $close == 0 )
					$close = $time;
			}

			$wpdb->query(
					$wpdb->prepare(
							"UPDATE " . $pty->t( 'imps' ) . " SET blurTime = %d, leaveTime = %d, closeTime = %d, convertTime = %d WHERE impid = %d", $blur, $leave, $close, $convert, $this->data['id']
					)
			);

			$this->msg = "Success.";
			$this->suc = 1;
			$this->finish();
		}

		public function getPopupAnalytics()
		{
			global $pty;
			$pty->log( 'Start Getting Popup Analytics', $this->data );
			$popup = new Pty_Theme( null, null, $this->data['popupid'] );
			$start = $pty->ifExists( $this->data['start'], false );
			$end = $pty->ifExists( $this->data['end'], false );
			$this->msg = "Grabbed Analytics Data";
			$this->rsp = $popup->analyzePopup( $popup, $start, $end);
			$this->suc = 1;
			$this->finish();
		}

		public function getSetAnalytics()
		{
			global $pty;
			if (!ini_get('safe_mode')) {
				set_time_limit(500);
			}
			$pty->updateAnalytics();
			$pty->log( 'Start Setting Popup Analytics', $this->data );
			$start = $pty->ifExists( $this->data['start'], false );
			$end = $pty->ifExists( $this->data['end'], false );
			$popups = explode( ',', $this->data['popups'] );
			$antcs = array( );
			foreach ( $popups as $popupid ) {
				if ($popupid) {
					$popup = new Pty_Theme( null, null, $popupid );
					$antcs[$popupid] = $popup->analyzePopup( $popup, $start, $end );
				}
			}
			$this->msg = "Grabbed Analytics Data";
			$this->rsp = array( "analytics" => $antcs );
			$this->suc = 1;
			$this->finish();
		}

		public function getFilterOptionData()
		{
			global $wpdb;
			switch ( $this->data['type'] ) {
				case 'posts':
					$table = $wpdb->prefix . 'posts';
					$options = $wpdb->get_results( "SELECT ID as value, post_title as label FROM $table WHERE post_status = 'publish'" );
					break;
				case 'cats':
					$cats = get_categories();
					$options = array( );
					foreach ( $cats as $cat ) {
						$option = new StdClass();
						$option->value = $cat->term_id;
						$option->label = $cat->name;
						$options[] = $option;
					}
					break;
				case 'types':
					$types = get_post_types();
					$options = array();
					foreach ($types as $type) {
						$option = new StdClass();
						$option->value = $type;
						$option->label = $type;
						$options[] = $option;
					}
				break;
				case 'roles':
					global $wp_roles;
				    $all_roles = $wp_roles->roles;
				    $roles = apply_filters('editable_roles', $all_roles);
					$options = array();
					foreach ($roles as $id => $role) {
						$option = new StdClass();
						$option->value = $id;
						$option->label = $role['name'];
						$options[] = $option;
					}
				break;
			}
			$this->msg = "Got Options";
			$this->rsp = array( "options" => $options );
			$this->suc = 1;
			$this->finish();
		}

		public function saveFilters()
		{
			if ( isset( $this->data['popupid'] ) && isset( $this->data['filters'] ) ) {
				$popup = new Pty_Theme( false, false, $this->data['popupid'] );
				$popup->clearFilters();
				$popup->addFilters( $this->data['filters'] );
				$this->msg = "Filters Saved!";
				$this->suc = 1;
			}
			else if (isset($this->data['popupid'])) {
				$popup = new Pty_Theme( false, false, $this->data['popupid'] );
				$popup->clearFilters();
				$this->msg = "Cleared all Filters";
				$this->suc = 1;
			}
			else {
				$this->msg = "No popupid set";
				$this->suc = 0;
			}
			$this->finish();
		}

		public function getTheme()
		{
			global $pty;
			$pty->log( 'Start Getting Theme', $this->data );

			// If we need a varient, we have to pull its parent as well!
			$getTheme = isset( $this->data['parent'] ) ? $this->data['parent'] : $this->data['theme'];
			$theme = $pty->getInstalledTemplates( $getTheme );
			$nocache = $this->data['nocache'];
			$theme = new Pty_Theme( $theme[$this->data['theme']], false, false, array( "nocache" => $nocache ) );
			$theme = $theme->getPkg();
			$this->msg = "Got theme";
			$this->rsp = array( "theme" => $theme );
			$this->suc = 1;
			$this->finish();
		}

		public function getPopup()
		{
			global $pty;
			$pty->log( 'Start Getting Popup', $this->data );
			$popup = new Pty_Theme( null, null, $this->data['popupid'], array( 'imps' => true ) );
			$this->rsp = array( "popup" => $popup->getPkg() );
			$this->msg = "Loaded Popup";
			$this->suc = 1;
			$this->finish();
		}

		public function ignoreVersion()
		{
			global $pty;
			$pty->log( 'Start Ignoring Version', $this->data );
			if ( isset( $this->data['v'] ) ) {
				update_option( 'pty_ignoreVersion', $this->data['v'] );
				$this->msg = "Version ignored.";
				$this->suc = 1;
			}
			else {
				$this->msg = "No version specified.";
				$this->err = 1;
			}
			$this->finish();
		}

		public function setLogging()
		{
			global $pty;
			if ( isset( $this->data['key'] ) ) {
				$key = get_option( 'pty_key' );
				if ( $this->data['key'] === $key ) {
					if ( $this->data['logging'] == 1 ) {
						update_option( 'pty_logging', time() );
						$this->msg = 'Logging On';
						$this->suc = 1;
					}
					else {
						update_option( 'pty_logging', 0 );
						$this->msg = 'Logging Off';
						$this->suc = 1;
					}
				}
			}
			$this->finish();
		}

		public function setReleaseLim()
		{
			global $pty;
			if ( isset( $this->data['lim'] ) ) {
				$pty->setReleaseLim( $this->data['lim'] );
				$this->msg = "Set to " . $this->data['lim'];
				$this->suc = 1;
			}
			else {
				$this->msg = 'No lim provided.';
				$this->err = 1;
			}
			$this->finish();
		}

	}

}
$dpl_ajax = new TmbAjax();