<?php
echo"<table width='160' cellpadding='1' cellspacing='0' class='statusbanner' border='0'>";
echo"<tr><th colspan='2'>";
switch ($gamename):
	case "game_q3ut4.png":
		echo"<a href='http://www.urbanterror.info/servers/" . gethostbyname("$ip") . ":$port/' target='_blank'><img align='left' src='/game_icon/" . $gamename . "' alt=" . $cvar["gamename"] . "><center>$sv_hostname</center></a>";
		break;
	case "game_q3urt42.png":
		echo"<a href='http://www.urbanterror.info/servers/" . gethostbyname("$ip") . ":$port/' target='_blank'><img align='left' src='/game_icon/" . $gamename . "' alt=" . $cvar["gamename"] . "><center>$sv_hostname</center></a>";
		break;
	default:
		echo"<img align='left' src='/game_icon/" . $gamename . "' alt=" . $cvar["gamename"] . "><center>$sv_hostname</center>";
endswitch;
echo"<br></th></tr>";
echo"<tr><th>Version:</th><td>" . $cvar["g_modversion"] . "</td></tr>";
echo"<tr><th>IP:</th><td>$ip:$port</td>";
echo"<tr><th>Status:</th><td>" . $cvar["g_needpass"] . "</td></tr>";
if ( isset($cvar["auth_status"]))		## Check if Auth cvar exist
    echo"<tr><th>Auth:</th><td>" . $cvar["auth_status"] . "</td></tr>";
echo"<tr><th>Mode:</th><td>" . $g_gametype . "</td></tr>";
echo"<tr><th>Info:</th><td>";
/*
echo lastcheck($ip,$port)[0];			## Show Last Year for Scanning
echo"/";
echo lastcheck($ip,$port)[1];			## Show Last Month for Scanning
echo"/";
echo lastcheck($ip,$port)[2];			## Show Last Day for Scanning
echo" ";
*/
echo lastcheck($ip,$port)[3];			## Show Last Hour for Scanning
echo":";
echo lastcheck($ip,$port)[4];			## Show Last Minute for Scanning
echo":";
echo lastcheck($ip,$port)[5];			## Show Last Second for Scanning

echo"</tr>";
echo"<tr><th>Slots:</th><td> $slots </td></tr>";
echo"</table>";

echo"<table width='160' cellpadding='1' cellspacing='0' class='statusbanner' border='0'>";
echo"<tr><td colspan='2'>" . $cvar["mapname"] . "</td></tr>";
echo"<tr><td colspan='2'><img align='center' width='150' height='120' alt='$mapimage' src='/levelshots/" , $mapimage , "'  class='statusbanner'></td></tr>";
echo"</table>";

echo"<table width='160' border='1' cellpadding='1' cellspacing='0' class='statusbanner'>";
echo"<tr><th colspan='3'><center>PLAYERS</center><hr></th></tr>";
echo"<tr><td><b> Score, </b></td><td><b> Ping, </b></td><td><b>Nick</b></td></tr>";

$i = 0; 
while ($i < $o_players):
	echo "<tr><td>";
	echo $player[$i]["Score"];
	echo ", ";
	echo "</td>";
	echo "<td>";
	echo $player[$i]["Ping"];
	echo ", ";
	echo "</td>";
	echo "<td>";
	echo $player[$i]["Nick"];
	echo "</td></tr>";
	$i++;
endwhile;
unset($i);
echo"</table>";
echo"<br />";
?>