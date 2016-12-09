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


<?php if ( !is_paged() ) { // IF first page of pagination ?>

		<section class="container">
		<div class="row"><div class="small-12 column">

		<?php do_action( 'foundationpress_after_header' ); ?>

			<section class="hero">
				  <div class="row intro">
				    <div class="small-centered medium-uncentered medium-7 large-8 columns">

						<h1>Make the most of your money</h1>
						<p>Get our free weekly newsletter to improve your finances.</p>

						<a class="large expanded button" data-open="nlmodalhome" aria-controls="nlmodal" id="reveal" aria-haspopup="true" tabindex="0">Get It Now!</a>
						</div>


						
				  </div>
			</section>


			<div class="reveal email_popup" id="nlmodalhome" data-reveal>

						            <div class="right_side">
						          <h1>Get our <span class="free">free</span> weekly newsletter packed with</h1>
						          
						          <ul>
						            <li>Links to our latest articles</li>
						            <li>Money saving tips</li>
						            <li>Tools, resources, and guides to improve your finances</li>
						          </ul>

						  <div id="WFItem654886" class="wf-formTpl">
						          <form action="https://app.getresponse.com/add_contact_webform.html?u=S86B" accept-charset="utf-8" method="post">

						            <input class="wf-input" type="text" name="name" value="Name"
						  				onblur="if(this.value==''){ this.value='Name'; this.style.color='#BBB';}"
						  				onfocus="if(this.value=='Name'){ this.value=''; this.style.color='#000';}"
						  				style="color:#BBB;" />


						                        <input class="wf-input wf-req wf-valid__email" type="text" name="email" value="Email"
						  onblur="if(this.value==''){ this.value='Email'; this.style.color='#BBB';}"
						  onfocus="if(this.value=='Email'){ this.value=''; this.style.color='#000';}"
						  style="color:#BBB;" /><br/>

						            <input type="submit" class="button wf-button"" value="Let's Roll"/>

						            <input type="hidden" name="webform_id" value="654886" />

						            </form>

						      <ul>
						        <li class="wf-captcha" rel="undefined" style="display:  none !important;">
		                        	<div class="wf-contbox wf-captcha-1" id="wf-captcha-1" wf-captchaword="Enter the words above:"
		                        wf-captchasound="Enter the numbers you hear:" wf-captchaerror="Incorrect please try again"></div>
		                    	</li>
		                    	<li class="wf-privacy" rel="undefined" style="display:  block !important;">
		                        	<div class="wf-contbox">
		                            	<div>
		                                	<a class="wf-privacy wf-privacyico" href="https://www.getresponse.com/permission-seal?lang=en"
		                                target="_blank" style="height: 18px !important; display: inline !important;">We respect your privacy<em class="clearfix clearer"></em></a>
		                            	</div>
		                            	<em class="clearfix clearer"></em>
		                        	</div>
		                    	</li>
		                    </ul>

						  </div><!--//WFItem654886-->

			</div>

						          <button class="close-button" data-close aria-label="Close modal" type="button">
						            <span aria-hidden="true">&times;</span>
						          </button>
						          
		    </div><!-- // email_popup -->

		    

			<section class="key_pages">
				  <div class="row">

				    <a class="key_link small-4 medium-2 columns" href="<?php echo site_url(); ?>/credit-cards/">
					    <span class="key_icon"><i class="fa fa-credit-card" aria-hidden="true"></i></span>
					    <h3>Credit Cards</h3>
				    </a>

				    <a class="key_link small-4 medium-2 columns" href="<?php echo site_url(); ?>/banking/high-yield-online-savings-account/">
					    <span class="key_icon"><i class="fa fa-money" aria-hidden="true"></i></span>
					    <h3>Savings Accounts</h3>
				    </a>

				    <a class="key_link small-4 medium-2 columns" href="<?php echo site_url(); ?>/banking/the-best-checking-account-promotions-and-deals/">
					    <span class="key_icon"><i class="fa fa-university" aria-hidden="true"></i></span>
					    <h3>Checking Accounts</h3>
				    </a>

				    <a class="key_link small-4 medium-2 columns" href="<?php echo site_url(); ?>/banking/the-best-checking-account-promotions-and-deals/">
					    <span class="key_icon"><i class="fa fa-line-chart" aria-hidden="true"></i></span>
					    <h3>CDs</h3>
				    </a>

				    <a class="key_link small-4 medium-2 columns" href="<?php echo site_url(); ?>/mortgage-rates/">
					    <span class="key_icon"><i class="fa fa-home" aria-hidden="true"></i></span>
					    <h3>Mortgages</h3>
				    </a>

				    <a class="key_link small-4 medium-2 columns" href="<?php echo site_url(); ?>/tools-resources/money-toolbox/">
					    <span class="key_icon"><i class="fa fa-wrench" aria-hidden="true"></i></span>
					    <h3>Tools</h3>
				    </a>

				  </div>

			</section>

<?php } // END IF first page of pagination ?>


