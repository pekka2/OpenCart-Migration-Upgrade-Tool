<?php
class Structure {
  
  private $version;
  private $vdata;
  private $tb;
  private $org;
  
  public function __construct($registry) {
		$this->db = $registry->get('db');
  }
  public function tables() {
       $query = $this->db->query("SHOW TABLES FROM `" . DB_DATABASE . "`");

        $table_list = array(0=>'//');
        foreach($query->rows as $table){
                      $table_list[] = $table['Tables_in_'. DB_DATABASE];
          }
        return $table_list;
  }

  public function columns( $table ) {

		$ret		= array(0 => '//');
        if( array_search( DB_PREFIX . $table, $this->tables() ) || $table == 'address'){
                $colums = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . $table . "`");
               foreach( $colums->rows as $field){
                 $ret[] = $field['Field'];
               }
         }
          return $ret;	
  }
   public function newData($upgrade){
		$file = DIR_SQL . $upgrade . '.sql';

		if (!file_exists($file)) {
			exit('Could not load sql file: ' . $file);
		}

		$string = '';

		$lines = file($file);

		$status = false;

		// Get only the create statements
		foreach($lines as $line) {
			// Set any prefix
			$line = str_replace("CREATE TABLE IF NOT EXISTS `oc_", "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX, $line);

			// If line begins with create table we want to start recording
			if (substr($line, 0, 12) == 'CREATE TABLE') {
				$status = true;
			}

			if ($status) {
				$string .= $line;
			}

			// If line contains with ; we want to stop recording
			if (preg_match('/;/', $line)) {
				$status = false;
			}
		}

		$table_new_data = array();

		// Trim any spaces
		$string = trim($string);

		// Trim any ;
		$string = trim($string, ';');

		// Start reading each create statement
		$statements = explode(';', $string);

		foreach ($statements as $sql) {
			// Get all fields
			$field_data = array();

			preg_match_all('#`(\w[\w\d]*)`\s+((tinyint|smallint|mediumint|bigint|int|tinytext|text|mediumtext|longtext|tinyblob|blob|mediumblob|longblob|varchar|char|datetime|date|float|double|decimal|timestamp|time|year|enum|set|binary|varbinary)(\((.*)\))?){1}\s*(collate (\w+)\s*)?(unsigned\s*)?((NOT\s*NULL\s*)|(NULL\s*))?(auto_increment\s*)?(default \'([^\']*)\'\s*)?#i', $sql, $match);

			foreach(array_keys($match[0]) as $key) {
				$field_data[] = array(
					'name'          => trim($match[1][$key]),
					'type'          => strtoupper(trim($match[3][$key])),
					'size'          => str_replace(array('(', ')'), '', trim($match[4][$key])),
					'sizeext'       => trim($match[6][$key]),
					'collation'     => trim($match[7][$key]),
					'unsigned'      => trim($match[8][$key]),
					'notnull'       => trim($match[9][$key]),
					'autoincrement' => trim($match[12][$key]),
					'default'       => trim($match[14][$key]),
				);
			}

			// Get primary keys
			$primary_data = array();

			preg_match('#primary\s*key\s*\([^)]+\)#i', $sql, $match);

			if (isset($match[0])) {
				preg_match_all('#`(\w[\w\d]*)`#', $match[0], $match);
			} else{
				$match = array();
			}

			if ($match) {
				foreach($match[1] as $primary) {
					$primary_data[] = $primary;
				}
			}

			// Get indexes
			$index_data = array();

			$indexes = array();

			preg_match_all('#key\s*`\w[\w\d]*`\s*\(.*\)#i', $sql, $match);

			foreach($match[0] as $key) {
				preg_match_all('#`(\w[\w\d]*)`#', $key, $match);

				$indexes[] = $match;
			}

			foreach($indexes as $index) {
				$key = '';

				foreach($index[1] as $field) {
					if ($key == '') {
						$key = $field;
					} else{
						$index_data[$key][] = $field;
					}
				}
			}

			// Table options
			$option_data = array();

			preg_match_all('#(\w+)=(\w+)#', $sql, $option);

			foreach(array_keys($option[0]) as $key) {
				$option_data[$option[1][$key]] = $option[2][$key];
			}

			// Get Table Name
			preg_match_all('#create\s*table\s*if\s*not\s*exists\s*`(\w[\w\d]*)`#i', $sql, $table);

			if (isset($table[1][0])) {
				$table_new_data[] = array(
					'sql'     => $sql,
					'name'    => $table[1][0],
					'field'   => $field_data,
					'primary' => $primary_data,
					'index'   => $index_data,
					'option'  => $option_data
				);
			}
		}
		return $table_new_data;
	}
	public function oldData(){
		$table_old_data = array();
		$table_query = $this->db->query("SHOW TABLES FROM `" . DB_DATABASE . "`");
		foreach ($table_query->rows as $table) {
			if (utf8_substr($table['Tables_in_' . DB_DATABASE], 0, strlen(DB_PREFIX)) == DB_PREFIX || DB_PREFIX == '' ){
				$field_data = array();

				$field_query = $this->db->query("SHOW COLUMNS FROM `" . $table['Tables_in_' . DB_DATABASE] . "`");

				foreach ($field_query->rows as $field) {
					$field_data[] = $field['Field'];
				}

				$table_old_data[$table['Tables_in_' . DB_DATABASE]] = $field_data;
			}
		};
         return $table_old_data;
   }
   public function settingData($upgrade){
		$file = DIR_SQL . $upgrade . '.sql';

		if (!file_exists($file)) {
			exit('Could not load sql file: ' . $file);
		}

		$string = '';

		$lines = file($file);

		$status = false;


		foreach($lines as $line) {

			if (substr($line, 0, 6) == 'INSERT') {
				$status = true;
			}

			if ($status) {
				$string .= $line;
			}

			if (preg_match('/\);/', $line)) {
				$status = false;
			}
		}

		// Start reading each create statement
		$stat = explode("VALUES", $string);
		$settings = explode("),", $stat[1]);
     $setting_data = array();
		foreach ($settings as $sql) {
			// Get all config settings
		$sql_data = explode('\'',$sql); 
		$sql_data2 = implode('',$sql_data); 
		$sql_data3 = explode(',',$sql_data2); 
        $sql_data3[0] = str_replace('(','',$sql_data3[0]);
        $sql_data3[0] = trim($sql_data3[0]);
        $sql_data3[5] = str_replace(')','',$sql_data3[5]);
				$setting_data[] = array(
					'store_id'     => $sql_data3[1],
					'code'         => $sql_data3[2],
					'key'          => $sql_data3[3],
					'value'        => $sql_data3[4],
					'serialized'   => $sql_data3[5]
				); 
        }
       return $setting_data;	
  }
  public function language() {
		 $sql = 'SELECT  * FROM  `' . DB_PREFIX . 'language`';

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

   public function getProductOption() {
 
	$sql = 'SELECT	*FROM `' . DB_PREFIX . 'product_option`';
		$result = $this->db->query( $sql );
		if( count( $result->rows ) >  0 ) {
				return true;
		}
	 return false;
   }
   public function getLayout() {
 
	$sql = 'SELECT * FROM `' . DB_PREFIX . 'layout`';
       if( array_search( DB_PREFIX . 'layout' , $this->tables())){
					$result = $this->db->query( $sql );
					if( count( $result->row ) == 0 ) {
						return false;
					}
     } else {
				return false;
      }
	return true;
   }
   public function getReturnAction() {
 
	$sql = 'SELECT	*  FROM	`' . DB_PREFIX . 'return_action`';
       if( array_search( DB_PREFIX . 'return_action' , $this->tables())){
					$result = $this->db->query( $sql );
					if( count( $result->row ) == 0 ) {
						return false;
					}
       } else {
				return false;
      }
	return true;
   }
   public function getVoucherTheme() {
 
	$sql = 'SELECT	*	FROM	`' . DB_PREFIX . 'voucher_theme`';
       if( array_search( DB_PREFIX . 'voucher_theme' , $this->tables())){
					$result = $this->db->query( $sql );
					if( count( $result->row ) == 0 ) {
						return false;
					}
     } else {
				return false;
      }
	return true;
   }
   public function getCustomerGroupDescription() {
 
	$sql = 'SELECT * FROM `' . DB_PREFIX . 'customer_group_description`';
       if( array_search( DB_PREFIX . 'customer_group_description' , $this->tables())){
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
	$sql = 'SELECT * FROM `' . DB_PREFIX . 'order_total`';
					$result = $this->db->query( $sql );
			if( count( $result->rows ) == 0 ) {
				return false;
			}
	return true;
   }

  public function hasOption( $val ) {                     
	$sql = 'SELECT * FROM `' . DB_PREFIX . 'option` WHERE `option_id` = \'' . $val . '\'';

	$result = $this->db->query( $sql );

	if( count( $result->row ) == 0 ) {
		return false;
	}

	return true;
  }

   public function hasApproval() {
	$sql = 'SELECT * FROM
		`' . DB_PREFIX . 'customer_group` WHERE `approval` = \'1\'';

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
  
  public function getColumnTypeVarchar( $table ) {
     if( array_search( DB_PREFIX . $table, $this->tables() ) || $table == 'address'){
            $fields = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . $table . "`" );
			$col = array();
           foreach( $fields->rows as $field){
           	if( strpos($field['Type'], 'char') ||strpos($field['Type'], 'ext') ){
           	  $col[] = array("field"=>$field['Field'], "type"=>$field['Type'], "null" => str_replace("NO", "NOT NULL", $field['Null']));
           	}
           }
           return $col;
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
   
   public function getVersion() {
   	$this->version = 0;
   	$this->vdata = 0;
   	$this->tb = 59;
   	$this->org = 59;
			if( !array_search( 'meta_keywords', $this->columns('meta_description') ) && !array_search( DB_PREFIX . 'affiliate', $this->tables())) {
		           ++$this->version;
		           $this->vdata = '1.4.7-1.4.8b';
		           $this->tb = count($this->tables());
		    }
			if( array_search( 'meta_keywords', $this->columns('meta_description') ) ) {
		          ++$this->version;
		            $this->vdata = '1.4.9';
		            $this->tb = count($this->tables());
		    }
			if( !array_search( 'serialized', $this->columns('setting') ) && !array_search( DB_PREFIX . 'tax_rate_to_customer_group', $this->tables() ) ) {
		           ++$this->version;
		           if($this->vdata == 0){
		           	$this->vdata = '1.5.0-1.5.0.5';
		            $this->tb = count($this->tables());
		            $this->org = 88;
		           }
		   }
			if( !array_search( DB_PREFIX . 'tax_rate_to_customer_group', $this->tables() ) ) {
		          ++$this->version;
		           if($this->vdata == 0){
		           	$this->vdata = '1.5.1-1.5.1.2';
		            $this->tb = count($this->tables());
		            $this->org = 90;
		           }
		   }
			if( !array_search( DB_PREFIX . 'api', $this->tables()) && !array_search( DB_PREFIX . 'order_fraud', $this->tables()) ) {
		          ++$this->version;
		           if($this->vdata == 0){
		           	$this->vdata = '1.5.1.3';
		            $this->tb = count($this->tables());
		            $this->org = 91;
		           }
		   }
			if( !array_search( DB_PREFIX . 'customer_group_description', $this->tables() ) ) {
		          ++$this->version;
		           if($this->vdata == 0){
		           	$this->vdata = '1.5.2';
		            $this->tb = count($this->tables());
		            $this->org = 92;
		           }
		   }
			if( !array_search( DB_PREFIX . 'customer_online', $this->tables() ) ) {
		          ++$this->version;
		           if($this->vdata == 0){
		           	$this->vdata = '1.5.3';
		            $this->tb = count($this->tables());
		            $this->org = 94;
		           }
		   }
			if( !array_search( DB_PREFIX . 'category_path', $this->tables() ) ) {
		          ++$this->version;
		           if($this->vdata == 0){
		           	$this->vdata = '1.5.4';
		            $this->tb = count($this->tables());
		            $this->org = 93;
		           }
         }
			if( !array_search( DB_PREFIX . 'order_recurring', $this->tables() ) ) {
		          ++$this->version;
		           if($this->vdata == 0){
		           	$this->vdata = '1.5.5.1';
		            $this->tb = count($this->tables());
		            $this->org = 109;
		           }
        }
			if( !array_search( DB_PREFIX . 'layout_module', $this->tables() ) ) {
		          ++$this->version;
		           if($this->vdata == 0){
		           	$this->vdata = '1.5.6';
		            $this->tb = count($this->tables());
		            $this->org = 115;
		           }
        }
		  if( !array_search( DB_PREFIX . 'module', $this->tables() ) ) {
		          ++$this->version; 
		           if($this->vdata == 0){
		           	$this->vdata = '2.0.0.0';
		            $this->tb = count($this->tables());
		            $this->org = 124;
		           }
	     }
		 if( !array_search( DB_PREFIX . 'api_id', $this->tables() ) ) {
		          ++$this->version; 
		           if($this->vdata == 0){
		           	$this->vdata = '2.0.1.0-2.0.3.1';
		            $this->tb = count($this->tables());
		            $this->org = 123;
		           }
	     }
		     return array('level' => $this->version, 'vdata' => $this->vdata, 'tables' => $this->tb, 'oc_tables' => $this->org);
     }
     public function getUpgrade(){
		        $oc_path  = DIR_DOCUMENT_ROOT . 'system/modification';
		        $oc_path2 = DIR_DOCUMENT_ROOT . 'system/library/db';
		        $oc_path3 = DIR_DOCUMENT_ROOT . 'catalog/controller/api';

		        if(is_dir($oc_path) && is_dir($oc_path2) && is_dir($oc_path3) ){
		        	return true;
		        }
          return false;
     }
     public function getOc2Tables(){
     	        $tables = array(2020 => 124, 2031 => 123, 2100 => 125);
               return $tables;
     }
}
?>
