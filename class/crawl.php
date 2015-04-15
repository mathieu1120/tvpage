<?php

class Crawl
{

  public $url = '';
  public $depth = '';

  public $urls = array();
  public $domains = array();
  public $pageVisited = 0;

  const MAX_PAGE_VISITED = 800;
  
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
    $this->pageVisited++;
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

      if ($parts && isset($parts['host']))
      {
        if (!isset($this->domains[$parts['host']]))
        $this->domains[$parts['host']] = 1;
        else
        $this->domains[$parts['host']]++;
      }
      $links[] = $href;
    }
    return array_unique($links);
  }

  public function getAllLinks()
  {
    $i = 0;
    while ($i <= $this->depth)
    {
      if ($i == 0)
      {
        $links = $this->_getLinksFromUrl($this->url);
        $this->urls[$this->url] = array('depth' => $i, 'links' => $links);
        foreach ($links as $l)
        $this->depths[$i + 1][$l] = true;
      }
      else
      {
        foreach ($this->depths[$i] as $link => $bool)
        {
          if (self::MAX_PAGE_VISITED < $this->pageVisited)
          return false;

          if (isset($this->urls[$link]))
          continue;

          $links = $this->_getLinksFromUrl($link);
          $this->urls[$link] = array('depth' => $i, 'links' => $links);
          foreach ($links as $l)
          {
            if (isset($this->depths[$i + 1][$l]))
            continue;
            $this->depths[$i + 1][$l] = true;
          }
        }
      }
      $i++;
    }
  }

  /*
  public function getAllLinks($url = '', $depth = 0)
  {
  //if (self::MAX_PAGE_VISITED < $this->pageVisited)
  //return false;
  
  if (!$url)
  $url = $this->url;
  
  if ($depth > $this->depth)
  return false;
  
  if (!isset($this->urls[$url]['depth']) || $this->urls[$url]['depth'] > $depth)
  $this->urls[$url]['depth'] = $depth;
  if (!isset($this->urls[$url]['links']))
  $this->urls[$url]['links'] = $this->_getLinksFromUrl($url);
  
  foreach ($this->urls[$url]['links'] as $link)
  $this->getAllLinks($link, $depth + 1);
  return true;
  }
   */
}