<?php

function showoff_encode($data, $max_length, $name)
{
  $encoded = json_encode($data);
  if (!$encoded) {
    throw new Exception("JSON decode failed: " . $data);
  }
  $length = strlen($encoded);
  if ($length > $max_length) {
    throw new Exception("Encoded $name is too long: $length");
  }
  return $encoded;
}

abstract class ShowOffStore
{
  protected $table_name;
  
  function __construct($table_base_name)
  {
    global $wpdb;
    
    $this->table_name = $wpdb->prefix . $table_base_name;
  }
  
  function activate()
  {
    global $wpdb;
    
    $table_name = $this->table_name;
    if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
      require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
      $sql = $this->get_create_statement();
      dbDelta($sql);
    }
  }
  
  abstract function get_create_columns();

  function get_create_statement()
  {
    $columns = $this->get_create_columns();
    return "CREATE TABLE {$this->table_name} (\n" . join(",\n", $columns) . "\n);";
  }
  
  private function delete_from_db($where)
  {
    global $wpdb;

    $sql = "DELETE FROM {$this->table_name} WHERE {$where}";
    $n = $wpdb->query($sql);

    if ($n) {
      return null;
    } else {
      return $wpdb->last_error;
    }
  }
  
  function delete($id)
  {
    return $this->delete_from_db("id = {$id}");
  }

  function delete_tag($tag)
  {
    return $this->delete_from_db("tag = '{$tag}'");
  }

  function get_list_rows()
  {
    global $wpdb;

    $query = "SELECT id, tag, name FROM {$this->table_name} ORDER BY tag ASC";
    $rows = $wpdb->get_results($query, OBJECT);
    return $rows;
  }
  
  abstract function new_record();
  
  function new_record_from_post()
  {
    $record = $this->new_record();
    $record->init_from_post();
    return $record;
  }

  private function new_record_from_db($where)
  {
    $record = $this->new_record();
    $record->init_from_db($where);
    return $record;
  }

  function new_record_from_id($id)
  {
    return $this->new_record_from_db("id = $id");
  }

  function new_record_from_tag($tag)
  {
    return $this->new_record_from_db("tag = '$tag'");
  }
}

class ShowOffTemplateStore extends ShowOffStore
{
  function __construct()
  {
    parent::__construct('showoff_template');
  }
  
  function get_create_columns()
  {
    return array('id bigint(20) unsigned NOT NULL auto_increment',
                 'tag varchar(40) NOT NULL',
                 'name varchar(100) NOT NULL',
                 'data varchar(8192) NOT NULL',
                 'PRIMARY KEY (id)',
                 'UNIQUE KEY (tag)');
  }
  
  function new_record()
  {
    return new ShowOffTemplateRecord($this->table_name);
  }
}

class ShowOffOfferStore extends ShowOffStore
{
  function __construct()
  {
    parent::__construct('showoff_offer');
  }

  function get_create_columns()
  {
    return array('id bigint(20) unsigned NOT NULL auto_increment',
                 'tag varchar(40) NOT NULL',
                 'name varchar(100) NOT NULL',
                 'offer_url varchar(512)',
                 'data varchar(8192) NOT NULL',
                 'PRIMARY KEY (id)',
                 'UNIQUE KEY (tag)');
  }
  
  function new_record()
  {
    return new ShowOffOfferRecord($this->table_name);
  }
}

class ShowOffField
{
  private $name;
  private $label;
  private $type;
  protected $value;
  
  function __construct($name, $label, $type)
  {
    $this->name = $name;
    $this->label = $label;
    $this->type = $type;
  }
  
  function get_name()
  {
    return $this->name;
  }
  
  function get_label()
  {
    return $this->label;
  }
  
  function get_type()
  {
    return $this->type;
  }
  
  function raw_set($value)
  {
    $this->value = $value;
  }
  
  function raw_get()
  {
    return $this->value;
  }
  
  function ui_set($text)
  {
    $text = trim($text);
    # Trim always returns a non-null string. Convert empty string to null
    # so that JSON omits the field completely.
    if (strlen($text) == 0) {
      $text = null;
    }
    $this->value = $text;
  }
  
