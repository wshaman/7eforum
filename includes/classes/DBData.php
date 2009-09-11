<?php
define( "CANTSAVE", "Wrong data supported");
/*!
 Main class to control tables in DB.
 Refer to /includes/mysql.php to find out mysql connection
 DB credentials set in /class/config.php
*/
class DBData implements DBObject{
	protected $table;
	protected $keyfield = "id";	
	protected $data = NULL;
	protected $slist = "_list";
	protected $id;
	private $fields = "*"; //TODO: implement setFields()
	protected $db;
	protected $order = NULL;
    private $returnOne = 0; //Last Query Length
    private $lql = TRUE;
    protected $ljoin = array(); //Array of left joins. 
    private $page = 0;
    private $usePager = false;
    private $ppp = 10;  // Posts per Page;
    private $pages_total = 0; 
	
	function __construct(){
		global $db;
		$this->db = $db;
	} 

    function setLeftJoins( $table, $field_my, $field_there, $array_fields ){
        $this->ljoin[] = array( "table"=>$table, "field_my"=>$field_my, "field_there"=>$field_there, "array_fields" => $array_fields );
    }

    public function enablePager( $yes = true ){
        $this->usePager = $yes;
    }

//! Set up ID. Used in cforeign calls.
	public function setID( $id ){
	  $this->id = $id;
	}

//! Set private $table in foreign calls
	public function setTable( $table ){
		$this->table = $table;		
	}

