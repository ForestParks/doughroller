<?php
/*
Plugin Name: GoTo Director
Description: GoTo Director replaces "goto:tag" with a URL to the goto.php script. The "tag" can contain letters, numbers, underscores and dashes, and it refers to an entry in the wp_goto_map table.
Version: 1.0
Author: Dave Meppelink

Copyright 2010 David J. Meppelink. All rights reserved.
*/

if (!function_exists('goto_replace')) {

  # Register the filter. Use priority 11 so that the replacement
  # happens after other text filters such as WP-Table Reloaded.
  add_filter('the_content', 'goto_replace', 11);

  # Replace all instances of "goto:XYZ" with proper redirect URLs.
  # $content is the entire content of the page
  # returns new page content with appropriate replacements
  function goto_replace($content) 
  {
    return preg_replace_callback('|goto:([a-zA-Z0-9_-]+)|',
                                 'goto_callback',
                                 $content);
  }

  # Convert the single matched "goto:XYZ" into a proper URL.
  function goto_callback($matches)
  {
    $options = get_option('goto_settings');

    # Start building the URL based on the tag and current page id.
    $base = $options['redir_url'];
    $tag = $matches[1];
    $pageid = get_the_ID();
    $url = "{$base}?t={$tag}&p={$pageid}";

    # If the option requests it, and the referrer exists,
    # add its query string to the URL.
    $include_search = $options['include_search'];
    if ($include_search) {
      $ref_url = wp_get_referer();
      $ref_query = parse_url($ref_url, PHP_URL_QUERY);
      parse_str($ref_query, $ref_query_array);
      $search = $ref_query_array['q'];
      if (!is_null($search)) {
        $url .= "&s={$search}";
      }
    }

    return $url;
  }

  # Setup during activation

  register_activation_hook(__FILE__, 'goto_activation');

  function goto_activation()
  {
    goto_add_defaults();
    goto_create_table();
    goto_create_config();
  }

  function goto_add_defaults()
  {
    $options = get_option('goto_settings');
    if(($options['restore_defaults']) || !is_array($options)) {
      $def = array('redir_url' => (get_option('siteurl') . '/goto.php'),
                   'include_search' => true,
                   'restore_defaults' => true);
      update_option('goto_settings', $def);
    }
  }

  function goto_patch_config($item)
  {
    if (strpos($item, 'CONFIG_HERE') !== false) {
      return
	"define('DB_HOST', '" . DB_HOST . "');\n" . 
	"define('DB_USER', '" . DB_USER . "');\n" . 
	"define('DB_PASSWORD', '" . DB_PASSWORD . "');\n" . 
	"define('DB_NAME', '" . DB_NAME . "');\n";
    } else {
      return $item;
    }
  }

  function goto_create_config()
  {
    $src = plugin_dir_path(__FILE__) . 'goto.tmpl';
    $dst = plugin_dir_path(__FILE__) . 'goto.php';

    $lines = file($src);

    $lines = array_map('goto_patch_config', $lines);

    file_put_contents($dst, $lines);
  }

  # Create menu and page

  add_action('admin_menu', 'goto_create_menu');

  function goto_create_menu()
  {
    $dp = add_management_page('GoTo Director Tool', # page_title
			      'GoTo Director', # menu_title
			      'administrator', # capability
			      'goto_director_tool', # handle
			      'goto_dp_content'); # function

    add_action('admin_print_scripts-' . $dp, 'goto_dp_scripts');
    add_action('admin_print_styles-' . $dp, 'goto_dp_styles'); 

    # Show the options page under the Settings section.
    add_options_page('GoTo Settings', # page_title
                     'GoTo Director', # menu_title
                     'administrator', # capability
                     'goto_director_options', # page id (handle)
                     'goto_options_page'); # function
  }

  function goto_dp_scripts()
  {
    wp_enqueue_script('goto_dp_prototype', # handle
		      'http://ajax.googleapis.com/ajax/libs/prototype/1.6.0.2/prototype.js', # source
		      array(), # dependencies
		      null, # version
		      false); # in footer

    wp_enqueue_script('goto_dp_tablekit', # handle
		      plugins_url('js/tablekit.js', __FILE__), # source
		      array('goto_dp_prototype'), # dependencies
		      null, # version
		      false); # in footer

    wp_enqueue_script('goto_dp_script', # handle
		      plugins_url('js/goto_director.js', __FILE__), # source
		      array('goto_dp_tablekit'), # dependencies
		      null, # version
		      false); # in footer
  }

  function goto_dp_styles()
  {
      wp_enqueue_style('goto_dp_style', # handle
		       plugins_url('css/goto_director.css', __FILE__), # source
		       array(), # dependencies
		       1.0, # version
		       'all'); # media
  }

  function goto_dp_content()
  {
?>
<div class="wrap">
  <div class="icon32" id="icon-tools"><br></div>
  <h2>GoTo Director</h2>
  <h3>Add New Mapping</h3>
    <form id="goto_add_form">
      <table>
      <tr><td>Tag:</td><td><input type="text" name="tag" size="50" maxlength="40"/></td></tr>
      <tr><td>URL:</td><td><input type="text" name="url" size="80" maxlength="250"/></td></tr>
      <tr><td>&nbsp;</td><td><input id="goto_add_button" type="submit" value="Add"/></td></tr>
      </table>
  </form>
  <h3>All Mappings</h3>
  <p>URLs can contain a few special values that are expanded when the link is used:</p>
  <ul class="notes">
    <li><tt>[KEY]</tt> is replaced with the key used in the source page</li>
    <li><tt>[PAGE]</tt> is replaced with the WordPress page_id of the source page</li>
    <li><tt>[SEARCH]</tt> is replaced with the search text that brought the browser to the source page</li>
  </ul>
  <table class="sortable resizable editable">
    <thead>
      <tr>
        <th id='tag' class='sortcol sortasc'>Tag</th>
        <th id='url' class='sortcol'>URL</th>
        <th id='action' class='nosort noedit'>Action</th>
      </tr>
    </thead>
    <tbody>
<?php
    global $wpdb;

    $table_name = $wpdb->prefix . 'goto_map';

    $rows = $wpdb->get_results("SELECT * FROM $table_name ORDER BY tag ASC", ARRAY_N);
    foreach($rows as $row) {
      echo "<tr id='{$row[0]}'>";
      echo "<td class='tagcell'>$row[1]</td>";
      echo "<td class='urlcell'>$row[2]</td>";
      echo "<td><a onclick='goto_delete_request($row[0], \"$row[1]\");'>delete</a></td>";
      echo '</tr>';
    }
?>
    </tbody>
  </table>
</div>
<?php
  }

  function goto_options_page()
  {
?>
<div class="wrap">
  <div class="icon32" id="icon-options-general"><br></div>
  <h2>GoTo Settings</h2>
  <form method="post" action="options.php">
    <?php settings_fields('goto_settings'); ?>
    <?php do_settings_sections('goto_director_options'); ?>
    <p class="submit">
      <input name="Submit" type="submit" class="button-primary" value="Save Changes" />
    </p>
  </form>
</div>
<?php
  }

  add_action('wp_ajax_goto_add', 'goto_add_record');
  add_action('wp_ajax_goto_edit', 'goto_ws_edit_record');
  add_action('wp_ajax_goto_delete', 'goto_ws_delete_record');

  function goto_add_record()
  {
    global $wpdb;
    $table_name = $wpdb->prefix . 'goto_map';

    $tag = $_POST['tag'];
    $url = $_POST['url'];

    $wpdb->insert($table_name,
		  array('tag' => $tag,
			'url' => $url));
    $id = $wpdb->insert_id;

    echo "Added record for $tag (id $id)";
    die();
  }

  function goto_ws_edit_record()
  {
    global $wpdb;
    $table_name = $wpdb->prefix . 'goto_map';

    $id = $wpdb->escape($_POST['id']);
    $field = $wpdb->escape($_POST['field']);
    $value = $wpdb->escape($_POST['value']);

    $wpdb->update($table_name,
		  array($field => $value), # data
		  array('id' => $id)); # where

    echo $value;
    die();
  }

  function goto_ws_delete_record()
  {
    global $wpdb;
    $table_name = $wpdb->prefix . 'goto_map';

    $id = $wpdb->escape($_POST['id']);
    $tag = $_POST['tag'];

    $wpdb->query("DELETE FROM $table_name WHERE id = $id");

    echo "Deleted record for $tag (id $id)";
    die();
  }

  add_action('admin_init', 'goto_register_settings');
  function goto_register_settings()
  {
    register_setting('goto_settings', # identifies all of the options in the set
                     'goto_settings', # the name that is stored in the options db
                     'goto_sanitize_options' ); # validation function

    add_settings_section('goto_url_section', # section id
                         'URL Generation', # title displayed on panel
                         'goto_url_section_text', # displays section information
                         'goto_director_options'); # page id

    add_settings_field('redir_url', # field id
                       'Redirect URL', # display title
                       'goto_option_redir_url', # rendering callback
                       'goto_director_options', # page id
                       'goto_url_section'); # section id

    add_settings_field('include_search', # field id
                       'Include Search Terms', # display title
                       'goto_option_include_search', # rendering callback
                       'goto_director_options', # page id
                       'goto_url_section'); # section id

    add_settings_section('goto_misc_section', # section id
                         'Miscellaneous', # title displayed on panel
                         'goto_misc_section_text', # displays section information
                         'goto_director_options'); # page id

    add_settings_field('restore_defaults', # field id
                       'Restore Defaults at Reactivation', # display title
                       'goto_option_restore_defaults', # rendering callback
                       'goto_director_options', # page id
                       'goto_misc_section'); # section id
  }

  function goto_url_section_text()
  {
    echo 'These options control how the "goto:tag" URLs are generated.';
  }

  function goto_misc_section_text()
  {
    echo 'Various settings that control the plugin behavior.';
  }

  function goto_option_redir_url()
  {
    $options = get_option('goto_settings');
    $value = $options['redir_url'];
    echo "<input id='redir_url' name='goto_settings[redir_url]' size='50' type='text' value='$value' />";
  }

  function goto_option_checkbox($name)
  {
    $options = get_option('goto_settings');
    $value = $options[$name];
    if($value)
      $checked = 'checked="checked"';
    echo "<input id='$name' name='goto_settings[$name]' type='checkbox' $checked />";
  }

  function goto_option_include_search()
  {
    goto_option_checkbox('include_search');
  }

  function goto_option_restore_defaults()
  {
    goto_option_checkbox('restore_defaults');
  }

  function goto_sanitize_options($options)
  {
    $options['redir_url'] = wp_filter_nohtml_kses($options['redir_url']);
    return $options;
  }

  function goto_create_table()
  {
    global $wpdb;

    $table_name = $wpdb->prefix . 'goto_map';

    if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
      $sql = "CREATE TABLE $table_name (
          id bigint(20) unsigned NOT NULL auto_increment,
          tag varchar(40) NOT NULL,
          url varchar(250) NOT NULL,
          PRIMARY TAG (id),
          UNIQUE TAG (tag)
        );";

      require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
      dbDelta($sql);
    }
  }

}

?>
