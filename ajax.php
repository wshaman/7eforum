<?php
/*!
 * Control AJAX requests from client.
 */
	require_once( "includes/config.php" );
	if( isset( $_REQUEST["get_message"] ) ){
		$m = $msgview->getOne( $_REQUEST["get_message"] );
		echo $m["text"];
	} else 
		echo "Wrong request";
	echo $E->getErrors();    
?>
