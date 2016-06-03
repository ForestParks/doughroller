+---------------------------------------------------------------------+
|     Secure and Accessible PHP Contact Form v.2.0 by Mike Cherim     |
| Installation Instructions and Form Administartor Configuration File |
+---------------------------------------------------------------------+
|     Working Demo and Support Form: http://green-beast.com/gbcf/     |
|   Additional Information: http://green-beast.com/blog/?page_id=71   |
|     Additional Information: http://green-beast.com/blog/?p=128      |
+---------------------------------------------------------------------+  



1) FORM INSTALLATION INSTRUCTIONS

   1. Unpack the *.zip file and extract the individual files.


   2. Using a text editor like Notepad, open the file named 
      gbcf_config.php and complete at least section one of the 
      configuration steps.


   3. Upload the files gbcf_config.php, gbcf_form.php, and 
      gbcf_focus.js in a single "form-files-directory" on your 
      server. Name the directory however you want.


   4. You can also place the gbcf_styles.css in the same directory, 
      but you will likely just want to add the file’s contents to your 
      own CSS file. It's your call.


   5. Using the PHP include function, include the file gbcf_form.php
      in any *.php page file where you want it to be. It must be 
      below the <body> tag and preferably below the main heading. 
      I recommend placing it in the main content area. 
      See Example 1, below.

      Example 1
       <?php include("form-files-directory/gbcf_form.php"); ?>


   6. Place a JavaScript link within the <head></head> of the page. 
      See Example 2, below. Place it in a conditional comment since 
      it's only needed for IE. You won’t need this unless your are 
      providing form field focus (recommended).

      Example 2
       <!--[if IE]><script type="text/javascript" src="form-files-directory/gbcf_focus.js"></script><![endif]--> 


   7. That's it. Your done. At this point you will want to link to 
      the style sheet if you're not adding the elements, selectors, 
      classes, and IDs to your own style sheet, modify the 
      configuration file further if wanted, and of course test your 
      new form. To link to the style sheet, please see Example 3, below.

      Example 3
        <link rel="stylesheet" type="text/css" href="form-files-directory/gbcf_styles.css" media="screen" />
        (Note: XHTML method shown, for HTML drop the slash at the end and close the gap.)



