<?php
class Structure {
  
  public function __construct($registry) {
		$this->db = $registry->get('db');
  }
  public function tables() {
       $query = $this->db->query("SHOW TABLES FROM `" . DB_DATABASE . "`");

        $table_list = array();
        foreach($query->rows as $key => $table){
        	foreach($table as $tb){
                      $table_list[] = $tb;
        	}
          }
        return $table_list;
  }

  public function columns( $table ) {
	$ret	= array();
        if( array_search( DB_PREFIX . $table, $this->tables() ) || $table == 'address'){
                $colums = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . $table . "`");

               foreach( $colums->rows as $field){
               	    foreach($field as $fd){
                        $ret[] = $fd;
               	    }
               }
         }
          return $ret;	
  }
   
  public function language() {
		 $sql = '
                SELECT 
                       *
                FROM  `' . DB_PREFIX . 'language`';

          $languages = $this->db->query($sql);
          
          return $languages->rows;	
  }
  
   public function hasLayout( $val ) {
 
	$sql = '
	SELECT
		*
	FROM
		`' . DB_PREFIX . 'layout_module`
	WHERE
		`code` LIKE  \'' . $val . '%\'';
       if( array_search( DB_PREFIX . 'layout_module' , $this->tables())){
					$result = $this->db->query( $sql );
					if( count( $result->row ) == 0 ) {
						return false;
					}
     } else {
				return false;
      }
	return true;
   }

   public function hasTotal() {
	$sql = '
	SELECT
		*
	FROM
		`' . DB_PREFIX . 'order_total`';
					$result = $this->db->query( $sql );
			if( count( $result->rows ) == 0 ) {
				return false;
			}
	return true;
   }

  public function hasOption( $val ) {                     
	$sql = '
	SELECT
		*
	FROM
		`' . DB_PREFIX . 'option`
	WHERE
		`option_id` = \'' . $val . '\'';

	$result = $this->db->query( $sql );

	if( count( $result->row ) == 0 ) {
		return false;
	}

	return true;
  }

   public function hasApproval() {
	$sql = '
	SELECT
		*
	FROM
		`' . DB_PREFIX . 'customer_group`
	WHERE
	   `approval` = \'1\'';

	    if( array_search('approval', $this->columns('customer_group')) ){
			$result = $this->db->query( $sql );
			if( count( $result->rows ) == 0 ) {
				return false;
			}
		} else {
				return false;
		}
	return true;
   }
  
  public function getColumnType( $column, $type, $table ) {
     if( array_search( DB_PREFIX . $table, $this->tables() ) || $table == 'address'){
            $fields = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . $table . "`" );

        if( array_search( $column, $this->columns( $table ) ) ){
           foreach( $fields->rows as $field){
              if($field['Field'] == $column ){
  
                 return strpos($field['Type'], $type);
    
              }
           }
        }
     }
  }
  
  public function getColumnKey( $column, $table ) {
     if( array_search( DB_PREFIX . $table, $this->tables() ) || $table == 'address'){
                $fields = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . $table . "`");

        if( array_search( $column, $this->columns( $table ) ) ){
           foreach( $fields->rows as $field){
              if($field['Field'] == $column){
   
                 return ( !empty( $field['Key'] ) ? true : false );
    
             }
          }
        }  	
     }
  }

   public function hasExtension( $val ) {
			    if( array_search( 'code', $this->columns( 'extension' ) ) ){
			      $field = 'code';
			    }
			    if( array_search( 'key', $this->columns( 'extension' ) ) ){
			      $field = 'key';
			    }
				$sql = '
				SELECT
					*
				FROM
					`' . DB_PREFIX . 'extension`
				WHERE
					`' . $field . '` = \'' . $val . '\'';
			
				$result = $this->db->query( $sql );
			
				if( count( $result->row ) == 0 ) {
					return false;
				}

		return true;
   }

   public function hasModule() {
 
	$sql = '
	SELECT
		*
	FROM
		`' . DB_PREFIX . 'module`';
       if( array_search( DB_PREFIX . 'module' , $this->tables())){
	$result = $this->db->query( $sql );
	if( count( $result->row ) == 0 ) {
		return false;
	}
              } else {
		return false;
            }
	return true;
   }
   
   public function hasSetting( $val ) {
			$sql = '
			SELECT
				*
			FROM
				`' . DB_PREFIX . 'setting`
			WHERE
				`key` = \'' . $val . '\'';
		
			$result = $this->db->query( $sql );
		
			if( count( $result->row ) == 0 ) {
				return false;
	}

	return true;
   }
}
?>
