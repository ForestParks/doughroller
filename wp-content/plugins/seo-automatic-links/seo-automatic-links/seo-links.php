<?php

/*
Plugin Name: SEO Smart Links
Version: 1.7.2
Plugin URI: http://www.prelovac.com/vladimir/wordpress-plugins/seo-smart-links
Author: Vladimir Prelovac
Author URI: http://www.prelovac.com/vladimir
Description: SEO Smart Links provides SEO benefits for your blog by inter-linking the articles. 

*/



// Avoid name collisions.
if ( !class_exists('SEOLinks') ) :

class SEOLinks {
	
	// Name for our options in the DB
	var $SEOLinks_DB_option = 'SEOLinks';
	var $SEOLinks_options; 
	
	// Initialize WordPress hooks
	function SEOLinks() {	
	  $options = $this->get_options();
	  if ($options)
	  {
	  	if ($options['post'] || $options['page'])		
				add_filter('the_content',  array(&$this, 'SEOLinks_the_content_filter'), 10);	
			if ($options['comment'])						
				add_filter('comment_text',  array(&$this, 'SEOLinks_comment_text_filter'), 10);	
		}
		
		add_action( 'create_category', array(&$this, 'SEOLinks_delete_cache'));
		add_action( 'edit_category',  array(&$this,'SEOLinks_delete_cache'));
		add_action( 'edit_post',  array(&$this,'SEOLinks_delete_cache'));
		add_action( 'save_post',  array(&$this,'SEOLinks_delete_cache'));
		// Add Options Page
		add_action('admin_menu',  array(&$this, 'SEOLinks_admin_menu'));
	
	}

function SEOLinks_process_text($text, $mode)
{

	global $wpdb, $post;
	
	$options = $this->get_options();

	$links=0;

	

	if ($options['onlysingle'] && !(is_single() || is_page()))
		return $text;
	
	if (!$mode)
	{
		if ($post->post_type=='post' && !$options['post'])
			return $text;
		else if ($post->post_type=='page' && !$options['page'])
			return $text;
		
		if (($post->post_type=='page' && !$options['pageself']) || ($post->post_type=='post' && !$options['postself']))
			$add=', '.$post->post_title;
		else
			$add='';
	
	}
		
	$maxlinks=($options['maxlinks']>0) ? $options['maxlinks'] : 0;
	
	$maxsingle=($options['maxsingle']>0) ? $options['maxsingle'] : -1;
	
	
	
	
	$arrignore=$this->explode_trim(",", strtolower($options['ignore'].$add));
	
	$text = " $text ";

	// custom keywords
	if (!empty($options['customkey']))
	{
		
		$kw_array = array();
		foreach (explode("\n", $options['customkey']) as $line) {
			list($keyword, $url) = array_map('trim', explode(",", $line, 2));
			if (!empty($keyword)) $kw_array[$keyword] = $url;
		}
				
		foreach ($kw_array as $name=>$url) 
		{
			
			if (!$maxlinks || ($links < $maxlinks) && !in_array( strtolower($name), $arrignore) && strstr($text, $name))
			{
				$name= preg_quote($name, '/');
				$regexp="/(?!(?:[^<]+>|[^>]+<\/a>))\b($name)\b/imsU";
				$replace="<a title=\"$1\" href=\"$url\">$1</a>";
				
				$newtext = preg_replace($regexp, $replace, $text, $maxsingle);			
				if ($newtext!=$text) {					
					$links++;
					$text=$newtext;
				}	
				
			}		
		}
	}

	// posts and pages
	if ($options['lposts'] || $options['lpages'])
	{
		if ( !$posts = wp_cache_get( 'seo-links-posts', 'seo-smart-links' ) ) {
	
	
			$query="SELECT post_title, ID, guid, post_type FROM $wpdb->posts WHERE post_status = 'publish' AND LENGTH(post_title)>3 ORDER BY LENGTH(post_title) DESC";
			$posts = $wpdb->get_results($query);
			
			wp_cache_add( 'seo-links-posts', $posts, 'seo-smart-links', 86400 );
		}
	
		
		foreach ($posts as $postitem) 
		{
			if (($options['lposts'] && $postitem->post_type=='post') || ($options['lpages'] && $postitem->post_type=='page') &&
			!$maxlinks || ($links < $maxlinks) && !in_array( strtolower($postitem->post_title), $arrignore)  && strstr($text, $postitem->post_title) )
				{
					$name = preg_quote($postitem->post_title, '/');		
					$regexp="/(?!(?:[^<]+>|[^>]+<\/a>))\b($name)\b/imsU";
					$replace='<a title="$1" href="$$$url$$$">$1</a>';
				
					$newtext = preg_replace($regexp, $replace, $text, $maxsingle);
					if ($newtext!=$text) {		
						$url = get_permalink($postitem->ID);							
						$links++;
						$text=str_replace('$$$url$$$', $url, $newtext);	
					}		
				}
		}
	}
	
	// categories
	if ($options['lcats'])
	{
		if ( !$categories = wp_cache_get( 'seo-links-categories', 'seo-smart-links' ) ) {
			
			$query="SELECT $wpdb->terms.name, $wpdb->terms.term_id FROM $wpdb->terms LEFT JOIN $wpdb->term_taxonomy ON $wpdb->terms.term_id = $wpdb->term_taxonomy.term_id WHERE $wpdb->term_taxonomy.taxonomy = 'category'  AND LENGTH($wpdb->terms.name)>3  ORDER BY LENGTH($wpdb->terms.name) DESC";
			$categories = $wpdb->get_results($query);
		
			wp_cache_add( 'seo-links-categories', $categories, 'seo-smart-links',86400 );
		}
	
		foreach ($categories as $cat) 
		{
			if (!$maxlinks || ($links < $maxlinks) && !in_array( strtolower($cat->name), $arrignore) && strstr($text, $cat->name) )
			{
					$name= preg_quote($cat->name, '/');	
					$regexp="/(?!(?:[^<]+>|[^>]+<\/a>))\b($name)\b/imsU";
					$replace='<a title="$1" href="$$$url$$$">$1</a>';
				
					$newtext = preg_replace($regexp, $replace, $text, $maxsingle);
					if ($newtext!=$text) {						
						$url = (get_category_link($cat->term_id));				
						$links++;
						$text=str_replace('$$$url$$$', $url, $newtext);	
					}	
				
			}		
		}
	}
	
	// tags
	if ($options['ltags'])
	{
		
		if ( !$tags = wp_cache_get( 'seo-links-tags', 'seo-smart-links' ) ) {
			
			$query="SELECT $wpdb->terms.name, $wpdb->terms.term_id FROM $wpdb->terms LEFT JOIN $wpdb->term_taxonomy ON $wpdb->terms.term_id = $wpdb->term_taxonomy.term_id WHERE $wpdb->term_taxonomy.taxonomy = 'post_tag'  AND LENGTH($wpdb->terms.name)>3  ORDER BY LENGTH($wpdb->terms.name) DESC";	
			$tags = $wpdb->get_results($query);
			
			wp_cache_add( 'seo-links-tags', $tags, 'seo-smart-links',86400 );
		}
		
		foreach ($tags as $tag) 
		{
			if (!$maxlinks || ($links < $maxlinks) && !in_array( strtolower($name), $arrignore)  && strstr($text, $tag->name))
			{
				$name = preg_quote($tag->name, '/');	
				$regexp="/(?!(?:[^<]+>|[^>]+<\/a>))\b($name)\b/imsU";
				$replace='<a title="$1" href="$$$url$$$">$1</a>';
								
				$newtext = preg_replace($regexp, $replace, $text, $maxsingle);
				if ($newtext!=$text) {
					$url = (get_tag_link($tag->term_id));						
					$links++;
					$text=str_replace('$$$url$$$', $url, $newtext);	
				}			
			}
		}
	}
	
	return trim( $text );

} 

function SEOLinks_the_content_filter($text) {
	
	$result=$this->SEOLinks_process_text($text, 0);
	
	$options = $this->get_options();
	$link=parse_url(get_bloginfo('wpurl'));
	$host='http://'.$link['host'];
	
	if ($options['blanko'])
		$result = preg_replace('%<a(\s+.*?href=\S(?!' . $host . '))%i', '<a target="_blank"\\1', $result); // credit to  Kaf Oseo
	if ($options['nofolo'])	
		$result = preg_replace('%<a(\s+.*?href=\S(?!' . $host . '))%i', '<a rel="nofollow"\\1', $result); 
	return $result;
}

function SEOLinks_comment_text_filter($text) {
	$result = $this->SEOLinks_process_text($text, 1);
	
	$options = $this->get_options();
	$link=parse_url(get_bloginfo('wpurl'));
	$host='http://'.$link['host'];

	if ($options['blanko'])
		$result = preg_replace('%<a(\s+.*?href=\S(?!' . $host . '))%i', '<a target="_blank"\\1', $result); // credit to  Kaf Oseo
		
	if ($options['nofolo'])	
		$result = preg_replace('%<a(\s+.*?href=\S(?!' . $host . '))%i', '<a rel="nofollow"\\1', $result); 
		
	return $result;
}
	
