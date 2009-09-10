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
    
    public function theme(){
        if( is_numeric( $this->arguments[0] ) ){
            $posts = $this->fagent->getMessagesByTheme( $this->arguments[0] );
            $info = $this->fagent->getThemeInfo( $this->arguments[0] );
            var_dump( $info );
            T::assign( "posts", $posts );
            T::assign( "info", $info );
        }
    }

    public function test( $a ){
        echo qqqqqqqqqqqq;
        var_dump( $a );
    }
}
?>
