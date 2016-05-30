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
                <div id="fb-root"></div>
                        <script>(function(d, s, id) {
                          var js, fjs = d.getElementsByTagName(s)[0];
                          if (d.getElementById(id)) return;
                          js = d.createElement(s); js.id = id;
                          js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.6&appId=123830554341895";
                          fjs.parentNode.insertBefore(js, fjs);
                        }(document, 'script', 'facebook-jssdk'));</script>

                        <div class="fb-like" data-href="<?php echo urlencode(get_permalink($post->ID)); ?>" data-width="50px" data-layout="standard" data-action="like" data-show-faces="false" data-share="false"></div>
                </div>

                <div class="twitter ems">
                    <a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php echo urlencode(get_permalink($post->ID)); ?>" data-via="doughroller" data-related="doughroller">Tweet</a>
                    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
                </div>

                <div class="pinterest ems">

                    <a data-pin-do="buttonPin" data-pin-count="beside" href="https://www.pinterest.com/pin/create/button/?url=<?php echo urlencode(get_permalink($post->ID)); ?>&media=&description=Next%20stop%3A%20Pinterest"><img src="//assets.pinterest.com/images/pidgets/pinit_fg_en_rect_gray_20.png" /></a>

                    <script async defer src="//assets.pinterest.com/js/pinit.js"></script>
                </div>

                <div class="linkedin ems">
                    <script src="//platform.linkedin.com/in.js" type="text/javascript"> lang: en_US</script>
                    <script type="IN/Share" data-url="<?php echo urlencode(get_permalink($post->ID)); ?>" data-counter="right"></script>
                </div>
        

                 <div class="email ems">
                        <a href="mailto:?subject=I wanted you to see this post&amp;body=Check out the post here <?php echo urlencode(get_permalink($post->ID)); ?>""
           title="Share by Email">
                          <i class="fa fa-envelope" aria-hidden="true"></i>

                </a>
                </div>

        </div>
	
	<?
	}
endif;

?>