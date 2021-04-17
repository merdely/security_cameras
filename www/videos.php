<html>
<head>
<style type="text/css">
  body { background-color: #222222; color: white; }
  a:link, a:visited { color: grey; }
  a:hover, a:active { color: yellow; }
</style>
<title>Security Camera Videos</title>
<link rel="icon" href="images/ipcamera.ico" type="image/x-icon"/>
<link rel="shortcut icon" href="images/ipcamera.ico" type="image/x-icon"/>
<style>
  td {
      text-align: center;
  }
</style>
</head>
<body>

<?php
$cameras = array('frontdoorcam', 'garagecam', 'chimneycam', 'shedcam', 'backyardcam');
if (isset($_REQUEST['camera']) and in_array($_REQUEST['camera'], $cameras)) {
    $camera = $_REQUEST['camera'];
} else {
    $camera = '';
}
if (isset($_REQUEST['date']) and (bool)strtotime($_REQUEST['date']) and (strtotime($_REQUEST['date']) <= strtotime(date("Y-m-d")))) {
    $date = date("Ymd", strtotime($_REQUEST['date']));
} else {
    $date = date("Ymd");
}
$camdirroot = '/share/media/Cameras/';
$camwebroot = '/Cameras/';

if ($camera != '')
  echo "<h1>$camera Videos Archive</h1>\n";
else
  echo "<h1>All Videos Archive</h1>\n";
echo "<p><a href='/'>Live Cameras</a> | \n";
echo "<a href='cameras.php'>Activity Archive</a> | \n";
echo "<a href='$camwebroot$camera/'>All $camera Videos</a></p>\n";


echo "<form>";
echo "Pick camera: ";
echo "<select name='camera' id='camera'>\n";
foreach ($cameras as $cam) {
    echo "<option value='$cam'";
    if ($camera == $cam)
        echo " selected";
    echo ">$cam</option>\n";
}
echo "</select> &nbsp; \n";
echo "Choose date: \n";
echo "<input type='date' name='date' id='date' value='" .  date("Y-m-d", strtotime($date)) . "' />\n";
echo "<input type='submit' />";
echo "</form>\n";
echo "</body>\n";
echo "</html>\n";
if ($camera == '') {
    exit;
}
?>

<table width="100%">
<tr><td width="50%" style="text-align:left"><?php
echo "<a href=?camera=$camera&date=" . date("Y-m-d", strtotime($date) - 86400) . ">"
?>Previous Day</a></td>
<td width="50%" style="text-align:right"><?php
if (strtotime($date) < strtotime(date("Y-m-d"))) {
    echo "<a href=?camera=$camera&date=" . date("Y-m-d", strtotime($date) + 86400) . ">Next Day</a>";
} else {
    echo "&nbsp;";
}
?></td></tr>
</table>

<table border="1">
<?php
print_activity_files($camera . '/', $date);
?>
</table>
<table width="100%">
<tr><td width="50%" style="text-align:left"><?php
echo "<a href=?camera=$camera&date=" . date("Y-m-d", strtotime($date) - 86400) . ">"
?>Previous Day</a></td>
<td width="50%" style="text-align:right"><?php
if (strtotime($date) < strtotime(date("Y-m-d"))) {
    echo "<a href=?camera=$camera&date=" . date("Y-m-d", strtotime($date) + 86400) . ">Next Day</a>";
} else {
    echo "&nbsp;";
}
?></td></tr>
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

function print_activity_files($cam, $date) {
    global $camdirroot, $camwebroot;

    $count = 0;
    $flist = scandir("$camdirroot$cam/$date", 1);
    for ($j = 0; $j < count($flist); ++$j) {
        if (preg_match('/\.mp4$/', $flist[$j])) {
            if ($count == 0) {
                print('<tr>');
            }
            print('<td>');
            print("<a href='$camwebroot$cam$date/{$flist[$j]}'>");
            print("<img src='$camwebroot$cam$date/.thumbs/" . substr($flist[$j], 0, -4) . ".png' width='320px' />");
            print('<br />');
            print(strftime("%A", strtotime(substr($flist[$j], 2, 2) . '/' . substr($flist[$j], 4, 2) . '/' . substr($flist[$j], 0, 2))) . ': ');
            //print(strftime("%A", strtotime($matches[2] . '/' . $matches[3] . '/' . $matches[1])) . ': ');
            // print(date('l', strtotime($date)) . ': ');
            $ret = get_time($flist[$j]);
            print($ret[1] . ':' . $ret[2] . ':' . $ret[3]);
            print(' to ');
            print($ret[4] . ':' . $ret[5] . ':' . $ret[6]);
            print('</a>');
            print('<br />');
            print('</td>');
            $count++;
            if ($count % 5 == 0) {
                print('</tr><tr>');
            }
        }
    }
    if ($count > 0) {
        print('</tr>');
    }
}
?>