2) FORM CONFIGURATION INSTRUCTIONS
   
   Important General Information:
   > Enter any and all needed values as currently formatted and edit 
     nothing else. In other words, if items are between quotes and
     end with a semi-colon, make sure you do the same. If items are
     parsed with apostrophes and end with a comma, you must do the 
     same. If you need to enter quote marks in a line parsed with 
     quotes, you must "escape" your quotes by putting a backslash 
     in front of them.

       Example: 
	     "I want to ask about \"quoted\" text"; 

       Or use character entities:
         "I want to ask about &quot;quoted&quot; text"; 
       Or:
         "I want to ask about &#8220;quoted&#8221; text"; 
        	

   SPECIFIC CONFIG INFORMATION

   1.  General form configuration:

       a. "Enter your email address" (what the form submits to).

          Example: $gb_email_address=       "info@foo.org";

       b. "Enter your name or company" (for the email greeting).

          Example: $gb_contact_name=        "The Foo Organization";
           Or
          Example: $gb_contact_name=        "John Foo";

       c. "Set site/form possession" (this changes wording from 
          "me" to "us," "my" to "our," etc. This is for the main 
          heading, error messages, etc.).

          Example: $gb_possession=          "org";

       d. "Enter website name" (or page name, company name, whatever).

          Example: $gb_website_name=        "The Foo Organization";
           Or
          Example: $gb_website_name=        "Foo.org";

       e. "Enter time offset if needed" (if server time varies from local)
           
          Example: $time_offset=            "0";
           Or
          Example: $time_offset=            "+1";
           Or
          Example: $time_offset=            "+2";
           Or
          Example: $time_offset=            "-1";
           Or
          Example: $time_offset=            "-2";
           Or
          Whatever the offset amount plus or minus


   2. "Contact Reason" menu: Use this to enter options for the pulldown 
      (select) menu. Enter as many options you want, the form will adapt.
      Some common ones are provided.

      Example:  
        "I need a work quote";    
        "I want you to call me";
        "I need the meaning of life";


   3. "Anti-Spam" q/a options: Use this to enter a simple question and
      answer. Suggestions are provided.

      Example: $gb_randomq=      "What color is the sky?";
        Example: $gb_randoma=   "blue";

      /*
         These "comment" marks block the others from being used.
      */

      // You could also put two forward slashes in from of each
      // to comment or block them out


   4. Heading options:

      a. "Set heading size" (enter a numeric value, 1-6).

          Example: $gb_heading=             "3";

      b. "Enter your error heading" (what your errors say, set color 
         to .error class in CSS).

         Example: $error_heading=          "You Made a Boo-Boo!";

      c. "Enter your success heading" (what your success result 
         says, set color to .success class in CSS).

         Example: $success_heading=         "Yipee! You Did It!";


   5. Other config options menu:

      a. "Choose XHTML or HTML" (enter the form markup type needed 
         for your site).

         Example: $x_or_html=              "html";

      b. "Enter your button text" (enter optional text for your 
         "Submit Form" button).

         Example: $send_button=            "Send Mail";

      c. "Enter credit link option" (a simple yes or no determines 
         if you want the link back to me to show... I hope you keep 
         it as "yes" but it's your call).

         Example: $showcredit=             "no";

      d. "Enter privacy link option" (a simple yes or no... "yes" if 
         you have a privacy policy. "no" if you don't).

         Example: $showprivacy=            "yes";

      e. "Enter privacy link url" (if you answered "yes" to above 
         option, enter the absolute URL or relative path to that policy).

         Example: $privacyurl=             "http://yourdomain.com/privacy.html";

       f. "Choose to allow/disallow CC option"
          Example: $show_cc=                "yes";
           Or
          Example: $show_cc=                "no";


   6. Custom tabindex assignments: If you want to set tabindex values, use 
      this section to do so. All are set to zero by default.

      Example: 
        $tab_privacy=            "1";
        $tab_name=               "2";
        $tab_email=              "3";
        $tab_phone=              "4";
        $tab_url=                "5";
        $tab_reason=             "6";
        $tab_message=            "7";
        $tab_spam=               "8";
        $tab_why=                "9";
        $tab_cc=                 "10";
        $tab_submit=             "11";



   7. IP Blacklist: If you have a need to block a certain IP address, you 
      can enter it in this array. The array is structured for you but left
      blank by default. Use this only as a last resort and know that this 
      is not effective against anyone who doesn’t have a static IP address. 
      When you get emails sent to you, the user’s IP address is part of the 
      data you will receive.

      Example:
        '00.00.00.00',
        '12.344.67.89',



   8. Form location specification: This is done for you by way of a PHP code
      snippet, but if you have a problem with the form giving referrer mismatch
      or spam trap errors during testing, it may be due to its location or in 
      instances where you have custom URLs resulting from apache_mod_rewrite rules 
      being in effect. If so, enter your absolute or URL or relative path in place of 
      the snippet used in the form_location variable.

      Example:
        // $form_location= "http://".$_SERVER['HTTP_HOST']."".@$_SERVER['REQUEST_URI']."";
        $form_location= "http://yourdomain.com/contact.php";

      Or:

      Example:
        // $form_location= "http://".$_SERVER['HTTP_HOST']."".@$_SERVER['REQUEST_URI']."";
        $form_location= "http://yourdomain.com/contact/";


      IMPORTANT NOTE: 
        The "form location" is your contact page URL as seen on your browser's address
        bar and it must match exactly. Please double check this.



+---------------------------------------------------------------------+ 
| Need help, a custom version, styling modifications. If you can't    |
| find what you're looking for or figure it out, you can request paid |
| support. Contact Mike Cherim at http://green-beast.com/contact/     |
+---------------------------------------------------------------------+

