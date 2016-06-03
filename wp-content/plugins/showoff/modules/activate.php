<?php

function showoff_activate()
{
  $store = new ShowOffOfferStore();
  $store->activate();

  $store = new ShowOffTemplateStore();
  $store->activate();

  showoff_activate_defaults();
}

function showoff_activate_defaults()
{
  $options = get_option('showoff_settings');
  if(!is_array($options) || $options['restore_defaults']) {
    $def = array('restore_defaults' => true);
    update_option('showoff_settings', $def);
  }
}

register_activation_hook(SHOWOFF_BASE_FILE, 'showoff_activate');

?>
