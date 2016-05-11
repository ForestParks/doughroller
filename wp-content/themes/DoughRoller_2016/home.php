<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * e.g., it puts together the home page when no home.php file exists.
 *
 * Learn more: {@link https://codex.wordpress.org/Template_Hierarchy}
 *
 * @package FoundationPress
 * @since FoundationPress 1.0.0
 */

get_header(); ?>

	<section class="hero">
		  <div class="row intro">
		    <div class="small-centered medium-uncentered medium-6 large-7 columns">
		      <h1>Truth? What truth?</h1>
		      <p>Don't you want to take a leap of faith? Or become an old man, filled with regret, waiting to die alone!</p>
		    </div>
		    <div class="small-centered medium-uncentered medium-6 large-5 columns">
		      <div class="tech-img"></div>
		    </div>
		  </div>
	</section>

	HOME CONTENT HERE


</div>

<?php get_footer();
