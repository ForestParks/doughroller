<?php
	/* reviews widget
	*/

	// Prevent loading this file directly - Busted!
	if ( ! class_exists( 'WP' ) )
	{
		header( 'Status: 403 Forbidden' );
		header( 'HTTP/1.1 403 Forbidden' );
		exit;
	}
	
	// actions
	add_action( 'widgets_init', 'ar_widget_recent_reviews_img' );		//load widget widget

	// register the widget
	function ar_widget_recent_reviews_img() {
		register_widget( 'WP_Widget_Recent_Reviews_Img' );
	}

/**
 * Recent_Reviews widget class
 */
class WP_Widget_Recent_Reviews_Img extends WP_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'widget_recent_reviews_img', 'description' => __( "Display post reviews with screenshots on your site") );
		parent::__construct('recent-reviews-img', __('Reviews with Screenshots'), $widget_ops);
		$this->alt_option_name = 'widget_recent_reviews_img';

		add_action( 'save_post', array(&$this, 'flush_widget_cache') );
		add_action( 'deleted_post', array(&$this, 'flush_widget_cache') );
		add_action( 'switch_theme', array(&$this, 'flush_widget_cache') );
	}

	function widget($args, $instance) {
		$cache = wp_cache_get('widget_recent_reviews', 'widget');
		//$rating_name = 'Set Product Name'; // define

		if ( !is_array($cache) )
			$cache = array();

		if ( isset($cache[$args['widget_id']]) ) {
			echo $cache[$args['widget_id']];
			return;
		}

		ob_start();
		extract($args);

		$title = apply_filters('widget_title', empty($instance['title']) ? __('Recent Reviews') : $instance['title'], $instance, $this->id_base);
		
		if ( ! $number = absint( $instance['number'] ) )
 			$number = 10;
		
		// set order
		if (isset($instance['order'])) {$order = 'ta_post_review_rating';} else {$order = 'date';}

		$r = new WP_Query(array(
									'posts_per_page' => $number,
									'no_found_rows' => true,
									'post_status' => 'publish',
									'meta_key'=>'ta_post_review_rating',
									'ignore_sticky_posts' => true,
									'orderby'=> $order,
									'order'=> 'DESC',
								));
								
		if ($r->have_posts()) :
?>
		<?php echo $before_widget; ?>
		<?php if ( $title ) echo $before_title . $title . $after_title; ?>
		
        
		
		<?php  while ($r->have_posts()) : $r->the_post(); ?>
		
        <?php
        
		// get custom vaules
		$custom = get_post_custom();
		
		// check rating
		if ((isset($custom["ta_post_review_name"][0]))) {$rating_name = $custom["ta_post_review_name"][0];}
		else {$rating_name = '';}	// get title
		
		// do the math
		$rating = $custom["ta_post_review_rating"][0];	// get rating
		$rating_star = $rating * 20;	// calculate rating
		
		// get screenshot
		if ((isset($custom["ta_post_screenshot"][0]))) {$review_screenshot = $custom["ta_post_screenshot"][0];}
		else {$review_screenshot = '';}
		
		// get permalink and title
		$permalink = get_permalink();
		$title = esc_attr(get_the_title() ? get_the_title() : get_the_ID());
		
		
		// start display item
		echo '<div class="widget_review_item_img">';
        	
			if ($instance['screenshot']) {
			// check review screenshot field
							if ((isset($custom["ta_post_review_image"][0]))) {
								
								    $image = $custom["ta_post_review_image"][0];
			
								//echo '<div class="widget_review_screenshot">';
								echo '<a href="'.$permalink.'" title="'.$title.'">';
								echo '<img class="widget_review_screenshot_img" src="' . $image . '" width="40" height="40" alt="" />';
								echo '</a>';
								//echo '</div>';
								
							}
			// end of check review screenshot field
			}
			
			echo '<p>';
			// display the link
			echo '<a href="'.$permalink.'" title="'.$title.'">';
			if($rating_name) echo $rating_name; else echo get_the_ID();
			echo '</a>';
			echo '<br>';
			// display rating stars
            echo '<span class="ta_rating result rating ta_widget_rating_img">';
			echo '<span class="result" style="width:' . $rating_star . '%;" title="' . $rating . '"></span>';
			echo '</span>';
			echo '</p>';
        
		// end display item    
		echo '</div>';
		
		endwhile;
		
		// display after widget
		echo $after_widget;
		
		// Reset the global $the_post as this query will have stomped on it
		wp_reset_postdata();

		endif;

		$cache[$args['widget_id']] = ob_get_flush();
		wp_cache_set('widget_recent_reviews', $cache, 'widget');
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = (int) $new_instance['number'];
		$instance['screenshot'] = $new_instance['screenshot'];
		$instance['order'] = $new_instance['order'];
		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['widget_recent_entries']) )
			delete_option('widget_recent_entries');

		return $instance;
	}

	function flush_widget_cache() {
		wp_cache_delete('widget_recent_reviews', 'widget');
	}

	function form( $instance ) {
		$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
		$number = isset($instance['number']) ? absint($instance['number']) : 5;
		$screenshot = isset($instance['screenshot']) ? $instance['screenshot'] : false ;
		$order = isset($instance['order']) ? $instance['order'] : false ;
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of reviews to show:'); ?></label>
		<input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>
        
                
       <p><label for="<?php echo $this->get_field_id('screenshot'); ?>"><?php _e('Display Screenshot?'); ?></label>
 			<input type="checkbox" class="checkbox" <?php checked( $instance['screenshot'], 'on' ); ?> id="<?php echo $this->get_field_id('screenshot'); ?>" name="<?php echo $this->get_field_name('screenshot'); ?>" /></p>
            
		<p><label for="<?php echo $this->get_field_id('order'); ?>"><?php _e('Order by best rating?'); ?></label>
 			<input type="checkbox" class="checkbox" <?php checked( $instance['order'], 'on' ); ?> id="<?php echo $this->get_field_id('order'); ?>" name="<?php echo $this->get_field_name('order'); ?>" /></p>

<?php
	}
}
?>