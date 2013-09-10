<?php

$GEAR = array  	## Define Gear Array Keys
(
	0 => "Grenades",
	1 => "Snipers",
	2 => "Spas",
	3 => "Pistols",
	4 => "Automatic Guns",
	5 => "Negev",
);

$VOTE = array   	## Define Vote Array Keys
(
	0 => "reload",
	1 => "restart",
	2 => "map",
	3 => "nextmap",
	4 => "kick/clientkick",
	5 => "swapTeams",
	6 => "shuffleTeams",
	7 => "g_friendlyFire",
 	8 => "g_followStrict",
	9 => "g_gameType",
	10 => "g_waveRespawns",
	11 => "timelimit",
	12 => "fragLimit",
	13 => "captureLimit",
	14 => "g_respawnDelay",
	15 => "g_redWaveRespawnDelay",
	16 => "g_blueWaveRespawnDelay",
	17 => "g_bombExplodeTime",
	18 => "g_bombDefuseTime",
	19 => "g_survivorRoundTime",
	20 => "g_caputureScoreTime",
	21 => "g_warmup",
	22 => "g_matchMode",
	23 => "g_timeouts",
	24 => "g_timeoutLength",
	25 => "exec",
	26 => "g_swapRoles",
	27 => "g_maxRounds",
	28 => "g_gear",
	29 => "cyclemap",
);

if ( empty($error) )																## Do it only if is everything OK
{
	### Calculating Gear ###
	$gear_test = $cvar["g_gear"];
	for ($i = 0 ; $i < 6; $i++)
	{
		$gear[$GEAR[$i]] = (($gear_test & 1) == 1 ? "disabled" : "enabled");	## Check Bit	
		$gear_test >>= 1;														## Remove last Bit
	}
	unset($i);
	unset($gear_test);
	
	### Calculating AllowVote ###
	$vote_test = $cvar["g_allowvote"];
	for ($i = 0 ; $i < 30; $i++)
	{
		$vote[$VOTE[$i]] = (($vote_test & 1) == 1 ? "enabled" : "disabled");	## Check Bit
		$vote_test >>= 1;														## Remove last Bit
	}
	unset($i);
	unset($vote_test);
	
	### OutPut ###
	echo "<table width='160' cellpadding='1' cellspacing='0' class='statusbanner' border='0'> \r\n";
	foreach (array_keys($cvar) as $key)
	{
		switch ($key):
		case "g_gear":
			echo "<tr><th>";
			echo $key;
			echo "</th><td>";
			echo $cvar[$key];
			echo "</td></tr> \r\n";
			
			foreach (array_keys($gear) as $key)
			{
				echo "<tr><td colspan='2'>";
				echo ($gear[$key] == "disabled" ? "&nbsp - &nbsp" : "&nbsp + &nbsp");
				echo $key;
				echo "</td></tr> \r\n";
			}
			break;
		case "g_allowvote":
			echo "<tr><th>";
			echo $key;
			echo "</th><td>";
			echo $cvar[$key];
			echo "</td></tr> \r\n";
			foreach (array_keys($vote) as $key)
			{
				echo "<tr><td colspan='2'>";
				echo ($vote[$key] == "disabled" ? "&nbsp - &nbsp" : "&nbsp + &nbsp");
				echo $key;
				echo "</td></tr> \r\n";
			}
			break;
		default:
			echo "<tr><th>";
			echo $key;
			echo "</th><td>";
			echo $cvar[$key];
			echo "</td></tr> \r\n";
			break;
	endswitch;
	}	
	unset($key);
	echo "</table> \r\n";
}
?>