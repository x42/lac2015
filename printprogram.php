<?php
# vim: ts=2 et
  require_once('lib/lib.php');
  require_once('lib/programdb.php');
  xhtmlhead('Conference Schedule', 'printstyle.css', '<link rel="stylesheet" type="text/css" media="print" href="'.BASEURL.'static/print.css" />');
?>
<body id="content">
<div class="menu">Back to the <a href="<?=local_url('program')?>">conference site</a>.</div>
<h1 class="center"><?=SHORTTITLE?> - Conference Schedule</h1>
<div class="center"><?=$config['headerlocation']?></div>
<div class="center">All times are CEST = UTC+2</div>
<?php
  require_once('lib/lib.php');
  require_once('lib/programdb.php');

  table_program($db,1,true);
  table_program($db,2,true);
  table_program($db,3,true);

  #echo '<h2 class="ptitle">Day 4 -  Sunday, April/12</h2>';
  # TODO extract from pages/excursion.php (include it and use print CSS?)
?>
<p><br/></p>
<?php
  hardcoded_concert_and_installation_info($db, false);
  hardcoded_disclaimer();
?>
</body>
</html>
