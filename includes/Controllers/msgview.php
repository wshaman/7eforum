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

    public function test( $a ){
        echo qqqqqqqqqqqq;
        var_dump( $a );
    }
}
?>
