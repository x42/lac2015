<?php
# vim: ts=2 et
#default page
  $homepage='about';

#pages listed as 'tabs' on the site
  $pages = array(
    'about' => 'About',
    'registration' => 'Registration',
    'participants' => 'Attendees',
    'program'  => 'Schedule',
    'speakers'  => 'Delegates',
    'travel' => 'Travel &amp; Stay',
    'contact' => 'Contact',
    'sponsors' => 'Supporters',
  );

# other available pages - not shown as 'tabs'
  $hidden = array(
    'participation' => 'CFP',
    'excursion' => 'Excursion',
    'files' => 'Download',
    'profile' => 'Profile',
  );

#pages that require authentication
  $adminpages = array(
    'upload',
    'admin',
    'adminschedule',
  );

#define sponsors/supportes
  $sponsors = array(
    'http://linuxaudio.org/' => array('img' => 'img/logos/lao.png', 'title' => 'linuxaudio.org'),
    'http://www.uni-mainz.de/eng/index.php' => array('img' => 'img/logos/jgu_logo.png', 'title' => 'Johannes Gutenberg University'),
    'http://www.ikm.uni-mainz.de/' => array('img' => 'img/logos/ikm_logo.png', 'title' => 'Institut für Kunstgeschichte und Musikwissenschaft'),
    'http://www.musik.uni-mainz.de/' => array('img' => 'img/logos/hfm_logo.png', 'title' => 'Hochschule für Musik'),
    'http://www.journalistik.uni-mainz.de/' => array('img' => 'img/logos/JS_logo.png', 'title' => 'Journalistisches Seminar'),
    'http://www.grame.fr/' => array('img' => 'img/logos/grame_logo.png', 'title' => 'Grame - Centre national de création musicale'),
    'http://www.hku.nl/home-en.htm' => array('img' => 'img/logos/HKU_MT_logo.png', 'title' => 'HKU University of the Arts Utrecht'),
    'http://www.bitwig.com/' => array('img' => 'img/logos/bitwig_logo_black.png', 'title' => 'Bitwig - next generation music software for Linux, Mac, Windows'),
    'http://hoertech.de/web_en/start/' => array('img' => 'img/logos/hoertechlogo.png', 'title' => 'HörTech gGmbH Oldenburg'),
    'http://www.portalmod.com/' => array('img' => 'img/logos/mod_logo.png', 'title' => 'MOD - Step onto the future', 'colspan' => 2),
  );

  function clustermap() {
?>
    <div class="center">
<a href="http://www4.clustrmaps.com/counter/maps.php?url=http://lac.linuxaudio.org/2015/" id="clustrMapsLink"><img src="http://www4.clustrmaps.com/counter/index2.php?url=http://lac.linuxaudio.org/2015/" style="border:0px;" alt="Locations of visitors to this page" title="Locations of visitors to this page" id="clustrMapsImg" />
</a>
<script type="text/javascript">
function cantload() {
img = document.getElementById("clustrMapsImg");
img.onerror = null;
img.src = "http://www2.clustrmaps.com/images/clustrmaps-back-soon.jpg";
document.getElementById("clustrMapsLink").href = "http://www2.clustrmaps.com";
}
img = document.getElementById("clustrMapsImg");
img.onerror = cantload;
</script>
    </div>
<?php
  }
