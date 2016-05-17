<?php
/**
 * Entry meta information for posts
 *
 * @package FoundationPress
 * @since FoundationPress 1.0.0
 */

if ( ! function_exists( 'foundationpress_entry_meta' ) ) :
	function foundationpress_entry_meta() { ?>

		<div class="article__author">
			<div class="author__image">
                <?php echo get_avatar( get_the_author_meta( 'ID' ), 48 ); ?>
            </div>
            
            <span class="author vcard reviewer"><?php the_author_posts_link(); ?></span>
        
      		<span class="post-time"><time class="updated" datetime="<?php the_modified_date('F j, Y'); ?>"><?php the_modified_date('F j, Y'); ?></time></span>

	        <span class="post-cat"><?php the_category( '&bull;' ); ?></span>
                
        </div>

        <div class="entry_meta_social">
        SOCIAL LINKS, WILL WE USE A PLUGIN HERE?
        </div>
	
	<?
	}
endif;

?>