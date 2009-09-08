<?php

class sql_db {
	var $db_connect_id;
	var $query_result;
	var $row = array();
	var $rowset = array();
	var $num_queries = 0;
	var $total_time_db = 0;
	var $time_query = "";
	
	
	function sql_db( $sqlserver, $sqluser, $sqlpassword, $database, $persistency = true ) {
        global $E;
		$this->db_connect_id = ( $persistency ) ? mysql_pconnect( $sqlserver, $sqluser, $sqlpassword ) :
			mysql_connect( $sqlserver, $sqluser, $sqlpassword );
		if ( $this->db_connect_id ) {
			if ( $database != "" && !mysql_select_db( $database ) ) {
				mysql_close( $this->db_connect_id );
				$this->db_connect_id = false;
			}
	$active_db = $this->db_connect_id;
	mysql_query( 'SET NAMES utf8' );
			return $this->db_connect_id;
		} else {
			$E->setError( "SQL Connection failed", E_CRIT );
			return false;
		}
	}

	function sql_close() {
		if ($this->db_connect_id) {
			if ($this->query_result) mysql_free_result($this->query_result);
			$result = mysql_close($this->db_connect_id);
			return $result;
		} else {
			return false;
		}
	}

	function sql_query($query = "", $transaction = false) {
		global $E;
		unset( $this->query_result );
		if ( $query != "" ) {
			$tdba = explode( " ", microtime( ) );
			$tdba = $tdba[1] + $tdba[0];
			$this->query_result = mysql_query( $query, $this->db_connect_id );
			if( !$this->query_result ){
				$E->setError('<div style="text-align:center;"><b>SQL::error</b></br>'.mysql_errno().' : '.mysql_error().'<br/> in ('.$query.')</div>' );
				return ERRCODE;
			}
			
			$tdbe = explode(" ", microtime());
			$tdbe = $tdbe[1] + $tdbe[0];
			$total_tdb = ($tdbe - $tdba);
			$this->total_time_db += $total_tdb;
			$this->time_query .= "".substr($total_tdb, 0, 10) > 0.01."" ? "<font color=\"red\"><b>".substr($total_tdb, 0, 10)."</b></font> "._SEC.". - [".$query."]<br /><br />" : "<font color=\"green\"><b>".substr($total_tdb, 0, 10)."</b></font> "._SEC.". - [".$query."]<br /><br />";
		}
		if ( $this->query_result ) {
			$this->num_queries += 1;
			unset( $this->row[$this->query_result] );
			unset( $this->rowset[$this->query_result] );
//		var_dump( $this->query_result );
		if ( !is_bool($this->query_result) && ( mysql_num_rows( $this->query_result ) > 0 ) ) {
			$rows = array();
			while($row_ = mysql_fetch_array( $this->query_result, MYSQL_ASSOC ) )
				$rows[] = $row_;
			return $rows;
		} else {
			return false;
		}
		
		
			return $this->query_result;
		} else {
			return ( $transaction == END_TRANSACTION ) ? true : false;
		}
	}
	
	function sql_numrows( $query_id = 0 ) {
		if ( !$query_id ) $query_id = $this->query_result;
		if ( $query_id ) {
			$result = mysql_num_rows( $query_id );
			return $result;
		} else {
			return false;
		}
	}


	function sql_fetchrow( $query_id = 0 ) {
		if ( !$query_id ) $query_id = $this->query_result;
		if ( $query_id ) {
			$this->row[$query_id] = mysql_fetch_array( $query_id );
			return $this->row[$query_id];
		} else {
			return false;
		}
	}


	function sql_error( $query_id = 0 ) {
		$result["message"] = mysql_error( $this->db_connect_id );
		$result["code"] = mysql_errno( $this->db_connect_id );
		return $result;
	}
}

$db = new sql_db($dbhost, $dbuname, $dbpass, $dbname, false);
if ( !$db->db_connect_id )
	$E->setError( "There seems to be a problem with the MySQL server, sorry for the inconvenience.", E_CRIT );
?>
