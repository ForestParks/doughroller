<?php

function showoff_shortcode($atts, $content = null, $code = '')
{
  $args = shortcode_atts(array('offer' => null,
			                   'template' => null),
			             $atts);
			             
  $offer_id = $args{'offer'}; 
  $template_id = $args{'template'};

  # Check the parameters and return suggestion if one or both are missing.
  $MISSING = "<span style='font-weight: bold'>TAG?</span>";
  if (is_null($offer_id)) {
  	if (is_null($template_id)) {
  		return "[showoff template=$MISSING offer=$MISSING]";
  	} else {
  		return "[showoff template=$template_id offer=$MISSING]";
  	}
  } else {
  	if (is_null($template_id)) {
  		return "[showoff template=$MISSING offer=$offer_id]";
  	}
  }
  
  # look up the records
  $offer_store = new ShowOffOfferStore();
  $offer_record = $offer_store->new_record_from_tag($offer_id);

  $template_store = new ShowOffTemplateStore();
  $template_record = $template_store->new_record_from_tag($template_id);
  
  # Report an error if either record is missing
  if ($offer_record->is_empty()) {
  	$BAD_OFFER = "<span style='font-weight: bold'>$offer_id</span>";
  	if ($template_record->is_empty()) {
  		$BAD_TEMPLATE = "<span style='font-weight: bold'>$template_id</span>";
  		return "[showoff template=$BAD_TEMPLATE offer=$BAD_OFFER]";
  	} else {
  		return "[showoff template=$template_id offer=$BAD_OFFER]";
  	}
  } else {
  	if ($template_record->is_empty()) {
  		$BAD_TEMPLATE = "<span style='font-weight: bold'>$template_id</span>";
  		return "[showoff template=$BAD_TEMPLATE offer=$offer_id]";
  	}
  }

  return $template_record->render($offer_record);
}

add_shortcode( 'showoff', 'showoff_shortcode' );

?>
