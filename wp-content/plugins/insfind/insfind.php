<?php
/*
Plugin Name: Insurance Finder
Description: Insurance Finder Plugin
Version: 1.0
Author: Dave Meppelink

Copyright 2010 David J. Meppelink. All rights reserved.
*/

if (!function_exists('insfind_replace')) {

  # Public function to echo the insurance finder with the given type.
  # If no type is provided, then use the default configured in the settings.
  function insfind_echo($type = null)
  {
    echo insfind_build_html($type);
  }

  # Register the filter.
  add_filter('the_content', 'insfind_replace', 20);

  function insfind_replace($content) 
  {
    # This pattern matches "[insurance_finder]" (no type specfied)
    # and it matches "[insurance_finder_TYPE]" (and TYPE is in $matches[2])
    return preg_replace_callback('/\[insurance_finder(_(\w+))?\]/',
                                 'insfind_callback',
                                 $content);
  }

  function insfind_callback($matches)
  {
    # Use the HTTP request parameter as the type.
    $type = $_REQUEST['type'];
    # If that is not valid, then use the value from the replacement token.
    if (!insfind_valid_type($type)) {
      $type = $matches[2];
      # no need to check the value here; the build function will do that.
    }
    return insfind_build_html($type);
  }

  function insfind_build_html($type)
  {
    $options = get_option('insfind_settings');

    # If caller does not provide type or it is invalid, then use default.
    if (!insfind_valid_type($type)) {
      $type = $options['default_type'];
    }

    $ad_client = $options['client_id_' . $type];

    $state = $_REQUEST['state'];
    if (is_null($state) || $state == '') {
      $state = $options['default_state'];
    }

    $params = "";
    foreach ($_GET as $name => $value) {
      if ($name != 'type' && $name != 'state') {
        $params .= "<input type='hidden' name='$name' value='$value'/>\n";
      }
    }

    $type_options = insfind_options_html(insfind_types(), $type);

    $state_options = insfind_options_html(insfind_states(), $state);

    $body = <<<END
<div><style type="text/css">
.insfind_div {
  margin: 4px 0px 8px 0px;
}
.insfind_pick_type {
  width: auto;
  margin: 0px 2px 0px 0px;
  padding: 0px;
}
.insfind_pick_state {
  width: auto;
  margin: 0px 0px 0px 2px;
  padding: 0px;
}
</style></div>
<div class="insfind_div">
<form name="insfind_pick" method="get">
{$params}
<select class="insfind_pick_type" name="type" onchange="javascript:this.form.submit();">
$type_options
</select>
in
<select class="insfind_pick_state" name="state" onchange="javascript:this.form.submit();">
$state_options
</select>
</form>
</div>
<script type="text/javascript">
ni_ad_client = {$ad_client};
ni_res_id = 2;
ni_alt_url = "https://www.shmktpl.com/search.asp";
ni_zc = "";
ni_str_state_code = "{$state}";
ni_var1 = "";
ni_display_width = {$options['display_width']};
ni_display_height = {$options['display_height']};
ni_color_border = "";
ni_color_bg = "";
ni_color_link = "";
ni_color_url = "";
ni_color_text = "";
</script>
<script type="text/javascript" src="https://www.shmktpl.com/retrieve_listings.asp"></script>
<noscript><a href="https://www.shmktpl.com/search.asp?src=188343&res=2"><img src="https://www.shmktpl.com/images/nojs/image.asp?src=188343&res=2" border="0"></a></noscript>
END;
    return $body;
  }

  # Setup during activation

  register_activation_hook(__FILE__, 'insfind_activation');

  function insfind_activation()
  {
    insfind_add_defaults();
  }

  function insfind_add_defaults()
  {
    $options = get_option('insfind_settings');
    if(($options['restore_defaults']) || !is_array($options)) {
      # create the default array with default values
      $def = array('display_width' => 500,
                   'display_height' => 1000,
                   'default_type' => 'auto',
                   'default_state' => 'CA',
                   'restore_defaults' => true);
      # preserve existing client_id_ values because it is annoying to loose them
      if (is_array($options)) {
        # use the list of known types, rather than name matching on the old values,
        # for greater precision
        foreach (array_keys(insfind_types()) as $type) {
          $def['client_id_' . $type] = $options['client_id_' . $type];
        }
      }
      update_option('insfind_settings', $def);
    }
  }

  # Create menu and page

  add_action('admin_menu', 'insfind_create_menu');

  function insfind_create_menu()
  {
    # Show the options page under the Settings section.
    add_options_page('Insurance Finder Settings', # page_title
                     'Insurance Finder', # menu_title
                     'administrator', # capability
                     'insfind_options', # page id (handle)
                     'insfind_options_page'); # function
  }

  function insfind_options_page()
  {
?>
<div class="wrap">
  <div class="icon32" id="icon-options-general"><br></div>
  <h2>Insurance Finder Settings</h2>
  <form method="post" action="options.php">
    <?php settings_fields('insfind_settings'); ?>
    <?php do_settings_sections('insfind_options'); ?>
    <p class="submit">
      <input name="Submit" type="submit" class="button-primary" value="Save Changes" />
    </p>
  </form>
</div>
<?php
  }

  add_action('admin_init', 'insfind_register_settings');
  function insfind_register_settings()
  {
    register_setting('insfind_settings', # identifies all of the options in the set
                     'insfind_settings', # the name that is stored in the options db
                     'insfind_sanitize_options' ); # validation function

    add_settings_section('insfind_affiliate_section', # section id
                         'Affiliate Information', # title displayed on panel
                         'insfind_affiliate_intro', # displays section information
                         'insfind_options'); # page id

    foreach (insfind_types() as $type => $type_name) {
      $field_id = 'client_id_' . $type;
      $title = $type_name . ' Client ID';
      add_settings_field($field_id, # field id
                         $title, # display title
                         'insfind_option_integer', # rendering callback
                         'insfind_options', # page id
                         'insfind_affiliate_section', # section id
                         array('id' => $field_id)); # rendering arguments
    }

    add_settings_section('insfind_appearance_section', # section id
                         'Appearance', # title displayed on panel
                         'insfind_appearance_intro', # displays section information
                         'insfind_options'); # page id

    add_settings_field('display_width', # field id
                       'Display Width', # display title
                       'insfind_option_integer', # rendering callback
                       'insfind_options', # page id
                       'insfind_appearance_section', # section id
                       array('id' => 'display_width')); # rendering arguments

    add_settings_field('display_height', # field id
                       'Display Height', # display title
                       'insfind_option_integer', # rendering callback
                       'insfind_options', # page id
                       'insfind_appearance_section', # section id
                       array('id' => 'display_height')); # rendering arguments

    add_settings_section('insfind_misc_section', # section id
                         'Miscellaneous', # title displayed on panel
                         'insfind_misc_intro', # displays section information
                         'insfind_options'); # page id

    add_settings_field('default_type', # field id
                       'Starting Type', # display title
                       'insfind_option_select', # rendering callback
                       'insfind_options', # page id
                       'insfind_misc_section', # section id
                       array('id' => 'default_type',
                             'entries' => insfind_types())); # rendering arguments

    add_settings_field('default_state', # field id
                       'Starting State', # display title
                       'insfind_option_select', # rendering callback
                       'insfind_options', # page id
                       'insfind_misc_section', # section id
                       array('id' => 'default_state',
                             'entries' => insfind_states())); # rendering arguments

    add_settings_field('restore_defaults', # field id
                       'Restore Defaults at Reactivation', # display title
                       'insfind_option_checkbox', # rendering callback
                       'insfind_options', # page id
                       'insfind_misc_section', # section id
                       array('id' => 'restore_defaults')); # rendering arguments
  }

  function insfind_affiliate_intro()
  {
    echo 'Settings for the affiliate account.';
  }

  function insfind_appearance_intro()
  {
    echo 'These settings control the offer appearance in the web browser.';
  }

  function insfind_misc_intro()
  {
    echo 'Various settings that control the plugin behavior.';
  }

  function insfind_option_integer($args)
  {
    $id = $args['id'];
    $options = get_option('insfind_settings');
    $value = $options[$id];
    echo "<input id='insfind_{$id}' name='insfind_settings[{$id}]' size='20' type='text' value='$value' />";
  }

  function insfind_option_checkbox($args)
  {
    $id = $args['id'];
    $options = get_option('insfind_settings');
    $value = $options[$id];
    if($value)
      $checked = 'checked="checked"';
    echo "<input id='insfind_{$id}' name='insfind_settings[{$id}]' type='checkbox' $checked />";
  }

  function insfind_option_select($args)
  {
    $id = $args['id'];
    $entries = $args['entries'];
    $options = get_option('insfind_settings');
    echo "<select id='insfind_{$id}' name='insfind_settings[{$id}]'>";
    echo insfind_options_html($entries, $options[$id]);
    echo "</select>";
  }

  function insfind_sanitize_options($options)
  {
    # none required (yet)
    return $options;
  }

  function insfind_options_html($entries, $current)
  {
    $options = '';
    foreach ($entries as $value => $name) {
      $selected = ($value == $current) ? " selected='1'" : "";
      $options .= "<option value='{$value}'{$selected}>{$name}</option>\n";
    }
    return $options;
  }

  function insfind_valid_type($type)
  {
    return key_exists($type, insfind_types());
  }

  function insfind_types()
  {
    return array('auto' => 'Auto Insurance',
                 'health' => 'Health Insurance',
                 'home' => 'Home Insurance',
                 'life' => 'Life Insurance');
  }

  function insfind_states()
  {
    return array('AK' => 'Alaska',
                 'AL' => 'Alabama',
                 'AR' => 'Arkansas',
                 'AZ' => 'Arizona',
                 'CA' => 'California',
                 'CO' => 'Colorado',
                 'CT' => 'Connecticut',
                 'DC' => 'Washington D.C.',
                 'DE' => 'Delaware',
                 'FL' => 'Florida',
                 'GA' => 'Georgia',
                 'HI' => 'Hawaii',
                 'IA' => 'Iowa',
                 'ID' => 'Idaho',
                 'IL' => 'Illinois',
                 'IN' => 'Indiana',
                 'KS' => 'Kansas',
                 'KY' => 'Kentucky',
                 'LA' => 'Louisiana',
                 'MA' => 'Massachusetts',
                 'MD' => 'Maryland',
                 'ME' => 'Maine',
                 'MI' => 'Michigan',
                 'MN' => 'Minnesota',
                 'MO' => 'Missouri',
                 'MS' => 'Mississippi',
                 'MT' => 'Montana',
                 'NC' => 'North Carolina',
                 'ND' => 'North Dakota',
                 'NE' => 'Nebraska',
                 'NH' => 'New Hampshire',
                 'NJ' => 'New Jersey',
                 'NM' => 'New Mexico',
                 'NV' => 'Nevada',
                 'NY' => 'New York',
                 'OH' => 'Ohio',
                 'OK' => 'Oklahoma',
                 'OR' => 'Oregon',
                 'PA' => 'Pennsylvania',
                 'RI' => 'Rhode Island',
                 'SC' => 'South Carolina',
                 'SD' => 'South Dakota',
                 'TN' => 'Tennessee',
                 'TX' => 'Texas',
                 'UT' => 'Utah',
                 'VA' => 'Virginia',
                 'VT' => 'Vermont',
                 'WA' => 'Washington',
                 'WI' => 'Wisconsin',
                 'WV' => 'West Virginia',
                 'WY' => 'Wyoming');
  }
}

?>
