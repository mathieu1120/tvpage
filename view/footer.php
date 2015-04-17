<p class="page-footer">TVPage Test. Mathieu Bertholino</p>
</div>
<script type="text/javascript" src="<?php echo JS_URI.'jquery.js';?>"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<?php
foreach ($viewVars['media']['js'] as $js)
{
  if (file_exists(JS_DIR.$js.'.js'))
  echo '<script type="text/javascript" src="'.JS_URI.$js.'.js?'.time().'"></script>';
}
?>
</body>
</html>