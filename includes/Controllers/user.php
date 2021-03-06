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
		$r = $this->getOne( "`login`='{$name}' AND `pass`='{$pass}'" );
//        var_dump( $r, $this->keyfield );die;
        if( isset( $r[$this->keyfield] ) ) {
            $this->name = $r["name"];
            $_SESSION[PROJECT]["name"] = $this->name;
            $_SESSION[PROJECT]["login"] = $r["login"];
            $_SESSION[PROJECT]["id"] = $r["id"];
            $_SESSION[PROJECT]["mt"] = sha1( microtime() );
            $_SESSION[PROJECT]["SID"] = $this->countSessionID( $r["cred"] );
            $_SESSION[PROJECT]["user_conf"]["ppp"] = $r["conf_ppp"];
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
//        var_dump( $_POST );
        $errmsgs = array();
        if( empty( $_POST["login"] ) ) $errmsgs[] = "Не указан логин для входа";
	if( !isset( $_POST["register"] ) ){
	        if( empty( $_POST["pass"] ) ) $errmsgs[] = "Не указан пароль для входа";
        	if( empty( $_POST["pasr"] ) || ( $_POST["pasr"] != $_POST["pass"] ) ) $errmsgs[] = "Пароль и подтверждение не совпадают";
	} else {
		if( empty( $_POST["pass"] ) ) unset( $_POST["pass"] );
		else
			if( $_POST["pasr"] != $_POST["pass"] ) $errmsgs[] = "Пароль и подтверждение не совпадают";
	}
	if( isset( $_POST["conf_ppp"] ) && (!is_numeric( $_POST["conf_ppp"] ) ) ) $errmsgs[] = "Число сообщений должно быть.... ЧИСЛОМ!";
        if( count( $errmsgs ) > 0 ){
            T::assign( "err_msg", implode( "<br/>", $errmsgs ) );
        } else {
            global $user;
            $this->data = $_POST;
//var_dump( $this->data ); die;
            if( isset( $this->data["pass"] ) )$this->data["pass"] = $this->hashpass( $this->data["pass"] );
            $this->data["cred"] = $this->hashpass( $this->data["name"].microtime() );
            if( isset( $this->data["pasr"] ) ) unset( $this->data["pasr"] );
	if( isset( $this->data["register"] ) ) unset( $this->data["register"] );
            $this->save();
		if( isset( $_POST["register"] ) ){
			T::assign( "empty_display", "Для применения изменений необходимо <a href=\"".FULLURL."user/logout\">перелогиниться</a>" );
			$this->showTemplate = false;
		}
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

    public function cabinet(){
        if( !$this->isLoged() ){ 
            //echo "Сначала нужно войти на форум( панель сверху ) или <a>зарегистрироваться!</a>";
            T::display( TEMPLATES."error_login.smarty" );
            $this->showTemplate = false;
            return false;
        }
        $r = $this->getOne( $this->getMyID() );
        T::assign( "user", $r );
    }


//! returns current user's ID.
/*!
 * TODO: Strongly recomend to rewrite this function with using special field "cred"
 */
    public function getMyID(){
	    if( !isset( $_SESSION[PROJECT] ) || $_SESSION[PROJECT]["id"] == 0 ) return ERRCODE;
	T::assign( "MYID", $_SESSION[PROJECT]["id"] );
        return $_SESSION[PROJECT]["id"];

    }

//! Return rigts level for current User. Use isAdmin() to check if User is an administrator
	public function checkRights(){
		if( !isset( $_SESSION[PROJECT] ) || $_SESSION[PROJECT]["id"] == 0 ) return ERRCODE;
		$r = $this->getOne( "`id`={$_SESSION[PROJECT]["id"]}" );
		if( !is_array( $r ) ) return ERRCODE;
		$r = $r;
		/*Check if rights are correct*/
		if( $_SESSION[PROJECT]["SID"] == $this->countSessionID( $r["cred"] ) )
			return $r["rights"];
		else
			return ERRBROKENPERM;
	}
//! Check if curent User is an administrator
    public function isAdmin(){
	$iadmin = $this->checkRights() == FULL_ACCESS;
	    T::assign( "IADMIN", $iadmin );
        return $iadmin;
    }
}
?>
