<?php

abstract class Controller
{
  public $view = 'home';

  static $viewVars = array();

  public function init()
  {
  }

  public function run()
  {
    $this->display();
  }

  public function display($view = null, $dir = VIEW_DIR)
  {
    $tpl = new Template();
    $tpl->viewVars = self::$viewVars;
    echo $tpl->render($dir.($view ? $view : $this->view).'.php');
  }
}