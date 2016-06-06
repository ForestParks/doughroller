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
                            <li><a href="/blog/">Blog</a></li>
                            <li><a href="/community/">Forum</a></li>
                            <li><a href="/banks/reviews.aspx">Bank Reviews</a></li>
                            <li><a href="/banks/health.aspx">Bank Health Ratings</a></li>
                            <li><a href="/tools/">Calculators and Tools</a></li>                            
                        </ul>
                    </div>
                    <div class="small-6 columns">
                        <ul>
                            <li><a href="/cd/">CD Rates</a></li>
                            <li><a href="/ira/">IRA Rates</a></li>
                            <li><a href="/moneymarket/">Money Market</a></li>
                            <li><a href="/savings/">Savings Accounts</a></li>
                            <li><a href="/checking/">Checking Accounts</a></li>
                        </ul>
                    </div>
                </div>

                <p></p>

                
                <div class="second">
                <h6>Company:</h6>
                    <div class="small-6 columns">
                        <ul>
                            <li><a href="/about/">About Us</a></li>
                            <li><a href="/about/media/">Media Page</a></li>
                        </ul>
                    </div>
                    <div class="small-6 columns">
                        <ul>
                            <li><a href="/about/advertising/">Advertising</a></li>
                            <li><a href="/content/disclaimer.html">Disclaimer</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="medium-3 small-12 columns footerColumn">
               
					<div class="social-links-footer first">
						<h6>Connect With Us</h6>  
						<ul class="social-links ">
							<li class="facebook"><a href="#"><i class="fa fa-facebook"></i></a></li>
							<li class="facebook"><a href="#"><i class="fa fa-twitter"></i></a></li>
							<li class="facebook"><a href="#"><i class="fa fa-pinterest"></i></a></li>
							<li class="facebook"><a href="#"><i class="fa fa-youtube"></i></a></li>
						</ul>
					</div>

					<p></p>

					<div class="join-newsletter newsletter second"> 
						<h6>Join Our Newsletter</h6>
						
						<div id="newsletter-form-cont">
							<form action="#" method="post" id="subForm">
							        <div class="row collapse margintop-20px">
     
                               <form action="https://app.getresponse.com/add_subscriber.html" accept-charset="utf-8" method="post">

            <div class="small-12 medium-6 columns">
            <input style="width:95%"  type="text" name="name" value="Name"
  onblur="if(this.value==''){ this.value='Name'; this.style.color='#BBB';}"
  onfocus="if(this.value=='Name'){ this.value=''; this.style.color='#BBB';}"
  style="color:#BBB;" />
                        </div>

                         <div class="small-12 medium-6 columns">
                        <input type="text" name="email" value="Email"
  onblur="if(this.value==''){ this.value='Email'; this.style.color='#BBB';}"
  onfocus="if(this.value=='Email'){ this.value=''; this.style.color='#BBB';}"
  style="color:#BBB;" />
  </div>

            <input type="hidden" name="campaign_token" value="TAZ6"/>
<div class="small-12 columns">
            <input type="submit" class="button" style="width:100%" value="Let's Roll"/>
            </div>
            </form>

							     
							        </div>
							      </form>
							 </div>

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
			<p><strong>Disclaimer</strong>:  The content on this site is for informational and educational purposes only and should not be construed as professional financial advice.  Should you need such advice, consult a licensed financial or tax advisor.  References to products, offers, and rates from third party sites often change.  While we do our best to keep these updated, numbers stated on our site may differ from actual numbers.See our <a href="http://www.doughroller.net/terms-of-use-privacy-policy/">Privacy Policy &amp; Disclaimer</a> for more details.</p>
            </div>
            <div class="small-12 medium-3 columns">
            <p><strong>Archives</strong>:  You can explore the site through our <a href="http://www.doughroller.net/archives/">archives</a> dating back to 2007.</p>
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


/* ALLOW SHOW / HIDE on ccbox*/
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

</body>
</html>
