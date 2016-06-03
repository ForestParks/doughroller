<?php

abstract class ShowOffController
{
  private $store;

  function __construct($store)
  {
    $this->store = $store;
  }

  function url($path)
  {
    return plugins_url($path, SHOWOFF_BASE_FILE);
  }

  function enhance_page($page)
  {
    add_action('admin_print_scripts-' . $page, array($this, 'scripts'));
    add_action('admin_print_styles-' . $page, array($this, 'styles'));    
  }

  function scripts()
  {
    wp_enqueue_script('showoff_script', // handle
                      $this->url('js/showoff.js'), // source
                      null, // dependencies
                      1.0, // version
                      false); // in footer
  }

  function styles()
  {
    wp_enqueue_style('showoff_style', // handle
                     $this->url('css/showoff.css'), // source
                     null, // dependencies
                     1.0, // version
                     'all'); // media
  }

  abstract function register();

  function render()
  {
    $method = $_SERVER['REQUEST_METHOD'];
    if ($method == 'POST') {
      switch($_POST['action']) {
      case 'Add New':
        $record = $this->store->new_record();
        $this->edit_page()->render($record);
        break;
      case 'Save':
        $record = $this->store->new_record_from_post();
        if (empty($_POST['id'])) {
          $msg = $record->insert();
        } else {
          $msg = $record->update();
        }
        $this->render_list($msg);
        break;
      case 'Delete':
        $id = $_POST['id'];
        $msg = $this->store->delete($id);
        $this->render_list($msg);
        break;
      default:
        $this->render_list($msg);
        break;
      }
    } else {
      $id = $_GET['edit'];
      if (empty($id)) {
        $this->render_list(null);
      } else {
        $record = $this->store->new_record_from_id($id);
        $this->edit_page()->render($record);
      }
    }
  }
  
  private function render_list($msg)
  {
    $rows = $this->store->get_list_rows();
    $this->list_page()->render($msg, $rows);
  }

  abstract function edit_page();

  abstract function list_page();
}

class ShowOffOfferController extends ShowOffController
{
  function __construct()
  {
    parent::__construct(new ShowOffOfferStore());
  }

  function register()
  {
    add_menu_page('ShowOff Offers', // page_title
                  'ShowOff', // menu_title
                  'edit_posts', // capability
                  'showoff_offers', // menu_slug
                  array($this, 'render'), // function
                  null, // icon_url
                  90); // position

    $page = add_submenu_page('showoff_offers', // parent_slug
                             'ShowOff Offers', // page_title
                             'Offers', // menu_title
                             'edit_posts', // capability
                             'showoff_offers', // menu_slug
                             array($this, 'render')); //function

    $this->enhance_page($page);
  }

  function edit_page()
  {
    return new ShowOffEditPage('Edit ShowOff Offer');
  }

  function list_page()
  {
    return new ShowOffListPage('ShowOff Offers', 'showoff_offer_list');
  }
}

class ShowOffTemplateController extends ShowOffController
{
  function __construct()
  {
    parent::__construct(new ShowOffTemplateStore());
  }

  function register()
  {
    $page = add_submenu_page('showoff_offers', // parent_slug
                             $this->page_title_list, // page_title
                             'Templates', // menu_title
                             'edit_posts', // capability
                             'showoff_templates', // menu_slug
                             array($this, 'render')); //function

    $this->enhance_page($page);
  }

  function edit_page()
  {
    return new ShowOffEditPage('Edit ShowOff Template');
  }

  function list_page()
  {
    return new ShowOffListPage('ShowOff Templates', 'showoff_template_list');
  }
}

?>
