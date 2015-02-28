<?php
# vim: ts=2 et
  require_once('lib/lib.php');
  require_once('site.php');
//////////////////////////////////////////////////////////////////////////////

  $page=$homepage;
  $preq='';

  if (isset($_REQUEST['page']))
    $preq=rawurldecode($_REQUEST['page']);

  if (!empty($preq) && in_array($preq, array_merge(array_keys($pages), array_keys($hidden))))
    $page=$preq;

  if (!empty($preq) && in_array($preq, $adminpages)) {
    if (authenticate()) {
      require_once('lib/submit.php');
      $page=$preq;
    } else {
      header('Location: logon.php');
      exit;
    }
  }

  if ($page=='program' || $page=='adminschedule') {
    require_once('lib/programdb.php');
  }

  if (  !empty($preq) && $preq=='registration'
      && isset($config['regclosed']) && $config['regclosed']) {
    $page='regclosed';
  }

  if (!empty($preq) && $preq=='registration') {
    require_once('lib/submit.php');
    if (checkreg())
      if (savereg())
        $page='regcomplete';
  }

  if (!empty($preq) && $preq!=$page && $page==$homepage) {
    header('HTTP/1.0 404 Not Found');
    $page='404';
  }

  $title='';
  if ($page=='program' && isset($_REQUEST['pdb_filterid'])) {
    $pdbid = intval(rawurldecode($_REQUEST['pdb_filterid']));
    $title=pdb_html_title($db, $pdbid);
  }

### BEGIN OUTPUT ###
  if (empty($title)) { $title = $pages[$page]; }
  if (empty($title)) { $title = $hidden[$page]; }
  $title.=' [Linux Audio Conference]';
  xhtmlhead($title);
  if (defined("TESTANDDEVEL")) {
    echo '<body style="background:url(img/testanddevel.jpg) repeat">'."\n";
  } else {
    echo "<body>\n";
  }
?>
<div class="braille"><a href="#main-content">Skip to content</a></div>
<div id="envelope">
 <div id="toprow">
  <div id="titlebar">
    <div id="maintitle">Linux Audio Conference <?=LACY?> </div>
    <div id="subtitle">The Open Source Music and Sound Conference</div>
    <div id="wherewhen"><?=$config['headerlocation']?></div>
    <div id="andwhat">LECTURES / WORKSHOPS / EXHIBITIONS / CONCERTS / CLUBNIGHTS</div>
  </div>
</div>

<?php
  if ($page=='admin' || $page=='adminschedule') {
    echo '<div id="adminmenu">';
    admin_fieldset(-3);
    echo '</div>'."\n";
  }
?>

<div id="payload-layout">
  <div id="logoright">
    <a href="https://www.facebook.com/LAC2015" rel="external"><img src="img/logos/fb-button-16x16.png" alt="find us on facebook" title="find us on facebook" /></a>
    <a href="https://plus.google.com/events/c7plph8ec1f7pvoufni3pb8sh68" rel="external"><img src="img/logos/gp-button-16x16.png" alt="google-plus event" title="google-plus event" /></a>
  </div>
  <div id="mainmenu">
<?php
  ### populate menu bar ##
  $i=0;
  foreach($pages as $p => $t) {
    echo '
    <div class="menuitem'.(($page==$p)?' tabactive':'').'">
        <a href="'.local_url($p).'">'.$t.'</a>
    </div>'."\n";
  }
  echo '    <div style="clear:both; height:0px;">&nbsp;</div>'."\n";
?>
  </div>

  <div id="main">
    <div id="content">
    <a name="main-content"></a>

<?php
  require_once('pages/'.$page.'.php');

  ### content-footer
  #format page mod time
  $mtime_page=filemtime('pages/'.$page.'.php');
  $mtime_idx=0;
  $mtime_skip=false;
  switch($page) {
    case 'sponsors':
      $mtime_idx=filemtime('site.php');
      break;
    case 'program':
    case 'adminschedule':
      $mtime_idx=filemtime(preg_replace('@^[^:]*:@', '', PDOPRGDB));
      break;
    case 'profile':
    case 'speakers':
      $mtime_skip = true;
      break;
    case 'admin':
    case 'participants':
      $mtime_idx=filemtime(preg_replace('@^[^:]*:@', '', PDOREGDB));
      break;
    case 'upload':
    case 'files':
    default:
      $mtime_idx=filemtime('index.php');
  }
  if (!$mtime_skip) {
    $mtime=$mtime_page>$mtime_idx?$mtime_page:$mtime_idx;
    $mdate=date("l, M j Y H:i e", $mtime);
  }
?>

    </div>
  <div style="clear:both; height:0px;">&nbsp;</div>
  </div>
</div>

<?php
  if (!$mtime_skip)
    echo '<div id="createdby">This page was last modified: '.$mdate.' - Albert Gr&auml;f</div>';
  else
    echo '<div id="createdby"><br/></div>';
?>
</div>

<div id="footerwrap">
<table border="0" width="100%" id="supporter" style="table-layout: fixed;">
<tr>
<?php

  $cnt=0;
  foreach ($sponsors as $sl => $si) {
    if ($cnt>0 && ($cnt%4 ==0)) {
      echo "</tr>\n<tr>\n";
    }
    if (array_key_exists('colspan',$si)) {
      $incr = $si['colspan'];
      echo '  <td colspan="'.$incr.'">'."\n";
    } else {
      $incr = 1;
      echo "  <td>\n";
    }
    echo '    <a href="'.$sl.'"'."\n";
    echo '     rel="supporter"><img src="'.$si['img'].'" title="'.$si['title'].'" alt="'.$si['title'].'"/>';
    echo "  </a></td>\n";
    $cnt += $incr;
  }
  /*
  while ($cnt++%5 !=0) {
    echo '  <td></td>';
  }
   */
?>
</tr>
</table>
<?php

  if (function_exists('clustermap')) { clustermap(); echo '<p>&nbsp;</p>';}
?>
  <a href="http://validator.w3.org/check?uri=referer" rel="external"><img
      src="img/button-xhtml.png"
      alt="Valid XHTML 1.0 Strict"/></a>
  <a href="http://jigsaw.w3.org/css-validator/check/referer?profile=css3" rel="external"><img
      src="img/button-css.png"
      alt="Valid CSS3"/></a>
  <a href="http://www.mozilla.com/en-US/firefox/firefox.html" rel="external"><img
      src="img/button-firefox.png"
      alt="Get Firefox"/></a><br/>
  <p>LINUX<sup>&reg;</sup> is a <a href="http://www.linuxmark.org/" rel="external">registered trademark</a> of Linus Torvalds in the USA and other countries.<br />Hosting provided by the <a href="http://www.music.vt.edu" rel="external">Virginia Tech Department of Music</a> and <a href="http://disis.music.vt.edu" rel="external">DISIS</a>.<br/>Design and implementation by <a href="http://gareus.org/" rel="external">RSS</a>.</p>
</div>
</body>
</html>
