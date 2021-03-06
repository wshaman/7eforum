<?php
/*! TODO: Make load-on-request for controllers
*/

	global $db;
	global $E;

    $dbhost = "localhost";
    $dbuname = "root";
    $dbpass = "root";
    $dbname = "db019";
	define( "PROJECT", "Forum");
	define( "PROJECT_CAPTION", "Форум");

	session_start();
	session_name( PROJECT );
	setcookie(session_name(), session_id( ), time()+3600*24, "/");

	define( "ERRBROKENPERM", -2 );
	define( "ERRCODE", -1 );
	define( "NOERR", 0 );
	define( "FULL_ACCESS", 1 );
	define( "BASEURL", "/" );
	define( "BASEDIR", dirname(dirname(__FILE__))."/" );		
	define( "INCLUDES", BASEDIR."includes/" );
	define( "UPLOADS", BASEDIR."uploads/" );
	define( "IMAGES", BASEDIR."img/" );
	define( "SMARTY", INCLUDES."smarty/" );
	define( "AJAX", INCLUDES."ajax/" );
	define( "JS", BASEDIR."js/" );
	define( "CSS", BASEDIR."styles/" );
	define( "TEMPLATES", BASEDIR."templates/" );
	define( "TEMPLATES_C", BASEDIR."templates_c/" );
	define( "CLASSES", INCLUDES."classes/" );
	define( "CONTROLLERS", INCLUDES."Controllers/" );
	define( "INTERFACES", INCLUDES."interfaces/" );
	define( "PAGES", TEMPLATES."pages/" );

	define( "FULLURL", ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 'https' : 'http')."://".$_SERVER['HTTP_HOST'].BASEURL );
	define( "IMAGES_URL", FULLURL."img/" );
	define( "UPLOADS_URL", BASEURL."uploads/" );
	define( "JS_URL", BASEURL."js/" );
	define( "CSS_URL", BASEURL."styles/" );
		
	require_once( CLASSES."Error.php" );
	require_once( INCLUDES."functions.php" );
	require_once( INCLUDES."mysql.php" );
	require_once( "template.php" );
	require_once( INTERFACES."main.php" );
	require_once( CLASSES."DBData.php" );
	require_once( INCLUDES."fagent.php" );

    $contr = scandir( CONTROLLERS );
    $controllerlist = array( );
	require_once( CONTROLLERS."Controller.php" );
    if( $contr ){
        foreach( $contr as $c ){
            if ( ( strtolower( $c ) != "controller.php" ) && ( preg_match( "/^[^\.][\d\w]+\.php$/i", $c ) ) ){
                require_once( CONTROLLERS."$c" );
                $cs=preg_replace( "/\.php$/i","",$c );
                global $$cs;
                $contname =  camelize($cs)."Controller"; 
                $$cs = new $contname();
                $controllerlist[] = $cs;
            }
        }
    }

	T::init( SMARTY, TEMPLATES, TEMPLATES_C, false );
	T::assign( "IMAGES", IMAGES_URL );
	T::assign( "PROJECT", PROJECT );
	T::assign( "FULLURL", FULLURL );
	T::assign( "TEMPLATES", TEMPLATES );
    $f = $user->isAdmin();
$f = $user->getMyID();
?>
