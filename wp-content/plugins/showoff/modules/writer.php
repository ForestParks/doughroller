<?php

class ShowOffPageWriter
{
  private $stack = array();

  function elem($elem, $attrs = null)
  {
    $this->start_element($elem, $attrs);
    echo " />";
  }

  function begin($elem, $attrs = null)
  {
    $this->start_element($elem, $attrs);
    echo ">";
    array_push($this->stack, $elem);
  }

  function end()
  {
    $elem = array_pop($this->stack);
    echo "</$elem>\n";
  }

  function text($text)
  {
    echo $text;
  }

  function wrap($elem, $text)
  {
    $this->begin($elem);
    $this->text($text);
    $this->end();
  }

  function para($text)
  {
    $this->wrap('p', $text);
  }

  private function start_element($elem, $attrs)
  {
    echo "<$elem";

    if (!is_null($attrs)) {
      foreach($attrs as $name => $value) {
        echo " $name='$value'";
      }
    }
  }
}

?>