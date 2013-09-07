<?php

function removespace ($result)		## Remove spaces between ""
{
	$result = str_replace ("&nbsp", " ", $result);
	$temp = explode("\"", $result);
	for($i=1;$i<=count($temp)-1;$i+=2)
	{
		$temp[$i] = str_replace (" ", "", $temp[$i]); 
	}
	$result = implode("\"", $temp);
	return $result;
	}

function stripcolors ($result)
{
	$result = preg_replace ("/(\^.)/", "</font>$1", $result);				## add </font> before new color
	$result = str_replace ("<", "&lt;", $result);							## convert < into &lt; for proper display
	$result = str_replace (">", "&gt;", $result);							## convert < into &gt; for proper display

	$result = str_replace ("^0", "<font style='color:black'>", $result);	## change ^0 to black
	$result = str_replace ("^1", "<font style='color:red'>", $result);		## change ^1 to red
	$result = str_replace ("^2", "<font style='color:green'>", $result);	## change ^2 to green
	$result = str_replace ("^3", "<font style='color:yellow'>", $result);	## change ^3 to yellow
	$result = str_replace ("^4", "<font style='color:blue'>", $result);		## change ^4 to blue
	$result = str_replace ("^5", "<font style='color:cyan'>", $result);		## change ^5 to cyan
	$result = str_replace ("^6", "<font style='color:magenta'>", $result);	## change ^6 to magenta
	$result = str_replace ("^7", "<font style='color:white'>", $result);	## change ^7 to white
	$result = str_replace ("^8", "<font style='color:orange'>", $result);	## change ^8 to orange
	$result = str_replace ("^9", "<font style='color:olive '>", $result);	## change ^9 to olive 
	
	$result = preg_replace ("/(\^.)/", "", $result);						## remove ^N, ^b etc..
	$result = str_replace ("\"", "", $result);								## remove "s
	return $result;
}

function cmd ($cmd, $ip, $port=27960)
{
	$timeout = 1;
	$cmd = "ÿÿÿÿ".$cmd;
	$errno = $errstr = null;
	$server = fsockopen('udp://' . $ip, $port, $errno, $errstr, $timeout);
	if (!$server)
		die ("Unable to connect. Error $errno - $errstr\n");
	socket_set_timeout ($server, 0, 500000);
	fwrite ($server, $cmd);
	$data = '';
	while ($d = fread ($server, 10000))
		$data .= $d;
	fclose ($server);
	return $data;
}

?>