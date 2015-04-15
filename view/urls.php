<?php

echo '<h2>'.$viewVars['given-url'].' - Depth: '.$viewVars['given-depth'].'</h2>';
echo '<p>'.$viewVars['page-visited'].' page'.($viewVars['page-visited'] == 1 ? '' : 's').' visited.</p>';
echo '<div class="canvas-div">Domain name Presence:<canvas class="canvas"></canvas></div>';
echo '<ul>';
foreach ($viewVars['urls'] as $url => $details)
{
  echo '<li>'.$url.' - Link Depth: '.$details['depth'].'<br/><select><option>URL in '.$url.'</option>';
  foreach ($details['links'] as $link)
  echo '<option value="'.$link.'">'.$link.'</option>';
  echo '</select></li>';
}
echo '</ul>';

$highest = array_sum($viewVars['domains']);
$highestPresence = array('domain' => '', 'percent' => 0);
foreach ($viewVars['domains'] as $domain => $number)
{
  if (($percent = $number * 100 / $highest) > $highestPresence['percent'])
  {
    $highestPresence['domain'] = $domain;
    $highestPresence['percent'] = $percent;
  }
}
echo '<div class="pieData" rel="'.floor($highestPresence['percent']).'">'.$highestPresence['domain'].'</div>';
