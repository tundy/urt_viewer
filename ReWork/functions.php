<?php

function removespace ($result) {		## Remove spaces between ""
	$result = str_replace ("&nbsp", " ", $result);
	$temp = explode("\"", $result);
	for($i=1;$i<=count($temp)-1;$i+=2)
		{ // =>
		$temp[$i] = str_replace (" ", "", $temp[$i]); 
		}
	$result = implode("\"", $temp);
	return $result;
	}

function stripcolors ($result) {
	$result = str_replace ("<", "&lt;", $result);							## convert < into &lt; for proper display
	$result = str_replace (">", "&gt;", $result);							## convert < into &gt; for proper display
	// $result = preg_replace ("/\^x....../i", "", $result);				## remove OSP's colors (^Xrrggbb) // Not In UrT
	$result = preg_replace ("/(\^.)/", "</font>$1", $result);				## add </font> before new color
	
	$result = str_replace ("-ANIKI-","<img width='12' height='12' alt='-ANIKI-' src='/aniki.png'/>", $result);		// change clantag to picture
	$result = str_replace ("[SVK]","<img width='12' height='12' alt='[SVK]' src='/svk.png'/>", $result);			// change clantag to picture
	$result = str_replace ("[ST]","<img width='12' height='12' alt='[ST]' src='/st.png'/>", $result);				// change clantag to picture
	$result = str_replace ("[CZARMY]","<img width='12' height='12' alt='[CZARMY]' src='/czarmy.png'/>", $result);	// change clantag to picture

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
	
	$result = preg_replace ("/(\^.)/", "", $result);						## remove OSP control (^N, ^B etc..)
	$result = str_replace ("\"", "", $result);								## remove "s ("nickname")
	return $result;
}

function lastcheck ($ip, $port = 27960) {		## Find date/time for last refresh
	$myFile = $ip . "%" . $port . ".time";
	$lastcheck = file_get_contents($myFile);
	$date = array();
	$date = explode ("/", $lastcheck);
	return $date;
}

function check ($ip, $port = 27960) {			## Diff between lastcheck and now
	$myFile = $ip . "%" . $port . ".time";
	$TimeData = "0";
	$dir = '.';
	$files= scandir($dir);
	foreach ($files as $file) {
		if ($file == $myFile) {
		$TimeData = "1"; }}
	
	if ($TimeData == "0") {
		$TimeFile = fopen($myFile, 'w') or die("can't open file");
		fwrite($TimeFile, "0/0/0/0/0/0");
		fclose($TimeFile);}

	$time = lastcheck($ip,$port);
	$lastcheck = date_create("$time[0]-$time[1]-$time[2] $time[3]:$time[4]:$time[5]");		## 2013-7-19 18:10:57
	$now = date_create('now');
	$diff = date_diff($lastcheck, $now);
	$Y = $diff->format('%Y');	## Years
	$m = $diff->format('%m');	## Months
	$d = $diff->format('%d');	## Days
	$H = $diff->format('%H');	## Hours
	$i = $diff->format('%i');	## Minutes
	$s = $diff->format('%s');	## Seconds
	$time = $now->format("Y/m/d/H/i/s");
	
	if
	(	$Y > 0
	||	$m > 0
	||	$d > 0
	||	$H > 0
	||	$i > $GLOBALS["minutes"]
	||	(
		$i == $GLOBALS["minutes"]
	&&	$s >  $GLOBALS["seconds"]
		)
	)
		{
		$status = "1";
		$fh = fopen($myFile, 'w') or die("can't open file");
		fwrite($fh, $time);
		fclose($fh);
	} else {
		$status = "0";
		}
	return $status;
}

function status ($ip, $port = 27960, $timeout = 1) { ## getstatus from ip:port
	$dataFile = $ip . "%" . $port . ".data";
	
	$dat = "0";
	$dir = '.';
	$files= scandir($dir);
	foreach ($files as $file) {
		if ($file == $dataFile) {
		$dat = "1"; }}
	
	if (check($ip,$port) == "1"
	OR $dat == "0") {
		$errno = $errstr = null;
		$cmd = "ÿÿÿÿgetstatus\n";
		$f = fsockopen('udp://' . $ip, $port, $errno, $errstr, $timeout);
		if (!$f)
			die ("Unable to connect. Error $errno - $errstr\n");
		socket_set_timeout ($f, 1, 0);
		fwrite ($f, $cmd);
		$data = '';
		while ($d = fread ($f, 10000)) {
			$data .= $d;
		}
		fclose ($f);
		$fd = fopen($dataFile, 'w') or die("can't open file");
		fwrite($fd, $data);
		fclose($fd);
	}
}
?>