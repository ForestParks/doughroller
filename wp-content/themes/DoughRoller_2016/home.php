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

				          <form action="https://app.getresponse.com/add_subscriber.html" accept-charset="utf-8" method="post">

				            <input type="text" name="name" value="Name"
				  onblur="if(this.value==''){ this.value='Name'; this.style.color='#BBB';}"
				  onfocus="if(this.value=='Name'){ this.value=''; this.style.color='#000';}"
				  style="color:#BBB;" />


				                        <input type="text" name="email" value="Email"
				  onblur="if(this.value==''){ this.value='Email'; this.style.color='#BBB';}"
				  onfocus="if(this.value=='Email'){ this.value=''; this.style.color='#000';}"
				  style="color:#BBB;" /><br/>

				            <input type="hidden" name="campaign_token" value="TAZ6"/>

				            <input type="submit" class="button" value="Let's Roll"/>
				            </form>
				        </div>

				          <button class="close-button" data-close aria-label="Close modal" type="button">
				            <span aria-hidden="true">&times;</span>
				          </button>
				          
    </div><!-- // email_popup -->



	<section class="key_pages">
		  <div class="row">

		    <a class="key_link small-4 medium-2 columns" href="#">
			    <span class="key_icon"><i class="fa fa-credit-card" aria-hidden="true"></i></span>
			    <h3>Credit Cards</h3>
		    </a>

		    <a class="key_link small-4 medium-2 columns" href="#">
			    <span class="key_icon"><i class="fa fa-money" aria-hidden="true"></i></span>
			    <h3>Savings Accounts</h3>
		    </a>

		    <a class="key_link small-4 medium-2 columns" href="#">
			    <span class="key_icon"><i class="fa fa-university" aria-hidden="true"></i></span>
			    <h3>Checking Accounts</h3>
		    </a>

		    <a class="key_link small-4 medium-2 columns" href="#">
			    <span class="key_icon"><i class="fa fa-line-chart" aria-hidden="true"></i></span>
			    <h3>CDs</h3>
		    </a>

		    <a class="key_link small-4 medium-2 columns" href="#">
			    <span class="key_icon"><i class="fa fa-home" aria-hidden="true"></i></span>
			    <h3>Mortgages</h3>
		    </a>

		    <a class="key_link small-4 medium-2 columns" href="#">
			    <span class="key_icon"><i class="fa fa-wrench" aria-hidden="true"></i></span>
			    <h3>Tools</h3>
		    </a>

		  </div>

	</section>


<section class="latest-posts">

	<div class="row">

		<div class="small-12 columns"><h2><i class="fa fa-pencil" aria-hidden="true"></i> Latest Posts</h2></div>


        <?php $query = new WP_Query( array(
            'post_type' => 'post',
            'posts_per_page' => 3
        ) );
        while ($query->have_posts()) : $query->the_post(); ?>
        
        	<div class="small-12 medium-4 columns blocks">
        		<a href="<?php the_permalink(); ?>">
        		<div class="inner">
        			<div class="home_thumb"><?php the_post_thumbnail( 'large' ); ?></div>
	        		<h4><?php the_title(); ?></h4>
	    		</div>  
	    		</a>	
        	</div>
        
        <?php endwhile; ?>

 	</div>


</section>


<section class="latest-posts popular">


	<div class="row">

		<div class="small-12 columns"><h2><i class="fa fa-microphone" aria-hidden="true"></i> Most Popular</h2></div>


        <?php $query = new WP_Query( array(
            'post_type' => 'post',
            'posts_per_page' => 4,
            'category_name' => 'home-featured'
        ) );
        while ($query->have_posts()) : $query->the_post(); ?>
		    <div class="small-6 medium-3 columns blocks">
        		<a href="<?php the_permalink(); ?>">
        		<div class="inner">
        			<div class="home_thumb"><?php the_post_thumbnail( 'large' ); ?></div>
	        		<h4><?php the_title(); ?></h4>
	    		</div>  
	    		</a>	
        	</div>
        <?php endwhile; ?>

 	</div>


</section>

<section class="latest-posts podcast">


	<div class="row">

		<div class="small-12 columns"><h2><i class="fa fa-microphone" aria-hidden="true"></i> Latest From The Podcast</h2></div>


   <?php

  // The Query
  $query = new WP_Query( array(
            'post_type' => 'post',
            'posts_per_page' => 7,
            'category_name' => 'podcast'
        ) );

  // The Loop
  while ($query->have_posts()) : $query->the_post();

     if ( $query->current_post == 0 ) {  // first post ?>

        <div class="small-12 medium-6 columns blocks">
        		<a href="<?php the_permalink(); ?>">
        		<div class="inner">
        			<div class="home_thumb"><?php the_post_thumbnail( 'large' ); ?></div>
	        		<h4><?php the_title(); ?></h4>
	    		</div>  
	    		</a>	
        </div>

       <?php 
     } else { ?>

	        <div class="small-12 medium-6 columns blocks podright">
	        <h4><a href="<?php the_permalink(); ?>"><i class="fa fa-microphone" aria-hidden="true"></i><?php the_title(); ?></a></h4>
        </div>

       <?php 
     }

     endwhile;

  	 ?>

 	</div>


</section>

<section class="home_signup">
		  <div class="row">
		  <div class="small-8 medium-8 columns center">

		  <h3><strong>Make the most of your money</strong></h3> <h4>Get my free weekly newsletter to improve your finances.</h4>
		  					<div id="newsletter-form-cont">
					

		  							</br>
							    	 <form action="https://app.getresponse.com/add_subscriber.html" accept-charset="utf-8" method="post">

							    	 	<div class="small-12 medium-4 columns">
								<input type="text" name="name" value="Name" onblur="if(this.value==''){ this.value='Name'; this.style.color='#BBB';}" onfocus="if(this.value=='Name'){ this.value=''; this.style.color='#000';}" style="color:#BBB;" />
										</div>

										<div class="small-12 medium-4 columns">
								<input type="text" name="email" value="Email" onblur="if(this.value==''){ this.value='Email'; this.style.color='#BBB';}" onfocus="if(this.value=='Email'){ this.value=''; this.style.color='#000';}" style="color:#BBB;" />
										</div>

											<input type="hidden" name="campaign_token" value="TAZ6"/>

										<div class="small-12 medium-4 columns">
								<input type="submit" class="button" value="Let's Roll"/>
										</div>
									
									</form>


							 </div>

		  </div>
		  </div>

	</section>


<section class="latest-posts resources-guides">


	<div class="row">

		<div class="small-12 columns"><h2><i class="fa fa-wrench" aria-hidden="true"></i> Resources And Guides</h2></div>

<?php dynamic_sidebar( 'resources-guides-left' ); ?>
<?php dynamic_sidebar( 'resources-guides-right' ); ?>

 	</div>


</section>





<?php dynamic_sidebar( 'custom' ); ?>



</div>

<?php get_footer();
