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


<section class="container">

<div class="row"><div class="small-12 column">

<?php do_action( 'foundationpress_after_header' ); ?>

	<section class="hero_cat" style="background-color: <?php $cat_id = get_query_var('cat'); $cat_data = get_option("category_$cat_id"); if (isset($cat_data['bgcolor'])){ echo ''.$cat_data['bgcolor'].'';}?>; background-image: url(<?php $cat_id = get_query_var('cat'); $cat_data = get_option("category_$cat_id"); if (isset($cat_data['bgimg'])){ echo ''.$cat_data['bgimg'].'';}?>)">

		  <div class="row intro">
		    <div class="small-12 columns">

				<h1><?php single_cat_title(); ?></h1>
				<p><?php echo category_description(); ?></p>
				
		  </div>
	</section>
</div></div>
	


				<?php

				if (!empty($cat_data['sec1array'])) {
				?>

					<section class="latest-posts popular">

						<div class="row">

							<div class="small-12 columns"><h2>To Rename To Something Else</h2></div>

						        <?php 

						    			$seconearray = explode(',', $cat_data['sec1array']);

										$args = array( 
										'post__in' => $seconearray,
										'orderby'   => 'post__in',
										);

								        $query1 = new WP_Query( $args );
										// The Loop
										if ( $query1->have_posts() ) :
										while ( $query1->have_posts() ) : $query1->the_post(); ?>

										    <div class="small-6 medium-3 columns blocks">
								        		<a href="<?php the_permalink(); ?>">
								        		<div class="inner">
								        			<div class="home_thumb"><?php the_post_thumbnail( 'large' ); ?></div>
									        		<h4><?php the_title(); ?></h4>
									        		<?php custom_excerpt(10, '') ?>
									        		<div class='home_meta'>
									        			<div class="home_author_image">
									        				<?php echo get_avatar( get_the_author_meta( 'ID' ), 36 ); ?>
									        			</div>
										        		<div class="home_author_time">
											        		<span class="author_link"><?php the_author_link(); ?></span>
										        		</div>
									        		</div>
									    		</div>  
									    		</a>	
								        	</div>
								        <?php endwhile; ?>
								        <?php endif;?>
										<?php wp_reset_postdata(); ?>
					 	</div>

					</section><!--// latest-posts popular-->

				<?php
				} else { } ?>



				<?php

				if (!empty($cat_data['mostpoparray'])) {
				?>

							<section class="latest-posts cat_pop">


								<div class="row">

									<div class="small-12 columns"><h2>Most Popular</h2></div>


									   <?php

									   	$mostpoparray = explode(',', $cat_data['mostpoparray']);

									  	$args2 = array( 
										'post__in' => $mostpoparray,
										'orderby'   => 'post__in',
										);

								        $query2 = new WP_Query( $args2 );
										// The Loop
										if ( $query2->have_posts() ) :
										while ( $query2->have_posts() ) : $query2->the_post();

									     if ( $query2->current_post == 0 ) {  // first post ?>

									        <div class="small-12 medium-6 columns blocks">
									        		<a href="<?php the_permalink(); ?>">
									        		<div class="inner">
								        			<div class="home_thumb"><?php the_post_thumbnail( 'large' ); ?></div>
									        		<h4><?php the_title(); ?></h4>
									        		<?php the_excerpt(); ?>
									        		<div class='home_meta'>
									        			<div class="home_author_image">
									        				<?php echo get_avatar( get_the_author_meta( 'ID' ), 36 ); ?>
									        			</div>
										        		<div class="home_author_time">
											        		<span class="author_link"><?php the_author_link(); ?></span>
										        		</div>
									        		</div>
									    		</div>    
										    		</a>	
									        </div>

									       <?php } else { ?>

								        <div class="small-12 medium-6 columns blocks cat_popright">
								        <h4><a href="<?php the_permalink(); ?>"> <?php the_title(); ?></a></h4>
								        <div class='home_meta'>
									        			<div class="home_author_image">
									        				<?php echo get_avatar( get_the_author_meta( 'ID' ), 36 ); ?>
									        			</div>
										        		<div class="home_author_time">
											        		<span class="author_link"><?php the_author_link(); ?></span>
										        		</div>
									        		</div>
								        </div>

								       <?php } endwhile; ?>
								        <?php endif;?>
										<?php wp_reset_postdata(); ?>
					 	</div>

					</section>

				<?php
				} else { } ?>



				<?php

				if (!empty($cat_data['reviewarray'])) {
				?>
						<section class="latest-posts cat_review">

							<div class="row">

								<div class="small-12 columns"><h2>Reviews</h2></div>

								<?php 

						    			$reviewarray = explode(',', $cat_data['reviewarray']);

										$args3 = array( 
										'post__in' => $reviewarray,
										'orderby'   => 'post__in',
										);

								        $query3 = new WP_Query( $args3 );
										// The Loop
										if ( $query3->have_posts() ) :
										while ( $query3->have_posts() ) : $query3->the_post(); ?>
											<div class="small-12 columns blocks">
									        		<a href="<?php the_permalink(); ?>">
									        		<div class="inner">
									        			<div class="home_thumb"><?php the_post_thumbnail( 'related-thumb' ); ?></div>
											        		<h4><?php the_title(); ?></h4>
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
								        <?php endif;?>
										<?php wp_reset_postdata(); ?>

						 	</div>


						</section>

				<?php
				} else { } ?>



</div>



<section class="latest-posts cat_late">

	<div class="row">

		<div class="small-12 columns"><h2><!--<i class="fa fa-pencil" aria-hidden="true"></i>-->Latest Articles</h2></div>

		<?php 
			// the query
			$the_query = new WP_Query( array( 'posts_per_page' => 10, 'paged'=>$paged, 'cat' => get_query_var('cat') ) ); ?>

			<?php if ( $the_query->have_posts() ) : ?>

				<!-- pagination here -->

				<!-- the loop -->
				<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
					<div class="small-12 medium-6 columns blocks">
			        		<a href="<?php the_permalink(); ?>">
			        		<div class="inner">
			        			<div class="home_thumb"><?php the_post_thumbnail( 'related-thumb' ); ?></div>
					        		<h4><?php the_title(); ?></h4>
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



<?php get_footer();