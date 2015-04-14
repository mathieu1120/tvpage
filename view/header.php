<!DOCTYPE html>
<html>
  <head>
    <title>TVPage Web Crawler test</title>
    <meta charset="UTF-8">
    <?php
    foreach ($viewVars['media']['css'] as $css)
    {
      if (file_exists(CSS_DIR.$css.'.css'))
      echo '<link href="'.CSS_URI.$css.'.css?'.time().'" type="text/css" rel="stylesheet">';
    }
    ?>
  </head>
  <body>
      <div id="header"><h1>TVPage Web Crawler test</h1></div>