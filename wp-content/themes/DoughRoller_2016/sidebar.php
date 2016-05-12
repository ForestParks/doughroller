<?php
/**
 * The sidebar containing the main widget area
 *
 * @package FoundationPress
 * @since FoundationPress 1.0.0
 */

?>
<aside class="sidebar">

	<div class="email_sidebar">
		<h3>Make the most of your money</h3>
		<p>Get my free weekly newsletter to improve your finances.</p>
		<a class="large expanded button" data-open="nlmodal">Get It Now!</a>
		</div>

		<div class="reveal" id="nlmodal" data-reveal>
		  <h1>Awesome. I Have It.</h1>
		  <p class="lead">Some text here</p>
		  <p>We can put the email signup here...</p>
		  <p></p>
		  <button class="close-button" data-close aria-label="Close modal" type="button">
		    <span aria-hidden="true">&times;</span>
		  </button>
	</div><!-- // email_sidebar -->
		




	<?php do_action( 'foundationpress_before_sidebar' ); ?>
	<?php dynamic_sidebar( 'sidebar-widgets' ); ?>
	<?php do_action( 'foundationpress_after_sidebar' ); ?>
</aside>
