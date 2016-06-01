<?php

DEFINE ('DB_USER','root');
DEFINE ('DE_PASSWORD', '');
DEFINE ('DB_HOST', 'localhost');
DEFINE ('DB_NAME', 'moodle');

$dbc = @mysqli_connect(DB_HOST,DB_USER,DE_PASSWORD,DB_NAME) OR die('could not connect to the database '.msqli_connect_error());

?>