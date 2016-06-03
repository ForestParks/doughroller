<?php

//------------------------------------------------------------------------------

define('DUMP_XML', false);
define('DUMP_RECORD', false);
define('STORE', true);

function get_basedir()
{
  global $argv;
  if (count($argv) > 1) {
    $basedir = $argv[1];
  } else {
    $basedir = realpath(dirname(__FILE__) . '/../../../..');
  }
  if (file_exists("$basedir/wp-config.php")) {
    return $basedir;
  }
  print "Unable to find wp-config.php\n";
  exit(1);
}

define('BASEDIR', get_basedir());

$config = file(BASEDIR . '/wp-config.php');

foreach ($config as $line) {
  if (strpos($line, 'DB_') !== false ||
      strpos($line, 'table_prefix') !== false) {
    eval($line);
  }
}

define('WP_DEBUG', false);

function is_multisite() { return false; }

function apply_filters($tag, $value)
{
  return $value;
}
/**
 * Set the mbstring internal encoding to a binary safe encoding when func_overload
 * is enabled.
 *
 * When mbstring.func_overload is in use for multi-byte encodings, the results from
 * strlen() and similar functions respect the utf8 characters, causing binary data
 * to return incorrect lengths.
 *
 * This function overrides the mbstring encoding to a binary-safe encoding, and
 * resets it to the users expected encoding afterwards through the
 * `reset_mbstring_encoding` function.
 *
 * It is safe to recursively call this function, however each
 * `mbstring_binary_safe_encoding()` call must be followed up with an equal number
 * of `reset_mbstring_encoding()` calls.
 *
 * @since 3.7.0
 *
 * @see reset_mbstring_encoding()
 *
 * @param bool $reset Optional. Whether to reset the encoding back to a previously-set encoding.
 *                    Default false.
 */
function mbstring_binary_safe_encoding( $reset = false ) {
  static $encodings = array();
  static $overloaded = null;

  if ( is_null( $overloaded ) )
    $overloaded = function_exists( 'mb_internal_encoding' ) && ( ini_get( 'mbstring.func_overload' ) & 2 );

  if ( false === $overloaded )
    return;

  if ( ! $reset ) {
    $encoding = mb_internal_encoding();
    array_push( $encodings, $encoding );
    mb_internal_encoding( 'ISO-8859-1' );
  }

  if ( $reset && $encodings ) {
    $encoding = array_pop( $encodings );
    mb_internal_encoding( $encoding );
  }
}

/**
 * Reset the mbstring internal encoding to a users previously set encoding.
 *
 * @see mbstring_binary_safe_encoding()
 *
 * @since 3.7.0
 */
function reset_mbstring_encoding() {
  mbstring_binary_safe_encoding( true );
}
require_once BASEDIR . '/wp-includes/class-wp-error.php';
require_once BASEDIR . '/wp-includes/wp-db.php';
if (!isset($wpdb)) {
  $wpdb = new wpdb( DB_USER, DB_PASSWORD, DB_NAME, DB_HOST );
}
$wpdb->set_prefix($table_prefix);

function get_option($name)
{
  if ($name == 'siteurl') {
    return SITE_URL;
  } else {
    throw new Exception("option undefined: " . $name);
  }
}

$config = parse_ini_file('config.props');
if ($config) {
  print "Configuration:\n";
  foreach ($config as $name => $value) {
    print "  $name = $value\n";
    define($name, $value);
  }
} else {
  throw new Exception("Failed to load configuration file");
}

require_once dirname(__FILE__) . '/../modules/model.php';

//------------------------------------------------------------------------------

