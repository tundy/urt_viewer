<meta charset="UTF-8">
<style>
*
	{
	border: 0px;
	}

</style>
<link rel="stylesheet" href="status.css">

<body bgcolor='93, 8, 5' text='white'>

<?php //>

include_once('argopt.php');
include_once('text2table.php');
include_once('serverinfo.php');

//server_info("-help");
server_info("-ip server.urtsk.eu", "-b", "-s 5 -m 0");
//server_info("-i 37.157.196.35 -p 27962 -m 2 -b");

#include './master.php';
/*
echo '<pre>';
print_r(get_defined_vars());
echo '</pre>';
*/
//<?php ?>