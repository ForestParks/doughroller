<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the "off-canvas-wrap" div and all content after.
 *
 * @package FoundationPress
 * @since FoundationPress 1.0.0
 */

?>
			</div></div><!-- row and column ends FROM BELOW SECTION IN HEADER.PHP-->
		</section>


<div id="footer-container">
			<footer id="footer">

			<?php do_action( 'foundationpress_before_footer' ); ?>


    <div class="contentArea">

        <div class="row">
            <div class="medium-5 small-12 columns footerColumn">
                
                <div class="first">
                <h6>Site Sections:</h6>
                    <div class="small-5 columns ">
                        <ul>
                            <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>bank-rates/">Bank Rates</a></li>
                            <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>credit-cards/">Credit Cards</a></li>
                            <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>category/investing/">Investing</a></li>
                            <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>category/credit/">Credit & Debt</a></li>
                            <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>tools-resources/money-toolbox/">Resources</a></li>                            
                        </ul>
                    </div>
                    <div class="small-6 columns">
                        <ul>
                            <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>thepodcast/">Podcast</a></li>
                            <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>category/insurance">Insurance</a></li>
                            <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>category/taxes/">Taxes</a></li>
                            <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>category/retirement-planning/">Retirement</a></li>
                            <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>category/personal-finance/">Personal Finance</a></li>
                        </ul>
                    </div>
                </div>

                <p></p>

                <div class="second">
                <h6>Company:</h6>
                    <div class="small-6 columns">
                        <ul>
                            <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>about/">About Us</a></li>
                            <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>contact/">Contact</a></li>
                        </ul>
                    </div>
                    <div class="small-6 columns">
                        <ul>
                            <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>archives/">Archives</a></li>
                            <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>terms-of-use-privacy-policy/">Privacy Policy & Disclaimer</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="medium-3 small-12 columns footerColumn">
               
					<div class="social-links-footer first">
						<h6>Connect With Us</h6>  
						<ul class="social-links ">
							<li class="facebook"><a href="https://www.facebook.com/groups/1649504642004285/?ref=bookmarks"><i class="fa fa-facebook"></i></a></li>
							<li class="twitter"><a href="https://twitter.com/doughroller"><i class="fa fa-twitter"></i></a></li>
							<li class="pinterest"><a href="https://www.pinterest.com/doughroller"><i class="fa fa-pinterest"></i></a></li>
							<li class="newsletter"><a href="<?php echo esc_url( home_url( '/' ) ); ?>newsletter"><i class="fa fa-envelope"></i></a></li>
						</ul>
					</div>

					<p></p>

					<div class="join-newsletter newsletter second"> 
						<h6>Join Our Newsletter</h6>

						<div id="newsletter-form-cont">
             <div id="WFItem654886" class="wf-formTpl">
                <form action="https://app.getresponse.com/add_contact_webform.html?u=S86B" accept-charset="utf-8" method="post">
							        <div class="row collapse margintop-20px">
     

            <div class="small-12 medium-6 columns">
               <input class="wf-input" type="text" name="name" value="Name"
                  onblur="if(this.value==''){ this.value='Name'; this.style.color='#BBB';}"
                  onfocus="if(this.value=='Name'){ this.value=''; this.style.color='#000';}"
                  style="color:#BBB;" />
                        </div>

                         <div class="small-12 medium-6 columns">
                                <input class="wf-input wf-req wf-valid__email" type="text" name="email" value="Email"
          onblur="if(this.value==''){ this.value='Email'; this.style.color='#BBB';}"
          onfocus="if(this.value=='Email'){ this.value=''; this.style.color='#000';}"
          style="color:#BBB;" />
                          </div>

          <div class="small-12 columns">

            <input type="submit" class="button wf-button"" value="Let's Roll"/>

                    <input type="hidden" name="webform_id" value="654886" />
            </div>

							     
							        </div>
							      </form>
							 </div>
               </div><!--//WFItem654886-->

				</div>


            </div>

            <div class="medium-4 small-12 columns footerColumn">
                <h6>Featured On:</h6>
                <div id="featuredOn">
                	<a class="huff" href=""><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/asseen/huff-hov.jpg" /></a>
                    <a class="forbes" href=""><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/asseen/forbes-hov.jpg" /></a>
                    <a class="usn" href=""><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/asseen/usn-hov.jpg" /></a>
                    <a class="df" href=""><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/asseen/df-hov.jpg" /></a>

                </div>
            </div>
        </div>


       <div class="small-12 columns copyright">Copyright &copy; <?php echo date("Y") ?> Doughroller.net. All rights reserved.</div>

       <div class="small-12 columns disclaimers">

       		<div class="small-12 medium-3 columns">
			<p><strong>Advertiser Disclosure</strong>:  This site may be compensated in exchange for featured placement of certain sponsored products and services, or your clicking on links posted on this website.  The credit card offers that appear on this site are from credit card companies from which doughroller.net receives compensation.  This compensation may impact how and where products appear on this site (including, for example, the order in which they appear).  Doughroller.net does not include all credit card companies or all available credit card offers.</p>
			</div>
			<div class="small-12 medium-3 columns">
			<p><strong>Editorial Disclosure</strong>: This content is not provided or commissioned by the bank, credit card issuer, or other advertiser. Opinions expressed here are author's alone, not those of the bank, credit card issuer, or other advertiser, and have not been reviewed, approved or otherwise endorsed by the advertiser. This site may be compensated through the bank, credit card issuer, or other advertiser Affiliate Program</p>
			</div>
			<div class="small-12 medium-3 columns">
			<p><strong>Disclaimer</strong>:  The content on this site is for informational and educational purposes only and should not be construed as professional financial advice.  Should you need such advice, consult a licensed financial or tax advisor.  References to products, offers, and rates from third party sites often change.  While we do our best to keep these updated, numbers stated on our site may differ from actual numbers.See our <a href="<?php echo esc_url( home_url( '/' ) ); ?>terms-of-use-privacy-policy/">Privacy Policy &amp; Disclaimer</a> for more details.</p>
            </div>
            <div class="small-12 medium-3 columns">
            <p><strong>Archives</strong>:  You can explore the site through our <a href="<?php echo esc_url( home_url( '/' ) ); ?>archives/">archives</a> dating back to 2007.</p>
			</div>
       </div>


    </div>

				<?php do_action( 'foundationpress_after_footer' ); ?>

