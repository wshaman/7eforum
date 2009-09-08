<?php
interface DBObject {
	function Save( $d = null );
	function insert();
	function update();
}
interface serialFieldDB extends DBObject{
	function get( $where = NULL, $table=NULL );
	function edit( $id = 0 );
	function create( $parent );
/*	function getTop();
	function getBranch( $id );
	function buildTree();*/
//	function getFields();
//	function setFields();
}
?>