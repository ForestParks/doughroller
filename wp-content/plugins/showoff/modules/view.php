<?php

class ShowOffPage
{
  function href($name, $value)
  {
    return '?' . http_build_query(array('page' => $_GET['page'],
                                        $name => $value));
  }
  
  function message($p, $text)
  {
    $p->begin('div', array('id' => 'message', 'class' => 'updated')); {
      $p->para($text);
    } $p->end();
  }
}

class ShowOffListPage extends ShowOffPage
{
  private $page_title;
  private $screen_handle;

  function __construct($page_title, $screen_handle)
  {
    $this->page_title = $page_title;
    $this->screen_handle = $screen_handle;
  }

  function render($msg, $records)
  {
    register_column_headers($this->screen_handle, array('tag' => 'Tag', 'name' => 'Name'));

    $p = new ShowOffPageWriter();

    $p->begin('div', array('class' => 'wrap')); {
      $p->begin('div', array('class' => 'icon32', 'id' => 'icon-tools')); {
        $p->elem('br');
      } $p->end();
      $p->elem('h2'); {
        $p->text($this->page_title);
      } $p->end();
      if (!empty($msg)) {
        $this->message($p, $msg);
      }
      $p->begin('table', array('class' => 'widefat page fixed', 
                               'cellspacing' => '0',
                               'style' => 'width: auto')); {
        $p->begin('thead'); {
          $p->begin('tr'); {
            print_column_headers($this->screen_handle);
          } $p->end();
        } $p->end();
        $p->begin('tfoot'); {
          $p->begin('tr'); {
            print_column_headers($this->screen_handle, false);
          } $p->end();
        } $p->end();
        $p->begin('tbody'); {
          foreach($records as $record) {
            $p->begin('tr'); {
              $p->begin('td'); {
                $p->begin('a', array('href' => $this->href('edit', $record->id))); {
                  $p->text($record->tag);
                } $p->end();
              } $p->end();
              $p->begin('td'); {
                $p->text($record->name);
              } $p->end();
            } $p->end();
          }
        } $p->end();
      } $p->end();
      $p->begin('form', array('method' => 'post', 'action' => '')); {
        $p->begin('input', array('type'=> 'submit',
                                 'class' => 'button-secondary',
                                 'name' => 'action',
                                 'value' => 'Add New'));
      } $p->end();
    } $p->end();
  }
}

class ShowOffEditPage extends ShowOffPage
{
  private $page_title;

  function __construct($page_title)
  {
    $this->page_title = $page_title;
  }
  
  private function render_text_input($p, $id, $name, $label, $value, $size)
  {
    $p->begin('li'); {
      $p->begin('label', array('for' => $id)); {
        $p->text($label);
      } $p->end();
      $p->elem('input', array('type' => 'text',
                              'id' => $id,
                              'name' => $name,
                              'size' => $size,
                              'value' => $value));
      $p->text(' ');
      $p->text($name);
    } $p->end();
  }
  
  private function render_textarea_input($p, $id, $name, $label, $value)
  {
    $p->begin('li'); {
      $p->begin('label', array('for' => $id)); {
        $p->text($label);
      } $p->end();
      $p->begin('textarea', array('id' => $id,
                                  'name' => $name)); {
        $p->text($value);
      } $p->end();
      $p->text(' ');
      $p->text($name);
    } $p->end();
  }
  
  private function render_display($p, $id, $name, $label, $value)
  {
    $p->begin('li'); {
      $p->begin('label', array('for' => $id)); {
        $p->text($label);
      } $p->end();
      $p->begin('span', array('id' => $id, 'class' => 'display')); {
        $p->text($value);
      } $p->end();
      $p->text(' ');
      $p->text($name);
    } $p->end();
  }
  
  function render($record)
  {
    $p = new ShowOffPageWriter();

    $p->begin('div', array('class' => 'wrap')); {
      $p->begin('div', array('class' => 'icon32', 'id' => 'icon-tools')); {
        $p->elem('br');
      } $p->end();
      $p->begin('h2'); {
        $p->text($this->page_title);
      } $p->end();
      $p->begin('form', array('class' => 'showoff_form',
                              'method' => 'post',
                              'action' => $this->href(null, null))); {
        $p->begin('ol'); {
          foreach ($record->get_fields() as $field) {
            $name = $field->get_name();
            $id = 'form_' . $name;
            $label = str_replace(' ', '&nbsp;', $field->get_label());
            $value = $field->ui_get();
            switch($field->get_type()) {
              case 'text_short':
                $this->render_text_input($p, $id, $name, $label, $value, 20);
                break;
              case 'text_long':
                $this->render_text_input($p, $id, $name, $label, $value, 70);
                break;
              case 'text_area':
                $this->render_textarea_input($p, $id, $name, $label, $value);
                break;
              case 'display':
                $this->render_display($p, $id, $name, $label, $value);
                break;
              case 'hidden':
                // do nothing; this is a hidden field
                break;
              default:
                $p->text('Unknown type: ' + $field->get_type());
            }
          }
        } $p->end();
        $p->begin('fieldset', array('class' => 'submit')); {
          $p->elem('input', array('type' => 'submit',
                                  'class' => 'button-primary',
                                  'name' => 'action',
                                  'value' => 'Save'));
          $p->elem('input', array('type' => 'submit',
                                  'class' => 'button-secondary',
                                  'name' => 'action',
                                  'value' => 'Cancel'));
          $id = $record->get_id();
          if (!empty($id)) {
            $p->elem('input', array('type' => 'hidden',
                                    'name' => 'id',
                                    'value' => $id));
            $p->elem('input', array('type' => 'submit',
                                    'class' => 'button-primary button-right',
                                    'name' => 'action',
                                    'value' => 'Delete'));
          }
        } $p->end();
      } $p->end();
    } $p->end();
  }
}

?>
