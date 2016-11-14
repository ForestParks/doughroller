<?php
/**
 * The template for displaying archive pages
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * If you'd like to further customize these archive views, you may create a
 * new template file for each one. For example, tag.php (Tag archives),
 * category.php (Category archives), author.php (Author archives), etc.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package FoundationPress
 * @since FoundationPress 1.0.0
 */

get_header(); ?>

<section class="container cat_top" style="background-color: <?php $cat_id = get_query_var('cat'); $cat_data = get_option("category_$cat_id"); if (isset($cat_data['bgcolor'])){ echo ''.$cat_data['bgcolor'].'';}?> ">

<div class="row"><div class="small-12 column">

	<h3><?php single_cat_title(); ?></h3>
	<div><?php echo category_description(); ?></div>

<?php do_action( 'foundationpress_after_header' ); ?>

</div></div>
</section>



<section class="container" >
<div class="row"><div class="small-12 column">

	<section class="container">
		<div class="row"><div class="small-12 column">

		<?php do_action( 'foundationpress_after_header' ); ?>

<section class="latest-posts">

<?php $cat_id = get_query_var('cat'); $cat_data = get_option("category_$cat_id"); if (isset($cat_data['bgcolor'])){ echo '<p>'.$cat_data['bgcolor'].'</p>';}?>

</section>




<section class="latest-posts">

	<div class="row">

		<div class="small-12 columns"><h2><!--<i class="fa fa-pencil" aria-hidden="true"></i>-->Latest Articles</h2></div>


   

		<?php 
			// the query
			$the_query = new WP_Query( array( 'posts_per_page' => 6, 'paged'=>$paged, 'cat' => get_query_var('cat') ) ); ?>

			<?php if ( $the_query->have_posts() ) : ?>

				<!-- pagination here -->

				<!-- the loop -->
				<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
					<div class="small-12 medium-4 columns blocks">
			        		<a href="<?php the_permalink(); ?>">
			        		<div class="inner">
			        			<div class="home_thumb"><?php the_post_thumbnail( 'large' ); ?></div>
					        		<h4><?php the_title(); ?></h4>
					        		<?php custom_excerpt(12, '') ?>
					        		<div class='home_meta'>
					        			<div class="home_author_image">
					        				<?php echo get_avatar( get_the_author_meta( 'ID' ), 36 ); ?>
					        			</div>
						        		<div class="home_author_time">
							        		<span class="author_link"><?php the_author_link(); ?></span>
							        		<span class="post_time"><?php the_time('F jS, Y'); ?></span>
						        		</div>
					        		</div>
				    		</div>  
				    		</a>	
			        	</div>
				<?php endwhile; ?>
				<!-- end of the loop -->

				<!-- pagination here -->
				<?php /* Display navigation to next/previous pages when applicable */ ?>
				<?php if ( function_exists( 'foundationpress_pagination' ) ) { foundationpress_pagination(); } else if ( is_paged() ) { ?>
					<nav id="post-nav">
						<div class="post-previous"><?php next_posts_link( __( '&larr; Older posts', 'foundationpress' ) ); ?></div>
						<div class="post-next"><?php previous_posts_link( __( 'Newer posts &rarr;', 'foundationpress' ) ); ?></div>
					</nav>
				<?php } ?>

				<?php wp_reset_postdata(); ?>

			<?php else : ?>
				<p><?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
			<?php endif; ?>

 	</div>


</section>



</div>

<?php get_footer();
