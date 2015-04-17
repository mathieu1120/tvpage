<?php
echo '<div class="row panel panel-default result-block">';
echo '<div class="panel-heading">'.dp($viewVars['given-url']).' - Depth: '.(int)$viewVars['given-depth'].'<span class="pull-right">'.(int)$viewVars['page-visited'].' page'.($viewVars['page-visited'] == 1 ? '' : 's').' visited.</span></div><div class="panel-body">';
echo '<div class="row"><div class="col-md-8"><ul class="list-group">';
foreach ($viewVars['urls'] as $url => $details)
{
  echo '<li class="list-group-item">'.dp($url).' - Link Depth: '.(int)$details['depth'].'<br/>';
  if ($details['links'])
  {
    echo '<select><option>URL in '.dp($url).'</option>';
    foreach ($details['links'] as $link)
    echo '<option value="'.dp($link).'">'.dp($link).'</option>';
    echo '</select>';
  }
  else
  echo '<i>No "a" tag with valid "href" attributes were found on that page.<br/>The page can also be a redirection page.<br/>The page might not exist.</i>';
  echo '</li>';
}
echo '</ul></div>';
echo '<div class="canvas-div col-md-4 pull-right">Domain name Presence:<canvas class="canvas"></canvas></div></div>';

$highest = array_sum($viewVars['domains']);
$percent = 0;
$host = '';
$parts = parse_url($viewVars['given-url']);
if (isset($parts['host']) && $parts['host'] && isset($viewVars['domains'][$parts['host']]))
{
  $percent = $viewVars['domains'][$parts['host']] * 100 / $highest;
  $host = $parts['host'];
}

echo '<div class="pieData" rel="'.(float)$percent.'">'.dp($host).'</div>';
echo '</div>';