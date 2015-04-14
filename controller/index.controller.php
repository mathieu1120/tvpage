<?php

class IndexController extends Controller
{
  public function run()
  {
    $controller = new CrawlerController();
    $controller->init();

    $this->displayHeader();
    $controller->run();
    $this->displayFooter();                    
  }

  public function displayHeader()
  {
    parent::display('header');
  }

  public function displayFooter()
  {
    parent::display('footer');
  }
}