    public function setPPP( $ppp ){
        $this->ppp = $ppp;
    }

//! Set private $data in foreign calls
	public function setData( $data ){
		$this->data = $data;		
	}

//! Delets a record. Returns mysql_result	
	public function delete( $id = NULL ){
		global $E;
		$this->ar2id( $id );
		if( is_null( $this->id ) ) $E->setError( CANTSAVE, E_CRIT );
		$sql = "DELETE FROM `{$this->table}` WHERE `{$this->keyfield}`={$this->id}";
//        var_dump( $sql );
		return $this->db->sql_query( $sql );
	}

//! Sets a page number $page in query;
    public function setPage( $page ){
        $this->page = $page;
    }


//! Make a simple SELECT
/*!
    Accepts first parameter as WHERE clause, second as a table name.
    If omited $where and $this->id set, uses ID in WHERE CLAUSE
    If got 1 row as result, returns simple array.
*/
	public function get( $where = NULL/*, $table=NULL*/ ){
		//$table = ( is_null( $table ) ) ? $this->table : $table; 
		$table = $this->table;
		if ( is_numeric( $where ) )  
		  $where = " WHERE `{$table}`.`{$this->keyfield}`={$where} ";
        else {
            $where = ( !is_null( $where ) ) ? " WHERE {$where} " : '';
            if ( empty( $where ) && isset( $this->id ) && ( $this->id > 0 ) ) 
              $where = " WHERE `{$table}`.`{$this->keyfield}`={$this->id} ";
        }
        $order = ( is_null( $this->order ) ) ? '': " ORDER BY `".$this->order."` ";
        $joins='';
        $ljfields = array();
        foreach( $this->ljoin as $item ){
            $f = array();
            foreach( $item["array_fields"] as $field ){
                $f []= " {$item["table"]}.{$field} AS {$item["table"]}_{$field} ";
            }
            $f = implode( ',', $f );
            $ljfields[] = $f;
            $joins .=" LEFT JOIN {$item["table"]} ON {$table}.{$item["field_my"]}={$item["table"]}.{$item["field_there"]} ";
        }
        $ljfields = implode( ',', $ljfields );
        $fields = ( $ljfields == '' ) ? "{$table}.{$this->fields}" : "{$table}.{$this->fields}, $ljfields";
		$sql = "SELECT {$fields} FROM `{$table}` {$joins} {$where} ".$order;
   //     var_dump( $sql );
		$d =   $this->db->sql_query( $sql );
        $this->lql = count( $d );
        if( ( $this->usePager ) && ( $this->lql > $this->ppp ) ){
            $this->totalPages = (int)ceil( $this->lql / $this->ppp );
            $limit = " LIMIT ".($this->page -1)*$this->ppp.",{$this->ppp} ";
            $sql .= $limit;
            $d = $this->db->sql_query( $sql );
        }
        if( ( $this->returnOne ) && ( count( $d ) == 1  ) ) $d = $d[0];
		if( is_null( $this->data ) ) $this->data = $d;
		return $d;
	}
//! Returns total pages count for last query.
    public function getTotalPages(){
        return $this->totalPages;
    }

//! Meta. Force get to rerun 1 row
	public function getOne( $where = NULL, $table=NULL ){
        $this->returnOne = TRUE;
        $res = $this->get( $where, $table );
        return $res;
    }

//! Meta. Force get to rerun all rows
	public function getAll( $where = NULL, $table=NULL ){
        $this->returnOne = FALSE;
        $res = $this->get( $where, $table );
        return $res;
    }

//! Meta. Saves given data or $this->data. Returns mysql_result
/*!
    If keyfield in given data is not empty, use it as a key and call update() and call insert() otherwise
*/
	public function save( $d = null ){
		global $E;
		if( !is_null( $d ) && is_array( $d ) ) $this->data = $d;
		if( !is_array ( $this->data ) ) {$E->setError( CANTSAVE. " table: ".$this->table ); return ERRCODE; };
		if(isset( $this->data[$this->keyfield]) and $this->data[$this->keyfield] > 0 ){
			return $this->update();
		} else {
			return $this->insert();
		}
	}

//! Makes an Insert SQL query from given data for 1 record.
	public function insert(){
		global $E;
		$flds = array();
		$vals = array();
        var_dump( $this->data );
		foreach ( $this->data as $index => $item ){
			if( (!empty( $index ) ) && ( !empty( $item ) ) ){
				if( $index != $this->keyfield)
					$flds[] = '`'.$index.'`';
					$vals[] = "'$item'";
			}
		}
        var_dump( $vals );
        echo "<br>sss<br>";
		if( count( $flds ) > 0){
			$flds = implode( ',', $flds );
			$vals = implode( ',', $vals ); 
            var_dump( $vals );
			$sql = "INSERT INTO `{$this->table}` (".$flds.") VALUES ( ".$vals." )";
			return $this->db->sql_query( $sql );
		} else {
			$E->setError( CANTSAVE." for insert");
			return ERRCODE;
		}
	}
	
//! Makes an Update SQL query from given data for 1 record. Uses $keyfield as a key
	public function update(){
		global $db;
		global $E;
		$flds = array();
		$vals = array();
		foreach ( $this->data as $index => $item ){
			if( (!empty( $index ) ) && ( !empty( $item ) ) ){
				if( $index != $this->keyfield)
					$flds[] = "$index".'="'.$item.'"';
			}
		}
		if( count( $flds ) > 0){
			$fldlist = mb_implode( ',', $flds );
			$sql = "UPDATE ".$this->table." SET ".$fldlist." WHERE {$this->keyfield}=".$this->data[$this->keyfield];
			return $db->sql_query( $sql );
		} else{
			$E->setError( CANTSAVE." for update");
			return ERRCODE;
		}
	}
	
	//Helpful functions
//! Store array as a string in MySQL. Own replace for serialize() to avoid quotation problem
	protected function storeSfield( $delimeter = ';' ){
		$d = ( is_array( $this->data[$this->sfield] ) ) ? $this->data[$this->sfield] : explode( $delimeter, $this->data[$this->sfield] );
		if( is_array( $d ) )
			foreach( $d as $k => $i )
			  $d[$k] = base64_encode( htmlspecialchars( $i ) );
		//		$this->data[$this->sfield] = mysql_escape_string( serialize( $d ) );
		$this->data[$this->sfield] = mysql_escape_string( implode( '::', $d ) );
	}
	
//! Rstore array from string. unserialize() replacement
	protected function unstoreSfield( $delimeter = ';' ){
 		$d =  explode( '::', $this->data[$this->sfield] );
		unset( $this->data[$this->sfield] );
		$this->data[$this->sfield] = array();
		foreach( $d as $k => $i ){
		  $this->data[$this->sfield][] = base64_decode( $i );
		}
		$this->data[$this->sfield.$this->slist] =  implode( $delimeter, $this->data[$this->sfield] );
	}
//! Return value of $keyfield in given array if array and given value otherwise	
	protected function ar2id( $id, $setid = true ){
		if( is_array( $id ) ) $id = $id[$this->keyfield];
		if( $setid ) $this->id = $id;
		else return $id;
	}
}
?>
