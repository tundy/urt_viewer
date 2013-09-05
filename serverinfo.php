<?php
function server_info ()
{	
	$args = func_get_args();
	$arg = argopt
	($args,
	"i ip Ip I IP ? Set IP adress or domain for server \t \"127.0.0.1\"",
	"d domain D Domain ? Set and change domain to IP adress for server \t \"localhost\"",
	"p port P Port ? Set server port \t \"27960\"",
	"b banner B Banner ? add this opt for banner \t ",
	"f full F Full ? add this opt for full status \t ",
	"s sec seconds S Sec Seconds ? minimal wait time for server status \t \"30\"",
	"m min minutes M Min Minutes ? minimal wait time for server status \t \"2\""
	);
	
	if (is_array($arg)):
		if ( isset($arg["i"]) )
			$ip = gethostbyname($arg["i"] );
		else
			$ip = "127.0.0.1";
		
		if ( isset($arg["d"]) )
			$ip = $arg["d"];
		elseif ( !isset($arg["i"]) )
			$ip = "127.0.0.1";

		if (isset($arg["p"]))
			$port = $arg["p"];
		else
			$port = "27960";
	
		if (isset($arg["b"]))
			$banner = true;
		else
			$banner = false;

		if (isset($arg["f"]))
			$full = true;
		else
			$full = false;	
	
		if (isset($arg["s"]))
			$sec = $arg["s"];
		else
			$sec = 30;

		if (isset($arg["m"]))
			$min = $arg["m"];
		else
			$min = 2;	
		
		$GLOBALS["ip"] = $ip;
		$GLOBALS["port"] = $port;
	
		if ($sec > 60 || $sec < 0)
			$GLOBALS["seconds"] = 0 ;
		else
			$GLOBALS["seconds"] = $sec ;
		if ($min > 60 || $min < 0)
			$GLOBALS["minutes"] = 0 ;
		else
			$GLOBALS["minutes"] = $min ;
				
		include_once './func.php';
		include './status.php';
		
		if ($banner)
			include './banner.php';
		if ($full)
			include './fullstatus.php';

echo '<pre>';
print_r(get_defined_vars());
echo '</pre>';
			
		return 0;
	else:
		echo text2table($arg);
		return 1;
	endif;

}
?>