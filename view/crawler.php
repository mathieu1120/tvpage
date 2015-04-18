<div class="row">
  <div id="crawler-form" class="col-sm-6">
    <form action="./" method="POST" onsubmit="return processCrawlerURL();" class="form-horizontal" >
      <div class="form-group">
        <label class="col-sm-3 control-label">Previous Search:</label>
        <div class="col-sm-9">
          <select id="previous-url" class="form-control"><option>Select...</option>
            <?php
            foreach ($this->viewVars['previous_url'] as $url)
            echo '<option value="'.dp($url).'">'.dp($url).'</option>';
            ?>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-3 control-label">URL:</label>
        <div class="col-sm-9">
          <input class="form-control" type="text" name="url" value="http://" />
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-3 control-label">Depth:</label>
        <div class="col-sm-9">
          <input class="form-control" type="text" name="depth" value="0"/>
        </div>
      </div>
      <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
          <input type="submit" name="crawl-url" value="Submit" class="btn btn-default" />
        </div>
      </div>
    </form>
  </div>
  <div id="crawler-status" class="col-sm-6 well">
  </div>
</div>
<hr/>
<div id="crawler-results" class="row">
</div>