function load_cardsynergy()
{
  $startTime = time();

  print "CardSynergy: starting load for WEBSITE_ID " . WEBSITE_ID . "\n";

  $store = new ShowOffOfferStore();
  $insert_count = 0;
  $root = simplexml_load_file(FEED_URL);
  foreach ($root->children() as $child) {

    if ($child->getName() == 'products') {
      foreach ($child->children() as $product) {
        $fields = $product->children();
        $id = $fields->id;
        if (DUMP_XML) {
          print "==================== CardSynergy product $id\n";
          print str_replace("><", ">\n<", $product->asXML()) . "\n";
        }
    
        $tag = 'ncs' . $id;

        $record = $store->new_record_from_tag($tag);

        $record->set_field('tag', $tag);
        $record->set_field('name', $fields->name);
        $record->set_field('bullets', $fields->description);
        $record->set_field('short_description', $fields->shortDescription);
        $record->set_field('issuer', $fields->issuer);
        $record->set_field('intro_purchase_rate', $fields->introApr);
        $record->set_field('intro_purchase_period', $fields->introAprPeriod);
        $record->set_field('transfers', $fields->balanceTransfers);
        $record->set_field('transfers_value', $fields->q_balanceTransfers);
        $record->set_field('intro_transfer_rate', $fields->balanceTransferIntroApr);
        $record->set_field('intro_transfer_period', $fields->balanceTransferIntroAprPeriod);
        $record->set_field('purchase_rate', $fields->regularApr);
        $record->set_field('transfer_rate', $fields->balanceTransferGoToRate);
        $record->set_field('transfer_fee', $fields->balanceTransferFee);
        $record->set_field('exchange_fee', $fields->foreignCurrencyExchangeFee);
        $record->set_field('advance_rate', $fields->cashAdvanceGoToRate);
        $record->set_field('advance_fee', $fields->cashAdvanceFee);
        $record->set_field('penalty_rate', $fields->penaltyGoToRate);
        $record->set_field('annual_fee', $fields->annualFee);
        $record->set_field('transaction_fee_pin', $fields->transaction_fee_pin);
        $record->set_field('transaction_fee_sign', $fields->transaction_fee_signature);
        $record->set_field('load_fee', $fields->load_fee);
        $record->set_field('atm_fee', $fields->atm_fee);
        $record->set_field('activation_fee', $fields->activation_fee);
        $record->set_field('late_fee', $fields->lateFee);
        $record->set_field('credit_rating', $fields->creditNeeded);
        $record->set_field('image_url', IMAGE_PREFIX . $fields->imagePath);
        $record->set_field('image_url_large', IMAGE_PREFIX_LARGE . $fields->imagePath);
        $record->set_field('offer_url', $fields->url . "&sid=" . WEBSITE_ID);

        if (DUMP_RECORD) {
          print_r($record);
        }
        //echo '<pre>'.print_r($record,true).'</pre>';
        if (STORE) {
          // Store the record
          if ($record->is_empty()) {
            $err = $record->insert();
            if ($err) {
              print($err);
            } else {
              $insert_count++;
            }
          } else {
            $err = $record->update();
            if ($err) {
              print($err);
            } else {
              $update_count++;
            }
          }
        }
      }
    }
  }

  $elapsed = time() - $startTime;
  print "CardSynergy: Inserted $insert_count, updated $update_count products in $elapsed seconds.\n";
}

//------------------------------------------------------------------------------

