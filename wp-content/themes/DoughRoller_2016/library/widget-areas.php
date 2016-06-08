<?php
/**
 * Register widget areas
 *
 * @package FoundationPress
 * @since FoundationPress 1.0.0
 */

if ( ! function_exists( 'foundationpress_sidebar_widgets' ) ) :
function foundationpress_sidebar_widgets() {
	register_sidebar(array(
	  'id' => 'sidebar-widgets',
	  'name' => __( 'Sidebar widgets', 'foundationpress' ),
	  'description' => __( 'Drag widgets to this sidebar container.', 'foundationpress' ),
	  'before_widget' => '<article id="%1$s" class="row widget %2$s"><div class="small-12 columns">',
	  'after_widget' => '</div></article>',
	  'before_title' => '<h6>',
	  'after_title' => '</h6>',
	));

	register_sidebar(array(
	  'id' => 'footer-widgets',
	  'name' => __( 'Footer widgets', 'foundationpress' ),
	  'description' => __( 'Drag widgets to this footer container', 'foundationpress' ),
	  'before_widget' => '<article id="%1$s" class="large-4 columns widget %2$s">',
	  'after_widget' => '</article>',
	  'before_title' => '<h6>',
	  'after_title' => '</h6>',
	));
	register_sidebar(array(
	  'id' => 'resources-guides-left',
	  'name' => __( 'Resources and Guides Left', 'foundationpress' ),
	  'description' => __( 'Resources and Guide On Home Page - Left', 'foundationpress' ),
	  'before_widget' => '<div class="small-12 medium-6 columns blocks resources-guides">',
	  'after_widget' => '</div>',
	  'before_title' => '',
	  'after_title' => '',
	));
	register_sidebar(array(
	  'id' => 'resources-guides-right',
	  'name' => __( 'Resources and Guides Left', 'foundationpress' ),
	  'description' => __( 'Resources and Guide On Home Page - Right', 'foundationpress' ),
	  'before_widget' => '<div class="small-12 medium-6 columns blocks resources-guides">',
	  'after_widget' => '</div>',
	  'before_title' => '',
	  'after_title' => '',
	));
}


add_action( 'widgets_init', 'foundationpress_sidebar_widgets' );
endif;