  function ui_get()
  {
    return $this->value;
  }
    
  function db_set($text)
  {
    $this->value = $text;
  }
  
  function db_get()
  {
    return $this->value;
  }
}

class ShowOffTemplateField extends ShowOffField
{
  function __construct($name, $label, $type)
  {
    parent::__construct($name, $label, $type);
  }
  
  function ui_set($text)
  {
    $text = trim($text);
    # Trim always returns a non-null string, so just check its length.
    if (strlen($text) == 0) {
      $this->value = null;
    } else {
      $this->value = array();
      $cursor = 0; // cursor marks the start of the current search
      while ($cursor < strlen($text)) {
        // Starting at cursor position, search for head and tail of the token.
        // If either one is not found, stop the search.
        $head = strpos($text, '[', $cursor);
        if ($head === false) {
          break;
        }
        $tail = strpos($text, ']', $head);
        if ($tail === false) {
          break;
        }
        // Write out a literal for anything before the token. 
        if ($head > $cursor) {
          $this->value[] = array(1, substr($text, $cursor, $head - $cursor));
        }
        // Build the operation from the token.
        $op = split('\|', substr($text, $head + 1, $tail - $head - 1));
        if (count($op) == 1) {
          // A single-argument token is a special case; create a field access operation.
          if (empty($op[0])) {
            $op = null; // If no name, then create literal text operation.
          } else {
            array_unshift($op, 2);
          }
        } else {
          // A multi-argument token becomes some other operation, depending on the first argument.  
          switch($op[0]) {
            case 'anchor':
              $op[0] = 3;
              break;
            default:
              $op = null; // default to literal text operation.
              break;
          }
        }
        if (is_null($op)) {
          // If no operation, then treat the token as literal text.
          $this->value[] = array(1, substr($text, $head, $tail - $head + 1));
        } else {
          // Add the operation to the value.
          $this->value[] = $op;
        }
        // Advance cursor to just after current token.
        $cursor = $tail + 1;
      }
      // Create a literal text for anything left after last token. 
      if ($cursor < strlen($text)) {
        $this->value[] = array(1, substr($text, $cursor));
      }
    }
  }
  
  function ui_get()
  {
    if (is_null($this->value)) {
      return null;
    }
    $text = '';
    foreach ($this->value as $statement) {
      $opcode = array_shift($statement);
      switch($opcode) {
        case 1: // literal
          $text .= $statement[0];
          break;
        case 2: // field
          $text .= '[' . join('|', $statement) . ']';
          break;
        case 3: // anchor text url target rel
          $text .= '[anchor|' . join('|', $statement) . ']';
          /* TODO 
          $text = $statement[1];
          $url = $statement[2];
          $target = $statement[3];
          $rel = $statement[4];
          
          $text .= "<a href='$url'";
          if (!empty($target)) {
            $text .= " target='$target'";
          }
          if (!empty($rel)) {
            $text .= " rel='$rel'";
          }
          $text .= ">$text</a>";
          */
          break;
      }
    }
    return $text;
  }
    
  function db_set($text)
  {
    $this->value = json_decode($text);
  }
  
  function db_get()
  {
    if (is_null($this->value) || count($this->value) == 0) {
      return null;
    }
    return showoff_encode($this->value, 8192, $this->name);
  }
}

class ShowOffDisplayField extends ShowOffField
{
  function __construct($name, $label)
  {
    parent::__construct($name, $label, 'display');
  }
  
  function ui_set($text)
  {
    // This is a display-only field, so it cannot be set.
  }

  // Do not override ui_get() because superclass does the right thing.
    
  function db_set($text)
  {
    // This is a display-only field, so it cannot be set.
  }
  
  function db_get()
  {
    // This is a display-only field, so it is not stored in the database.
    return null;
  }
}

abstract class ShowOffRecord
{
  protected $table_name;
  protected $id;
  protected $fields;

