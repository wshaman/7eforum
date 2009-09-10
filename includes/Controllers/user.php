<?php
//! Class descrybing upper-level functionality of Actor. 
/*!
    All methods are inherited from parent class Actor.
    All Actors( Administrators and Viewers are described as User class ).
    GLOBAL $U is used through all the system as instance of User class
*/
class UserController extends Controller{
    var $table="users";
    
    function login(){
        $this->checkLogin( $_POST["login"], $_POST["pass"], true );
    }

//! Checking credentials function
	function checkLogin( $name, $pass, $plain=TRUE ){
		global $E;		
		$pass = ( $plain ) ? $this->hashpass( $pass ) : $pass;
		$r = $this->get( "`login`='{$name}' AND `pass`='{$pass}'" );
        if( isset( $r[$this->keyfield] ) ) {
            $this->name = $r["name"];
            $_SESSION[PROJECT]["name"] = $this->name;
            $_SESSION[PROJECT]["login"] = $r["login"];
            $_SESSION[PROJECT]["id"] = $r["id"];
            $_SESSION[PROJECT]["mt"] = sha1( microtime() );
            $_SESSION[PROJECT]["SID"] = $this->countSessionID( $r["cred"] );
            T::assign( "MSG_LOGIN", "Login successful!" );
            return $r;
        } else {
            T::assign( "MSG_LOGIN", "Login failed!" );
            return false;
        }
	}

    function register(){

    }

    function register2(){
        var_dump( $_POST );
        $errmsgs = array();
        if( empty( $_POST["login"] ) ) $errmsgs[] = "Не указан логин для входа";
        if( empty( $_POST["pass"] ) ) $errmsgs[] = "Не указан пароль для входа";
        if( empty( $_POST["pasr"] ) || ( $_POST["pasr"] != $_POST["pass"] ) ) $errmsgs[] = "Пароль и подтверждение не совпадают";
        if( count( $errmsgs ) > 0 ){
            T::assign( "err_msg", implode( "<br/>", $errmsgs ) );
        } else {
            global $user;
            $this->data = $_POST;
            $this->data["pass"] = $this->hashpass( $this->data["pass"] );
            $this->data["cred"] = $this->hashpass( $this->data["name"].microtime() );
            unset( $this->data["pasr"] );
            $this->save();
        }
    }

//! Set secure info to check Actor rights	
	function countSessionID( $r ){
		return sha1( $_SESSION[PROJECT]["mt"].$r );
	}
//! Unset "Actor present in a system"
	function logout(){
		if( isset( $_SESSION[PROJECT] ) )
			unset( $_SESSION[PROJECT] );
        redirect( '/' );
	}
//! Double hash password /and anything else/ function
	function hashpass( $plain ){
		return sha1( md5( $plain ) );
	}
//! Check if user is loged in and returns user's name
    public function isLoged(){
		if( isset( $_SESSION[PROJECT]["name"] ) ) return $_SESSION[PROJECT]["name"];
        else return false;
        
    }
//! returns current user's ID.
/*!
 * TODO: Strongly recomend to rewrite this function with using special field "cred"
 */
    public function getMyID(){
		if( !isset( $_SESSION[PROJECT] ) || $_SESSION[PROJECT]["id"] == 0 ) return ERRCODE;
        return $_SESSION[PROJECT]["id"];

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
