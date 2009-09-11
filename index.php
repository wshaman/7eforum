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
        if( $mover[0] == "admin" ){
            //TODO: Make admin login
            if( !$user->isAdmin() ){
                $E->setError( "Только администратор может смотреть эту страницу" );
                $admin = false;
                $controller = "msgview"; 
                $args = array( "index" );
                $mover[0]= '';
            } else {
                $admin = true;
                $e= array_shift( $mover );
            }
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
?>
