<?php
/*!
 * Main file. Call simple pages, init page controllers.
 * Accept redirections from .htaccss file
 */
    require_once( "includes/config.php" );
	T::assign( "title", "");
	T::assignCSS( "main.css" );
    $controller = NULL;
    $args = NULL;
    $admin = false;
    if( isset( $_REQUEST["url"] ) ){
        $mover = explode( '/', $_REQUEST["url"] );
//        var_dump( $mover );
//       var_dump( $controllerlist );
        if( $mover[0] == "admin" ){
            //TODO: Make admin login
            $admin = true;
            $e= array_shift( $mover );
        }
        if( in_array( $mover[0], $controllerlist ) ) {
            $controller = array_shift( $mover );
            $args = $mover;
        }
        else die( "wrong link" );
    } else {
        $controller = "msgview"; 
        $args = array( "index" );
    }
    $$controller->run( $args, $admin );
    echo $E->getErrors()
/*	if ( isset( $_POST["login"] ) && isset( $_POST["pass"] ) )
	$U->login( $_POST["login"], $_POST["pass"], TRUE );
	$P->process( $_REQUEST["url"] );*/
?>
