<?php

class Crawl
{

  public $url = '';
  public $depth = '';

  public $urls = array();
  public $domains = array();
  public $pageVisited = 0;

  const MAX_PAGE_VISITED = 600;
  
  public function __construct($url, $depth)
  {
    $this->url = $url;
    $this->depth = (int)$depth;
  }

  private function _getLastDirInURL($url, $href)
  {
    $hrefArray = explode('/', $href);
    $urlArray = array_reverse(explode('/', $url));
    $i = 0;
    if (strpos($urlArray[0], '.'))
    unset($urlArray[$i++]);

    foreach ($hrefArray as $key => $dir)
    {
      if ($dir == '..')
      {
        if (isset($urlArray[$i]))
        {
          unset($urlArray[$i++]);
          unset($hrefArray[$key]);
        }
      }
      else if ($dir == '.')
      unset($hrefArray[$key]);
    }

    return implode('/', array_reverse($urlArray)).'/'.implode('/', $hrefArray);
  }

  private function _getLinksFromUrl($url)
  {
    $links = array();
    $dom = new DOMDocument();
    $this->pageVisited++;

    if (!@$dom->loadHTMLFile($url))
    return array();
    $anchors = $dom->getElementsByTagName('a');

    foreach ($anchors as $element)
    {
      $href = $element->getAttribute('href');
      if (!$href)
      continue;
      if (stripos($href, 'http') !== 0)
      {
        $parts = parse_url($url);
        if ($href[0] != '/')
        $href = $parts['scheme'].'://'.$this->_getLastDirInURL(str_replace($parts['scheme'].'://', '',$url), $href);
        else if ($href[0] == '/' && isset($href[1]) && $href[1] == '/')
        $href = $parts['scheme'].':'.$href;
        else
        {
          $path = '/' . ltrim($href, '/');
          $href = $parts['scheme'].'://'.(isset($parts['user']) && isset($parts['pass']) ? $parts['user'].':'.$parts['pass'].'@' : '').$parts['host'].(isset($parts['port']) ? ':'.$parts['port'] : '').$path;
        }
        while (in_array(substr($href, -1), array('#', '/')))
        $href = substr($href, 0, -1);
      }
      else
      {
        while (in_array(substr($href, -1), array('#', '/')))
        $href = substr($href, 0, -1);
        $parts = parse_url($href);
      }

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

  /*
  Breadth-first search algorithme
   */

  public function getAllLinks()
  {
    $i = 0;
    while ($i <= $this->depth)
    {
      if ($i == 0)
      {
        $links = $this->_getLinksFromUrl($this->url);
        if (!$links)
        return false;
        $this->urls[$this->url] = array('depth' => $i, 'links' => $links);
        foreach ($links as $l)
        $this->depths[$i + 1][$l] = true;
      }
      else if (isset($this->depths[$i]))
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
}