<?php
class ThemesController extends Controller{
    var $table = "themes";

    function admin_view(){
        $list = $this->getAll();
        T::assign( "list", $list );
    }

    function index(){
        $this->redirect( "view" );      

    }

    function admin_edit(){
        $id = ( is_numeric( $this->arguments[0] ) ) ? $this->arguments[0] : 0;
        if( $id > 0 ){
            $data = $this->getOne( (int)$id );
            T::assign( "part", $data );
        }
    }
    
    function admin_add(){
        $part_id = ( is_numeric( $this->arguments[0] ) ) ? $this->arguments[0] : 1;
        T::assign( "part_id", $part_id );
    }
    
    function admin_delete(){
//        var_dump( $this->arguments[0] );die;
        DBData::delete( $this->arguments[0] );
        $this->redirect( "view" );
//        redirect( "/admin/parts/view" );
    }

    function admin_save( $data=NULL ){
//        $this->data = ( is_null( $data ) || empty( $data ) ) ? $_POST : $data;
        $this->data =  $_POST;
        DBData::save();
//        $this->showTemplate=false;
        $this->redirect( "view" );        
//        redirect( "/admin/parts/view" );
    }
}
?>
