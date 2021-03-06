<?php
$error = 0;				## set no errors
$players = array();			## Clear $players

$DataFile = @fopen("$ip%$port.data", "r");		## Open Data File with last updated server's infos
if ($DataFile) 									## Try to open DataFile
	{
	$status = fgets($DataFile);					## Get First Line from file		// Is server online ?
	
	if ( preg_match("/....statusResponse.*/", $status) == false)	## Is server online ?
		$error = "Error: no status response.\r\n";					## No
	else
		unset($status);												## Yes

	$f_cvars = fgets($DataFile);									## Get Second Line from file	// Server cvars
	
	$ID = 0;											## players ID
	while (($buffer = fgets($DataFile)) !== false)		## Get all other lines
		{
		$buffer = removespace ($buffer);				## Remove Spaces from players's name
		sscanf
		(
		$buffer,"%d %d %s"				## Get 2 numbers and 1 string
		,$players[$ID]["Score"]
		,$players[$ID]["Ping"]
		,$players[$ID]["Nick"]
		);
		$players[$ID]["Nick"] = stripcolors ($players[$ID]["Nick"]);								## Add colors to nicks
		$players[$ID]["Nick"] = "<font style='color:white'>".$players[$ID]["Nick"]."</font>";		## Default color
		$pattern = "/<font[^>]*><\\/font[^>]*>/"; 													## Grep for empty <font> tags
		$players[$ID]["Nick"] = preg_replace($pattern, '', $players[$ID]["Nick"]); 					## Remove empty <font> tags
		unset($pattern);
		if ($players[$ID]["Ping"] == 0)																## Change ping 0 to BOT string
			$players[$ID]["Ping"] = "BOT";								
		$ID++;																						## Next players
		}
	unset($buffer);				## UnSet Buffer
	$o_players = $ID;			## Get number of players from last players ID
	unset($ID);					## UnSet players ID, don't need it anymore
    if (!feof($DataFile)) 								## Unexpected End of File !!
		$error = "Error: unexpected end of datafile\r\n";	## set error msg
    fclose($DataFile);			## Close DataFile
	unset($DataFile);			## UnSet DataFile
	rsort($players);			## Sort an array in reverse order (highest to lowest) by Score
	} 
else
	$error = "Error: failed to open datafile.\r\n";		## Failed to load FileData

$cvar = explode('\\', $f_cvars);			## Split Cvars to 1 array
unset($cvar[0]);							## First Value in Array is null
$cvar  = stripcolors ($cvar);				## Add colors to cvars

$cvars = (substr_count("$f_cvars","\\"));	## Get number of Cvars
unset($f_cvars);							## Unset $f_cvars

### Start ### Chagning $cvar[] to $cvar["KEY"] alphabetically
if ( empty($error) )								## Do it only if is everything OK
{
	$i = 1;
	while ($i < $cvars):						## new $cvar's "KEY" is equal to old $cvar[$i]
		$cvar["$cvar[$i]"] = trim($cvar[$i + 1]);		## new $cvar["KEY"]'s VALUE is equal to old $cvar[$i+1]
		unset($cvar[$i]);						## After setting new KEY old one will be unset
		unset($cvar[$i + 1]);					## After setting new VALUE old one will be unset
		$i += 2;
	endwhile;
	unset($i);
	$cvars = count($cvar);						## Get real number of Cvars
	ksort($cvar);								## Sort array alphabetically by array KEYs
}
### End ### Chagning $cvar[] to $cvar["KEY"] alphabetically

if ( empty($error) )										## Do it only if is everything OK
{
	if (isset($cvar["sv_hostname"]))					## Exist this variable ?
	{
		$cvar["sv_hostname"] = "<font style='color:white'>" . $cvar["sv_hostname"] . "</font>";	
		$pattern = "/<font[^>]*><\\/font[^>]*>/"; 									## Grep for empty <font> tags
		$cvar["sv_hostname"] = preg_replace($pattern, '', $cvar["sv_hostname"]); 	## Remove empty <font> tags
		unset($pattern);	
		$sv_hostname = "<span style='font-size:13px'>" . $cvar["sv_hostname"] . "</span>";
	}
	if (isset($cvar["g_modversion"]))					## 4.1 and 4.1.1 servers behave same
	{
		if (strpos($cvar["g_modversion"], "4.1") == true)
			$cvar["g_modversion"] = "4.1 / 4.1.1";
	}
	if (isset($cvar["g_gametype"]))						## Change number to Human Readable text
	{
		$g_gametype = $cvar["g_gametype"];
		switch ($g_gametype):
			case "0":
				$g_gametype = "FFA [0]"/* Free For All"*/; break;
			case "1":
				$g_gametype = "LMS [1]"/* Last Man Standing"*/; break;
			case "3":
				$g_gametype = "TDM [3]"/* Team Death Match"*/; break;
			case "4":
				$g_gametype = "TS [4]"/* Team Survivor"*/; break;
			case "5":
				$g_gametype = "FTL [5]"/* Follow The Leader"*/; break;
			case "6":
				$g_gametype = "CaH [6]"/* Capture and Hold"*/; break;
			case "7":
				$g_gametype = "CTF [7]"/* Capture The Flag"*/; break;
			case "8":
				$g_gametype = "Bomb [8]"/* Bomb Mode"*/; break;
			case "9":
				$g_gametype = "Jump [9]"/* Jump Mode"*/; break;
			default:
				$g_gametype = "??? [" . $cvar[$i] . "]"/* Unknown"*/; break;
			endswitch;
	}
	if (isset($cvar["gamename"]))						## set game icon for banner from game/version
	{
		$gamename = $cvar["gamename"];
		switch ($gamename):
			case "q3ut4":
				$gamename = "game_q3ut4.png"; break;
			case "q3urt42":
				$gamename = "game_q3urt42.png"; break;
			default:
				$gamename = "game_unknown.png"; break;
			endswitch;
	}
	if (isset($cvar["g_needpass"]))						## Change number to Human Readable text
	{
		if ($cvar["g_needpass"] == "0")
			$cvar["g_needpass"] = "public";
		else
			$cvar["g_needpass"] = "private";
	}

	$dir = 'levelshots';								## Directory with LevelShots	
	if ( file_exists($dir . "/" . $cvar["mapname"] . ".png") )
		$mapimage = $cvar["mapname"] . ".png";
	elseif ( file_exists($dir . "/" . $cvar["mapname"] . ".jpg") )
		$mapimage = $cvar["mapname"] . ".jpg";
	elseif ( file_exists($dir . "/" . $cvar["mapname"] . ".gif") )
		$mapimage = $cvar["mapname"] . ".gif";
	elseif ( file_exists($dir . "/" . $cvar["mapname"] . ".bmp") )
		$mapimage = $cvar["mapname"] . ".bmp";
	elseif ( file_exists($dir . "/" . $cvar["mapname"] . ".tga") )
		$mapimage = $cvar["mapname"] . ".tga";
	else
		$mapimage = "no.png";	
	unset($dir);
	$slots = $o_players . " / " . ($cvar["sv_maxclients"]-$cvar["sv_privateClients"]) . " + " . $cvar["sv_privateClients"];
}

if ( !empty($error) )
	{
	$sv_hostname = "<span style='font-size:16px ;color:red'>OFFLINE</span>";
	$cvar["g_modversion"] = " ";
	$cvar["g_needpass"] = " ";
	$g_gametype = " ";
	$cvar["auth_status"] = " ";
	$gamename = "game_unknown.png";
	$cvar["gamename"] = "unknown";
	$slots = " ";
	$cvar["mapname"] = "$error";
	$mapimage = "offline.png";
	}	
?>