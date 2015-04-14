<?php

class CrawlerController extends Controller
{
  public function init()
  {
    self::$media['js'][] = 'crawler';
    self::$media['js'][] = 'Chart.min';
    self::$media['css'][] = 'crawler';
  }

  public function run()
  {
    self::$viewVars['previous_url'] = unserialize(Cookie::get('crawler-urls'));
    parent::display('crawler');
  }

  public function crawlURL($url, $depth = 0)
  {
    if (!preg_match('#^http(s)?://#i', $url))
    $url = 'http://'.$url;

    $this->setNewUrlInCookie($url);
    
    $crawl = new Crawl($url, $depth);
    $crawl->getAllLinks();

    self::$viewVars['given-url'] = $url;
    self::$viewVars['given-depth'] = $depth;
    self::$viewVars['urls'] = $crawl->urls;
    self::$viewVars['domains'] = $crawl->domains;
    parent::display('urls');
  }

  public function ajaxCrawlURL($param)
  {
    if (isset($param['url']))
    $this->crawlURL($param['url'], isset($param['depth']) ? $param['depth'] : 0);
  }

  public function setNewUrlInCookie($url)
  {
    $previousUrl = unserialize(Cookie::get('crawler-urls'));
    if (!$previousUrl)
    $previousUrl = array();
    if (!in_array($url, $previousUrl))
    $previousUrl[] = $url;
    sort($previousUrl);
    Cookie::update('crawler-urls', serialize($previousUrl));
  }
}