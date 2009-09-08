<?php
define( "E_WARN", 0 );
define( "E_CRIT", 1 );
/*!
 Store and display given errors messages
 */
class Error {
	private $errors;
	function __construct(){
		$this->errors = array();
	}

//! Store error message     
	public function setError( $text, $e_flag = E_WARN ){
		$this->errors[] = $text;
		if( $e_flag == E_CRIT ) $this->flushErrors();
	}

//! Show all stored errors and die
	public function flushErrors(){
		die( $this->msg() );
	}

//! Returns all errors
	public function getErrors(){		
		return( $this->msg() );		
	}

//! Represent all errors as string
	private function msg(){
		return implode( "<br/>", $this->errors );
	}
}
$E = new Error();
?>