	function explode_trim($separator, $text)
{
    $arr = explode($separator, $text);
    
    $ret = array();
    foreach($arr as $e)
    {        
      $ret[] = trim($e);        
    }
    return $ret;
}
	
	// Handle our options
	function get_options() {
	   
 $options = array(
	 'post' => 'on',
	 'postself' => '',
	 'page' => 'on',
	 'pageself' => '',
	 'comment' => '',
	 'lposts' => 'on', 
	 'lpages' => 'on',
	 'lcats' => '', 
	 'ltags' => '', 
	 'ignore' => 'about,', 
	 'maxlinks' => 3,
	 'maxsingle' => 1,
	 'customkey' => '',
	 'nofoln' =>'',
	 'nofolo' =>'',
	 'blankn' =>'',
	 'blanko' =>'',
	 'onlysingle' => 'on'
	 
	 );
	 
        $saved = get_option($this->SEOLinks_DB_option);
 
 
 if (!empty($saved)) {
	 foreach ($saved as $key => $option)
 			$options[$key] = $option;
 }
	
 if ($saved != $options)	
 	update_option($this->SEOLinks_DB_option, $options);
 	
 return $options;
	
	}



	// Set up everything
	function install() {
		$SEOLinks_options = $this->get_options();		
		
		
	}
	
