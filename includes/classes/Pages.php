<?php
/*! Class creating views for pages
 */
class Pages {
	private $url;
	private $page;
	private $args;
	private $db;
	
	function __construct(){
		global $db;
		$this->db = $db;
	}
//!Builds a top menu with login/logout functionality	
	private function makeTopMenu(){
		$logedin = ( isset( $_SESSION[PROJECT]["id"] ) && ( $_SESSION[PROJECT]["id"] > 0 ) ) ? TRUE : FALSE;
#		if( $logedin ){
#			$text = "Welcome, {$_SESSION[PROJECT]["name"]}! <a href=\"".BASEURL."logout\">logout</a>";
#		} else {
#			$text = "Welcome, Guest! <a href=\"".BASEURL."login\">login</a>";
#		}
        T::assign( "USER_OK", $logedin );
        T::assign( "PROJ_S", $_SESSION[PROJECT] );
        $text = T::fetch( TEMPLATES."userbar.smarty" );
		T::assign( "login_top", $text );
	}
//! Returns template for requested page
	private function showPage( ){
		global $E;
		if ( empty( $this->page ) ) $this->page="index";
		T::display( TEMPLATES."menu.smarty" );
        T::setTitle( $this->page ); 
		if( is_file( PAGES."{$this->page}.smarty" ) ){
			T::display( PAGES."{$this->page}.smarty" );
		} else { $E->setError( "Template not found: '".PAGES."{$this->page}.smarty' " ); }
	}
//! Main function displaying any of displayable controller's method	
	public function process( $url ){
		global $E;
		global $C;
		ob_start();		
		global $U;
		$this->url = explode( '?', $url, 2 );
		$this->args = $this->url[1];
		$this->url = explode( '/', $this->url[0] );
		$this->page = strtolower($this->url[0]);
		switch( $this->page ){
			case "logout":
				$U->logout();
				$this->page = '';
				$this->showPage( );
				break;
			case "catalogue":
				T::assignJS("transajax.js");
				T::assign( "top_level", $C->getTop() );
				$this->showPage( );
				break;
			default:
            T::assign( "top_level", $C->getTop() );
            $this->showPage( ); 
		}
		$this->makeTopMenu();
		T::assign( "content", ob_get_contents() );
		ob_clean();
		T::assign( "errors", $E->getErrors() );	
		T::display( "top.smarty" );
		T::display( "body.smarty" );
		T::display( "bottom.smarty" );		
	}
}
$P = new Pages();
?>
