<?php

class Template
{
  public $viewVars;

  public function render($file)
  {
    ob_start();
    require($file);
    return ob_get_clean();
  }
}