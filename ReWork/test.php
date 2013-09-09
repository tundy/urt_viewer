<?php

echo('<link rel="stylesheet" href="status.css">'."\r\n");

require_once("functions.php");
status("localhost", 27960, 0, 5);
status("server.urtsk.eu", 27960, 0, 5);

?>