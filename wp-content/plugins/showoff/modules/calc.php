<?php

//
// MODEL
//

//
// VIEW
//

abstract class ShowOffCalcRow
{
  const CONSTANT = 'constant';
  const COUNT = 'count';
  const AMOUNT = 'amount';
  const TOTAL = 'total';

  static $TYPES = array(ShowOffCalcRow::CONSTANT => 'Constant',
			ShowOffCalcRow::COUNT => 'Count',
			ShowOffCalcRow::AMOUNT => 'Amount',
			ShowOffCalcRow::TOTAL => 'Total');

  protected $row_type;
  protected $tag;
  protected $desc;

  function __construct($row_type, $tag, $desc)
  {
    $this->row_type = $row_type;
    $this->tag = $tag;
    $this->desc = $desc;
  }

  function render_decl($p)
  {
    $p->begin('tr'); {

      $p->begin('td'); {
	$p->elem('input', array('type' => 'text',
				'value' => $this->tag));
      } $p->end();

      $p->begin('td'); {
	$p->elem('input', array('type' => 'text',
				'value' => $this->desc));
      } $p->end();

      $p->begin('td'); {
	$this->render_type_select($p);
	$this->render_type_fields($p);
      } $p->end();

      $p->begin('td'); {
	$p->elem('input', array('type' => 'button',
				'value' => 'up'));
	$p->elem('input', array('type' => 'button',
				'value' => 'dn'));
	$p->elem('input', array('type' => 'button',
				'value' => 'del'));
      } $p->end();

    } $p->end();
  }

  function render_type_select($p)
  {
    $p->begin('select'); {
      foreach (ShowOffCalcRow::$TYPES as $row_type => $desc) {
	$attr = array('value' => $row_type);
	if ($row_type == $this->row_type) {
	  $attr['selected'] = 1;
	}
	$p->begin('option', $attr); {
	  $p->text($desc);
	} $p->end();
      }
    } $p->end();
  }

  abstract function render_type_fields($p);

  function render($p)
  {
    $p->begin('tr'); {

      $p->begin('td'); {
	$this->render_description($p);
      } $p->end();

      $p->begin('td'); {
	$this->render_input($p);
      } $p->end();

      $p->wrap('td', '999'); // TODO

    } $p->end();
  }

  function render_description($p)
  {
    $p->text($this->desc);
  }

  abstract function render_input($p);
}

class ShowOffCalcRowConst extends ShowOffCalcRow
{
  private $value;

  function __construct($tag, $desc, $value)
  {
    parent::__construct(ShowOffCalcRow::CONSTANT, $tag, $desc);
    $this->value = $value;
  }

  function render_type_fields($p)
  {
    $p->elem('input', array('type' => 'text',
			    'value' => $this->value));
  }

  function render_input($p)
  {
    $p->text($this->value);
  }
}

class ShowOffCalcRowCount extends ShowOffCalcRow
{
  private $min;
  private $max;
  private $def;

  function __construct($tag, $desc, $min, $max, $def)
  {
    parent::__construct(ShowOffCalcRow::COUNT, $tag, $desc);
    $this->min = $min;
    $this->max = $max;
    $this->def = $def;
  }

  function render_type_fields($p)
  {
    $p->text('minimum');
    $this->render_input($p, $this->min);
    $p->text('maximum');
    $this->render_input($p, $this->max);
    $p->text('default');
    $this->render_input($p, $this->def);
  }

  function render_input($p, $selected = null)
  {
    if (is_null($selected)) {
      $selected = $this->def;
    }
    $p->begin('select'); {
      for($n = $this->min; $n <= $this->max; $n++) {
	$attr = array('value' => $n);
	if ($n == $selected) {
	  $attr['selected'] = 1;
	}
	$p->begin('option', $attr); {
	  $p->text($n);
	} $p->end();
      }
    } $p->end();
  }
}

class ShowOffCalcRowAmount extends ShowOffCalcRow
{
  function __construct($tag, $desc)
  {
    parent::__construct(ShowOffCalcRow::AMOUNT, $tag, $desc);
  }

  function render_type_fields($p)
  {
  }

  function render_input($p)
  {
    $p->elem('input', array('type' => 'text'));
  }
}

class ShowOffCalcRowTotal extends ShowOffCalcRow
{
  function __construct()
  {
    parent::__construct(ShowOffCalcRow::TOTAL, 'total', 'Total');
  }

  function render_type_fields($p)
  {
  }

  function render_description($p)
  {
    $p->wrap('b', $this->desc);
  }

  function render_input($p)
  {
    $p->text('&nbsp;');
  }
}

//-------------------------------------------------------------------------

class ShowOffCalcFunc
{
  private $tag;
  private $zero_cost;
  private $ranges;

  function __construct($tag, $zero_cost, $ranges)
  {
    $this->tag = $tag;
    $this->zero_cost = $zero_cost;
    $this->ranges = $ranges;
  }

  function render_decl($p)
  {
    $p->begin('tr'); {
      $p->begin('td'); {
	$p->text($this->tag);
      } $p->end();
      $p->begin('td'); {
	$p->begin('select'); {
	  $p->begin('option'); {
	    $p->text('Range');
	  } $p->end();
	  $p->begin('option'); {
	    $p->text('JavaScript');
	  } $p->end();
	} $p->end();
      } $p->end();
      $p->begin('td'); {
	$p->text('Input 0: ');
	$p->elem('input', array('type' => 'text', 'size' => 5, 'value' => $this->zero_cost));
	$p->text('<br/>');
	foreach ($this->ranges as $range) {
	  $p->text('Input from ');
	  $p->begin('select'); {
	    $p->wrap('option', $range[0]);
	  } $p->end();
	  $p->text(' to ');
	  $p->text($range[1]);
	  $p->text(': (<b>input</b> * ');
	  $p->elem('input', array('type' => 'text', 'size' => 5, 'value' => $range[2]));
	  $p->text(') + ');
	  $p->elem('input', array('type' => 'text', 'size' => 5, 'value' => $range[3]));
	  $p->text('<br/>');
	}
      } $p->end();
    } $p->end();
  }
}

