<?php

abstract class Controller
{
  public $view = 'home';
  
  public $twig = null;

  static $viewVars = array();

  public function init()
  {
  }

  public function run()
  {
    $this->display();
  }

  private function _initTwig()
  {
    $loader = new Twig_Loader_Filesystem(TPL_DIR);
    $this->twig = new Twig_Environment($loader, array(
      //'cache' => CACHE_DIR,
    ));

    $this->twig->addGlobal('CSS_URI', CSS_URI);
    $this->twig->addGlobal('JS_URI', JS_URI);
  }

  public function display($view = null)
  {
    if (!$this->twig)
    $this->_initTwig();

    $template = $this->twig->loadTemplate($view.'.html');
    $template->display(self::$viewVars);
    /*$tpl = new Template();
    $tpl->viewVars = self::$viewVars;
    echo $tpl->render($dir.($view ? $view : $this->view).'.php');*/
  }
}