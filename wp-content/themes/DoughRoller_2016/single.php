<?php
/**
 * The template for displaying all single posts and attachments
 *
 * @package FoundationPress
 * @since FoundationPress 1.0.0
 */

get_header(); ?>

<section class="container">
<div class="row"><div class="small-12 column">

<?php do_action( 'foundationpress_after_header' ); ?>

<div id="single-post" <?php if( get_field('remove_sidebar') ){echo 'class="no-sidebar"';} else {}?> role="main">

<?php do_action( 'foundationpress_before_content' ); ?>
<?php while ( have_posts() ) : the_post(); ?>
	<article <?php post_class('main-content') ?> id="post-<?php the_ID(); ?>">
		<header>
			<h1 class="entry-title"><?php the_title(); ?></h1>
			<?php foundationpress_entry_meta(); ?>
		</header>
		<?php do_action( 'foundationpress_post_before_entry_content' ); ?>
		<div class="entry-content">

		<?php the_content(); ?>



		</div>

		<div class="email_sidebar">
		<h3>Take the 31-Day Money Challenge</h3>
		<p>Our 31-Day Money Challenge will help you get out of debt, save more, and take back control of your life.</p>
		<p><strong>Bonus:</strong> You'll also get instant access to my interview of a husband and father who retired at the ripe old age of . . . 30. Seriously!</p>
		<p><strong>What others are saying:</strong> "Hi Rob.  I'm at Day 26 in your 31 day money challenge podcast. Thank you, thank you, thank you!  I've been looking for a comprehensive guide to all-things-money and this has been so informative." --Danielle</p>
		<a class="large expanded button" data-open="nlmodal31">Get Instant Access!</a>

		</div>

		<div class="reveal email_popup" id="nlmodal31" data-reveal>

				            <div class="right_side">
				          <h1>Start the 31-day money challenge!</h1>		          
		

				          <div id="WFItem9307001" class="wf-formTpl">
				          <form action="https://app.getresponse.com/add_contact_webform.html?u=S86B" accept-charset="utf-8" method="post">

				            <input type="text" name="name" value="Name"
				  onblur="if(this.value==''){ this.value='Name'; this.style.color='#BBB';}"
				  onfocus="if(this.value=='Name'){ this.value=''; this.style.color='#000';}"
				  style="color:#BBB;" />


				                        <input type="text" name="email" value="Email"
				  onblur="if(this.value==''){ this.value='Email'; this.style.color='#BBB';}"
				  onfocus="if(this.value=='Email'){ this.value=''; this.style.color='#000';}"
				  style="color:#BBB;" /><br/>

				            <input type="hidden" name="campaign_token" value="PaQV"/>

				            <input type="submit" class="button" value="Let's Roll"/>

				            <ul>
				            <li class="wf-captcha" rel="undefined" style="display:  none !important;">
                        			<div class="wf-contbox wf-captcha-1" id="wf-captcha-1" wf-captchaword="Enter the words above:"
                        wf-captchasound="Enter the numbers you hear:" wf-captchaerror="Incorrect please try again"></div>
			                    </li>
			                    <li class="wf-privacy" rel="undefined" style="display:  block !important;">
			                        <div class="wf-contbox">
			                            <div>
			                                <a class="wf-privacy wf-privacyico" href="https://www.getresponse.com/permission-seal?lang=en"
			                                target="_blank" style="height: 18px !important; display: inline !important;">We hate spam as much as you do.</a>
			                            </div>
			                            <em class="clearfix clearer"></em>
			                        </div>
			                    </li>
			                </ul>

			                <input type="hidden" name="webform_id" value="9307001" />

				            </form>
				            </div><!--//WFItem9307001-->
				            <script type="text/javascript" src="http://app.getresponse.com/view_webform.js?wid=9307001&mg_param1=1&u=S86B"></script>
				        </div>

				          <button class="close-button" data-close aria-label="Close modal" type="button">
				            <span aria-hidden="true">&times;</span>
				          </button>
				          
    </div><!-- // email_popup -->


		<footer>
			<?php wp_link_pages( array('before' => '<nav id="page-nav"><p>' . __( 'Pages:', 'foundationpress' ), 'after' => '</p></nav>' ) ); ?>
			<p><?php the_tags(); ?></p>
		</footer>
		<?php do_action( 'foundationpress_post_before_comments' ); ?>
		<?php comments_template(); ?>
		<?php do_action( 'foundationpress_post_after_comments' ); ?>
	</article>
<?php endwhile;?>

<?php do_action( 'foundationpress_after_content' ); ?>

<?php 
	if( get_field('remove_sidebar') )
	{}
	else
	{get_sidebar();}
?>

</div>
<?php get_footer();
