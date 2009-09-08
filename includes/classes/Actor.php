<?php
//!  An abstract Actor class. 
/*!
    Plays as base class for all Actorsi/Users and Admin/.
*/

abstract class Actor extends DBData{
	public $name;
	public $rights;
	protected $db;
	
	function __construct(){
		DBData::setTable( 'Users' );
		DBData::__construct();
	}
	
/*	function save( $d = null){
		DBData::save( $d );
	}
	*/
//! Checking credentials function
	function login( $name, $pass, $plain=TRUE ){
		global $E;		
		$pass = ( $plain ) ? $this->hashpass( $pass ) : $pass;
		//$r = $this->db->sql_query( "SELECT * FROM `Users` WHERE `login`='{$name}' AND `pass`='{$pass}'" );
		$r = $this->get( "`login`='{$name}' AND `pass`='{$pass}'" );
		$this->name = $r["name"];
		$_SESSION[PROJECT]["name"] = $this->name;
		$_SESSION[PROJECT]["login"] = $r["login"];
		$_SESSION[PROJECT]["id"] = $r["id"];
		$_SESSION[PROJECT]["mt"] = sha1( microtime() );
		$_SESSION[PROJECT]["SID"] = $this->countSessionID( $r["cred"] );
		return $r;
	}
//! Set secure info to check Actor rights	
	function countSessionID( $r ){
		return sha1( $_SESSION[PROJECT]["mt"].$r );
	}
//! Unset "Actor present in a system"
	function logout(){
		if( isset( $_SESSION[PROJECT] ) )
			unset( $_SESSION[PROJECT] );
	}
//! Double hash password /and anything else/ function
	function hashpass( $plain ){
		return sha1( md5( $plain ) );
	}
//! Check if curent Actor has permission to edit data. A.w. if Actor is an administrator
	public function checkRights(){
		if( !isset( $_SESSION[PROJECT] ) || $_SESSION[PROJECT]["id"] == 0 ) return ERRCODE;
		$r = $this->get( "`id`={$_SESSION[PROJECT]["id"]}" );
		if( !is_array( $r ) ) return ERRCODE;
		$r = $r;
		/*Check if rights are correct*/
		if( $_SESSION[PROJECT]["SID"] == $this->countSessionID( $r["cred"] ) )
			return $r["rights"];
		else
			return ERRBROKENPERM;
	}
}
?>
