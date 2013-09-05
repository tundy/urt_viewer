<?php //>

###############################################################################
## argopt - Get options from the array[] argument list                       ##
##===========================================================================##
## array argopt (array $arguments, string "$option $alias1 $alias2 ? $help " ##
## [, "$option $alias1 $alias2 ? $help "] [,...] )                           ##
##                                                                           ##
## Return an array in format array["$option"] = VALUE;                       ##
## Options (or aliases) "h" "help" "?" can't be set !                        ##
## If -h option is set function will return string of all available options  ##
##                                                                           ##
##---------------------------------------------------------------------------##
##---Example-----------------------------------------------------------------##
##                                                                           ##
##<?php                                                                      ##
##  function sum()                                                           ##
##  {                                                                        ##
##   $args = func_get_args();                                                ##
##   $arg = argopt($args, "a var_a ? first number", "b c ? second number");  ##
##   if (is_array $arg)                                                      ##
##    return = $arg[a] . " + " . $arg[b] . " = " . ($arg[a]+$arg[b]);        ##
##   else                                                                    ##
##    return $arg;                                                           ##
##  }                                                                        ##
## echo sum("-b 5", "-var_a 3");                                             ##
## echo " | ";                                                               ##
## echo sum("-a 1 -c 2 -b 3");                                               ##
## echo " |";                                                                ##
## echo sum("-help");                                                        ##
##?php>                                                                      ##
##                                                                           ##
##---------------------------------------------------------------------------##
##---Output------------------------------------------------------------------##
##                                                                           ##
## 3 + 5 = 8 | 1 + 3 = 4 |                                                   ##
## -a -var_a: first number                                                   ##
## -b -c:     second number                                                  ##
###############################################################################

function argopt()
{
	$o_arguments = array();
	$input = func_get_args();
	$i_arguments = $input[0];
	unset($input[0]);
	$o_options = array_merge(array(), $input);
	unset($input);
	
	$temp = array();
	foreach	($i_arguments as $argument)
		if (!is_array($argument)):
			$temp = explode(" ", $argument);
			$o_arguments = array_merge($o_arguments, $temp);
		endif;
	
	unset($temp);
	unset($argument);
	unset($i_arguments);
	return (opt($o_arguments, $o_options));
}

function opt($i_arguments, $i_options)
{
	$count = count($i_arguments);
	$LastOpt = 0;
	$input = array();
	
	for ($argument = 0; $argument < $count; $argument++)
		if (substr($i_arguments[$argument], 0, 1) == "-"):
			$LastOpt = substr($i_arguments[$argument], 1);
			$input["$LastOpt"] = "";
		else:
			$input["$LastOpt"] .= " " . $i_arguments[$argument];
			$input["$LastOpt"] = trim($input[$LastOpt], " ");
		endif;
	unset($count);
	unset($argument);
	unset($i_arguments);
	unset($LastOpt);

	$output = array();
	$alias = array();
	$help = array();
	$HELP = false;
	foreach ($i_options as $i_option):
		$temp = explode(" ", $i_option);
			
		if ( ($temp[0] != "h") && ($temp[0] != "help") && ($temp[0] != "?")):
			$id = $temp[0];
			$alias["$id"] = "$id";
			unset($temp[0]);
		else:
			echo "\nYou can't set -h -help or -? for options !\n";
			continue;
		endif;

		$count = count($temp);
		for ($i = 1; $i < $count; $i++)
			if ($temp[$i] == "?"):
				unset($temp[$i]);
				$help["$id"] = implode(" ",$temp);
				break;
			else:
				$alias["$id"] .= " " . $temp[$i];
				unset($temp[$i]);
			endif;
	endforeach;
	unset($i_options);
	unset($i_option);
	unset($temp);
	unset($id);
	unset($i);
	unset($count);

	foreach (array_keys($input) as $one_input)
		if ( ($one_input == "h") || ($one_input == "help") || ($one_input == "?") )
			$HELP = true;
	unset($one_input);
	
	if (!$HELP):
		foreach ($input as $key => $value)
			foreach ($alias as $option => $aliases)
				foreach (explode(" ", $aliases) as $one_alias)
					if ($key == $one_alias):
						$output["$option"] = $value;
						break 2;
					endif;
	else:
		unset($output);
		$output = "\n\r";
		foreach ($alias as $option => $aliases):
			foreach (explode(" ", $aliases) as $one_alias)
				$output .= " -" . $one_alias;
			$output .= ":\t " . $help["$option"] . "\n\r";
		endforeach;
		$output .= " -h -help -?:\t " . "Show this help" . "\n\r";
	endif;
	unset($HELP);
	unset($help);
	unset($alias);
	unset($option);
	unset($one_alias);
	unset($aliases);
	unset($key);
	unset($value);
	unset($input);
			
	return $output;
}
?>