  function __construct($table_name)
  {
    $this->table_name = $table_name;
    $this->fields = array();
  }
  
  function add_field_object($field)
  {
    $this->fields[$field->get_name()] = $field;
  }
  
  function add_field($name, $label, $type)
  {
    $this->add_field_object(new ShowOffField($name, $label, $type));
  }
  
  function get_id()
  {
    return $this->id;
  }
  
  function is_empty()
  {
    return empty($this->id);
  }

  function get_fields()
  {
    return $this->fields;
  }
  
  function get_field($name)
  {
    return $this->fields{$name};
  }
  
  function set_field($name, $value)
  {
    $field = $this->fields{$name};
    if ($field) {
      $field->ui_set($value);
    }
  }

  function init_from_post()
  {
    $this->id = urldecode(@$_POST['id']);
    
    foreach ($this->fields as $name => $field) {
      // Use stripslashes to counteract wp_magic_quotes().
      $value = stripslashes($_POST[$name]);
      $field->ui_set($value);
    }
  }

  abstract function init_from_db($where);
  
  /**
   * Return an array of fields where key is field name, value is database text.
   */
  abstract function get_row();

  /**
   * Return the UI value of the given field
   */
  function get($field_name)
  {
    $field = $this->fields{$field_name};
    return $field->ui_get();
  }

  /**
   * Return an array where keys are field names and values are UI values.
   */
  function get_display()
  {
    $display = array();
    foreach ($this->fields as $name => $field) {
      $display[$name] = $field->ui_get();
    }
    return $display;
  }

  function insert()
  {
    global $wpdb;
    
    try {
      $row = $this->get_row();
      $wpdb->insert($this->table_name, $row);
      $this->id = $wpdb->insert_id;
      if ($this->id == false) {
	return $wpdb->last_error;
      } else {
	return null;
      }
    } catch (Exception $e) {
      return "ERROR: " . $e->getMessage();
    }
  }

  function update()
  {
    global $wpdb;

    try {
      $row = $this->get_row();
      $where = array('id' => $this->id);
      $n = $wpdb->update($this->table_name, $row, $where);

      if ($n > 0) {
	return null; // success
      } else if ($n === 0) {
	$query = "select count(*) from {$this->table_name} where id = {$this->id}";
	$count = $wpdb->get_var($query);
	if ($count == 0) {
	  // the record was not updated (i.e. where did not match)
	  return $this->insert();
	}
      } else {
	return $wpdb->last_error;
      }
    } catch (Exception $e) {
      return "ERROR: " . $e->getMessage();
    }

  }
}

class ShowOffTemplateRecord extends ShowOffRecord
{
  function __construct($table_name)
  {
    parent::__construct($table_name);
    $this->add_field('tag', 'Tag', 'text_short');
    $this->add_field('name', 'Name', 'text_long');
    $this->add_field_object(new ShowOffTemplateField('data', 'Template', 'text_area'));
  }

  function init_from_db($where)
  {
    global $wpdb;
    
    $row = $wpdb->get_row("select * from {$this->table_name} where {$where}", ARRAY_A);
    
    if (!is_null($row)) {
      $this->id = $row{'id'};

      foreach ($this->fields as $name => $field) {
        $field->db_set($row[$name]);
      }
    }
  }

  function get_row()
  {
    $row = array();
    foreach ($this->fields as $name => $field) {
      $row[$name] = $field->db_get();
    }
    return $row;
  }
  
  function render($offer)
  {
    $body = '';

    foreach ($this->fields{'data'}->raw_get() as $row) {
      $opcode = $row[0];
      switch($opcode) {
        case 1: # literal_text
          $body .= $row[1];
          break;
        case 2: # [field_name]
          $field_name = $row[1];
          $field = $offer->get_field($field_name);
          if(is_null($field)) {
            $body .= "[$field_name]";
          } else {
            $body .= $field->ui_get();
          }
          break;
        default:
          $body .= '(' . join(',', $row) . ')';
          break;
      }
    }
    return $body;
  }
}

