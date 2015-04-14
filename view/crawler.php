<div id="crawler-form">
  <form action="./" method="POST" onsubmit="return processCrawlerURL();" >
    <label>Previous Search:</label>
    <select id="previous-url"><option>Select...</option>
      <?php
      foreach ($viewVars['previous_url'] as $url)
      echo '<option value="'.$url.'">'.$url.'</option>';
      ?>
    </select>
    <label>URL:</label>
    <input type="text" name="url" value="http://" />
    <label>Depth:</label>
    <input type="text" name="depth" />
    <input type="submit" name="crawl-url" value="Submit" />
  </form>
</div>
<div id="crawler-status">
</div>
<hr/>
<div id="crawler-results">
</div>