<?php
if(!class_exists('PopUp_Domination')){
  die('No direct access allowed.');
}

class PopUp_Domination_Front extends PopUp_Domination {

  function PopUp_Domination_Front(){
    parent::PopUp_Domination();
  }

  /**
  * split_calculation()
  *
  * Takes percent and returns a number (representing each campaign) using the percentage to switch between.
  */
  function split_calculation($campaigns, $percent){
    $count = count($campaigns);
    if (empty($campaigns) || $count == 1){
      return null;
    }
    $random = mt_rand(1, 100);
    if ($random < $percent){
      return $campaigns[0];
    } else {
      $difference = 100 - $percent;
      $random -= $percent;
      $block = $difference / ($count-1);
      $i = 0;
      while($random > 0){
        $random -= $block;
        $i++;
      }
      return $campaigns[$i];
    }
  }

  /**
  * load_lightbox()
  *
  * The magic function which setups up everything for the json to build and animate the PopUp.
  */
  function load_lightbox($shortcode_campaign_id = false){
    global $post;
    global $new_preview;
    $campaigns_data = array();
    $ab_data = array();
    $absplit_id = '';
    $stop_lightbox = false;
    $form_action = '';
    $conversionpage = '';
    $camp = '';
    $num = '';
    $set = false;
    $split = '';
    $splitcampcookie = false;
    $datasplit = false;
    $curpage = $post->post_name;
    /**
    * Finished getting everything setup.
    */
    $data = $this->get_db('popdom_campaigns');
    $datasplit = $this->get_db('popdom_ab');
    $i = 0;
    $num = array();

    /* TODO review this code */
    if($this->option('facebook_enabled', false) == 'Y'){
      $app_access = "https://graph.facebook.com/oauth/access_token?client_id=".$this->option('facebook_id')."&client_secret=".$this->option('facebook_sec')."&grant_type=client_credentials";
      $app_access_token = file_get_contents($app_access);
      $user = $this->facebook->getUser();
      if ($user) {
        try {
          $user_profile = $this->facebook->api('/me');
          $permissions = $this->facebook->api("/me/permissions");
          $UserName = $user_profile['name'];
        } catch (FacebookApiException $e) {
          error_log($e);
          $user = null;
        }
      }
      if ($user) {
        $logoutUrl = $this->facebook->getLogoutUrl();
        if(!isset($_GET['state'])){
          if( array_key_exists('email', $permissions['data'][0]) ) {
            $datasplit = false;
            $data = false;
          }
        }else{
          echo '';
        }
      } else {
        $loginUrl = $this->facebook->getLoginUrl(array('scope' => 'email,publish_stream'));
      }
    }


    $ab_data = $this->get_db('popdom_ab');
    // Check if there are any A/B campaigns
    if (!empty($ab_data)){
      $campaigns_data = $ab_data;
      // Loop through each A/B campaigns
      foreach($ab_data as $ab_test){
        $id = $ab_test->id;
        $campaigns = unserialize($ab_test->campaigns);
        $schedule = unserialize($ab_test->schedule);
        $abstats = unserialize($ab_test->astats);
        $ab_settings = unserialize($ab_test->absettings);
        $active = $ab_test->active;
        // Check if A/B campaign is active
        if ($active && count($campaigns) >= 2){
          // Check if this is to be shown everywhere
          if ($schedule['everywhere'] != 'Y'){
            // Check if visitor is on front page
            if (is_front_page()){
              //display if everywhere or front page
              if ($schedule['front'] == 'Y'){
                //set up pop up to display
                $campaign_id = $this->split_calculation($campaigns, $ab_settings['visitsplit']);
                $absplit_id = $id;
              }
            } else {
              // Loop through post ID's that A/B campaign is set up on
              if(isset($schedule['pageid'])){
                foreach($schedule['pageid'] as $index => $pageid){
                  if ($post->ID == $pageid) {
                    //set up pop up to display
                    $campaign_id = $this->split_calculation($campaigns, $ab_settings['visitsplit']);
                    $absplit_id = $id;
                  }
                }
              }
              // Loop through categories
              
                if(isset($schedule['catid']) && isset($schedule['caton'])){
                //caton = 0:Both, 1:Category page[is_category($catID)], 2:Post page within category [has_category($catID)]
                foreach($schedule['catid'] as $index => $catid){
                  switch ($schedule['caton']) 
                  {
                      case 0:
                        if (has_category($catid, $post->ID) || is_category($catid)) 
                        {
                            $campaign_id = $this->split_calculation($campaigns, $ab_settings['visitsplit']);
                            $absplit_id = $id;
                        }
                        break;
                      case 1:
                        if (is_category($catid))
                        {
                            $campaign_id = $this->split_calculation($campaigns, $ab_settings['visitsplit']);
                            $absplit_id = $id;                        
                        }
                        break;
                      case 2:
                        if (has_category($catid, $post->ID) && !is_category($catid)) 
                        {
                            $campaign_id = $this->split_calculation($campaigns, $ab_settings['visitsplit']);
                            $absplit_id = $id;                        
                        }
                        break;
                  }
                }
              }
            }
          } else {
            //set up pop up to display
            $campaign_id = $this->split_calculation($campaigns, $ab_settings['visitsplit']);
            $absplit_id = $id;
          }
        }
      }
    }
    if (empty($campaign_id)) { // a campaign ID hasn't been set by an A/B test
        $campaigns_data = $this->get_db('popdom_campaigns');
        if (!isset($campaign_id) && !empty($campaigns_data)){
          // Loop through each campaign
          foreach($campaigns_data as $campaign_test){
            $id = $campaign_test->id;
            $campaign_name = $campaign_test->campaign;
            $pages = unserialize($campaign_test->pages);
            $active = $campaign_test->active;
            // Check if campaign is active
            if ($active){
              // Check if this is to be shown everywhere
              if ($pages['everywhere'] != 'Y' || (!empty($shortcode_campaign_id) && $shortcode_campaign_id != $id)){
                // Check if visitor is on front page
                if (is_front_page()){
                  //display if everywhere or front page
                  if ($pages['front'] == 'Y'){
                    //set up pop up to display
                    $campaign_id = $id;
                  }
                } else {
                  // Loop through post ID's that campaign is set up on
                  if (isset($pages['pageid'])){
                    foreach($pages['pageid'] as $index => $pageid){
                      if ($post->ID == $pageid) {
                        //set up pop up to display
                        $campaign_id = $id;
                      }
                    }
                  }
                  // Loop through categories
                  if(isset($pages['catid']) && isset($pages['caton'])){
                    //caton = 0:Both, 1:Category page[is_category($catID)], 2:Post page within category [has_category($catID)]
                    foreach($pages['catid'] as $index => $catid){
                      switch ($pages['caton']) 
                      {
                          case 0:
                            if (has_category($catid, $post->ID) || is_category($catid)) 
                            {
                                $campaign_id = $id;
                            }
                            break;
                          case 1:
                            if (is_category($catid))
                            {
                                $campaign_id = $id;
                            }
                            break;
                          case 2:
                            if (has_category($catid, $post->ID) && !is_category($catid)) 
                            {
                                $campaign_id = $id;
                            }
                            break;
                      }
                    }
                  }
                }
              } else {
                //set up pop up to display
                $campaign_id = $id;
              }
            }
          }
        } else {
          $splitcampcookie = true;
        }
    }

    foreach($campaigns_data as $campaign){
      if ($campaign->id == $campaign_id || $shortcode_campaign_id == $campaign->id && $campaign->active == 1 || ($new_preview && $shortcode_campaign_id == $campaign->id)){
        $selected_campaign = $campaign;
        break;
      }
    }
    
    if (!empty($absplit_id))
    {
        $selected_campaign = $this->get_db('popdom_campaigns',"id = $campaign_id");
        $selected_campaign = $selected_campaign[0];
    }
    //die (print_r($selected_campaign,1));
    if(!empty($selected_campaign)){

        /**
        * Unscramble the data of the chosen campaign and check it's been setup correctly, stops error appearing.
        */
        $campaign = unserialize($selected_campaign->data);
        $campidnum = $selected_campaign->id;
        $campname = $selected_campaign->campaign;
        $t = $campaign['template']['template'];
        if(!$enabled = $this->option('enabled'))
          return false;
        if($enabled != 'Y')
          return false;
        if(!$t = $campaign['template']['template'])
          return false;
        if(!$themeopts = $this->get_theme_info($t))
          return false;
        if(isset($themeopts['colors']) && !($color = $campaign['template']['color']))
          return false;
        $clickbank = '';
        /**
        * Collect the promote settings from DB.
        */
        if($promote = $this->option('promote')){
          if($promote == 'Y'){
            $clickbank = $this->option('clickbank');
          }
        } else {
          $promote = 'N';
        }


        /**
        * Begins the setup for all fields appearing in the popup.
        */
        $fields = array();
        $inputs = array();




        /*
        * Do all information retrieval for mailing list
        */

        $id = $campaign['mailing_option']['mailing_list'];
        $mailingid = $id;
        $mail_notify = $campaign['mailing_option']['mail_notify'];
        if ($id != -1) {
          $mailing_data = $this->get_mailing_option($id);
          $mailing_info = unserialize(base64_decode($mailing_data));

          if (!empty($mailing_info)){
            // set up variables used by every provider
            foreach($mailing_info as $key => $value){
              $$key = $value;
            }


            $hidden_fields = '';
            if (gettype($provider_details) == "array"){
              // create all variables required for each provider
              foreach($provider_details as $key => $value){
                $$key = $value;
              }
              $formhtml = stripslashes($provider_details['formhtml']);

              //strips slashes from HTML form tab hidden fields
              $hidden_fields = stripslashes($hidden_fields);

              if (isset($hidden)){
                foreach($hidden as $name => $value){
                  $hidden_fields .= '<input type="hidden" name="'.$name.'" value="'.$value.'" />';
                }
              }
            }
          }



          $target = 'target="_self"';
          if ($new_window == 'yes'){
            $target = 'target="popdom"';
          }

          /**
          * Collect the mailing list settings from DB.
          */
          $inputs = array('email'=>$campaign['fields']['field_email_default'], 'name' => $campaign['fields']['field_name_default']);


          /**
          * Start setting up the custom field parts. LOOK AT THIS AGAIN AND THINK HOW TO REDUCE THIS <------------------------------------------------------------------------------------------------------------------------
          */
          if($provider != 'aw'){
            for($i = 1; $i <= $custom_fields; $i++){
              $inputs['custom'.$i.'_box'] = $mailing_info['custom'.$i];
            }
          }

          /*
          * Set up required for the mailing manager
          */
          $form_action = ($form_action == '') ? '#' : $form_action;
          $fields['form_header'] = '<form action="'.$form_action.'" method="post" '.$target.'>';
          
          $inputs['hidden'] = '<input class="provider" type="hidden" name="provider" value="'.$provider.'" />';
          $inputs['hidden'] .= '<input class="listid" type="hidden" name="listid" value="'.$listid.'" />';
          $inputs['hidden'] .= '<input class="listname" type="hidden" name="listname" value="'.$listname.'" />';
          $inputs['hidden'] .= '<input class="mailingid" type="hidden" name="mailingid" value="'.$id.'" />';
          $inputs['hidden'] .= '<input class="mailnotify" type="hidden" name="mailnotify" value="'.$mail_notify.'" />';
          $inputs['hidden'] .= '<input class="campaignid" type="hidden" name="campaignid" value="'.$campidnum.'" />';
          $inputs['hidden'] .= '<input class="campname" type="hidden" name="campname" value="'.$campname.'" />';
         

          $inputs['hidden'] .= $hidden_fields;


          // Add mailing specific data
          if ($provider == 'aw') {
            if (empty($redirect_url)){
              $redirect_url = '//'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
              $inputs['hidden'] .= '<input type="hidden" name="meta_redirect_onlist" value="'.$redirect_url.'" />';
            }
          } else if($provider == 'nm') {
            if (empty($redirect_url)){
              $redirect_url = '//'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
            }
            $inputs['hidden'] .= '<input class="master" type="hidden" name="master" value="'.$username.'" />';
          }

          if (!empty($redirect_url) && $redirect == 'yes'){
            $inputs['hidden'] .= '<input class="redirect" type="hidden" name="redirect" value="'.$redirect_url.'" />';
          }




          /* Code which is used to add visible fields i.e. email, name and custom fields */
          $fstr = '';
          $name_box = isset($name_box) ? $name_box : 'name';
          $email_box = isset($email_box) ? $email_box : 'email';
          if ($disable_name != 'yes'){
            $fstr .= '<input type="text" class="name" placeholder="'.$inputs['name'].'" name="'.$name_box.'" />';
          }
          $fstr .= '<input type="text" class="email" placeholder="'.$inputs['email'].'" name="'.$email_box.'" />';

          /* Adds the lines required so following scripts will know which fields are the custom fields */
          /* TODO Some of this is only required for nm - force users to use custom1, custom2... etc? Probably better, less code involved */
          for ($i = 1; $i <= min($custom_fields, $campaign['num_cus']); $i++){
            $valuevar = 'custom' . $i . 'name';
            $customname = $$valuevar;
            $inputs['hidden'] .= '<input class="custom_id'.$i.'" type="hidden" name="custom'.$i.'" value="'.$customname.'" />';
            $inputs['custom'.$i] = $campaign['fields']['field_custom'.$i.'_default'];
            $customname = $provider == 'aw' ? 'custom ' . $customname: $customname;
            $fstr .= '<input type="text" class="custom'.$i.'_input" placeholder="'.$campaign['fields']['field_custom'.$i.'_default'].'" name="'.$customname.'" />';;
          }

        } else {
          //user is using a redirect theme (or not set up the mailing manager)
          $provider = 'form';
          $target = 'target="_self"';
          $form_action = !empty($campaign['mailing_option']['redirect_url']) ? $campaign['mailing_option']['redirect_url']: '#';
        }




        if($f = $campaign['images']){
          if(!empty($f)){
            $fieldsarr = unserialize($f);
            foreach($fieldsarr as $b){
              $inputs['hidden'] .= '<div style="display:none"><img src="'.$b.'" alt="" height="1" width="1" /></div>';
            }
          }
        }









        $list_items = array();
        if($l = $campaign['list']){
          if(!empty($l)){
            foreach($l as $litem){
              $list_items[] = $this->encode($litem);
            }
          }
        }
        $fields = array();
        if(isset($themeopts['fields']) && count($themeopts['fields']) > 0){
          foreach($themeopts['fields'] as $a => $b){
            $id = $b['field_opts']['id'];
            $fields[$b['field_opts']['id']] = $campaign['fields']['field_'.$id];
          }
        }
        $center = $themeopts['center'];
        $delay = $campaign['schedule']['delay'];
        $linkclick = $campaign['schedule']['linkclick'];
        $delay_hide = ' style="display:none"';
        $button_color = $campaign['template']['button_color'];
        $cookie_time = $campaign['schedule']['cookie_time'];
        $base = dirname($this->base_name);
        $theme_url = $this->theme_url.$t;
        $this->currentcss = $this->theme_url.$t;
        $lightbox_id = 'popup_domination_lightbox_wrapper';
        $lightbox_close_id = 'popup_domination_lightbox_close';
        $icount = $campaign['schedule']['impression_count'];
        $show_opt = $campaign['schedule']['show_opt'];
        $tab_height = $campaign['schedule']['tab_height'];
        $show_anim = $campaign['schedule']['show_anim'];
        $this->show_anim = $show_anim;
        $this->shortcode_campaign_id = $shortcode_campaign_id;
        $show_mobiles = $campaign['schedule']['show_mobiles'];
        $close_option = $campaign['schedule']['close_option'];
        $unload_msg = $campaign['schedule']['unload_msg'];
        $name = $this->option('name_box');
        $email = $this->option('email_box');
        $custom1 = $this->option('custom1_box');
        $custom2 = $this->option('custom2_box');
        $arr = array();

        if($campaign['num_cus'] > 0):
          if($this->option('custom_fields') > 0){
            if(isset($api['custom1']) && $provider != 'form' && !empty($api['custom1']) || isset($custom1) && $provider == 'form' && !empty($custom1)){
              if($provider != 'aw' && $provider != 'form'){
                $arr[] = array('class'=>'custom1_input','default'=>((isset($fields['custom1_default'])) ? $fields['custom1_default'] : ''), 'name' => 'custom1_default');
              }else if($provider == 'aw'){
                $arr[] = array('class'=>'custom1_input','default'=>((isset($fields['custom1_default'])) ? $fields['custom2_default'] : ''), 'name' => 'custom '.$api['custom1']);
              }else{
                $arr[] = array('class'=>'custom1_input','default'=>((isset($fields['custom1_default'])) ? $fields['custom2_default'] : ''), 'name' => $custom1);
              }
            }
            if(isset($api['custom2']) && $provider != 'form' && !empty($api['custom2']) || isset($custom2) && $provider == 'form' && !empty($custom2)){
              if($provider != 'aw' && $provider != 'form'){
                $arr[] = array('class'=>'custom2_input','default'=>((isset($fields['custom2_default'])) ? $fields['custom2_default'] : ''), 'name' => 'custom2_default');
              }else if($provider == 'aw'){
                $arr[] = array('class'=>'custom2_input','default'=>((isset($fields['custom2_default'])) ? $fields['custom2_default'] : ''), 'name' => 'custom '.$api['custom2']);
              }else{
                $arr[] = array('class'=>'custom2_input','default'=>((isset($fields['custom2_default'])) ? $fields['custom2_default'] : ''), 'name' => $custom2);
              }
            }
          }
        endif;
        $js = array();
        foreach($arr as $a){
          if(!empty($a['name']) && !empty($a['default'])){
            $js[$a['name']] = $a['default'];
          }
        }
        $promote_link = (($promote=='Y') ? '<p class="powered"><a href="'.((!empty($clickbank))?'http://'.$clickbank.'.popdom.hop.clickbank.net/':'http://www.popupdomination.com/').'" target="_blank">Powered By PopUp Domination</a></p>':'');
        if($promote=='N'){
          $promote_link = '';
        }
        ob_start();
        include $this->theme_path.$t.'/template.php';
        $output = ob_get_contents();
        $output = str_replace('{CURRENT_URL}',$this->input_val($this->get_cur_url()),$output);
        ob_end_clean();
        if ($show_anim == "inpost") {$center = "N";}
        if ($this->show_anim != "inpost" && $campidnum == $shortcode_campaign_id && !$new_preview)
        {
            $output = '';
        }
        $arr = array('defaults'=>$js,'delay'=>floatval($delay),'cookie_time'=>floatval($cookie_time),'center'=>$center,'cookie_path'=>COOKIEPATH,'show_opt'=>$show_opt,'tab_height'=>$tab_height,'show_anim'=>$show_anim, 'show_mobiles'=>$show_mobiles, 'linkclick' => $linkclick, 'unload_msg'=>$unload_msg,'impression_count'=>intval($icount),'redirect' => urlencode($redirect_url), 'splitcookie' => $splitcampcookie, 'conversionpage' => urlencode($conversionpage), 'campaign' => $absplit_id, 'popupid' => $campidnum, 'mailingid'=> $mailingid, 'close_option' =>$close_option, 'output'=>$output);
        $this->output = $output;

      if(!$this->is_enabled())
        return false;
      if(!$t = $this->option('template'))
        return false; ?>
    <link rel='stylesheet' href='<?php echo $this->currentcss ?>/lightbox.css' type='text/css' media='all' />
    <script type="text/javascript">
      var popup_domination_admin_ajax = '<?php echo admin_url('admin-ajax.php') ?>',popup_domination = <?php echo json_encode($arr); ?>, popup_non = 'false', popup_domination_new_window = "<?php echo $new_window ;?>";
    </script>
    <?php
    } else {
      if(isset($camp) && isset($conversionpage)){
        $arr = array('conversionpage' => urlencode($conversionpage), 'campaign' => $camp);
      ?>
      <script>
        console.log('The plugin has found no PopUp for this page. If this page should have one, check your setup as verification has failed. (Ignore if using shortcode only)');
        var popup_domination_admin_ajax = '<?php echo admin_url('admin-ajax.php') ?>', popup_domination = <?php echo json_encode($arr); ?>, popup_non = 'true';
      </script>
      <?php
      }else{
      ?>
      <script>
        console.log('The plugin has found no PopUp for this page. If this page should have one, check your setup as verification has failed. (Is the campaign active?)');
        var popup_domination_admin_ajax = '<?php echo admin_url('admin-ajax.php') ?>', popup_domination = '',  popup_non = 'true';
      </script>
      <?php
      }
    }
  }

