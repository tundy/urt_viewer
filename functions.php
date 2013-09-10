<?php

function removespace ($result)													## Remove spaces between ""						//:SVK: odstrani medzery medzi uvodzovkami
{
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
	$result = str_replace ("^<", "<font style='color:white'>", $result);		## change ^< to white							//:SVK: Biela Farba
	$result = str_replace ("^>", "<font style='color:white'>", $result);		## change ^> to white							//:SVK: Biela Farba
	$result = str_replace ("<", "&lt;", $result);								## convert < into &lt; for proper display		//:SVK: Zmeni to na specialny HTML znak
	$result = str_replace (">", "&gt;", $result);								## convert > into &gt; for proper display		//:SVK: Zmeni to na specialny HTML znak
	$result = preg_replace ("/(\^.)/", "</font>$1", $result);					## add </font> before new color					//:SVK: prida ukoncenie farebneho textu

	$result = str_replace ("^0", "<font style='color:black'>", $result);		## change ^0 to black							//:SVK: Cierna Farba
	$result = str_replace ("^1", "<font style='color:red'>", $result);			## change ^1 to red								//:SVK: Cervena Farba
	$result = str_replace ("^2", "<font style='color:green'>", $result);		## change ^2 to green							//:SVK: Zelena Farba
	$result = str_replace ("^3", "<font style='color:yellow'>", $result);		## change ^3 to yellow							//:SVK: Zlta Farba
	$result = str_replace ("^4", "<font style='color:blue'>", $result);			## change ^4 to blue							//:SVK: Modra Farba
	$result = str_replace ("^5", "<font style='color:cyan'>", $result);			## change ^5 to cyan							//:SVK: SvetloModra Farba
	$result = str_replace ("^6", "<font style='color:magenta'>", $result);		## change ^6 to magenta							//:SVK: Fialova Farba
	$result = str_replace ("^7", "<font style='color:white'>", $result);		## change ^7 to white							//:SVK: Biela Farba
	$result = str_replace ("^8", "<font style='color:orange'>", $result);		## change ^8 to orange							//:SVK: Oranzova Farba
	$result = str_replace ("^9", "<font style='color:olive '>", $result);		## change ^9 to olive 							//:SVK: Okrova Farba
	
	$result = preg_replace ("/(\^.)/", "<font style='color:white'>", $result);	## remove ^N, ^b etc.. 							//:SVK: Zmeni vsetky ostatne farby na bielu
	$result = str_replace ("\"", "", $result);									## remove "s									//:SVK: Odstrani uvodzovky z mien
	return $result;
}

function cmd ($cmd, $ip, $port = 27960)											## Send command to the server					//:SVK: Odosle prikaz na server
{
	$timeout = 1;
	$cmd = chr(255).chr(255).chr(255).chr(255).$cmd;
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
	if ( empty($data) )
		return "No answer from server !!";
	else
		return $data;
}

function status ($type, $ip, $port = 27960, $minutes = 2, $seconds = 30)							## Get status from server						//:SVK: Ziska status serveru
{
	$Data = 0;
	$DataFile = $ip . "%" . $port . ".data";												// 127.0.0.1%27960.data
	if ( file_exists($DataFile) )
		$Data = 1;
		
	if (check($ip,$port, $minutes, $seconds) == "1"	OR $Data == 0)							## Only if there is no status or Time expired
	{
		$status = cmd("getstatus\n", $ip, $port);
		$df = fopen($DataFile, 'w') or die("can't open file");
		fwrite($df, $status);
		fclose($df);
	}
	
	include("status.php");
	if ($type == 1)
		include("banner.php");
	elseif ($type == 2)
	{
		include("banner.php");
		include("fullstatus.php");
	}
	else
		include("fullstatus.php");
}

function lastcheck ($ip, $port = 27960)														## Find date/time for last refresh				//:SVK: Zisti cas posledneho statusu
{
	$File = $ip . "%" . $port . ".time";													// 127.0.0.1%27960.time
	$lastcheck = file_get_contents($File);
	$date = array();
	$date = explode ("/", $lastcheck);
	return $date;
}

function check ($ip, $port = 27960, $minutes = 2, $seconds = 30)							## Diff between lastcheck and now				//:SVK: Zisti kolko casu uplinulo
{
	$File = $ip . "%" . $port . ".time";
	$TimeData = "0";
	if ( file_exists($File) )
		$TimeData = "1";
	
	if ($TimeData == "0")
	{
		$TimeFile = fopen($File, 'w') or die("can't open file");
		fwrite($TimeFile, "0/0/0/0/0/0");
		fclose($TimeFile);
	}

	$time = lastcheck($ip,$port);
	$lastcheck = date_create("$time[0]-$time[1]-$time[2] $time[3]:$time[4]:$time[5]");		// 2013-7-19 18:10:57
	$now = date_create('now');
	$diff = date_diff($lastcheck, $now);
	$Y = $diff->format('%Y');	// Years
	$m = $diff->format('%m');	// Months
	$d = $diff->format('%d');	// Days
	$H = $diff->format('%H');	// Hours
	$i = $diff->format('%i');	// Minutes
	$s = $diff->format('%s');	// Seconds
	
	if
	(	
		$Y > 0			||
		$m > 0			||
		$d > 0			||
		$H > 0 			||
		$i > $minutes	||
		(
			$i == $minutes &&
			$s >  $seconds
		)
	)
	{
		$time = $now->format("Y/m/d/H/i/s");
		$TimeFile = fopen($File, 'w') or die("can't open file");
		fwrite($TimeFile, $time);
		fclose($TimeFile);
		return 1;
	}
	else
	{
		return  0;
	}
}

?>