<?php

class Crawl
{

  public $url = '';
  public $depth = '';

  public $urls = array();
  public $domains = array();
  
  public function __construct($url, $depth)
  {
    $this->url = $url;
    $this->depth = $depth;
  }

  private function _getLinksFromUrl($url)
  {
    $links = array();

    $dom = new DOMDocument();
    @$dom->loadHTMLFile($url);
    $anchors = $dom->getElementsByTagName('a');
    foreach ($anchors as $element)
    {
      $href = $element->getAttribute('href');
      if (stripos($href, 'http') !== 0)
      {
        $path = '/' . ltrim($href, '/');

        $parts = parse_url($url);
        $href = $parts['scheme'].'://';
        if (isset($parts['user']) && isset($parts['pass']))
        $href .= $parts['user'].':'.$parts['pass'].'@';
        
        $href .= $parts['host'];
        if (isset($parts['port']))
        $href .= ':'.$parts['port'];
          
        $href .= $path;
      }
      else
      $parts = parse_url($href);

      if (!isset($this->domains[$parts['host']]))
      $this->domains[$parts['host']] = 1;
      else
      $this->domains[$parts['host']]++;

      $links[] = $href;
    }
    return array_unique($links);
  }

  public function getAllLinks($url = '', $depth = 0)
  {
    if (!$url)
    $url = $this->url;

    if ($depth > $this->depth)
    return true;

    if (!isset($this->urls[$url]['depth']) || $this->urls[$url]['depth'] > $depth)
    $this->urls[$url]['depth'] = $depth;
    if (!isset($this->urls[$url]['links']))
    $this->urls[$url]['links'] = $this->_getLinksFromUrl($url);

    foreach ($this->urls[$url]['links'] as $link)
    $this->getAllLinks($link, $depth + 1);
  }
}