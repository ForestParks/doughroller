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
            
            <span class="author vcard reviewer"><?php the_author_posts_link(); ?> <?php edit_post_link('• Edit This Post'); ?></span>
        
      		<span class="post-time"><time class="updated" datetime="<?php the_modified_date('F j, Y'); ?>"><?php the_modified_date('F j, Y'); ?></time></span>
            <span class="time_cat_spacer">•</span>

	        <span class="post-cat"><?php the_category( '&bull;' ); ?>  </span>

                
        </div>

        <div class="entry_meta_social">


                <div class="facebook ems">

                <a href="http://www.facebook.com/share.php?u=<?php echo urlencode(get_permalink($post->ID)); ?>&title=<?php echo urlencode(html_entity_decode(get_the_title(), ENT_COMPAT, 'UTF-8')); ?>" target="_blank"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                
                </div>

                <div class="twitter ems">


                    <a href="http://twitter.com/intent/tweet?status=<?php echo urlencode(html_entity_decode(get_the_title(), ENT_COMPAT, 'UTF-8')); ?>+<?php echo urlencode(get_permalink($post->ID)); ?>" target="_blank"><i class="fa fa-twitter" aria-hidden="true"></i></a>

                </div>

                <div class="pinterest ems">

                     <a href="http://pinterest.com/pin/create/bookmarklet/?media=&url=<?php echo urlencode(html_entity_decode(get_the_title(), ENT_COMPAT, 'UTF-8')); ?>&is_video=false&description=<?php echo urlencode(get_permalink($post->ID)); ?>" target="_blank"><i class="fa fa-pinterest-p" aria-hidden="true"></i></a>

                </div>

                <div class="linkedin ems">
                    
                    <a href="http://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode(get_permalink($post->ID)); ?>&title=<?php echo urlencode(html_entity_decode(get_the_title(), ENT_COMPAT, 'UTF-8')); ?>&source=Doughroller" target="_blank"><i class="fa fa-linkedin" aria-hidden="true"></i></a>

                </div>
        

                 <div class="email ems">
                        <a href="mailto:?subject=I wanted you to see this post&amp;body=Check out the post here <?php echo urlencode(get_permalink($post->ID)); ?>""
           title="Share by Email">
                          <i class="fa fa-envelope" aria-hidden="true"></i>

                </a>
                </div>

                <?php if( is_single() OR is_page() ) { ?>
                    <div class="disclosure ems">
                    <a data-open="nlmodaldis" aria-controls="nlmodal" id="reveal" aria-haspopup="true" tabindex="0">Advertising Disclosure</a>
                    </div>

                    <div class="reveal ad_disclosure" id="nlmodaldis" data-reveal>
                        <p>DoughRoller receives compensation from some companies issuing financial products, like credit cards and bank accounts, that appear on this site. Unless a post is clearly marked "Sponsored", however, products mentioned in editorial articles and reviews are based on the author's subjective assessment of their value to readers, not compensation. Compensation may impact how and where products appear on non-editorial pages (e.g., comparison or "marketplace" pages). That said, our standard is that we will never accept advertising from a product which we wouldn't use ourselves.</p>
                    <button class="close-button" data-close="" aria-label="Close reveal" type="button">
                      <span aria-hidden="true">×</span>
                    </button>
                    </div>
                <?php } ?>

        </div>
	
	<?
	}
endif;

?>