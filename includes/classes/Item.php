<?php
/*!
 *  Class Item is a contoller class.
 *  Class Item represents "item" in UC.
 *  It has some parameters inherited from "category" which can be filled with values.
 */
class Item extends DBData implements serialFieldDB{
	protected $table = "Items";
	protected $sfield = "fields";					
	protected $data;
	//protected $keyfield = "id";
	protected $id;
	
//! Meta. Return a form with set up "parent" for adding a new Item. Refer to edit().
	public function create( $parent ){
        $p =  ( is_array( $parent ) ) ? $parent["parent"] : $parent  ;
		$this->data = array( "parent" => $p );
		return $this->edit( 0 );
	}

//! Stores array of properties as a string and calls ::save(); Refer to DBData for save() function;
	public function save( $d = null ){
		if( ! is_null( $d ) )  $this->data = $d;
		$this->storeSfield();
		DBData::save( $this->data );
	}

//! Returns a form to view an "item"
	public function view( $id = NULL ){
		$this->ar2id( $id );
		if( $this->id == 0 ) return ERRCODE;
		$this->prepareMyData();
		return T::fetch( PAGES."view_item.smarty" );			
	}

//! Return a form to edit an "item"
	public function edit( $id = NULL ){
		$this->ar2id( $id );
		if( is_null( $this->id ) ) return ERRCODE;
		$this->prepareMyData();
		return T::fetch( PAGES."edit_item.smarty" );	
	}

//! Get info about "item" and set required SMARTY varaibles.
	protected function prepareMyData(){
		global $C;
        if( $this->id > 0 ){
            $this->get( "`id`={$this->id}" );
            $this->unstoreSfield();
        }
		$C->setID( $this->data['parent'] );
		$C->get( "`id`={$this->data['parent']}" );
		$C->unstoreSfield();
        $p = $C->getData();
        if( is_array( $p ) ){
			T::assign( "parent", $p["name"] );
			$fieldlist=array();
            if( is_array( $p["fields"] ) )
                foreach( $p["fields"] as $key => $item ){
                    $fieldlist[] = array( "cnt" => $key, "name" => $item, "value"=> $this->data[$this->sfield][$key] );
                }
			T::assign( "fieldlist", $fieldlist );
        }
		T::assign( "f", $this->sfield );
		T::assign( "item",$this->data );		
	}

}
$I = new Item();
?>
