<?php
class Parts extends Controller{
    var $table = "parts";

    function admin_view(){
        $list = $this->getAll();
//        var_dump( $list );die();
        T::assign( "list", $list );
    }
    function admin_edit(){
        $id = ( is_numeric( $this->arguments[0] ) ) ? $this->arguments[0] : 0;
        if( $id > 0 ){
            $data = $this->getOne( (int)$id );
            T::assign( "part", $data );
        }
    }
    function admin_save( $data=NULL ){
//        $this->data = ( is_null( $data ) || empty( $data ) ) ? $_POST : $data;
        $this->data =  $_POST;
        DBData::save();
//`        $this->showTemplate=false;
        $this->redirect( "view" );        
    }
}
$parts = new Parts();
?>
