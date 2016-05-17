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
		    <div class="small-centered medium-uncentered medium-7 large-8 columns">


				<h1>Make the most of your money</h1>
				<p>Get my free weekly newsletter to improve your finances.</p>
				<a class="large expanded button" data-open="nlmodal" aria-controls="nlmodal" id="k0uzl3-reveal" aria-haspopup="true" tabindex="0">Get It Now!</a>
				</div>


		    </div>
		  </div>
	</section>

	<section class="key_pages">
		  <div class="row">

		    <a class="key_link small-2 columns" href="#">
			    <span class="key_icon"><i class="fa fa-credit-card" aria-hidden="true"></i></span>
			    <h3>Credit Cards</h3>
		    </a>

		    <a class="key_link small-2 columns" href="#">
			    <span class="key_icon"><i class="fa fa-money" aria-hidden="true"></i></span>
			    <h3>Savings Accounts</h3>
		    </a>

		    <a class="key_link small-2 columns" href="#">
			    <span class="key_icon"><i class="fa fa-university" aria-hidden="true"></i></span>
			    <h3>Checking Accounts</h3>
		    </a>

		    <a class="key_link small-2 columns" href="#">
			    <span class="key_icon"><i class="fa fa-line-chart" aria-hidden="true"></i></span>
			    <h3>CDs</h3>
		    </a>

		    <a class="key_link small-2 columns" href="#">
			    <span class="key_icon"><i class="fa fa-home" aria-hidden="true"></i></span>
			    <h3>Mortgages</h3>
		    </a>

		    <a class="key_link small-2 columns" href="#">
			    <span class="key_icon"><i class="fa fa-wrench" aria-hidden="true"></i></span>
			    <h3>Tools</h3>
		    </a>

		  </div>

	</section>


<section class="latest-posts">

	<h2><i class="fa fa-pencil" aria-hidden="true"></i> Latest Posts</h2>

	<div class="row">

        <?php $query = new WP_Query( array(
            'post_type' => 'post',
            'posts_per_page' => 6
        ) );
        while ($query->have_posts()) : $query->the_post(); ?>
        <div class="small-4 medium-2 columns">
        	<?php the_post_thumbnail( 'medium' ); ?>
	        <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
	      	<p><a href="<?php the_permalink(); ?>">Read more...</a></p>
        </div>
        <?php endwhile; ?>

 	</div>


</section>


	<section class="key_pages">
		  <div class="row">

		    <a class="key_link small-2 columns" href="#">
			    <span class="key_icon"><i class="fa fa-credit-card" aria-hidden="true"></i></span>
			    <h3>Credit Cards</h3>
		    </a>

		    <a class="key_link small-2 columns" href="#">
			    <span class="key_icon"><i class="fa fa-money" aria-hidden="true"></i></span>
			    <h3>Savings Accounts</h3>
		    </a>

		    <a class="key_link small-2 columns" href="#">
			    <span class="key_icon"><i class="fa fa-university" aria-hidden="true"></i></span>
			    <h3>Checking Accounts</h3>
		    </a>

		    <a class="key_link small-2 columns" href="#">
			    <span class="key_icon"><i class="fa fa-line-chart" aria-hidden="true"></i></span>
			    <h3>CDs</h3>
		    </a>

		    <a class="key_link small-2 columns" href="#">
			    <span class="key_icon"><i class="fa fa-home" aria-hidden="true"></i></span>
			    <h3>Mortgages</h3>
		    </a>

		    <a class="key_link small-2 columns" href="#">
			    <span class="key_icon"><i class="fa fa-wrench" aria-hidden="true"></i></span>
			    <h3>Tools</h3>
		    </a>

		  </div>

	</section>


<section class="latest-posts podcast">

	<h2><i class="fa fa-microphone" aria-hidden="true"></i> Latest From The Podcast</h2>

	<div class="row">

        <?php $query = new WP_Query( array(
            'post_type' => 'post',
            'posts_per_page' => 6,
            'category_name' => 'podcast'
        ) );
        while ($query->have_posts()) : $query->the_post(); ?>
        <div class="small-4 medium-2 columns">
        	<?php the_post_thumbnail( 'medium' ); ?>
	        <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
	      	<p><a href="<?php the_permalink(); ?>">Read more...</a></p>
        </div>
        <?php endwhile; ?>

 	</div>


</section>



</div>

<?php get_footer();
