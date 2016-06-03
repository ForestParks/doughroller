<?php


global $wpdb;
if ( !class_exists( "Pty_Theme" ) ) {

	class Pty_Theme
	{

		public $settings;
		public $name;
		public $path;
		public $author;
		public $descr;
		public $date;
		public $tags;
		public $numInputs;
		public $htmlField;
		public $textField;
		public $inputField;
		public $fieldOrder;
		public $preload;
		public $x;
		public $y;
		public $failed;

		function __construct( $confs=false, $row=false, $popupid=false, $opts = array( ) )
		{
			global $pty;
			if ( !$confs && !$row && !$popupid ) {
				$this->failed = true;
				return false;
			}
			if ( $popupid ) {
				global $wpdb;
				$row = $wpdb->get_row( "SELECT * FROM " . $pty->t( 'popups' ) . " WHERE popupid = '$popupid'" );
				if ( !is_object( $row ) ) {
					$this->failed = true;
					return false;
				}
			}
			if ( is_object( $row ) ) {
				if (strpos($row->settings, '_____enc______') === 0) {
					$row->settings = base64_decode(str_replace('_____enc______', '', $row->settings));
				}
				$settings = unserialize( $row->settings );
				if ($settings) {
					$confs = json_decode( json_encode( $settings ) );
					$this->raw = $row->settings;
				}
				else {
					$this->failed = true;
					return false;
				}
			}
			$this->confs = $confs;
			$this->processConfs();
			$this->formType = $this->getFormType();
			if ( $row ) {
				$this->popupid = $row->popupid;
				$this->status = $row->status;
				$this->step = $row->step;
				$this->cookie = $row->cookie;
				$this->priority = $row->priority;
				$this->label = strlen($row->label) ? $row->label : 'Popup #' . $row->popupid;
				$this->statusOut = $this->getStatusOut();
			}
			$this->init();
			if (!file_exists($this->path)) {
				$this->failed = true;
				return false;
			}
			$this->initFiles( $opts );
			$this->initAnalytics( $opts );
			$this->setPreloadImages();
			$this->setPreloadFonts();
		}

		public function init()
		{
			$this->path = PTY_DIR . '/themes/' . $this->file . '/';
			$this->url = PTY_URL . '/themes/' . $this->file . '/';
			$this->img = $this->path . $this->file . '.png';
			$this->imgUrl = $this->url . $this->file . '.png';
		}

		public function initFiles( $opts )
		{

			// Set the main Files
			$this->html = str_replace( array( '\t', '\n' ), '', $this->getFile( 'html' ) );
			$this->js = $this->getFile( 'js' );
			$this->json = '{' . $this->getFile( 'conf' ) . '}';
			$this->parseCss( $opts );
			$this->parseHtml( $opts );
			$this->initCache( $opts );
		}

		public function parseCss( $opts )
		{
			// Parse images correctly in css
			$imgDir = $this->url . 'images/';
			$temp = '';
			$this->css = $this->getFile('css');
			$this->css = str_replace('url("', 'url(', $this->css);
			$this->css = str_replace('url(\'', 'url(', $this->css);
			$this->css = str_replace('\')', ')', $this->css);
			$this->css = str_replace('")', ')', $this->css);
			$this->css = str_replace('url(', 'url(' . $imgDir, $this->css);
			$this->css = str_replace('url(' . $imgDir . 'pty_images', 'url(' . PTY_URL . '/images', $this->css);
			foreach (explode("\n", $this->css) as $l) {
				if ((isset($opts['nocache']) && $opts['nocache']) || isset($_GET['nocache'])) {
					if (strpos($l, ':') !== false && strpos($l, 'url') === false) {
				//		continue;
					}
				}
				if ( strpos( $l, 'opacity:' ) !== false ) {
					$bits = explode( ':', $l );
					$temp .= "filter:alpha(opacity=" . $bits[1] * 100 . ");";
				}

				$temp .= " $l";
			}
			$this->css = $temp;
			$this->css = file_get_contents( PTY_DIR . '/css/pippity_base.css' ) . $this->css;
			$this->css = str_replace( array( '\t', '\n' ), '', $this->css );
		}

		public function parseHtml( $opts )
		{
			$temp = '';
			foreach ( explode( "\n", $this->html ) as $l ) {
				if ( strpos( $l, "<!--test-->" ) === false || !isset( $opts['active'] ) || !$opts['active'] ) {
					$temp .= $l;
				}
			}
			$this->html = $temp;
		}

		public function initAnalytics( $opts )
		{
			global $pty;
			if ( isset( $opts['imps'] ) ) {
				$this->analyzePopup( $this );
			}
		}
		
		/*
		 * Analytics
		 */
		public function analyzePopup( $p, $start = false, $end = false, $raw = false )
		{
			global $wpdb, $pty;
			if ( !$start )
				$start = 0;
			if ( !$end )
				$end = time();

			$query = "select sum(imps) as imps, sum(convs) as convs, sum(convs)/sum(imps) as crate, avg(onpopup) as onpopup, avg(onpage) as  onpage, avg(convertdelay) as convertdelay from " . $pty->t('stats') . " where ts between '$start' and '$end' and popupid='{$p->popupid}'";
			$imps = $wpdb->get_results( $query );
			if ($imps) {
				$i = $imps[0];
				$a['conversions'] = 1 * $i->convs;
				$a['impressions'] = 1 * $i->imps;
				$a['closeTime'] = 1 * number_format( 1 * $i->onpopup, 2 );
				$a['leaveTime'] = 1 * number_format( 1 * $i->onpage, 2 );
				$a['cRate'] = $i->crate * 100;
				$a['convertTime'] = 1 * $i->convertdelay;

				// DAILY
				$query = "select ts, imps, convs, convs/imps as crate, onpage, onpopup, convertdelay from " . $pty->t('stats') . " where ts between '$start' and '$end' and popupid='{$p->popupid}'";
				$imps = $wpdb->get_results( $query );
				$a['daily'] = array( );
				foreach ( $imps as $i ) {
					$a['daily'][$i->ts]['closeTime'] = 1 * number_format( 1 * $i->onpopup, 2 );
					$a['daily'][$i->ts]['leaveTime'] = 1 * number_format( 1 * $i->onpage, 2 );
					$a['daily'][$i->ts]['convs'] = 1 * $i->convs;
					$a['daily'][$i->ts]['crate'] = 1 * $i->crate * 100;
					$a['daily'][$i->ts]['imps'] = 1 * $i->imps;
					$a['daily'][$i->ts]['convertTime'] = 1 * $i->convertdelay;
				}

				$p->a = $a;
				return $a;
			}
			else {
				return array();
			}
		}

		public function initCache( $opts )
		{
			// Prevent cache if we're editing
			if ( (isset( $opts['nocache'] ) && $opts['nocache']) || isset( $_GET['nocache'] ) ) {
				$endings = array( 'png', 'jpg', 'gif' );
				foreach ( $endings as $e ) {
					$this->css = str_replace( ".$e", ".$e?" . uniqid(), $this->css );
				}
				$this->html = str_replace( '.css"', '.css?' . uniqid() . '"', $this->html );
				$this->html = str_replace( '.css\'', '.css?' . uniqid() . '\'', $this->html );
			}
		}

		public function processConfs()
		{
			foreach ( $this->confs as $i => $conf ) {
				$ignore = array( "popupid", "label", "status", "statusOut" );
				if ( isset( $conf ) && $conf != 'null' && $conf != null && !in_array( $i, $ignore ) ) {
					$this->$i = $conf;
				}
			}
		}

		public function setPreloadImages()
		{
			global $pty;
			$preload = array( );
			foreach ( scandir( $this->path . 'images/' ) as $img ) {
				$exts = array( "png", "jpg", "gif" );
				foreach ( $exts as $ext ) {
					if ( strpos( $img, $ext ) !== false && strpos( $img, '__' ) === false ) {
						$preload[] = $this->url . 'images/' . $img;
						break;
					}
				}
			}
			foreach ( $this->copy as $item ) {
				if ( $item->type == 'image' ) {
					if ( strlen( $item->src ) > 0 ) {
						$preload[] = $item->src;
					}
				}
			}
			if ( isset( $this->styleImgs ) ) {
				foreach ( $this->styleImgs as $key => $img ) {
					$preload[] = $this->url . 'images/' . $key . '__' . $img . '.png';
				}
			}
			$pty->allImages = array_merge( $pty->allImages, $preload );
			$this->preload = $preload;
		}

		public function setPreloadFonts()
		{
			$css = $this->css;
			$fonts = array( );
			foreach ( explode( '}', $css ) as $chunk ) {
				global $pty;
				foreach ( explode( '{', $chunk ) as $justDefs ) {
					if ( strpos( $justDefs, ';' ) !== false ) {
						foreach ( explode( ';', $justDefs ) as $def ) {
							$props = explode( ':', $def );
							$props[1] = isset( $props[1] ) ? str_replace( '"', '', trim( $props[1] ) ) : '';
							if ( trim( $props[0] ) == 'font-family' && !in_array( $props[1], $fonts ) ) {
								$fonts[] = $props[1];
								if ( !in_array( $props[1], $pty->allFonts ) ) {
									$pty->allFonts[] = $props[1];
								}
							}
						}
					}
				}
			}
			$this->families = $fonts;
			if ( isset( $this->fonts ) ) {
				foreach ( $this->fonts as $f ) {
					if ( !in_array( $f, $pty->allFamilies ) ) {
						$pty->allFamilies[] = $f;
					}
				}
			}
		}

		public function getMeta()
		{
			$meta = array( );
			$meta['sImgs'] = $this->getStylableImages();
			$meta['styles'] = $this->getStyles();
			return $meta;
		}

		public function getStylableImages()
		{
			$sImgs = array( );
			foreach ( scandir( $this->path . 'images' ) as $img ) {
				if ( $this->isStylableImage( $img ) ) {
					$img = explode( '.', $img );
					$img = explode( '__', $img[0] );
					$imgKey = $img[0];
					if ( !isset( $sImgs[$imgKey] ) || !is_array( $sImgs[$imgKey] ) ) {
						$sImgs[$imgKey] = array( );
					}
					$sImgs[$imgKey][] = $img[1];
				}
			}
			return $sImgs;
		}

		public function getStyles()
		{
			$styles = array( );
			foreach ( scandir( $this->path ) as $file ) {
				$style = $this->fileToStyle( $file );
				if ( $style ) {
					$styles[$style['key']] = $style['content'];
				}
			}
			if ( file_exists( $this->path . 'styles' ) ) {
				foreach ( scandir( $this->path . 'styles' ) as $file ) {
					$style = $this->fileToStyle( $file, 'styles/' );
					if ( $style ) {
						$styles[$style['key']] = $style['content'];
					}
				}
			}
			return $styles;
		}

		public function fileToStyle( $file, $append = '' )
		{
			if ( $this->isStylableConf( $file ) ) {
				$fname = $file;
				$file = explode( '.', $file );
				$file = explode( '__', $file[0] );
				$fileKey = $file[0];
				$style = json_decode( '{' . file_get_contents( $this->path . $append . $fname ) . '}' );
				if ( !isset( $style->customCss ) ) {
					$style->customCss = '';
				}
				if ( !isset( $style->styleImgs ) ) {
					$style->styleImgs = new StdClass();
				}
				if ( !isset( $style->styleCopy ) ) {
					$style->styleCopy = new StdClass();
				}
				return array( "key" => $file[1], "content" => $style );
			}
			return false;
		}

		public function isStylableImage( $img )
		{
			if ( strpos( $img, '__' ) !== false ) {
				return true;
			}
			else {
				return false;
			}
		}

		public function isStylableConf( $file )
		{
			return $this->isStylableImage( $file );
		}

		public function unSlug( $str )
		{
			$str = explode( '-', $str );
			foreach ( $str as $i => $w ) {
				$str[$i] = ucfirst( $w );
			}
			$str = implode( ' ', $str );
			return $str;
		}

		public function getStatusOut()
		{
			if ( $this->status ) {
				return 'Active';
			}
			else {
				return 'Inactive';
			}
		}

		public function getFormType()
		{
			if ( !isset($this->form) || !isset( $this->form->type ) ) {
				$type = '';
			}
			else {
				$type = $this->form->type;
			}
			switch ($type) {
				case 'aweber':
					return 'Aweber';
					break;
				case 'mailchimp':
					return 'MailChimp';
					break;
				case 'madmimi':
					return 'Mad Mimi';
					break;
				default:
					return 'None';
					break;
			}
		}

		public function getJson()
		{
			return json_encode( $this->getPkg() );
		}

		public function getPkg()
		{
			$pkg = $this->confs;
			if ( isset( $this->parent ) ) {
				$pkg->parent = $this->parent;
			}
			$pkg->filters = $this->getFilters();
			$pkg->css = $this->css;
			$pkg->html = $this->html;
			$pkg->js = $this->js;
			$pkg->img = $this->img;
			$pkg->url = $this->url;
			$pkg->status = 0;
			if (isset($this->cookie)) {
				$pkg->cookie = $this->cookie;
			}
			if (isset($this->status) && isset($this->status)) {
				$pkg->status = (int)$this->status;
			}
			if ( isset( $this->step ) ) {
				$pkg->step = $this->step;
			}
			$pkg->imgUrl = $this->imgUrl;
			$pkg->label = isset( $this->label ) ? $this->label : null;
			$pkg->popupid = isset( $this->popupid ) ? $this->popupid : null;
			$pkg->preload = $this->preload;
			$pkg->families = $this->families;
			if (isset($this->customForm)) {
				$pkg->customForm = stripslashes($this->customForm);
			}
			if ( isset( $this->a ) ) {
				$pkg->a = $this->a;
			}
			return $pkg;
		}

		public function getFile( $ext )
		{
			$file = $this->file;
			$path = $this->path;
			$contents = '';
			$path = $path . $file . '.' . $ext;
			if ( file_exists( $path ) ) {
				$contents = file_get_contents( $path );
			}
			return $contents;
		}

		public function activate()
		{
			global $pty, $wpdb;
			$wpdb->query(
					"UPDATE " . $pty->t( 'popups' ) . " SET status = '1' WHERE popupid = '{$this->popupid}'"
			);
		}

		public function deactivate()
		{
			global $wpdb, $pty;
			$js = PTY_DIR . '/js/active/' . md5( $this->popupid ) . '.js';
			if ( file_exists( $js ) ) {
				unlink( $js );
			}
			$wpdb->query(
					"UPDATE " . $pty->t( 'popups' ) . " SET status = '0' WHERE popupid = '{$this->popupid}'"
			);
		}

		public function reactivate()
		{
			if ( $this->status ) {
				$this->deactivate();
				$this->activate();
			}
		}

		/*
		 * Filters
		 */
		public function clearFilters()
		{
			global $wpdb, $pty;
			$table = $pty->t('filters');
			$popupid = $this->popupid;
			$wpdb->query("DELETE FROM $table WHERE popupid = '$popupid'");
			return true;
		}
		public function addFilters($filters, $parentid = 0)
		{
			global $wpdb, $pty;
			foreach ($filters as $filter) {
				$filter = (array)$filter;
				if (isset($filter['isVal'])) {
					$filter['is'] = $filter['isVal'];
				}
				$vals = array(
					"popupid" => $this->popupid,
					"parentid" => $parentid,
					"isVal" => $filter['is'],
					"type" => $filter['type'],
					"matchVal" => $filter['matchVal'],
					"matchStr" => $filter['matchStr']
				);
				$types = array( "%d", "%d", "%d", "%s", "%s", "%s" );
				$wpdb->insert($pty->t('filters'), $vals, $types);
				if (isset($filter['and']) && is_array($filter['and'])) {
					$this->addFilters($filter['and'], $wpdb->insert_id);
				}
			}
		}
		public function getFilters($parent = 0)
		{
			global $wpdb, $pty;
			
			if (isset($this->popupid)) {
				$popupid = $this->popupid;
			}
			else {
				return false;
			}
			$filters = $wpdb->get_results("SELECT * FROM " . $pty->t("filters") . " WHERE popupid = '$popupid' and parentid = '$parent'");
			$filterSet = array();
			foreach ($filters as $filter) {
				$f = new StdClass();
				$f->isVal = $filter->isVal;
				$f->type = $filter->type;
				$f->matchVal = $filter->matchVal;
				$f->matchStr = $filter->matchStr;
				$f->and = $this->getFilters($filter->filterid);
				$filterSet[] = $f;
			}
			return $filterSet;
		}
	}

}
