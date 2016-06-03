<?php
/*
Plugin Name: cc_box
Description: Credit Card Box
Version: 1.4
Author: Dave Meppelink

Copyright 2010 David J. Meppelink. All rights reserved.
*/

if (!isset($ccBoxPlugin)) {
  class CCBoxPlugin
  {
    private $baseURL;

    function __construct()
    {
    }

    function inject($content) 
    {
      $prefix = 'cc_box(';
      $suffix = ')';

      $tail = 0;
      while(true) {
	$head = strpos($content, $prefix, $tail);
	if ($head === false) {
	  break;
	} else {
	  $tail = strpos($content, $suffix, $head);
	  $arg_head = $head + strlen($prefix);
	  $arg_length = $tail - $arg_head;
	  $arg_string = substr($content, $arg_head, $arg_length);
	  $arg_list = explode(',', $arg_string);
	  $product_id = trim($arg_list[0]);
	  $alternate = (boolean) trim($arg_list[1]);
	  $code = build($product_id, $alternate);
	  if (!is_null($code)) {
	    $content = substr_replace($content, $code, $head, $tail - $head + 1);
	  }
	}
      }

      return $content;
    }
  }

  function build($productID, $alternate)
  {
    $result = mysql_query("select * from cc_box where product_id = '$productID'");
    if (!$result) {
      return;
    }

    $record = mysql_fetch_assoc($result);

    if(!$record) {
      return;
    }

    $name = $record['name'];
    $bullets = $record['bullets'];

    if ($record['intro_purchase_rate'] == 'N/A') {
      // no intro rate for purchases
      if ($record['intro_transfer_rate'] == 'N/A') {
	// no intro rates at all
	$introAPR = 'N/A';
	$introPeriod = 'N/A';
	$introForTransfer = 'No';
      } else {
	// have intro rate for transfers only
	$introAPR = $record['intro_transfer_rate'];
	$introPeriod = $record['intro_transfer_period'];
	$introForTransfer = 'Yes';
      }
    } else {
      // have intro rate for purchases
      if ($record['intro_transfer_rate'] == 'N/A') {
	// have intro rate for purchases only
	$introAPR = $record['intro_purchase_rate'];
	$introPeriod = $record['intro_purchase_period'];
	$introForTransfer = 'No';
      } else {
	// have intro rate for both purchases and transfers
	if ($record['intro_purchase_rate'] == $record['intro_transfer_rate']) {
	  // same rate for both, so just use it
	  $introAPR = $record['intro_transfer_rate'];
	  $introPeriod = $record['intro_transfer_period'];
	  $introForTransfer = 'Yes';
	} else {
	  // different rates, just show the transfer rate because
	  // that's the most interesting.
	  $introAPR = $record['intro_transfer_rate'] . ' for transfers';
	  $introPeriod = $record['intro_transfer_period'] . ' for transfers';
	  $introForTransfer = 'Yes';
	}
      }
    }

    //
    // Hard-coded overrides (January 2011)
    //
    if ($productID == 'ncs445') {
      $introForTransfer = 'Yes';
    }
    if ($productID == 'ncs1581') {
      $introForTransfer = 'Yes';
    }
    if ($productID == 'ncs1500') {
      $introForTransfer = 'Yes';
    }
    if ($productID == 'ncs442') {
      $introForTransfer = 'Yes';
    }

    $siteurl = get_option('siteurl');
    $imgurl = $siteurl . '/wp-content/uploads/2008/09/apply-now-secure.gif';

    $purchaseAPR = $record['purchase_rate'];
    $annualFee = $record['annual_fee'];
    $creditRating = $record['credit_rating'];
    $imageURL = $record['image_url'];
    if ($alternate && !is_null($record['alternate_url'])) {
      // Caller wants the alternate URL,
      // and the record has an alternate URL to use,
      // so use the alternate URL
      $applyURL = $record['alternate_url'];
    } else {
      // The caller wants the primary URL,
      // or the caller wants the alternate URL but the record doesn't have one,
      // so use the primary URL.
      global $post;
      $applyURL = 'goto:' . $record['alias'];
    }
    if (is_null($record['article_path'])) {
      // The record does not have a relative path to the article,
      // so we can't build an article URL.
      $articleURL = null;
    } else {
      // The record has a relative path to the article, 
      // so use it to build the article URL.
      $articleURL = $siteurl . '/credit-cards/' . $record['article_path'];
    }

    $content = "<table class='cctable' align='center' cellpadding='0' cellspacing='0' style='border-width:3px;border-style:solid;border-color:#cccccc;'>\n";

    // title row
    $content .= "<tr><th colspan='2'>";
    if (is_null($articleURL)) {
      $content .= $name;
    } else {
      $content .= "<a href='$articleURL'>$name</a>";
    }
    $content .= "</th></tr>\n";

    // icon & bullet row
    $nameattr = htmlspecialchars($name);
    $content .=
      "<tr>" .
      "<td width='15%' class='ccimage'><a href='$applyURL' target='_blank' rel='nofollow'><img border='0' alt='$nameattr' src='$imageURL' width='99'></a><br/><a href='$applyURL' target='_blank' rel='nofollow'><img src='$imgurl' alt='Apply Now...'></a></td>" .
      "<td width='85%' class='ccbullets'>$bullets</td>" .
      "</tr>\n";

    // details row, which contains a table
    $content .=
      "<tr><td colspan='2'>" .
      "<table align='center' class='rate-rc' cellpadding='0' cellspacing='1'>\n" .
      "<tr>\n" .
      "<td class='rate-top'>Intro APR</td>\n" .
      "<td class='rate-top'>Intro APR<br/>Period</td>\n" .
      "<td class='rate-top'>Regular<br/>APR</td>\n" .
      "<td class='rate-top'>Annual<br/>Fee</td>\n" .
      "<td class='rate-top'>Balance<br/>Transfer</td>\n" .
      "<td class='rate-top'>Credit<br/>Needed</td>\n" .
      "</tr>\n" .
      "<tr>\n" .
      "<td class='rates-bottom'>$introAPR</td>\n" .
      "<td class='rates-bottom'>$introPeriod</td>\n" .
      "<td class='rates-bottom'>$purchaseAPR</td>\n" .
      "<td class='rates-bottom'>$annualFee</td>\n" .
      "<td class='rates-bottom'>$introForTransfer</td>\n" .
      "<td class='rates-bottom'>$creditRating</td>\n" .
      "</tr>\n" .
      "</table>" .
      "</td></tr>\n" .
      "</table>\n";

    return $content;
  }

  // Create the singleton instance of Ccboxplugin.
  $ccBoxPlugin = new CCBoxPlugin();

  // Register the singleton filters and actions
  add_filter('the_content', array(&$ccBoxPlugin, 'inject'), 9);
 }
?>
