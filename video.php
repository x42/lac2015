<?php
# vim: ts=2 et
require_once('lib/lib.php');
require_once('lib/programdb.php');
?>
<body>
<div class="container">
<?php
function printerror($msg) {
  html5head('Video Player', 'vstyle.css');
  echo '<body>';
  echo '<div class="error">ERROR: '.$msg.'</div>';
  echo '<div class="footer">Back to <a href="'.local_url('program').'">conference site</a>.</div>';
  echo '</body></html>';
  exit;
}

$id=0;
$height=360;
$width=640;

if (!isset($_REQUEST['id'])) {
  printerror('invalid request - no id specified.');
} else {
  $id=intval(rawurldecode($_REQUEST['id']));
}
if (isset($_REQUEST['h'])) {
  $h=intval(rawurldecode($_REQUEST['h']));
  if ($h == 720) { $height=720; $width=1280; }
  else if ($h == 360) { $height=360; $width=640; }
}

if ($id > 0) {
  $q='SELECT * FROM activity WHERE id='.$id.';';

  try {
    $db=new PDO("sqlite:tmp/lac".LACY.".db"); // XXX -> config.php
  } catch (PDOException $exception) {
    die ('Database Failure: '.$exception->getMessage());
  }

  $res=$db->query($q);
  if (!$res)
    printerror('database query failed.');
  $result=$res->fetchAll();
  if (count($result) ==0)
    printerror('No matching entries found.');
  $v=$result[0];
} else if ($id == -1 || $id == -2) {
  $v['url_slides'] = '';
  $v['url_paper'] = '';
  $v['url_misc'] = '';
  $v['abstract'] = '';
} else {
  printerror('No matching entries found.');
}

if ($config['hidepapers']) $v['url_paper'] = '';

if ($id > 0) {
  $url=$v['url_stream'];

  # TODO use strreplace or sth faster (or update database)
  $url=preg_replace('@\.webm$@', '', $url);
  $url=preg_replace('@\.mp4$@', '', $url);
  $url=preg_replace('@_720p$@', '', $url);
  $url=preg_replace('@_360p$@', '', $url);

  $pagetitle=pdb_html_title($db, $id);
} else if ($id == -1) {
  $url='http://lacstreamer.stackingdwarves.net/lac2015-lq.webm';
  $width = 768;
  $height = 576;
  $pagetitle='Live Stream (HQ)';
} else if ($id == -2) {
  $width = 320;
  $height = 240;
  $url='http://lacstreamer.stackingdwarves.net/lac2015-lq.webm';
  $pagetitle='Live Stream (LQ)';
}

if (empty($pagetitle)) {
  $pagetitle = "Video Player";
} else {
  $pagetitle = "Video: ".$pagetitle;
}

html5head($pagetitle, 'vstyle.css');
echo '<div class="header">Linux Audio Conference '.LACY.'</div>';
echo '<div class="title">';
echo '<b>'.xhtmlify($v['title']).'</b><br/>';
if ($id > 0) {
  echo '<em>'; $i=0;
  $a_users = fetch_selectlist($db, 'user', 'ORDER BY name');
  foreach (fetch_authorids($db, $id) as $user_id) {                    
    if ($i++>0) echo ', ';
    echo xhtmlify($a_users[$user_id]);
  }
  echo '</em>';
}
echo '</div>';
if (!empty($url)) {
  echo '</div>'; # container
  echo '<div class="player" style="width:'.$width.'px;">';
  if ($id > 0) {
  echo '<video width="'.$width.'" height="'.$height.'" autoplay controls tabindex="0">'
    .'<source type="video/webm" src="'.$url.'_'.$height.'p.webm" />'
  # .'<source type="video/mp4" src="'.$url.'_'.$height.'p.mp4" />'
    .'</video>';
  }
  if ($id < 0) {
  echo '<video width="'.$width.'" height="'.$height.'" autoplay controls tabindex="0">'
    .'<source type="video/webm" src="'.$url.'" />'
    .'</video>';
  }
  echo '</div>';
  echo '<div class="container">';
  echo '<div id="sizebar">';
  if ($id > 0) {
    foreach (array(360, 720) as $s) {
      if ($s == $height)
        echo '&nbsp;<b>'.$s.'p</b>&nbsp;';
      else
        echo '&nbsp;<a href="video.php?id='.$id.'&amp;h='.$s.'">'.$s.'p</a>&nbsp;';

    }
  }
  echo '</div>';
} else {
  echo '<div class="error"><br/>This presentations has not been recorded.</div>';
}

echo '<div class="links"><ul>';
if (!empty($v['url_slides']))
  echo '<li>Slides: <a href="'.$v['url_slides'].'" rel="external">'.$v['url_slides'].'</a></li>';
if (!empty($v['url_paper']))
  echo '<li>Paper: <a href="'.$v['url_paper'].'" rel="external">'.$v['url_paper'].'</a></li>';
if (!empty($v['url_misc']))
  echo '<li>Site: <a href="'.$v['url_misc'].'" rel="external">'.$v['url_misc'].'</a></li>';
if (!empty($url) && $id > 0) {
  echo '<li>Video URL: ';
  foreach (array(360, 720) as $s) {
    #echo '<a href="'.$url.'_'.$s.'p.mp4">'.$s.'p mp4</a>&nbsp;';
    echo '<a href="'.$url.'_'.$s.'p.webm">'.$s.'p webm</a>&nbsp;';
  }
  echo '</li>';
}
if (!empty($url) && $id < 0) {
  echo '<li>Video URL: ';
  echo '<a href="'.$url.'">Live webm</a>&nbsp;';
  echo '</li>';
}
echo '</ul></div>';

if (!empty($v['abstract'])) {
  echo '<div class="abstract">';
  echo '<p>'.str_replace("\\n",'<br/>', xhtmlify($v['abstract'])).'</p>';
  echo '</div>';
}

?>
<div class="license">
<!--
<a rel="license" href="http://creativecommons.org/licenses/by-sa/3.0/"><img alt="Creative Commons License" style="border-width:0" src="http://i.creativecommons.org/l/by-sa/3.0/88x31.png" /></a><br />
-->
The video is licensed in terms of the <a rel="license" href="http://creativecommons.org/licenses/by-sa/3.0/">Creative Commons Attribution-ShareAlike 3.0 Unported License</a>. Attribute to <a xmlns:cc="http://creativecommons.org/ns#" href="<?=CANONICALURL?>" property="cc:attributionName" rel="cc:attributionURL"><?=$config['organization']?></a>. All copyright(s) remain with the author/speaker/presenter.
</div>
<div class="footer">Back to <a href="<?=local_url('program')?>">conference site</a>.</div>
</div>
<br/>
</body>
</html>
