<?php
require_once( CLASSES."Actor.php" );
//! Class descrybing upper-level functionality of Actor. 
/*!
    All methods are inherited from parent class Actor.
    All Actors( Administrators and Viewers are described as User class ).
    GLOBAL $U is used through all the system as instance of User class
*/
class User extends Actor{
	private $plainpass;
	public $hashpass;
//!    Use this function to save user data just encrypting password
	public function save( $d = null ){
		global $E;
		if( is_null( $d ) ) {
			$E->setError( "Can't save empty user" );
			//TODO: implement current user modifying on this
			return ERRCODE;
		}
 		$d["cred"] = $this->hashpass($data["name"].microtime() );
		$d["pass"] = $this->hashpass( $d["pass"] );
		Actor::save( $d );
	}

}
$U = new User();

?>
