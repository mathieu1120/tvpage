<div class="row panel panel-default result-block">
  <div class="panel-heading"><?php echo dp($this->viewVars['given-url']).' - Depth: '.(int)$this->viewVars['given-depth']; ?>
    <span class="pull-right"><?php echo (int)$this->viewVars['page-visited'].' page'.($this->viewVars['page-visited'] == 1 ? '' : 's'); ?>' visited.</span>
  </div>
  <div class="panel-body">
    <div class="row"><div class="col-sm-8">
      <ul class="list-group">
        <?php
        foreach ($this->viewVars['urls'] as $url => $details)
        {
          echo '<li class="list-group-item">'.dp($url).' - Link Depth: '.(int)$details['depth'].'<br/>';
          if ($details['links'])
          {
            echo '<select class="form-control"><option>URL in '.dp($url).'</option>';
            foreach ($details['links'] as $link)
            echo '<option value="'.dp($link).'">'.dp($link).'</option>';
            echo '</select>';
          }
          else
          echo '<i>No "a" tag with valid "href" attributes were found on that page.<br/>The page can also be a redirection page.<br/>The page might not exist.</i>';
          echo '</li>';
        }
        ?>
      </ul>
    </div>
    <div class="canvas-div col-sm-4 pull-right">Domain name Presence:
      <canvas class="canvas"></canvas>
    </div>
    </div>
    <div class="pieData" rel="<?php echo (float)$this->viewVars['percent'];?>"><?php echo dp($this->viewVars['host']); ?></div>
  </div>