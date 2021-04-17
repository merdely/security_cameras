<html>
<head>
<style type="text/css">
  body { background-color: #222222; color: white; }
  a:link, a:visited { color: grey; }
  a:hover, a:active { color: yellow; }
</style>
<title>Security Cameras</title>
<link rel="icon" href="images/ipcamera.ico" type="image/x-icon"/>
<link rel="shortcut icon" href="images/ipcamera.ico" type="image/x-icon"/>
<style>
  td {
      text-align: center;
  }
</style>
</head>
<?php
$camdirroot = '/share/media/Cameras/';
$camwebroot = '/Cameras/';
$maxlistings = 10;
?>
<body>

<h1>Security Cameras</h1>

<p><a href="/">Live Cameras</a> |
<a href="videos.php">All Videos Archive</a> |
<a href="Cameras/">All Video Files</a>

<p>Most recent <?= $maxlistings ?> recordings with activity per camera.</p>

<h2>Front Door Camera</h2>

<p><a href="<?= $camwebroot ?>frontdoorcam/">All Front Door Camera Video Files</a> |
<a href="videos.php?camera=frontdoorcam">All Front Door Camera Videos</a></p>

<table border="1">
<?php
print_activity_files('frontdoorcam/');
?>
</table>

<h2>Garage Camera</h2>

<p><a href="<?= $camwebroot ?>garagecam/">All Garage Camera Video Files</a> |
<a href="videos.php?camera=garagecam">All Garage Camera Videos</a></p>

<table border="1">
<?php
print_activity_files('garagecam/');
?>
</table>

<h2>Chimney Camera</h2>

<p><a href="<?= $camwebroot ?>chimneycam/">All Chimney Camera Video Files</a> |
<a href="videos.php?camera=chimneycam">All Chimney Camera Videos</a></p>

<table border="1">
<?php
print_activity_files('chimneycam/');
?>
</table>

<h2>Shed Camera</h2>

<p><a href="<?= $camwebroot ?>shedcam/">All Shed Camera Video Files</a> |
<a href="videos.php?camera=shedcam">All Shed Camera Videos</a></p>

<table border="1">
<?php
print_activity_files('shedcam/');
?>
</table>

<h2>Backyard Camera</h2>

<p><a href="<?= $camwebroot ?>backyardcam/">All Backyard Camera Video Files</a> |
<a href="videos.php?camera=backyardcam">All Backyard Camera Videos</a></p>

<table border="1">
<?php
print_activity_files('backyardcam/');
?>
</table>

<p>&nbsp;</p>

</body>
</html>
<?php
function get_time($filename) {
    if ($ret = preg_match('/\d{6}_(\d{2})(\d{2})(\d{2})_(\d{2})(\d{2})(\d{2})_([AP])\.mp4/', $filename, $matches)) {
        return $matches;
    } else {
        return $ret;
    }
}

function print_activity_files($cam) {
    global $camdirroot, $camwebroot, $maxlistings;

    $count = 0;
    $list = scandir($camdirroot . $cam, 1);
    for ($i = 0; $i < count($list); ++$i) {
        if (preg_match('/^(\d{4})(\d{2})(\d{2})$/', $list[$i], $matches)) {
            $flist = scandir($camdirroot . $cam . $list[$i], 1);
            for ($j = 0; $j < count($flist); ++$j) {
                if (preg_match('/_A\.mp4$/', $flist[$j])) {
                    if ($count >= $maxlistings) {
                        break;
                    }
                    if ($count == 0) {
                        print('<tr>');
                    }
                    print('<td>');
                    print('<a href="' . $camwebroot . $cam . $list[$i] . '/' . $flist[$j] . '">');
                    print('<img src="' . $camwebroot . $cam . $list[$i] . '/.thumbs/' . substr($flist[$j], 0, -4) . '.png" width="320px" />');
                    print('<br />');
                    print(strftime("%A", strtotime($matches[2] . '/' . $matches[3] . '/' . $matches[1])) . ': ');
                    $ret = get_time($flist[$j]);
                    print($ret[1] . ':' . $ret[2] . ':' . $ret[3]);
                    print(' to ');
                    print($ret[4] . ':' . $ret[5] . ':' . $ret[6]);
                    print('</a>');
                    print('<br />');
                    if ($j > 0) {
                        print('<a href="' . $camwebroot . $cam . $list[$i] . '/' . $flist[$j-1] . '">');
                        print('&lt;-- next');
                        print('</a>');
                    }

                    print(' &nbsp; ');

                    if ($j < count($flist)) {
                        print('<a href="' . $camwebroot . $cam . $list[$i] . '/' . $flist[$j+1] . '">');
                        print('previous --&gt;');
                        print('</a> ');
                    }

                    print('</td>');
                    $count++;
                    if ($count % 5 == 0) {
                        print('</tr><tr>');
                    }
                }
            }
        }
    }
    if ($count > 0) {
        print('</tr>');
    }
}
?>
