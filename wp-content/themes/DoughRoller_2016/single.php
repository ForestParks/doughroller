<?php
/**
 * The template for displaying all single posts and attachments
 *
 * @package FoundationPress
 * @since FoundationPress 1.0.0
 */

get_header(); ?>

<div id="single-post" role="main">

<?php do_action( 'foundationpress_before_content' ); ?>
<?php while ( have_posts() ) : the_post(); ?>
	<article <?php post_class('main-content') ?> id="post-<?php the_ID(); ?>">
		<header>
			<h1 class="entry-title"><?php the_title(); ?></h1>
			<?php foundationpress_entry_meta(); ?>
		</header>
		<?php do_action( 'foundationpress_post_before_entry_content' ); ?>
		<div class="entry-content">

		<?php if ( has_post_thumbnail() ) : ?>
			<div class="row">
				<div class="column">
					<?php the_post_thumbnail( '', array('class' => 'th') ); ?>
				</div>
			</div>
		<?php endif; ?>

		<?php the_content(); ?>
		</div>

		<div class="email_sidebar">
		<h3>Take Back Control of Your Money Today</h3>
		<p>Our 31-Day Money Challenge will help you get out of debt, save more, and take back control of your life.</p>
		<p><strong>Bonus:</strong> You'll also get instant access to my interview of a husband and father who retired at the ripe old age of . . . 30. Seriously!</p>
		<p><strong>What others are saying:</strong> "Hi Rob.  I'm at Day 26 in your 31 day money challenge podcast. Thank you, thank you, thank you!  I've been looking for a comprehensive guide to all-things-money and this has been so informative." --Danielle</p>
		<a class="large expanded button">Get Instant Access!</a>

		<div><a class="wf-privacy wf-privacyico" href="http://www.getresponse.com/permission-seal?lang=en" target="_blank" style="height: 18px !important; display: inline !important;">We hate spam as much as you do.</a></div>

		</div>


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
<?php get_sidebar(); ?>
</div>
<?php get_footer();
