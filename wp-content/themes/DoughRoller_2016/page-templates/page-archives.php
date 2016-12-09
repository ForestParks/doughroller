<?php
/*
Template Name: Archives Page
*/
get_header(); ?>

<section class="container">
<div class="row"><div class="small-12 column">

<?php do_action( 'foundationpress_after_header' ); ?>

<div id="page" role="main">
  <article class="main-content">
  <?php the_post(); ?>
    <h1 class="entry-title"><?php the_title(); ?></h1>
    
    <?php get_search_form(); ?>
    
    <h2>Archives by Month:</h2>
    <ul>
      <?php wp_get_archives('type=monthly'); ?>
    </ul>
    
    <h2>Archives by Subject:</h2>
    <ul>
       <?php wp_list_categories(); ?>
    </ul>

    <?php /* Display navigation to next/previous pages when applicable */ ?>
    <?php if ( function_exists( 'foundationpress_pagination' ) ) { foundationpress_pagination(); } else if ( is_paged() ) { ?>
      <nav id="post-nav">
        <div class="post-previous"><?php next_posts_link( __( '&larr; Older posts', 'foundationpress' ) ); ?></div>
        <div class="post-next"><?php previous_posts_link( __( 'Newer posts &rarr;', 'foundationpress' ) ); ?></div>
      </nav>
    <?php } ?>

  </article>
  <?php get_sidebar(); ?>

</div>

<?php get_footer();