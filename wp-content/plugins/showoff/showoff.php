<?php
/*
Plugin Name: showoff
Description: ShowOff is an easy and flexible way to show marketing offers on a WordPress page.
Version: 2.0
Author: Dave Meppelink

Copyright 2010-2012 David J. Meppelink. All rights reserved.
*/

if (!defined('SHOWOFF_BASE_FILE')) {
  define('SHOWOFF_BASE_FILE', 'showoff/showoff.php');

  require_once 'modules/writer.php';
  require_once 'modules/model.php';
  require_once 'modules/view.php';
  require_once 'modules/controller.php';
  require_once 'modules/calc.php';

  // wire the controllers into WP
  add_action('admin_menu', 'showoff_admin_menu_action');

  function showoff_admin_menu_action()
  {
    $c = new ShowOffOfferController();
    $c->register();
    $c = new ShowOffTemplateController();
    $c->register();
    $c = new ShowOffCalcController();
    $c->register();
  }

  require_once 'modules/activate.php';

  require_once 'modules/shortcode.php';
}

?>