//-------------------------------------------------------------------------

class ShowOffCalcPage extends ShowOffPage
{
  private $page_title = 'ShowOff Calculators';

  function render()
  {
    $p = new ShowOffPageWriter();

    // TODO
    $card_name = 'SOME CARD';

    // TODO
    $rows = array(new ShowOffCalcRowConst('monthly_fee', 'Monthly Fee', 1),
		  new ShowOffCalcRowCount('atm_withdraw', 'ATM Withdrawals', 0, 30, 1),
		  new ShowOffCalcRowCount('bill_pays', 'Bill Payments', 0, 10, 0),
		  new ShowOffCalcRowCount('online_buy', 'Online Purchases', 0, 30, 5),
		  new ShowOffCalcRowCount('store_buy', 'In-Store Purchases', 0, 30, 5),
		  new ShowOffCalcRowCount('overdraft', 'Overdrafts', 0, 10, 0),
		  new ShowOffCalcRowAmount('spent', 'Total Spent'),
		  new ShowOffCalcRowTotal());

    // TODO
    $funcs = array(new ShowOffCalcFunc('monthly_fee', 3.95),
		   new ShowOffCalcFunc('atm_withdraw', 0.00,
				       array(array(1, 3, '0.00', '3.00'),
					     array(4, 10, '0.75', '3.00'))));

    $p->begin('div', array('class' => 'wrap')); {
      $p->begin('div', array('class' => 'icon32', 'id' => 'icon-tools')); {
        $p->elem('br');
      } $p->end();
      $p->wrap('h2', $this->page_title);

      $p->wrap('h3', 'Calculator Template');

      $p->para('This section defines the rows that the calcuator will display for each offer. This template is more restrictive an than offer template; you can only specify the text that appears in each row. The overall structure of the calculator is controlled by the plugin.');

      $p->para('The simple implementation uses a text box where you can enter the rows:');

      $p->begin('textarea', array('style' => 'width: 500px; height: 150px; font-family: monospace;')); {
	// TODO
	$p->text("monthly_fee, Monthly Fee, CONSTANT, 1\n");
	$p->text("atm_withdraw, ATM Withdrawals, COUNT, 0, 30\n");
	$p->text("bill_pays, Bill Payments, COUNT, 0, 10\n");
	$p->text("online_buy, Online Purchases, COUNT, 0, 30\n");
	$p->text("store_buy, In-Store Purchases, COUNT, 0, 30\n");
	$p->text("overdraft, Overdrafts, COUNT, 0, 10");
      } $p->end();

      $p->para('The advanced implementation provides a web interface for building and editing the rows:');

      $p->begin('table'); {
	$p->begin('tr'); {
	  $p->wrap('th', 'Tag');
	  $p->wrap('th', 'Description');
	  $p->wrap('th', 'Type');
	  $p->wrap('th', 'Action');
	} $p->end();
	foreach($rows as $row) {
	  $row->render_decl($p);
	}
	$p->begin('tr', array('colspan' => 4)); {
	  $p->begin('td'); {
	    $p->elem('input', array('type' => 'button',
				  'value' => 'Add Row'));
	  } $p->end();
	} $p->end();
      } $p->end();

      $p->wrap('h3', 'Offer Calculations');

      $p->para('This section defines how a particular offer will calculate the value for each row defined by the calculator.');

      $p->para('The simple implementation uses a text box where you can enter the rows:');

      $p->begin('textarea', array('style' => 'width: 500px; height: 100px; font-family: monospace;')); {
	// TODO
	$p->text("monthly_fee, 3.95\n");
	$p->text("atm_withdraw, 0.00, 1, 0.00, 3.00, 4, 0.75, 3.00\n");
      } $p->end();

      $p->para('The advanced implementation provides a web interface for building and editing the rows:');

      $p->begin('table'); {
	$p->begin('tr'); {
	  $p->wrap('th', 'Tag');
	  $p->wrap('th', 'Type');
	  $p->wrap('th', 'Calculation');
	} $p->end();
	foreach($funcs as $func) {
	  $func->render_decl($p);
	}
	$p->begin('tr', array('colspan' => 3)); {
	  $p->begin('td'); {
	    $p->elem('input', array('type' => 'button',
				  'value' => 'Add Row'));
	  } $p->end();
	} $p->end();
      } $p->end();

      $p->wrap('h3', 'Sample Calculator');

      $p->para('This section shows how the calculator will appear when the shortcode is used with the given calculator template and offer:');
      $p->wrap('pre', '[showcalc template=CALC1 offer=ABC123]');

      $p->begin('table'); {
	$p->begin('tr'); {
	  $p->wrap('th', '&nbsp;');
	  $p->wrap('th', 'Transactions<br/>per Month');
	  $p->wrap('th', $card_name);
	} $p->end();
	foreach($rows as $row) {
	  $row->render($p);
	}
      } $p->end();

    } $p->end();
  }
}

//
// CONTROLLER
//

class ShowOffCalcController
{
  function __construct()
  {
  }

  function register()
  {
    $page = add_submenu_page('showoff_offers', // parent_slug
                             'ShowOff Calculators', // page_title
                             'Calculators', // menu_title
                             'edit_posts', // capability
                             'showoff_calc', // menu_slug
                             array($this, 'render')); //function
  }

  function render()
  {
    $p = new ShowOffCalcPage();
    $p->render();
  }
}

?>