class ShowOffOfferRecord extends ShowOffRecord
{
  function __construct($table_name)
  {
    parent::__construct($table_name);

    // A record always has a tag field, so we add it here.
    $this->add_field('tag', 'Tag', 'text_short');

    $path = dirname(__FILE__) . '/fields.txt';
    $lines = file($path, FILE_IGNORE_NEW_LINES);
    foreach ($lines as $line) {
      $line = trim($line);
      if (strlen($line) === 0 || strpos($line, '#') === 0) {
	continue;
      }
      $row = array_map('trim', explode(',', $line));
      $name = $row[0];
      $label = $row[1];
      $type = $row[2];

      switch ($type) {
      case 'text_short':
      case 'text_long':
      case 'text_area':
	$this->add_field($name, $label, $type);
	break;
      case 'display':
	$this->add_field_object(new ShowOffDisplayField($name, $label));
	break;
      default:
	throw new Exception("Unknown field type '$type' for field '$name'");
      }
    }
  }

  function init_from_db($where)
  {
    global $wpdb;
    
    $row = $wpdb->get_row("select id, data from {$this->table_name} where {$where}");

    if (!is_null($row)) {
      $this->id = $row->id;

      $data = json_decode($row->data);

      foreach ($data as $name => $value) {
        $this->set_field($name, $value);
      }
      
      // TODO url prefix should come from a setting
      $tag = $this->fields['tag']->raw_get();
      $url = get_option('siteurl') . '/goto?tag=' . $tag;
      $this->fields['goto_url']->raw_set($url);
   
      // Calculated fields are derived from these values
      $intro_purchase_rate = $this->fields['intro_purchase_rate']->raw_get();
      $intro_purchase_period = $this->fields['intro_purchase_period']->raw_get();
      $intro_transfer_rate = $this->fields['intro_transfer_rate']->raw_get();
      $intro_transfer_period = $this->fields['intro_transfer_period']->raw_get();
      
      if (strpos($intro_purchase_rate, 'N/A') === 0) {
        // no intro rate for purchases
        if ($intro_transfer_rate == 'N/A') {
          // no intro rates at all
          $intro_rate = 'N/A';
          $intro_period = 'N/A';
          $intro_for_transfer = 'No';
        } else {
          // have intro rate for transfers only
          $intro_rate = $intro_transfer_rate;
          $intro_period = $intro_transfer_period;
          $intro_for_transfer = 'Yes';
        }
      } else {
        // have intro rate for purchases
        if (strpos($intro_transfer_rate, 'N/A') === 0) {
          // have intro rate for purchases only
          $intro_rate = $intro_purchase_rate;
          $intro_period = $intro_purchase_period;
          $intro_for_transfer = 'No';
        } else {
          // have intro rate for both purchases and transfers
          if ($intro_purchase_rate == $intro_transfer_rate) {
            $intro_rate = $intro_transfer_rate;
          } else {
            $intro_rate = $intro_purchase_rate . ' for purchases<br/>' .
                          $intro_transfer_rate . ' for transfers';
          }
          if ($intro_purchase_period == $intro_transfer_period) {
            $intro_period = $intro_transfer_period;
          } else {
            $intro_period = $intro_purchase_period . '<br/>' .
                            $intro_transfer_period;
          }
          $intro_for_transfer = 'Yes';
        }
      }
      
      $this->fields['intro_rate']->raw_set($intro_rate);
      $this->fields['intro_period']->raw_set($intro_period);
      $this->fields['intro_for_transfer']->raw_set($intro_for_transfer);
    }
  }
  
  function get_row()
  {
    $row = array();
    $row['tag'] = $this->fields['tag']->db_get();
    $row['name'] = $this->fields['name']->db_get();
    $row['offer_url'] = $this->fields['offer_url']->db_get();
    
    $data = array();
    foreach ($this->fields as $name => $field) {
      $value = $field->db_get();
      if (!is_null($value)) {
        $data[$name] = $value;
      }
    }
    $row['data'] = showoff_encode($data, 8192, $row['tag']);

    return $row;
  }
}

?>