<section class="latest-posts">

	<div class="row">

		<div class="small-12 columns"><h2><!--<i class="fa fa-pencil" aria-hidden="true"></i>-->Latest Articles</h2></div>


        <?php $query = new WP_Query( array(
            'post_type' => 'post',
            'posts_per_page' => 6,
            'paged'=>$paged
        ) );

        while ($query->have_posts()) : $query->the_post(); ?>
        
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
				        		<span class="author_link"><?php the_author_posts_link(); ?></span>
				        		<span class="post_time"><?php the_time('F jS, Y'); ?></span>
			        		</div>
		        		</div>
	    		</div>  
	    		</a>	
        	</div>
        
        <?php endwhile; ?>

				<?php if ( function_exists( 'foundationpress_pagination' ) ) { foundationpress_pagination(); } else if ( is_paged() ) { ?>
					<nav id="post-nav">
						<div class="post-previous"><?php next_posts_link( __( '&larr; Older posts', 'foundationpress' ) ); ?></div>
						<div class="post-next"><?php previous_posts_link( __( 'Newer posts &rarr;', 'foundationpress' ) ); ?></div>
					</nav>
				<?php } ?>

				<?php wp_reset_postdata(); ?>

 	</div>


</section>


<?php if ( !is_paged() ) { // IF first page of pagination ?>


					<section class="latest-posts popular">

						<div class="row">

							<div class="small-12 columns"><h2>Most Popular</h2></div>


						        <?php 
						        		$popoptionsarray = get_option( 'homepop' );

						    			$homepoparray = explode(',', $popoptionsarray);

										$args = array( 
										'post__in' => $homepoparray,
										'orderby'   => 'post__in',
										'posts_per_page' => 20
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
											        		<span class="author_link"><?php the_author_posts_link(); ?></span>
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





					<section class="home_signup">
							  <div class="row">
							  <div class="small-8 medium-8 columns center">

							  <h3><strong>Make the most of your money</strong></h3> <h4>Get our free weekly newsletter to improve your finances.</h4>
							  					<div id="newsletter-form-cont">					

							  							</br>
							  							<div id="WFItem654886" class="wf-formTpl">
									          <form action="https://app.getresponse.com/add_contact_webform.html?u=S86B" accept-charset="utf-8" method="post">

												    	 	<div class="small-12 medium-4 columns">
													<input class="wf-input" type="text" name="name" value="Name"
									  				onblur="if(this.value==''){ this.value='Name'; this.style.color='#BBB';}"
									  				onfocus="if(this.value=='Name'){ this.value=''; this.style.color='#000';}"
									  				style="color:#BBB;" />
															</div>

															<div class="small-12 medium-4 columns">
													<input class="wf-input wf-req wf-valid__email" type="text" name="email" value="Email"
									  onblur="if(this.value==''){ this.value='Email'; this.style.color='#BBB';}"
									  onfocus="if(this.value=='Email'){ this.value=''; this.style.color='#000';}"
									  style="color:#BBB;" />
															</div>

															<div class="small-12 medium-4 columns">
													<input type="submit" class="button wf-button"" value="Let's Roll"/>
													<input type="hidden" name="webform_id" value="654886" />
															</div>
														
														</form>
														</div><!--//WFItem654886-->


												 </div>

							  </div>
							  </div>

						</section>



					<section class="latest-posts resources-guides">


						<div class="row">

							<div class="small-12 columns"><h2><!--<i class="fa fa-wrench" aria-hidden="true"></i>-->Resources and Guides</h2></div>

					<?php dynamic_sidebar( 'resources-guides-left' ); ?>
					<?php dynamic_sidebar( 'resources-guides-right' ); ?>

					 	</div>


					</section>

<?php } // END IF first page of pagination ?>


</div>



<?php get_footer(); ?>
