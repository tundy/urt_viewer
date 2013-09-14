<?php
if ( !$server_country = geoip_country_code_by_name($ip) )
	$server_country = "null";

$server_country = strtolower($server_country);
$banner_width = 150;

// Head Table
echo"<table width='$banner_width' cellpadding='1' cellspacing='0' class='statusbanner' border='0'> \r\n";
echo"<tr><th>";
switch ( $cvar["gamename"] ):
	case "q3ut4":
	case "q3urt42":
		echo "<img align='left' src='game_icon/" . $gamename . "' alt=" . $cvar["gamename"] . ">" . "</th> \r\n";
		echo "<th><center>" . "<a href=\"urt://" . gethostbyname("$ip") . ":$port\" target=\"_blank\" >PLAY NOW</a>" . "</center></th> \r\n";
		echo "<th>" . "<img align='right' src='flags/" . $server_country . ".png' alt=" . $server_country . ">" . "</th></tr> \r\n";
		echo "<tr><th colspan='3'>" . "<a href='http://www.urbanterror.info/servers/" . gethostbyname("$ip") . ":$port/' target='_blank'><center>$sv_hostname</center></a>";
		break;
	default:
		echo"<img align='left' src='game_icon/" . $gamename . "' alt=" . $cvar["gamename"] . "><center>$sv_hostname</center>";
endswitch;
echo"</th></tr> \r\n";
echo"</table> \r\n";

// Info Table
echo"<table width='$banner_width' cellpadding='1' cellspacing='0' class='statusbanner' border='0'> \r\n";
echo"<tr><th>Version:</th><td>" . $cvar["g_modversion"] . "</td></tr> \r\n";
echo"<tr><th>IP:</th><td>$ip : $port</td></tr> \r\n";
echo"<tr><th>Status:</th><td>" . $cvar["g_needpass"] . "</td></tr> \r\n";
if ( isset($cvar["auth_status"]))		## Check if Auth cvar exist
    echo"<tr><th>Auth:</th><td>" . $cvar["auth_status"] . "</td></tr> \r\n";
echo"<tr><th>Mode:</th><td>" . $g_gametype . "</td></tr> \r\n";
echo"<tr><th>Info:</th><td>";

$lastcheck = lastcheck($ip,$port);
echo $lastcheck[0];			## Show Last Year for Scanning
echo"/";
echo $lastcheck[1];			## Show Last Month for Scanning
echo"/";
echo $lastcheck[2];			## Show Last Day for Scanning
echo" ";
echo $lastcheck[3];			## Show Last Hour for Scanning
echo":";
echo $lastcheck[4];			## Show Last Minute for Scanning
echo":";
echo $lastcheck[5];			## Show Last Second for Scanning
unset ($lastcheck);

echo"</tr> \r\n";
echo"<tr><th>Slots:</th><td> $slots </td></tr> \r\n";
echo"<tr><th>Map:</th><td>" . $cvar["mapname"] . "</td></tr> \r\n";
echo"</table> \r\n";

// Map Image Table
echo"<table width='$banner_width' cellpadding='1' cellspacing='0' class='statusbanner' border='0'> \r\n";
echo"<tr><td colspan='2'><img align='center' width='" . ($banner_width - 2) . "' height='120' alt='$mapimage' src='levelshots/" , $mapimage , "'  class='statusbanner'></td></tr> \r\n";
echo"</table> \r\n";

// Players Table
echo"<table width='$banner_width' border='1' cellpadding='1' cellspacing='0' class='statusbanner'> \r\n";
echo"<tr><th colspan='3'><center>PLAYERS</center><hr></th></tr> \r\n";
echo"<tr><td><b> Score, </b></td><td><b> Ping, </b></td><td><b>Nick</b></td></tr> \r\n";

$i = 0; 
while ($i < $o_players):
	echo "<tr><td>";
	echo $players[$i]["Score"];
	echo ",";
	echo "</td>";
	echo "<td>";
	echo $players[$i]["Ping"];
	echo ",";
	echo "</td>";
	echo "<td>";
	echo $players[$i]["Nick"];
	echo "</td></tr> \r\n";
	$i++;
endwhile;
unset($i);
echo"</table> \r\n";
echo"<br> \r\n";
?>