function load_creditkarma()
{
  $startTime = time();

  print "Credit Karma: starting load for CCOiq at " . date('j-M-Y G:i:s') . "\n";

  $store = new ShowOffOfferStore();
  $insert_count = 0;
  $update_count = 0;

  $root = simplexml_load_file(FEED_URL2);
  foreach ($root->children() as $child) {
    if ($child->getName() == 'offers') {
      foreach ($child->children() as $product) {

        $fields = $product->children();
        $id = $fields->id;
        if (DUMP_XML) {
          print "==================== Credit Karma product $id\n";
          print str_replace("><", ">\n<", $product->asXML()) . "\n";
        }
    
        $tag = $id;

        $record = $store->new_record_from_tag($tag);

        $record->set_field('tag', $tag);
        $record->set_field('title' , $fields->title);
        $record->set_field('name', $fields->name);
        $record->set_field('headline', $fields->headline);
        $record->set_field('offer_url', $fields->url);
        $record->set_field('credit_rating', $fields->creditNeeded);
        $record->set_field('issuer' , $fields->issuer);
        $record->set_field('purchase_rate', $fields->apr->display);
        $record->set_field('short_description', $fields->summary->short);
        $record->set_field('bullets', $fields->summary->long);
        $record->set_field('networks_category' , $fields->categories->networks->networksCategory);
        $record->set_field('issuers_category' , $fields->issuers->issuersCategory);
        $record->set_field('user_types_category' , $fields->userTypes->userTypesCategory);
        $record->set_field('credit_category' , $fields->credit->creditCategory);
        $record->set_field('custom_category' , $fields->custom->customCategory);
        $record->set_field('all_category' , $fields->all->allCategory);
        $record->set_field('lowest_credit_score' , $fields->approval->lowest);
        $record->set_field('average_credit_score' , $fields->approval->average);    
        $record->set_field('intro_purchase_rate', $fields->intro->purchases->apr->display);
        $record->set_field('intro_purchase_period', $fields->intro->purchases->period->display);
        $record->set_field('intro_transfer_rate', $fields->intro->balanceTransfer->apr->display);
        $record->set_field('intro_transfer_period', $fields->intro->balanceTransfer->period->display);
        $record->set_field('annual_fee', $fields->fees->annual->display);
		$record->set_field('monthly_fee' , $fields->fees->monthly->display);
        $record->set_field('late_fee', $fields->fees->late->display);		        
        $record->set_field('transfer_fee', $fields->fees->balanceTransfer->display);
        $record->set_field('advance_fee', $fields->fees->cashAdvance->display);
        $record->set_field('exchange_fee', $fields->fees->foreignCurrency->display);
        $record->set_field('activation_fee', $fields->fees->cardActivation->display);
        $record->set_field('advance_rate', $fields->cashAdvance->apr->display);
        $record->set_field('balance_transfer_apr' , $fields->balanceTransfer->apr->display);
        $record->set_field('rewards_type' , $fields->rewards->type);
        $record->set_field('rewards_per_dollar_base_value' , $fields->rewards->perDollarBase->value);
        $record->set_field('rewards_per_dollar_base_display' , $fields->rewards->perDollarBase->display);
        $record->set_field('rewards_per_dollar_max_value' , $fields->rewards->perDollarMax->value);
        $record->set_field('rewards_per_dollar_max_display' , $fields->rewards->perDollarMax->display);
        $record->set_field('points_bonus_value' , $fields->rewards->pointsBonus->value);
        $record->set_field('points_bonus_display' , $fields->rewards->pointsBonus->display);
        $record->set_field('per_dollar_gas' , $fields->rewards->perDollarGas);
        $record->set_field('per_dollar_groceries' , $fields->rewards->perDollarGroceries);
        $record->set_field('per_dollar_restaurants' , $fields->rewards->perDollarRestaurants);
        $record->set_field('per_dollar_airfare' , $fields->rewards->perDollarAirfare);
        $record->set_field('minimum_deposit' , $fields->minimumDeposit);
        $record->set_field('image_url', $fields->cardArt->small);
        $record->set_field('image_url_large', $fields->cardArt->big);
        $record->set_field('card_art_vertical' , $fields->cardArt->vertical);
        $record->set_field('chart_data' , $fields->chartData);
        $record->set_field('reviews_rating' , $fields->reviews->rating);
		$record->set_field('most_popular_comment' , $fields->reviews->mostPopularComment->commentText);
		$record->set_field('comment_screenname' , $fields->reviews->mostPopularComment->screenName);
		
        if (DUMP_RECORD) {
          print_r($record);
        }

        if (STORE) {
          // Store the record
          if ($record->is_empty()) {
            $err = $record->insert();
            if ($err) {
              print($err);
            } else {
              $insert_count++;
            }
          } else {
            $err = $record->update();
            if ($err) {
              print($err);
            } else {
              print("Updated $id\n");
              $update_count++;
            }
          }
        }
      }
    }
  }

  $elapsed = time() - $startTime;
  print "Credit Karma: Inserted $insert_count, updated $update_count products in $elapsed seconds.\n";
}

//------------------------------------------------------------------------------
// main

if (STORE) {
  // Connect to the database
  mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
  mysql_select_db(DB_NAME);
}

load_cardsynergy();
load_creditkarma();

if (STORE) {
  mysql_close();
}

//------------------------------------------------------------------------------

?>
