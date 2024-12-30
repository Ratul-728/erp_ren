<?php

session_start();


	if($_REQUEST['code'] == '302662'){
	
	echo '<pre>'.print_r($_SESSION, true).'</pre>';

		echo "The time is ". date("h:i:sa");
	}
?>