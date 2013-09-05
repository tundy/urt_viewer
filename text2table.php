<?php //?
function text2table ($string)
{
	$string = nl2br($string);
	$string = str_replace ("<br />", "<br>", $string);
	$string = str_replace ("<br/>", "<br>", $string);
	$string = str_replace ("<br>", "</td></tr><tr><td>", $string);
	$string = str_replace ("\t", "</td><td>", $string);
	$string = "<table style='text-align: left;'><tr><td>" . $string . "</td></tr></table>";
	$string = str_replace ("<tr><td></td></tr>", "", $string);
	$string = str_replace ("<td></td>", "", $string);
	$string = str_replace ("<tr></tr>", "", $string);
	return $string;
}
?>