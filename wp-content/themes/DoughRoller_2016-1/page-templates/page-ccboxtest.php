<?php
/*
Template Name: CC Box Test
*/
get_header(); ?>

 <?php get_template_part( 'template-parts/featured-image' ); ?>

 <div id="page" role="main">

 <?php do_action( 'foundationpress_before_content' ); ?>
 <?php while ( have_posts() ) : the_post(); ?>
   <article <?php post_class('main-content') ?> id="post-<?php the_ID(); ?>">
       <header>
           <h1 class="entry-title"><?php the_title(); ?></h1>
       </header>
       <?php do_action( 'foundationpress_page_before_entry_content' ); ?>
       <div class="entry-content">


            <!-- TESTS FOR CREDIT CARD BOXES -->

            <!-- FULL PAGE CC -->



<div class="card_box card_full small-12 columns">

                <div class="col card small-12 medium-3 columns">
                  <div class="img-wrap">
                    <a href="http://www.comparecards.com/redirect/slate-from-chase" target="_blank" rel="nofollow">
                      <img src="http://cdn.comparecards.com/uploads/images/items/1495.gif" border="0" id="snapshot-card-img-426" alt="Low Interest Credit Card: Chase Slate">
                    </a>
                  </div>
                </div><!-- /col -->

                <div class="wrap small-12 medium-9 columns">

                  <div class="wrap small-12 columns">

                        <div class="col_top small-7 medium-8 columns">
                              <p class="featured">Featured</p>
                              <h3><a class="detail-tog" href="#">Chase Slate<sup>®</sup> </a></h3>
                              <div class="rating">
                              <span>★</span><span>★</span><span>★</span><span>★</span><span class="blank_star">★</span>
                              </div>
                        </div><!-- /col -->

                        <div class="col_top small-5 medium-4 columns">
                               <a href="http://www.comparecards.com/redirect/slate-from-chase" class="button" target="_blank" rel="nofollow">Apply Now</a>
                        </div><!-- /col -->
                    </div>

                    <div class="wrap small-12 columns">
                        <div class="col small-12 medium-4 columns">
                              <div class="col_block">
                                <h4>Intro APR</h4>
                                <p>0% for 15 Months*</p>
                              </div>

                              <div class="col_block">
                                <h4>Annual Fees</h4>
                                <p>$0</p>
                              </div>

                        </div><!-- /col -->

                        <div class="col_top small-12 medium-4 columns">
                              <div class="col_block">
                                <h4>Rates &amp; Fees</h4>
                                <p>No Annual Fee &amp; Intro No Balance Transfer Fee*</p>
                              </div>

                              <div class="col_block">
                                <h4>Credit Needed</h4>
                                <p>Excellent/Good</p>
                              </div>
                        </div><!-- /col -->

                        <div class="col_top small-12 medium-4 columns">
                              <div class="col_block">
                                <h4>Regular Purchase APR</h4>
                                <p>13.24%-23.24% Variable</p>
                              </div>
                        </div><!-- /col -->   
                    </div>

                </div><!-- wrap 9 columns -->           
                    
                <div class="wrap small-12 columns" id="moredeets" style="display:none">
                  <h4>Highlights</h4>
                  <div class="small-12 medium-6 columns">
                    <ul>
                      <li>Click "<a href="http://www.comparecards.com/redirect/slate-from-chase" target="_blank">APPLY NOW</a>" to apply online</li>
                      <li>Chase Slate named "Best Credit Card for Balance Transfers" three years in a row by MONEY Magazine</li>
                      <li>$0 Introductory balance transfer fee for transfers made during the first 60 days of account opening</li>
                      <li>0% Introductory APR for 15 months on purchases and balance transfers</li>
                    </ul>
                  </div>

                  <div class="small-12 medium-6 columns">
                    <ul>
                      <li>Monthly FICO® Score and Credit Dashboard for free</li>  
                      <li>No Penalty APR - Paying late won't raise your interest rate (APR). All other account pricing and terms apply</li>
                      <li>$0 Annual Fee</li>
                    </ul>
                  </div>
                 
                </div><!-- // wrap -->

            <a href="#" onclick="toggle_visibility('moredeets');toggle_visibility('more');toggle_visibility('less');"><span id="more" style="display:block">More Details</span><span id="less" style="display:none">Less Details</span></a>
                  
</div><!-- //card-full -->



<div class="card_box card_half small-12 medium-6 columns">


      <div class="col_top small-12 columns">
        <p class="featured">Featured</p>
        <h3><a class="detail-tog" href="#">Chase Slate<sup>®</sup> </a></h3>
      </div><!-- /col -->

      <div class="col card small-12 medium-6 columns">
                  <div class="img-wrap">
                    <a href="http://www.comparecards.com/redirect/slate-from-chase" target="_blank" rel="nofollow">
                      <img src="http://cdn.comparecards.com/uploads/images/items/1495.gif" border="0" id="snapshot-card-img-426" alt="Low Interest Credit Card: Chase Slate">
                    </a>
                  </div>
      </div><!-- /col -->

      <div class=" small-12 medium-6 columns">
          <a href="http://www.comparecards.com/redirect/slate-from-chase" class="button" target="_blank" rel="nofollow">Apply Now</a>
      </div><!-- /col -->

      <div class="small-6 columns">
          <div class="rating">
           <span>★</span><span>★</span><span>★</span><span>★</span><span class="blank_star">★</span>
          </div>
      </div><!-- /col -->


      <div class="deets small-12 columns">
      <center><h4>REWARDS</h4></center>
      <center><strong><h3>1.5% Cash Back</h3></strong></center>
      </div>

      <div class="deets small-12 columns">
        <p style="float:left">Annual Fee</p>
        <p style="float:right; font-weight:bold">$0</p>
      </div>

      <div class="deets small-12 columns">
        <p style="float:left">Signup Bonus</p>
        <p style="float:right;">
            <span><strong>$100</strong></span>
            <span><small>Cash Back</small></span>
        </p>
      </div>





</div><!-- //card-half -->


           <?php the_content(); ?>
       </div>
       <footer>
           <?php wp_link_pages( array('before' => '<nav id="page-nav"><p>' . __( 'Pages:', 'foundationpress' ), 'after' => '</p></nav>' ) ); ?>
           <p><?php the_tags(); ?></p>
       </footer>
       <?php do_action( 'foundationpress_page_before_comments' ); ?>
       <?php comments_template(); ?>
       <?php do_action( 'foundationpress_page_after_comments' ); ?>
   </article>
 <?php endwhile;?>

 <?php do_action( 'foundationpress_after_content' ); ?>
 <?php get_sidebar(); ?>

 </div>

 <?php get_footer();
