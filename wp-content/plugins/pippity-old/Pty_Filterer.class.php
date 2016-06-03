<?php

if(!class_exists("Pty_Filterer")){
	class Pty_Filterer{
		public $logged_in;
		public $categories; 
		public $page_id;
		public $is_single;
		function __construct($opts)
		{
			$this->logged_in = is_user_logged_in() ? 1 : 0;
			$this->pagetype = array();
			$this->post_type = get_post_type();
			$this->pagetype[0] = is_single();
			$this->pagetype[1] = is_page();
			$this->pagetype[2] = is_home() || is_front_page();
			$this->pagetype[3] = is_search() || is_archive();
			$this->categories = get_the_category();
			$this->page_id = get_the_ID();
			$this->referrer = isset($opts['referrer']) ? $opts['referrer'] : '';
			$this->referrer = str_replace('https://', '', str_replace('http://', '', trim($this->referrer, '/ ')));
			$this->uri = $_SERVER['REQUEST_URI'];
			$this->uri = '/' . trim($this->uri, '/');
			$this->init_cookies();
		}

		/*
		 * If a pty_ cookie is marked visited, add it to list
		 * of cookies that should be filtered
		 */
		public function init_cookies()
		{
			$this->filterCookies = array();
			foreach ($_COOKIE as $i => $cookie) {
				if (strpos($i, 'pty_') === 0 && $cookie == 'visited') {
					$this->filterCookies[] = $i;
				}
			}
		}

		/*
		 * Get all popups that are active and haven't been
		 * seen (not cookie filtered). 
		 * 
		 * Check filters on each to see if it can be displayed
		 * 
		 * Order by Priority
		 * 
		 * Randomly select from highest priority group
		 */
		public function run_filters()
		{
			global $wpdb, $pty;
			$page_id = $this->page_id;
			$referrer = $this->referrer;
			$cookieWheres = '';
			if (count($this->filterCookies)) {
				$cookieWheres = ' AND (';
				foreach ($this->filterCookies as $c) {
					$cookieWheres .= " (cookie != '$c') AND";
				}
				$cookieWheres = rtrim($cookieWheres, " AND") . ')';
			}
			$popups = $wpdb->get_results("SELECT * FROM " . $pty->t('popups') . " WHERE status = '1' $cookieWheres ");
			$byPriority = array();
			$highPriority = 0;
			foreach ($popups as $i => $popup) {
				if ($this->check_popup($popup)) {
					$priority = $popup->priority;
					if (!isset($byPriority[$priority])) {
						$byPriority[$priority] = array();
					}
					$byPriority[$priority][] = $popup;
					if ($priority > $highPriority) {
						$highPriority = $priority;
					}
				}
			}
			if ($popups && count($byPriority)) {
				if ($byPriority[$highPriority] > 1) {
					$pty->updateAnalytics();
					$least = (object)array(
						"popupid" => 0,
						"imps" => 999999999,
						"inx" => 0,
					);
					foreach($byPriority[$highPriority] as $inx => $poss_pop) {
						$popupid = $poss_pop->popupid;
						$stats = $wpdb->get_row("
							SELECT * FROM " . $pty->t('stats') . " WHERE popupid = '$popupid' AND ts = UNIX_TIMESTAMP(CURDATE())
						");
						if ($stats && $stats->imps < $least->imps) {
							$least->imps = $stats->imps;
							$least->popupid = $stats->popupid;
							$least->inx = $inx;
						}
					}
					if ($least->popupid) {
						return $byPriority[$highPriority][$least->inx];
					}
					else {
						$i = array_rand($byPriority[$highPriority]);
						return $byPriority[$highPriority][$i];
					}
				}
				else {
					return $byPriority[$highPriority][0];
				}
			}
			else {
				return false;
			}
		}

		/*
		 * Check all popups filters within this parent and each
		 * child of each filter (recursively check filters)
		 */
		function check_popup($popup, $parent = 0)
		{
			global $wpdb, $pty;
			$page_id = $this->page_id;
			$referrer = $this->referrer;
			$loggedin = $this->logged_in;
			$uri = $this->uri;
			$filters = $wpdb->get_results("
					SELECT * FROM " . $pty->t('filters') . " 
					WHERE popupid = '" . $popup->popupid . "' AND parentid = '$parent'
			");
			if (!$filters) {
				return true;
			}
			foreach ($filters as $filter) {
				$match = $filter->matchVal;
				$valid = false;
				switch($filter->type) {
					case 'post':
						if ((string)$page_id == (string)$match) {
							$valid = true;
						}
					break;
					case 'referred':
						if ($this->is_referredBy($match)) {
							$valid = true;
						}
					break;
					case 'loggedin':
						if ($loggedin == $match || $match == 2) {
							$valid = true;
						}
					break;
					case 'cat':
						if ($this->has_category($match)) {
							$valid = true;
						}
					break;
					case 'url': 
						if ($this->matches_uri($match)) {
							$valid = true;
						}
					break;
					case 'type': 
						if ($this->matches_post_type($match)) {
							$valid = true;
						}
					break;
					case 'pagetype':
						if (isset($this->pagetype[$match]) && $this->pagetype[$match]) {
							$valid = true;
						}
					break;
					case 'roles':
						if ($this->user_has_role($match)) {
							$valid = true;
						}
					break;
				}
				if (!$filter->isVal) {
					$valid = !$valid;
				}
				if ($valid) {
					if (!$this->check_popup($popup, $filter->filterid)) {
						$valid = false;
					}
					else {
						return true;
					}
				}
			}
			return $valid;
		}

		/*
		 * Check if a given category is in the posts
		 * categories
		 */
		function has_category($match)
		{
			foreach ($this->categories as $cat) {
				if ((string)$cat->cat_ID == (string)$match) {
					return true;
				}
			}
			return false;
		}

		function is_post($match)
		{
			return (string)$page_id === (string)$match;
		}

		function is_referredBy($match)
		{
			return ( strpos($this->referrer, $match) !== false );
		}

		function matches_uri($match)
		{
			$match = str_replace('*', '(.*)', $match);
			$match = '/' . str_replace('/', '\/', $match) . '/s';
			return (bool)preg_match($match, $this->uri);
		}
		function matches_post_type($match) {
			return (string)$this->post_type == (string)$match;
		}
		function user_has_role($match) {
			global $current_user;
			foreach($current_user->roles as $role) {
				if ($match == $role) {
					return true;
				}
			}
			return false;
		}
	}
}