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

    public function theme(){
        if( is_numeric( $this->arguments[0] ) ){
            if( isset( $this->arguments[1] ) && ( is_numeric( $this->arguments[1] ) ) )
                $page = $this->arguments[1];
            else $page = 1;
            $posts = $this->fagent->getMessagesByTheme( $this->arguments[0], $page );
            $pages = $this->fagent->getTotalPages();
            $info = $this->fagent->getThemeInfo( $this->arguments[0] );
//            var_dump( $pages );die;
            T::assign( "posts", $posts );
            T::assign( "info", $info );
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
            echo "Сначала нужно войти на форум( панель сверху ) или <a>зарегистрироваться!</a>";
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
