<?php

abstract class Controller
{
  public $view = 'home';

  static $media = array('css' => array(), 'js' => array());
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
    self::$viewVars['media'] = self::$media;
    $viewVars = self::$viewVars;
    require($dir.($view ? $view : $this->view).'.php');
  }
}