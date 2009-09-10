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
        var_dump( $this->arguments );    
        $old_post = $this->fagent->getPost( $this->arguments[0] );
        var_dump( $old_post );
        T::assign( "post", $old_post );
        if( isset( $this->arguments[1] ) && ( $this->arguments[1] == "quot" ) ) T::assign( "with_quote", true );
    }

    public function theme(){
        if( is_numeric( $this->arguments[0] ) ){
            $posts = $this->fagent->getMessagesByTheme( $this->arguments[0] );
            $info = $this->fagent->getThemeInfo( $this->arguments[0] );
            var_dump( $info );
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
        echo "Ваше сообщение сохранено";
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
