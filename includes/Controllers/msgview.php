<?php
class Msgview extends Controller {
    var $table = "posts";
    var $table2 = "themes";
    var $table3 = "part";
    protected static $class = __CLASS__;

    public function test( $a ){
        echo qqqqqqqqqqqq;
        var_dump( $a );
    }
}
$msgview = new Msgview();
?>
