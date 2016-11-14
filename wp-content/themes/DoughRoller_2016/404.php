<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package FoundationPress
 * @since FoundationPress 1.0.0
 */

get_header(); ?>

<section class="container">
<div class="row"><div class="small-12 column">

<?php do_action( 'foundationpress_after_header' ); ?>

<div class="row">
	<div  id="single-post" class="no-sidebar 404" role="main">

		<article <?php post_class() ?> id="post-<?php the_ID(); ?>">
			<header>
				<center><h1 class="entry-title"><?php _e( '404 Not Found', 'foundationpress' ); ?></h1></center>
			</header>
			<div class="entry-content">
				<div class="error">
					<p class="bottom"><?php _e( 'The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.', 'foundationpress' ); ?></p>
				</div>
				<p><?php _e( 'Please try the following:', 'foundationpress' ); ?></p>
				<ul>
					<li><?php _e( 'Check your spelling', 'foundationpress' ); ?></li>
					<li><?php printf( __( 'Return to the <a href="%s">home page</a>', 'foundationpress' ), home_url() ); ?></li>
					<li><?php _e( 'Click the <a href="javascript:history.back()">Back</a> button', 'foundationpress' ); ?></li>
				</ul>
			</div>
		</article>

	</div>
</div>
<?php get_footer();