</footer>
</div><!-- //footer-container -->


		<?php do_action( 'foundationpress_layout_end' ); ?>

<?php if ( get_theme_mod( 'wpt_mobile_menu_layout' ) == 'offcanvas' ) : ?>
		</div><!-- Close off-canvas wrapper inner -->
	</div><!-- Close off-canvas wrapper -->
</div><!-- Close off-canvas content wrapper -->
<?php endif; ?>


<?php wp_footer(); ?>
<?php do_action( 'foundationpress_before_closing_body' ); ?>

<!-- script for sidebar and standard signup form-->
<script type="text/javascript" src="http://app.getresponse.com/view_webform.js?wid=654886&mg_param1=1&u=S86B"></script>

<!--<script type="text/javascript">
        $(window).scroll(function() {    
            var scroll = $(window).scrollTop();

            if (scroll >= 300) {
                $(".entry_meta_social.floating").addClass("viewable");
            } else {
                $(".entry_meta_social.floating").removeClass("viewable");
            }
        });
</script>-->

<!-- PULL QUOTES -->

<script type="text/javascript">
      $(document).ready(function() { 
        $('span.pull-right').each(function(index) { 
        var $parentParagraph = $(this).parent('p'); 
        $parentParagraph.css('position', 'relative'); 
        $(this).clone() 
          .addClass('pulled-right') 
          .prependTo($parentParagraph); 
        }); 
      $('span.pull-left').each(function(index) { 
        var $parentParagraph = $(this).parent('p'); 
        $parentParagraph.css('position', 'relative'); 
        $(this).clone() 
          .addClass('pulled-left') 
          .prependTo($parentParagraph); 
        });
      }); 
    </script>


<!-- ALLOW SHOW / HIDE on ccbox -->

<script type="text/javascript">
<!--
    function toggle_visibility(id) {
       var e = document.getElementById(id);
       if(e.style.display == 'block')
          e.style.display = 'none';
       else
          e.style.display = 'block';
    }
//-->
</script>

<script type='text/javascript' id="__bs_script__">//<![CDATA[
    document.write("<script async src='http://HOST:3000/browser-sync/browser-sync-client.2.11.1.js'><\/script>".replace("HOST", location.hostname));
//]]></script>

<script type="text/javascript">
  // The function actually applying the offset for jumplinks
  function offsetAnchor() {
      if(location.hash.length !== 0) {
          window.scrollTo(window.scrollX, window.scrollY - 100);
      }
  }

  // This will capture hash changes while on the page
  window.addEventListener("hashchange", offsetAnchor);

  // This is here so that when you enter the page with a hash,
  // it can provide the offset in that case too. Having a timeout
  // seems necessary to allow the browser to jump to the anchor first.
  window.setTimeout(offsetAnchor, 1); // The delay of 1 is arbitrary and may not always work right (although it did in my testing).
</script>

</body>
</html>
