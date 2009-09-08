<?php
/*!
 * Control AJAX requests from client.
 * Main file for "tree view" functionality
 */
	require_once( "includes/config.php" );
	$action = $_REQUEST["act"];
	switch ( $action ){
		case "Catalogue":
			$fid = ( isset( $_REQUEST["folder"] ) ) ? $_REQUEST["folder"] : 0;
			echo( $C->buildTree( $fid ) );
			break;
		case "Category" :
				$data = ( empty( $_POST) ) ? $_REQUEST : $_POST;
				echo ( $C->{$_REQUEST["mov"]}( $data ) );			
			break;			
		case "Item" :
				$data = ( empty( $_POST) ) ? $_REQUEST : $_POST;
				echo ( $I->{$_REQUEST["mov"]}( $data ) );
			break; 
		default:
			break;
	}
	
	echo $E->getErrors();    
?>
