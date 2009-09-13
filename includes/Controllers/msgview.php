<?php
class MsgviewController extends Controller {
    var $table = "posts";
    var $table2 = "themes";
    var $table3 = "part";
    var $fagent;

    function __construct(){
        $this->fagent = new ForumAgent();
        parent::__construct();
    }

    public function index(){
        $p = $this->fagent->getPartsThemes();
        T::assign( "themes", $p );
    }
    
    function reply(){
        if( !$this->isLoged() ) return false;
        if( isset( $this->arguments[1] ) && ( $this->arguments[1] == "theme" ) ){
            T::assign( "with_quote", false );
            T::assign( "by_theme", true );
            $info = $this->fagent->getThemeInfo( $this->arguments[0] );
            T::assign( "post", array( "parent"=>0, "theme_id"=>$info["id"] ) );
            T::assign( "info", $info );
        } else {
            $old_post = $this->fagent->getPost( $this->arguments[0] );
            T::assign( "post", $old_post );
            if( isset( $this->arguments[1] ) && ( $this->arguments[1] == "quot" ) ) T::assign( "with_quote", true );
        }
}

   	public  function edit(){
		if( is_numeric( $this->arguments[0] ) ){
			$post = $this->getOne( $this->arguments[0] );
            global $user;
            if( ( $user->getMyID() != $post["user_id"] ) && !$user->isAdmin() ){
                global $E;
                $E->setError( "Editing not allowed" );
                T::assign( "post", "Editing not allowed" );
            } else {
                T::assign( "post", $post );
            }
		}
//		$this->redirect( "index" );
	}

	public  function delete(){
		if( is_numeric( $this->arguments[0] ) ){
			$r = $this->fagent->delete( $this->arguments[0] );
//TODO: Delete not allowed
			if( $r=="NOTALLOWED" ){}			
		}
		$this->redirect( "index" );
	}

    public function theme(){
        if( is_numeric( $this->arguments[0] ) ){
            if( isset( $this->arguments[1] ) && ( is_numeric( $this->arguments[1] ) ) )
                $page = $this->arguments[1];
            else $page = 1;
		global $user;
		$ia = $user->isAdmin();
		$mi = $user->getMyID();
            $posts = $this->fagent->getMessagesByTheme( $this->arguments[0], $page );
            $pages = $this->fagent->getTotalPages();
            $info = $this->fagent->getThemeInfo( $this->arguments[0] );
//            var_dump( $pages );die;
            T::assign( "posts", $posts );
            T::assign( "info", $info );
        }
    }

    public function tree(){
	T::assignJS( "transajax.js" );
	if( is_numeric( $this->arguments[0] ) ){
		T::assign( "theme_id", $this->arguments[0] );
		T::assign( "tree", $this->fagent->buildTree( $this->arguments[0] ) );
	}
    }

    public function savePost(){
        global $user;
        if( !$this->isLoged() ) return false;
        $this->showTemplate = false;
        $this->data = $_POST;
        $this->data["user_id"] = $user->getMyID();
        parent::save();
        echo "Ваше сообщение сохранено. <a href=\"".FULLURL."msgview/theme/{$this->data["theme_id"]}\">К теме</a>";
    }

    private function isLoged(){
        global $user;
        if( $user->isLoged() ){
            return true;
        } else {
//            echo "Сначала нужно войти на форум( панель сверху ) или <a>зарегистрироваться!</a>";
            T::display( TEMPLATES."error_login.smarty" );
            $this->showTemplate = false;
            return false;
        }
    }

    public function test( $a ){
        echo qqqqqqqqqqqq;
        var_dump( $a );
    }
}
?>