  /*
  *  Retrieve individual mailing list details
  */
  function get_mailing_option($id){
    $return = '';
    $data = $this->get_db('popdom_mailing', 'id="'.$id.'"');
    foreach($data as $option){
      $return = $option->settings;
    }
    return $return;
  }

  /**
  * wp_print_styles()
  *
  * Send alls the needs stylesheets to wordpress to load into the header.
  */

  function wp_print_styles(){
    if(!$this->is_enabled())
      return false;
    if(!$t = $this->option('template'))
      return false;
    //wp_enqueue_style('popup_domination',$this->currentcss.'/lightbox.css');
  }
  
    function popdom_shortcode_handler($atts){
        if (empty($atts['id'])) 
        {
            return '';
        }
        else {
            //we don't want to show the shortcode inpost campaigns if there's a popup on the page as it'll mess up the styling
            $all_campaigns = $this->get_db('popdom_campaigns',"id != {$atts['id']} AND data NOT LIKE '%show_anim\";s:6:\"inpost%'");
            unset($show_this_one);
            $show_this_one = true;
            foreach ($all_campaigns as $campaign)
            {
                $is_active = ($campaign->active == 1);
                if ($is_active)
                {
                    //there might be a popup on this page!
                    $pages = unserialize($campaign->pages);
                    $data = unserialize($campaign->data);
                    if (
                        (!empty($pages['everywhere']) && $pages['everywhere'] == "Y") ||
                        (!empty($pages['front']) && $pages['front'] == "Y" && is_front_page())
                       )
                    {
                        $show_this_one = false;
                    }
                    else {
                        // Loop through post ID's that campaign is set up on
                        if (!empty($pages['pageid'])){
                            foreach($pages['pageid'] as $index => $pageid)
                            {
                                if ($post->ID == $pageid)
                                {
                                    $show_this_one = false;
                                }
                            }
                        }
                        // Loop through categories
                        if(!empty($pages['catid']) && !empty($pages['caton'])){
                            //caton = 0:Both, 1:Category page[is_category($catID)], 2:Post page within category [has_category($catID)]
                            foreach($pages['catid'] as $index => $catid)
                            {
                                switch ($pages['caton']) 
                                {
                                    case 0:
                                        if (has_category($catid, $post->ID) || is_category($catid)) 
                                        {
                                            $show_this_one = false;
                                        }
                                        break;
                                    case 1:
                                        if (is_category($catid))
                                        {
                                            $show_this_one = false;
                                        }
                                        break;
                                    case 2:
                                        if (has_category($catid, $post->ID) && !is_category($catid)) 
                                        {
                                            $show_this_one = false;
                                        }
                                        break;
                                }
                            }
                        }
                    }
                }
            }
            unset($all_campaigns, $campaign, $data);
            if ($show_this_one)
            {
                unset ($this->output,$this->show_anim);
                ob_start(); //start buffering this so we can capture and/or discard popups from display
                parent::wp_print_scripts($atts['id']);
                if (!empty($this->output) && $this->show_anim == "inpost")
                {
                    $inpostPopup = '';
                    $inpostPopup .= ob_get_contents(); //save the buffer - it's an in-post
                    ob_end_clean();
                    $inpostPopup .= "<div id='popdom-inline-container'>\n";
                    $inpostPopup .= stripslashes( $this->output );
                    $inpostPopup .= "\n</div>\n";
                }
                else
                {
                    ob_end_clean(); //lose the buffer - it's a popup or it's turned off
                    $inpostPopup = "<script>console.log('Shortcode error for campaign id {$atts['id']}');</script>";
                    if (!$this->is_inpost_campaign($atts['id'])) $inpostPopup = "<script>console.log('Shortcode error for campaign id {$atts['id']} (Not in-post campaign)');</script>";
                    elseif (!$this->is_active_campaign($atts['id'])) $inpostPopup = "<script>console.log('Shortcode error for campaign id {$atts['id']} (Not turned on)');</script>";
                    else $inpostPopup = "<script>console.log('Shortcode error for campaign id {$atts['id']} (Undefined Error)');</script>";
                }
            }
            else 
            {
                $inpostPopup= "<script>console.log('Shortcode detected but active popup on this page!');</script>";
            }
            return $inpostPopup;
        }
    }
    
    function popdom_preview_handler($preview_campaign_id)
    {
        global $new_preview;
        $returnval = '';
        $new_preview = true;
        ob_start();
        $this->load_lightbox($preview_campaign_id);
        echo "<a href='#' class='popup-domination-link'>PopUp Domination</a>";
        $returnval .= ob_get_clean();
        $returnval .= "<script>var popdom_preview = true;</script>";
        $returnval .= "<script src='{$this->plugin_url}js/lightbox.js'></script>";
        return $returnval;
    }
    
  
}

if (!file_exists(WP_PLUGIN_DIR."/popup-domination-ABP-avoider/popdom-abp-avoider.php"))
{
 $popup_domination = new PopUp_Domination_Front();
}
else {
    if (!class_exists('PUD_ABP'))
    {
        require_once WP_PLUGIN_DIR."/popup-domination-ABP-avoider/popdom-abp-avoider.front.php";
        $popup_domination = new PUD_ABP();
    }
}