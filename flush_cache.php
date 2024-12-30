<?php
ini_set('display_errors',1);
if(opcache_reset()){
echo "OK";
}else{
	echo "NOT OK";
}
?>