	function handle_options()
	{
		
		$options = $this->get_options();
		if ( isset($_POST['submitted']) ) {
		
			
			$options['post']=$_POST['post'];					
			$options['postself']=$_POST['postself'];					
			$options['page']=$_POST['page'];					
			$options['pageself']=$_POST['pageself'];					
			$options['comment']=$_POST['comment'];					
			$options['lposts']=$_POST['lposts'];					
			$options['lpages']=$_POST['lpages'];					
			$options['lcats']=$_POST['lcats'];					
			$options['ltags']=$_POST['ltags'];					
			$options['ignore']=$_POST['ignore'];					
			$options['maxlinks']=(int) $_POST['maxlinks'];					
			$options['maxsingle']=(int) $_POST['maxsingle'];					
			$options['customkey']=$_POST['customkey'];	
			$options['nofoln']=$_POST['nofoln'];		
			$options['nofolo']=$_POST['nofolo'];	
			$options['blankn']=$_POST['blankn'];	
			$options['blanko']=$_POST['blanko'];	
			$options['onlysingle']=$_POST['onlysingle'];	
		
			
			update_option($this->SEOLinks_DB_option, $options);
			$this->SEOLinks_delete_cache(0);
			echo '<div class="updated fade"><p>Plugin settings saved.</p></div>';
		}

		
	

		$action_url = $_SERVER['REQUEST_URI'];	

		$post=$options['post']=='on'?'checked':'';
		$postself=$options['postself']=='on'?'checked':'';
		$page=$options['page']=='on'?'checked':'';
		$pageself=$options['pageself']=='on'?'checked':'';
		$comment=$options['comment']=='on'?'checked':'';
		$lposts=$options['lposts']=='on'?'checked':'';
		$lpages=$options['lpages']=='on'?'checked':'';
		$lcats=$options['lcats']=='on'?'checked':'';
		$ltags=$options['ltags']=='on'?'checked':'';
		$ignore=$options['ignore'];
		$maxlinks=$options['maxlinks'];
		$maxsingle=$options['maxsingle'];
		$customkey=$options['customkey'];
		$nofoln=$options['nofoln']=='on'?'checked':'';
		$nofolo=$options['nofolo']=='on'?'checked':'';
		$blankn=$options['blankn']=='on'?'checked':'';
		$blanko=$options['blanko']=='on'?'checked':'';
		$onlysingle=$options['onlysingle']=='on'?'checked':'';
		
			
		$imgpath=trailingslashit(get_option('siteurl')). 'wp-content/plugins/seo-automatic-links/i';	
		echo <<<END

<div class="wrap" style="max-width:950px !important;">
	<h2>SEO Smart Links</h2>
				
	<div id="poststuff" style="margin-top:10px;">

	 <div id="sideblock" style="float:right;width:220px;margin-left:10px;"> 
		 <h3>Information</h3>
		 <div id="dbx-content" style="text-decoration:none;">
			 <img src="$imgpath/home.png"><a style="text-decoration:none;" href="http://www.prelovac.com/vladimir/wordpress-plugins/seo-smart-links"> SEO Smart Links Home</a><br /><br />
			 <img src="$imgpath/help.png"><a style="text-decoration:none;" href="http://www.prelovac.com/vladimir/forum"> Plugin Forums</a><br /><br />
			  <img src="$imgpath/rate.png"><a style="text-decoration:none;" href="http://wordpress.org/extend/plugins/seo-automatic-links/"> Rate SEO Smart Links</a><br /><br />
			 <img src="$imgpath/more.png"><a style="text-decoration:none;" href="http://www.prelovac.com/vladimir/wordpress-plugins"> My WordPress Plugins</a><br /><br />
			 <br />
		
			 <p align="center">
			 <img src="$imgpath/p1.png"></p>
			
			 <p> <img src="$imgpath/idea.png"><a style="text-decoration:none;" href="http://www.prelovac.com/vladimir/services"> Need a WordPress Expert?</a></p>
 		</div>
 	</div>

	 <div id="mainblock" style="width:710px">
	 
		<div class="dbx-content">
		 	<form name="SEOLinks" action="$action_url" method="post">
					<input type="hidden" name="submitted" value="1" /> 
					<h3>Overview</h3>
					
					<p>SEO Smart Links can automatically link keywords and phrases in your posts and comments with corresponding posts, pages, categories and tags on your blog.</p>
					<p>Further SEO Smart links allows you to set up your own keywords and set of matching URLs.</p>
					<p>Finally SEO Smart links allows you to set nofollow attribute and open links in new window.</p>
					
					<h3>Internal Links</h3>
					<p>SEO Smart Links can process your posts, pages and comments in search for keywords to automatically interlink.</p>
					<input type="checkbox" name="post"  $post/><label for="post"> Posts</label>
					<ul><input type="checkbox" name="postself"  $postself/><label for="postself"> Allow links to itself</label></ul>
					<input type="checkbox" name="page"  $page/><label for="page"> Pages</label>
					<ul><input type="checkbox" name="pageself"  $pageself/><label for="pageself"> Allow links to itself</label></ul>
					<input type="checkbox" name="comment"  $comment /><label for="comment"> Comments</label> (may slow down performance) <br>
		
					<h4>Target</h4>
					<p>The targets SEO Smart links should consider. The match will be based on post/page title or category/tag name, case insensitive.</p>
					<input type="checkbox" name="lposts" $lposts /><label for="lposts"> Posts</label>  <br>
					<input type="checkbox" name="lpages" $lpages /><label for="lpages"> Pages</label>  <br>
					<input type="checkbox" name="lcats" $lcats /><label for="lcats"> Categories</label> (may slow down performance)  <br>
					<input type="checkbox" name="ltags" $ltags /><label for="ltags"> Tags</label> (may slow down performance)  <br>
					
					<h4>Only single</h4>
					<p>To reduce database load you can choose to have SEO SMART links work only on single posts and pages (for example not on main page or archives).</p>
					<input type="checkbox" name="onlysingle" $onlysingle /><label for="onlysingle"> Process only single posts and pages</label>  <br>
					
					<h4>Ignore</h4>				
					<p>You may wish to ignore certain words or phrases from automatic linking. Seperate them by comma.</p>
					<input type="text" name="ignore" size="90" value="$ignore"/> 
					 <br><br>
					<h3>Custom Keywords</h3>
					<p>Here you can enter manually the extra keywords you want to automaticaly link. Use comma to seperate keyword and url, next one in next line and so on. You can have these keywords link to any url, not only your site.</p>
					<p>Example:<br />
					google, http://www.google.com<br />
					vladimir prelovac, http://www.prelovac.com/vladimir
					</p>
					
					<textarea name="customkey" id="customkey" rows="15" cols="80"  >$customkey</textarea>
					<br><br>
					
					<h3>Limits</h3>				
					<p>You can limit the maximum number of links SEO Smart Links will generate per post. Set to 0 for no limit. </p>
					Max Links: <input type="text" name="maxlinks" size="2" value="$maxlinks"/>  
					<p>You can also limit maximum number of links created with the same keyword. Set to 0 for no limit. </p>
					Max Single: <input type="text" name="maxsingle" size="2" value="$maxsingle"/>  
					 <br><br>
					<h3>External Links</h3>			
					<p>SEO Smart links can open external links in new window and add nofollow attribute.</p>
					
				
					<input type="checkbox" name="nofolo" $nofolo /><label for="nofolo"> Add nofollow attribute</label>  <br>
					
				
					<input type="checkbox" name="blanko" $blanko /><label for="blanko"> Open in new window</label>  <br>
					
					
					<div class="submit"><input type="submit" name="Submit" value="Update" /></div>
			</form>
		</div>
		
		<br/><br/><h3>&nbsp;</h3>	
	 </div>

	</div>
	
<h5>WordPress plugin by <a href="http://www.prelovac.com/vladimir/">Vladimir Prelovac</a></h5>
</div>
END;
		
		
	}
	
	function SEOLinks_admin_menu()
	{
		add_options_page('SEO Smart Links Options', 'SEO Smart Links', 8, basename(__FILE__), array(&$this, 'handle_options'));
	}

function SEOLinks_delete_cache($id) {
	 wp_cache_delete( 'seo-links-categories', 'seo-smart-links' );
	 wp_cache_delete( 'seo-links-tags', 'seo-smart-links' );
	 wp_cache_delete( 'seo-links-posts', 'seo-smart-links' );
}
//add_action( 'comment_post', 'SEOLinks_delete_cache');
//add_action( 'wp_set_comment_status', 'SEOLinks_delete_cache');


}

endif; 

if ( class_exists('SEOLinks') ) :
	
	$SEOLinks = new SEOLinks();
	if (isset($SEOLinks)) {
		register_activation_hook( __FILE__, array(&$SEOLinks, 'install') );
	}
endif;

?>