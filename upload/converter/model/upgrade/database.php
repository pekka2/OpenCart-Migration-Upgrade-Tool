<?php
class ModelUpgradeDatabase extends Model {
	  private $lang;
	  private $languages;
	  private $simulate;
	  private $showOps;
	  private $tablecounter;
	  private $collatecounter;
	  private $columncollatecounter;
	public function addTable($data) {
        $this->simulate = ( !empty( $data['simulate'] ) ? true : false );
        $this->showOps  = ( !empty( $data['showOps'] ) ? true : false );
        $this->version = 0;
        $this->max = 9;
        $this->min = 4;

        $this->lang = $this->lmodel->get('upgrade_database');
        $this->languages = $this->structure->language(); 

        $this->tablecounter = 0;
        $text = '';
        $table_new_data = array();

			$table_new_data = $this->structure->newData($data['upgrade']);

		foreach ($table_new_data as $table) {
			// If table is not found create it

			if (!array_search($table['name'], $this->structure->tables())) {

				$sql = $table['sql'];
					 if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ){
	                $text .= '<p><pre>' . $sql .'</pre></p>';
                }

		$text .= $this->msg( sprintf( $this->lang['msg_table'],   $table['name'] ) );
			++$this->tablecounter;	
			}
	    }
	        // Upgrade Memory Log
	        $memory = DIR_UPGRADE . '/upgrade_cache.log';
	        $cache = array();
	        $oc2 = $this->structure->getOc2Tables();
	            if( !file_exists($memory) ){
	            	    $cache = array('upgrade' => $data['upgrade'],
	            	    	           'oc2_tables' => $oc2[$data['upgrade']],
	            	    	           'simulate' => $this->simulate,
	            	                   'steps' => $data['steps']);
	            } else {
	            	   $string = file_get_contents($memory);
	                if(!empty($string)){
	            	    $cache = unserialize($string);
	            	    $cache['upgrade'] = $data['upgrade'];
	            	    $cache['oc2_tables'] = $oc2[$data['upgrade']];
	            	    $cache['simulate'] = $this->simulate;
	            	    $cache['steps'] = $data['steps'];
	                }
	            }
	            if($cache){
	            	    if( !$this->simulate ){
	            	     $cache['step'] = '1';
	            	    }
	                $str = serialize($cache);
	                $fw = fopen($memory,'wb');
	                fwrite($fw,$str);
	                fclose($fw);
	            }
        $text .=  $this->header( sprintf( addslashes($this->lang['msg_table_count']), $this->tablecounter, '' ) );

		return $text;
	}
	public function addCollate($data) {
        $this->simulate = ( !empty( $data['simulate'] ) ? true : false );
        $this->showOps  = ( !empty( $data['showOps'] ) ? true : false );
        $this->upgrade  = $data['upgrade'];
        $this->version = 0;
        $this->max = 9;
        $this->min = 4;
        $this->collatecounter = 0;
        $this->columncollatecounter = 0;

        $this->lang = $this->lmodel->get('upgrade_database');
        $this->languages = $this->structure->language(); 

        $text = '';

        $table_new_data = array();
			if(!$this->cache->get('table_new_data')){
					$table_new_data = $this->structure->newData($data['upgrade']);
			} else{
			  $table_new_data = $this->cache->get('table_new_data');
			}
        $table_old_data = array();
			if(!$this->cache->get('table_old_data')){
					$table_old_data = $this->structure->oldData();
			} else{
			  $table_old_data = $this->cache->get('table_old_data');
			}

		foreach ($table_new_data as $table) {
			// DB Engine
				if ( array_key_exists($table['name'],$table_old_data) && isset($table['option']['ENGINE'])) {
					$sql = "ALTER TABLE `" . $table['name'] . "` ENGINE = `" . $table['option']['ENGINE'] . "`";
					
				    if( !$this->simulate ) {
                       $this->db->query( $sql );
                    }
                    if( $this->showOps ){
	                $text .= '<p><pre>' . $sql  .'</pre></p>';
                    }
				}

				// Charset
				if (array_key_exists($table['name'], $table_old_data) &&isset($table['option']['CHARSET']) && isset($table['option']['COLLATE'])) {
					$sql = "ALTER TABLE `" . $table['name'] . "` DEFAULT CHARACTER SET `" . $table['option']['CHARSET'] . "` COLLATE `" . $table['option']['COLLATE'] . "`";
			        if( !$this->simulate ) {
                       $this->db->query( $sql );
                    }
                    if( $this->showOps ){
	                $text .= '<p><pre>' . $sql .'</pre></p>';
                    }
                   ++$this->collatecounter;
				}
				
				$i = 0;
				foreach ($table['field'] as $field) {		
					if (array_search($table['name'], $this->structure->tables()) && in_array($field['name'], $table_old_data[$table['name']])) {
						// Remove auto increment from all fields
						$sql = "ALTER TABLE `" . $table['name'] . "` CHANGE `" . $field['name'] . "` `" . $field['name'] . "` " . strtoupper($field['type']);

						if ($field['size']) {
							$sql .= "(" . $field['size'] . ")";
						}
						if ($field['collation']) {
							$sql .= " " . $field['collation'];
						}
						if ($field['notnull']) {
							$sql .= " " . $field['notnull'];
						}
						if ($field['default']) {
							$sql .= " DEFAULT '" . $field['default'] . "'";
						}
						if (isset($table['field'][$i - 1])) {
							$sql .= " AFTER `" . $table['field'][$i - 1]['name'] . "`";
						} else {
							$sql .= " FIRST";
						}

                ++$this->columncollatecounter;
					 if( !$this->simulate ) {
                       $this->db->query( $sql );
                     }
                    if( $this->showOps ){
	                $text .= '<p><pre>' . $sql .'</pre></p>';
                    }
				  }
			 }
		}

        $text .=  $this->header(sprintf( addslashes($this->lang['msg_collate_count']), $this->collatecounter, '' ) );
        $text .=  $this->header(sprintf( addslashes($this->lang['msg_column_collate_count']), $this->columncollatecounter, '' ) );
		if(!$this->cache->get('table_new_data')){
	    /*  $this->cache->set('table_new_data', $table_new_data);
	      $this->cache->set('table_old_data', $table_old_data); */
	    }
		return $text;
	}
	public function addColumns($data) {
        $this->simulate = ( !empty( $data['simulate'] ) ? true : false );
        $this->showOps  = ( !empty( $data['showOps'] ) ? true : false );
        $this->upgrade  = $data['upgrade'];
        $this->version = 0;
        $this->max = 9;
        $this->min = 4;
	     $this->columncounter = 0;

        $this->lang = $this->lmodel->get('upgrade_database');
        $this->languages = $this->structure->language(); 

        $text = '';

        $table_new_data = array();
			if(!$this->cache->get('table_new_data')){
					$table_new_data = $this->structure->newData($data['upgrade']);
			} else{
			  $table_new_data = $this->cache->get('table_new_data');
			}
        $table_old_data = array();
			if(!$this->cache->get('table_old_data')){
					$table_old_data = $this->structure->oldData();
			} else{
			  $table_old_data = $this->cache->get('table_old_data');
			}

		foreach ($table_new_data as $table) {
			// If table is not found create it
			if (array_search($table['name'], $this->structure->tables())) {
           $i = 0;
				foreach ($table['field'] as $field) {
					// If field is not found create it
					if (!in_array($field['name'], $table_old_data[$table['name']])) {
						$sql = "ALTER TABLE `" . $table['name'] . "` ADD COLUMN `" . $field['name'] . "` " . $field['type'];
                  ++$this->columncounter;
						if ($field['size']) {
							$sql .= "(" . $field['size'] . ")";
						}
						if ($field['collation']) {
							$sql .= " " . $field['collation'];
						}
						if ($field['notnull']) {
							$sql .= " " . $field['notnull'];
						}
						if ($field['default']) {
							$sql .= " DEFAULT '" . $field['default'] . "'";
						}
						if (isset($table['field'][$i - 1])) {
							$sql .= " AFTER `" . $table['field'][$i - 1]['name'] . "`";
						} else {
							$sql .= " FIRST";
						}
					    if( !$this->simulate ) {
                            $this->db->query( $sql );
                        }
                        if( $this->showOps ){
	                       $text .= '<p><pre>' . $sql .'</pre></p>';
                       }
					} 
				}

				$status = false;

				// Drop primary keys and indexes.
				$query = $this->db->query("SHOW INDEXES FROM `" . $table['name'] . "`");

				foreach ($query->rows as $result) {
					if ($result['Key_name'] != 'PRIMARY') {
						$sql = "ALTER TABLE `" . $table['name'] . "` DROP INDEX `" . $result['Key_name'] . "`";
					 if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ){
	                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
					} else {
						$status = true;
					}
				}

				if ($status) {
					$sql = "ALTER TABLE `" . $table['name'] . "` DROP PRIMARY KEY";
					
					if( !$this->simulate ) {
                       $this->db->query( $sql );
                    }
                    if( $this->showOps ){
	                   $text .= '<p><pre>' . $sql .'</pre></p>';
                    }
				}

				// Add a new primary key.
				$primary_data = array();

				foreach ($table['primary'] as $primary) {
					$primary_data[] = "`" . $primary . "`";
				}

				if ($primary_data) {
					$sql = "ALTER TABLE `" . $table['name'] . "` ADD PRIMARY KEY(" . implode(',', $primary_data) . ")";
					 if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ){
	                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
				}

				// Add the new indexes
				foreach ($table['index'] as $index) {
					$index_data = array();
					foreach ($index as $key) {
						$index_data[] = '`' . $key . '`';
					}

					if ($index_data) {
						$sql = "ALTER TABLE `" . $table['name'] . "` ADD INDEX (" . implode(',', $index_data) . ")";
						
					 if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ){
	                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
					}
				}

				// Add auto increment to primary keys again
				foreach ($table['field'] as $field) {
					if ($field['autoincrement']) {
						$sql = "ALTER TABLE `" . $table['name'] . "` CHANGE `" . $field['name'] . "` `" . $field['name'] . "` " . strtoupper($field['type']);

						if ($field['size']) {
							$sql .= "(" . $field['size'] . ")";
						}

						if ($field['collation']) {
							$sql .= " " . $field['collation'];
						}

						if ($field['notnull']) {
							$sql .= " " . $field['notnull'];
						}

						if ($field['default']) {
							$sql .= " DEFAULT '" . $field['default'] . "'";
						}

						if ($field['autoincrement']) {
							$sql .= " AUTO_INCREMENT";
						}
					 if( !$this->simulate ) {
                       $this->db->query( $sql );
		                }
		                if( $this->showOps ){
			                $text .= '<p><pre>' . $sql .'</pre></p>';
		                }
					}
				}
			}
		}

	$text .= $this->header(sprintf( $this->lang['msg_col_counter'], $this->columncounter, '' ) );

		return $text;
	}
	public function addData($data) {
        $this->simulate = ( !empty( $data['simulate'] ) ? true : false );
        $this->showOps  = ( !empty( $data['showOps'] ) ? true : false );
        $this->languages = $this->structure->language();
        $this->lang = $this->lmodel->get('upgrade_database');

		$text = '';
		// category_description
	  if($data['upgrade'] > 1564){
		$sql = "UPDATE " . DB_PREFIX . "category_description SET `meta_title` = `name`";
		
					 if( !$this->simulate ) {
                       $this->db->query( $sql );
		                }
		                if( $this->showOps ){
			                $text .= '<p><pre>' . $sql .'</pre></p>';
		                }
			$text .= $this->msg( sprintf( $this->lang['msg_text'], 'category_description',$this->lang['msg_new_data'] ) );

		// product_description
		$sql = "UPDATE " . DB_PREFIX . "product_description SET `meta_title` = `name`";
		
					 if( !$this->simulate ) {
                       $this->db->query( $sql );
		                }
		                if( $this->showOps ){
			                $text .= '<p><pre>' . $sql .'</pre></p>';
		                }
			$text .= $this->msg( sprintf( $this->lang['msg_text'], 'product_description',$this->lang['msg_new_data'] ) );
		// product_option

		if( array_search('option_value',$this->structure->columns('product_option'))){
		    if( $this->structure->getProductOption()){
		        $sql = "UPDATE " . DB_PREFIX . "product_option SET `value` = `option_value`";
					 if( !$this->simulate ) {
                       $this->db->query( $sql );
		                }
		                if( $this->showOps ){
			                $text .= '<p><pre>' . $sql .'</pre></p>';
		                }
			$text .= $this->msg( sprintf( $this->lang['msg_text'], 'product_option',$this->lang['msg_new_data'] ) );
		    }
		 }
		// setting
		if( array_search('group',$this->structure->columns('setting'))){
		$sql = "UPDATE " . DB_PREFIX . "setting SET `code` = `group`";
		
					 if( !$this->simulate ) {
                       $this->db->query( $sql );
		                }
		                if( $this->showOps ){
			                $text .= '<p><pre>' . $sql .'</pre></p>';
		                }
			$text .= $this->msg( sprintf( $this->lang['msg_text'], 'setting',$this->lang['msg_new_data'] ) );
		 }
		}
		// layout and layout_route in version 1.4.x
		if( !$this->structure->getLayout()){
$sql = "INSERT INTO `" . DB_PREFIX . "layout` (`layout_id`, `name`) VALUES
(1, 'Home'),
(2, 'Product'),
(3, 'Category'),
(4, 'Default'),
(5, 'Manufacturer'),
(6, 'Account'),
(7, 'Checkout'),
(8, 'Contact'),
(9, 'Sitemap'),
(10, 'Affiliate'),
(11, 'Information'),
(12, 'Compare'),
(13, 'Search')";
					 if( !$this->simulate ) {
                       $this->db->query( $sql );
		                }
		                if( $this->showOps ){
			                $text .= '<p><pre>' . $sql .'</pre></p>';
		                }

			$text .= $this->msg( sprintf( $this->lang['msg_text'], 'layout',$this->lang['msg_new_data'] ) );

$sql = "INSERT INTO `" . DB_PREFIX . "layout_route` (`layout_route_id`, `layout_id`, `store_id`, `route`) VALUES
(38, 6, 0, 'account/%'),
(17, 10, 0, 'affiliate/%'),
(44, 3, 0, 'product/category'),
(42, 1, 0, 'common/home'),
(20, 2, 0, 'product/product'),
(24, 11, 0, 'information/information'),
(23, 7, 0, 'checkout/%'),
(31, 8, 0, 'information/contact'),
(32, 9, 0, 'information/sitemap'),
(34, 4, 0, ''),
(45, 5, 0, 'product/manufacturer'),
(52, 12, 0, 'product/compare'),
(53, 13, 0, 'product/search')";

					 if( !$this->simulate ) {
                       $this->db->query( $sql );
		                }
		                if( $this->showOps ){
			                $text .= '<p><pre>' . $sql .'</pre></p>';
		                }

			$text .= $this->msg( sprintf( $this->lang['msg_text'], 'layout_route',$this->lang['msg_new_data'] ) );
		}
	  // customer_group_description
	if( !$this->structure->getCustomerGroupDescription()){
		      $query = '  SELECT  *  FROM  `' . DB_PREFIX . 'customer_group`';

                $customers = $this->db->query($query);
                
       if( count( $customers->rows ) > 0 ){
       	foreach($this->languages as $language){
            foreach( $customers->rows as $customer ){
$sql = "INSERT INTO
`" . DB_PREFIX . "customer_group_description` (`customer_group_id`, `language_id`, `name`, `description`)
VALUES
(" . $customer['customer_group_id'] . ",
" . $language['language_id'] . ", 
'" . $customer['name'] . "',
'" . $customer['name'] . "')";
			   if( !$this->simulate ) {
	               $this->db->query( $sql );
	            }
	           if( $this->showOps ) {
	                $text .= '<p><pre>' . $sql .'</pre></p>';
	           }
				                
            }	
		  $text .= $this->msg( sprintf( $this->lang['msg_text'],   'customer_group_description', $this->lang['msg_new_data'] ) );
         }
	  }
	}
	// customer_group in version 1.4
    if( ! $this->structure->hasApproval()) {

$sql = "SELECT * FROM `" . DB_PREFIX . "customer_group`";

          $results = $this->db->query( $sql );
             $row = 1;
         foreach($results->rows as $result){    
 
$sql = "UPDATE `" . DB_PREFIX . "customer_group` SET `approval` = '1', `sort_order` = '" . $row . "' WHERE `customer_group_id` = '" . $result['customer_group_id'] . "'";

             if( !$this->simulate ) {
				$this->db->query($sql);
            }
            if( $this->showOps ) {
                   $text .= '<p><pre>' . $sql .'</pre></p>';
            }     
	               ++$row;
          }
			$text .= $this->msg( sprintf( $this->lang['msg_text'], 'customer_group',$this->lang['msg_new_data'] ) );
			
         $this->cache->delete( 'customer_group' );
    }
      // return_action, return_reason and return_status in version 1.4.x
 
	if( !$this->structure->getReturnAction()){
        foreach($this->languages as $language){

$sql = 'INSERT INTO  `' . DB_PREFIX . 'return_action` (`return_action_id`, `language_id`, `name`)
VALUES
       (1, ' . $language['language_id'] . ', \'Refunded\'),
       (2, ' . $language['language_id'] . ', \'Credit Issued\'),
       (3, ' . $language['language_id'] . ', \'Replacement Sent\');';

		                if( !$this->simulate ) {
                            $this->db->query( $sql );
                        }
                        if( $this->showOps ){
                            $text .= '<p><pre>' . $sql .'</pre></p>';
                        }
		        $text .= $this->msg( sprintf( $this->lang['msg_text'],   'return_action', $this->lang['msg_new_data'] ) );
        }

         foreach($this->languages as $language){

$sql = 'INSERT INTO `' . DB_PREFIX . 'return_reason` (`return_reason_id`, `language_id`, `name`) VALUES
(1, ' . $language['language_id'] . ', \'Dead On Arrival\'),
(2, ' . $language['language_id'] . ', \'Received Wrong Item\'),
(3, ' . $language['language_id'] . ', \'Order Error\'),
(4, ' . $language['language_id'] . ', \'Faulty, please supply details\'),
(5, ' . $language['language_id'] . ', \'Other, please supply details\');';

				        if( !$this->simulate ) {
                            $this->db->query( $sql );
                        }
                        if( $this->showOps ){
	                        $text .= '<p><pre>' . $sql .'</pre></p>';
                        }               
		        $text .= $this->msg( sprintf($this->lang['msg_text'],'return_reason',$this->lang['msg_new_data'] ) );
         }

        foreach($this->languages as $language){

$sql = 'INSERT INTO `' . DB_PREFIX . 'return_status` (`return_status_id`, `language_id`, `name`) VALUES
(1, ' . $language['language_id'] . ', \'Pending\'),
(3, ' . $language['language_id'] . ', \'Complete\'),
(2, ' . $language['language_id'] . ', \'Awaiting Products\');';

				        if( !$this->simulate ) {
                            $this->db->query( $sql );
                        }
                        if( $this->showOps ){
                            $text .= '<p><pre>' . $sql .'</pre></p>';
                        }              
		        $text .= $this->msg( sprintf( $this->lang['msg_text'],   'return_status', $this->lang['msg_new_data'] ) );
         }
    }
     // return_product data change
	if( array_search( DB_PREFIX . 'return_product', $this->structure->tables() ) ) {

$sql = 'SELECT  *  FROM  `' . DB_PREFIX . 'return_product`';

     $query = $this->db->query($sql);

        if( count( $query->rows ) > 0 ){
       /*
        * Change content from table return_product
        * to table return
        */
$sql = "UPDATE `' . DB_PREFIX . 'return` (`product_id`,`product`,`model`,`quantity`,`opened`,`return_reason_id`,`return_action_id`,`comment`)
SELECT `product_id`, `name`,`model`,`quantity`,`opened`,`return_reason_id`,`return_action_id`,`comment` FROM `' . DB_PREFIX . 'return_product`";
	
		                if( !$this->simulate ) {
                            $this->db->query( $sql );
                        }
                        if( $this->showOps ) {
                            $text .= '<p><pre>' . $sql .'</pre></p>';
                        }
		        $text .= $this->msg( sprintf( $this->lang['msg_text'], $this->data['msg_new_data'],  DB_PREFIX . 'return' ) );
        }
    }

     // voucher_theme and voucher_theme_description
	if( !$this->structure->getVoucherTheme() ){	
       
$sql = "INSERT INTO `" . DB_PREFIX . "voucher_theme` (`voucher_theme_id`, `image`) VALUES
(8, ''),
(7, ''),
(6, '')";
				        if( !$this->simulate ) {
                            $this->db->query( $sql );
                        }
                        if( $this->showOps ){
                            $text .= '<p><pre>' . $sql .'</pre></p>';
                        }        
             
		        $text .= $this->msg( sprintf( $this->lang['msg_text'],   'voucher_theme', $this->lang['msg_new_data'] ) );

            foreach($this->languages as $language){
$sql = "INSERT INTO `" . DB_PREFIX . "voucher_theme_description` (`voucher_theme_id`, `language_id`, `name`)
VALUES
    (6, " . $language['language_id'] . ", 'Christmas'),
    (7, " . $language['language_id'] . ", 'Birthday'),
    (8, " . $language['language_id'] . ", 'General')";

				        if( !$this->simulate ) {
                            $this->db->query( $sql );
                        }
                        if( $this->showOps ){
                            $text .= '<p><pre>' . $sql .'</pre></p>';
                        }
		        $text .= $this->msg( sprintf( $this->lang['msg_text'],   'voucher_theme_description', $this->lang['msg_new_data'] ) );
            }
    }

      // category in version 1.4.x
    if( array_search( DB_PREFIX . 'product_option_description' , $this->structure->tables()) ) {
	     $sql = "UPDATE `" . DB_PREFIX . "category` SET `top` = '1'  WHERE parent_id = '0'";

				        if( !$this->simulate ) {
                             $this->db->query($sql);
                        }
                        if( $this->showOps ){
                             $text .= '<p><pre>' . $sql .'</pre></p>';
                        } 
			$text .= $this->msg( sprintf( $this->lang['msg_text'], 'category',$this->lang['msg_new_data'] ) );
    }
         if($this->structure->getCategoryPath()){
          $text .= $this->categoryPath();   
      }
          $text .= $this->changeOptions();
	  return $text;
   }
  private function changeOptions(){
           $text = '';
      if( array_search( DB_PREFIX . 'product_option_description' , $this->structure->tables()) ) {
        /* Opencart version 1.4.x is Found */
$sql = "SELECT MIN(product_option_id) AS option_id
 FROM
     `" . DB_PREFIX . "product_option`;";
            if( !$this->simulate ) {
	         $query = $this->db->query( $sql );
                  $option = $query->row['option_id'];
                } else {
                        $option = 0;
              }

            if( isset($query->row['option_id']) && !$this->structure->hasOption($option)  || $this->simulate){
 $sql = "INSERT INTO `" . DB_PREFIX . "option` (`option_id`, `type`, `sort_order`)
SELECT `product_option_id`, 'select', `sort_order`
FROM `" . DB_PREFIX . "product_option`;";

                if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ){
                     $text .= '<p><pre>' . $sql .'</pre></p>';
                }

		  $text .= $this->msg( sprintf( $this->lang['msg_text'],   'option', $this->lang['msg_new_data'] ) );

 $sql = "INSERT INTO
     `" . DB_PREFIX . "option_description`  (`option_id`, `language_id`, `name`)
SELECT
      `product_option_id`, `language_id`, `name`
FROM
      `" . DB_PREFIX . "product_option_description`;";

		        if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ){
                       $text .= '<p><pre>' . $sql .'</pre></p>';
                }

		  $text .= $this->msg( sprintf( $this->lang['msg_text'],   'option_description', $this->lang['msg_new_data'] ) );

$sql = "INSERT INTO
        `" . DB_PREFIX . "option_value`   (`option_value_id`, `option_id`, `sort_order`)
 SELECT
        `product_option_value_id`, `product_option_id`, `sort_order`
FROM
         `" . DB_PREFIX . "product_option_value`;";

		       if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ){
                       $text .= '<p><pre>' . $sql .'</pre></p>';
                }

		  $text .= $this->msg( sprintf( $this->lang['msg_text'],   'option_value', $this->lang['msg_new_data'] ) );


 $sql = "INSERT INTO
       `" . DB_PREFIX . "option_value_description` (`option_value_id`, `language_id`, `option_id`, `name`)
SELECT
       `pov`.`product_option_value_id` ,  `language_id` ,  `pov`.`product_option_id` ,  `name`
FROM
       `" . DB_PREFIX . "product_option_value_description` AS `povd`
INNER JOIN
       `" . DB_PREFIX . "product_option_value` AS `pov`
ON
      `pov`.`product_option_value_id` =  `povd`.`product_option_value_id`";

				if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ){
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
 
		  $text .= $this->msg( sprintf( $this->lang['msg_text'],   'option_value_description', $this->lang['msg_new_data'] ) );

		         if( !$this->simulate ) {
                    $this->db->query("UPDATE `" . DB_PREFIX . "product_option` SET `option_id` = `product_option_id`, `required` = 1");
                    $this->db->query("UPDATE `" . DB_PREFIX . "product_option_value` SET `option_id` = `product_option_id`, `option_value_id` = `product_option_value_id`");
                 }
            }
        }
        return $text;
    }
private function categoryPath(){
  $text = '';
		$sql = '
		SELECT
			*
		FROM
			`' . DB_PREFIX . 'category`
		ORDER BY
			category_id ASC';

		$query = $this->db->query( $sql );

		$categories = array();

		if( isset( $query->row['category_id'] ) ) {
			foreach( $query->rows as $category ){
				if( $category['parent_id'] == 0 ) {
					$categories[] = array(
						'category_id'	=> $category['category_id'],
						'path_id'		=> $category['category_id'],
						'level'			=> 0
					);
				}else{
					$path = $category['parent_id'];

					$sql = '
					SELECT
						*
					FROM
						`' . DB_PREFIX . 'category`
					WHERE
						category_id = \'' . (int) $category['parent_id']. '\'';

					$info = $this->db->query( $sql );

					if( $info->row['parent_id'] == 0 ) {
						$categories[] = array(
							'category_id'	=> $category['category_id'],
							'path_id'		=> $category['category_id'],
							'level'			=> 1
						);

						$categories[] = array(
							'category_id'	=> $category['category_id'],
							'path_id'		=> $category['parent_id'],
							'level'			=> 0
						);
					}elseif( $info->row['parent_id'] > 0 ) {
						$sql = '
						SELECT
							*
						FROM
							`' . DB_PREFIX . 'category`
						WHERE
							category_id = \'' . (int) $info->row['parent_id'] . '\'';

						$info2 = $this->db->query( $sql );

						if( $info2->row['parent_id'] == 0 ) {
							$level = 2;

							$categories[] = array(
								'category_id'	=> $category['category_id'],
								'path_id'		=> $category['category_id'],
								'level'			=> 2
							);

							$categories[] = array(
								'category_id'	=> $category['category_id'],
								'path_id'		=> $category['parent_id'],
								'level'			=> 1
							);

							$categories[] = array(
								'category_id'	=> $category['category_id'],
								'path_id'		=> $info->row['parent_id'],
								'level' => 0
							);
						}elseif( $info2->row['parent_id'] > 0 ) {
							$sql = '
							SELECT
								*
							FROM
								`' . DB_PREFIX . 'category`
							WHERE
								category_id = \'' . (int) $info2->row['parent_id'] . '\'';

							$info3 = $this->db->query( $sql );

							if( $info3->row['parent_id'] == 0 ) {
								$categories[] = array(
									'category_id'	=> $category['category_id'],
									'path_id'		=> $category['category_id'],
									'level'			=> 3
								);

								$categories[] = array(
									'category_id'	=> $category['category_id'],
									'path_id'		=>	$category['parent_id'],
									'level'			=> 2
								);

								$categories[] = array(
									'category_id'	=> $category['category_id'],
									'path_id'		=> $info->row['parent_id'],
									'level'			=> 1
								);

								$categories[] = array(
									'category_id'	=> $category['category_id'],
									'path_id'		=> $info2->row['parent_id'],
									'level'			=> 0
								);

								$level = 3;
							}elseif( $info3->row['parent_id'] > 0 ) {
								$sql = '
								SELECT
									*
								FROM
									`' . DB_PREFIX . 'category`
								WHERE
									category_id = \'' . (int) $info3->row['parent_id'] . '\'';

								$info4 = $this->db->query( $sql );

								if( $info4->row['parent_id'] == 0 ) {
									$categories[] = array(
										'category_id'	=> $category['category_id'],
										'path_id'		=> $category['category_id'],
										'level'			=> 4
									);
									$categories[] = array(
										'category_id'	=> $category['category_id'],
										'path_id'		=> $category['parent_id'],
										'level'			=> 3
									);
									$categories[] = array(
										'category_id'	=> $category['category_id'],
										'path_id'		=> $info->row['parent_id'],
										'level'			=> 2
									);
									$categories[] = array(
										'category_id'	=> $category['category_id'],
										'path_id'		=> $info2->row['parent_id'],
										'level' 		=> 1
									);
									$categories[] = array(
										'category_id'	=> $category['category_id'],
										'path_id'		=> $info3->row['parent_id'],
										'level'			=> 0
									);
								}
							}
						}
					}
				}
			}
		}

		if( count( $categories) != 0 ) {
			$i = 0;

			foreach( $categories as $path ) {
				$sql = '
				INSERT INTO
					`' . DB_PREFIX . 'category_path`
				SET
					category_id = \'' . (int) $path['category_id'] . '\',
					path_id = \'' . (int) $path['path_id'] . '\',
					level = \'' . (int) $path['level'] . '\'';

				                if( !$this->simulate ) {
                                  $this->db->query( $sql );
                                }
                                if( $this->showOps ) {
                                  $text .= '<p><pre>' . $sql .'</pre></p>';
                                }
				++$i;
			}

		  $text .= $this->msg( sprintf( $this->lang['msg_text'],   'category_path', $this->lang['msg_new_data'] ) );
			
		}
		return $text;
	}
	public function cleanSetting($data){
        $this->simulate = ( !empty( $data['simulate'] ) ? true : false );
        $this->showOps  = ( !empty( $data['showOps'] ) ? true : false );
        $this->lang = $this->lmodel->get('upgrade_database');
		$text = '';

          $sql = "DELETE FROM `" . DB_PREFIX . "setting` WHERE `key` LIKE '%module'";
				                if( !$this->simulate ) {
                                  $this->db->query( $sql );
                                }
                                if( $this->showOps ) {
                                  $text .= '<p><pre>' . $sql .'</pre></p>';
                                }

           $expireds = array("manufacturer","bestseller","featured","special","latest");
           foreach($expireds as $key => $expired){

          $sql = "DELETE FROM `" . DB_PREFIX . "setting` WHERE `code` = '" . $expired . "'";
                                if( !$this->simulate ) {
                                  $this->db->query( $sql );
                                }
                                if( $this->showOps ) {
                                  $text .= '<p><pre>' . $sql .'</pre></p>';
                                }

           }
          $setting = array();
		if( array_search('group', $this->structure->columns('setting')) ) {
          $sql = "SELECT * FROM `" . DB_PREFIX . "setting` WHERE `group` = 'slideshow'";
           } else{
          $sql = "SELECT * FROM `" . DB_PREFIX . "setting` WHERE `code` = 'slideshow'";
           }
          $results = $this->db->query($sql);
         if(count($results->rows) > 0){
	        foreach($results->rows as $result){
	        	$setting[] = array('setting_id' => $result['setting_id'], 'key' => $result['key']);
	        }
         }
          $sql = "SELECT * FROM `" . DB_PREFIX . "setting` WHERE `key` LIKE '%carousel%'";
          $results = $this->db->query($sql);
         if(count($results->rows) > 0){
	        foreach($results->rows as $result){
	        	$setting[] = array('setting_id' => $result['setting_id'], 'key' => $result['key']);
	        }
         }
          
		if( array_search('group', $this->structure->columns('setting')) ) {
          $sql = "SELECT * FROM `" . DB_PREFIX . "setting` WHERE `group` = 'banner'";
           } else{
          $sql = "SELECT * FROM `" . DB_PREFIX . "setting` WHERE `code` = 'banner'";
           }
          $results = $this->db->query($sql);
         if(count($results->rows) > 0){
	        foreach($results->rows as $result){
	        	$setting[] = array('setting_id' => $result['setting_id'], 'key' => $result['key']);
	        }
         }
		if( array_search('group', $this->structure->columns('setting')) ) {
          $sql = "SELECT * FROM `" . DB_PREFIX . "setting` WHERE `group` = 'category'";
           } else{
          $sql = "SELECT * FROM `" . DB_PREFIX . "setting` WHERE `code` = 'category'";
           }
          $results = $this->db->query($sql);
         if(count($results->rows) > 0){
	        foreach($results->rows as $result){
	        	$setting[] = array('setting_id' => $result['setting_id'], 'key' => $result['key']);
	        }
         }
		if( array_search('group', $this->structure->columns('setting')) ) {
          $sql = "SELECT * FROM `" . DB_PREFIX . "setting` WHERE `group` = 'account'";
           } else{
          $sql = "SELECT * FROM `" . DB_PREFIX . "setting` WHERE `code` = 'account'";
           }
          $results = $this->db->query($sql);
         if(count($results->rows) > 0){
	        foreach($results->rows as $result){
	        	$setting[] = array('setting_id' => $result['setting_id'], 'key' => $result['key']);
	        }
         }
		if( array_search('group', $this->structure->columns('setting')) ) {
          $sql = "SELECT * FROM `" . DB_PREFIX . "setting` WHERE `group` = 'information'";
           } else{
          $sql = "SELECT * FROM `" . DB_PREFIX . "setting` WHERE `code` = 'information'";
           }
          $results = $this->db->query($sql);
         if(count($results->rows) > 0){
	        foreach($results->rows as $result){
	        	$setting[] = array('setting_id' => $result['setting_id'], 'key' => $result['key']);
	        }
         }
		if( array_search('group', $this->structure->columns('setting')) ) {
          $sql = "SELECT * FROM `" . DB_PREFIX . "setting` WHERE `group` = 'affiliate'";
           } else{
          $sql = "SELECT * FROM `" . DB_PREFIX . "setting` WHERE `code` = 'affiliate'";
           } 
          $results = $this->db->query($sql);
         if(count($results->rows) > 0){
	        foreach($results->rows as $result){
	        	$setting[] = array('setting_id' => $result['setting_id'], 'key' => $result['key']);
	        }
         }

         foreach($setting as $mod){

         	if( !strpos($mod['key'],'status') ){
         		$sql = "DELETE FROM `" . DB_PREFIX . "setting` WHERE `key` = '" . $mod['key'] . "'";

				                if( !$this->simulate ) {
                                  $this->db->query( $sql );
                                }
                                if( $this->showOps ) {
                                  $text .= '<p><pre>' . $sql .'</pre></p>';
                                }
         	}
         }

        $text .=  $this->header( sprintf( addslashes($this->lang['msg_table_count']), $this->tablecounter, '' ) );

         return $text;
	}
	public function cleanStructure($data){
        $this->simulate = ( !empty( $data['simulate'] ) ? true : false );
        $this->showOps  = ( !empty( $data['showOps'] ) ? true : false );
        $this->lang = $this->lmodel->get('upgrade_database');
		$text = '';
		  $deletetab = 0;
    $text = '';
        $droptable = array(
                           'coupon_description',
                           'customer_ip_blacklist',
                           'order_misc',
                           'product_featured',
                           'product_tag',
                           'product_tags',
                           'return_product',
                           'store_description'
		             );

        if( $data['upgrade'] > 1564 ){
            $droptable[] = 'customer_field';
            $droptable[] = 'order_download';
            $droptable[] = 'order_field';
            $droptable[] = 'product_profile';
            $droptable[] = 'profile';
            $droptable[] = 'profile_description';
        }
        if( $data['upgrade'] > 2020 ){
            $droptable[] = 'order_fraud';
        }
       foreach( $droptable as $table ) {
	       if( array_search( DB_PREFIX . $table, $this->structure->tables() ) ) {
		$sql = 'DROP TABLE `' . DB_PREFIX . $table . '`';

		    if( !$this->simulate ) {
               $this->db->query( $sql );
            }
            if( $this->showOps ) {
            $text .= '<p><pre>' . $sql .'</pre></p>';
            }
		    ++$deletetab;
	      }
	   }

	$text .= $this->header( sprintf( $this->lang['msg_delete_table'], $deletetab ) );

		$table_old_data = $this->structure->oldData();

			$table_new_data = $this->structure->newData($data['upgrade']);
                $colums = array();
		foreach ($table_new_data as $table) {
			// If table is not found create it
			if (array_search($table['name'], $this->structure->tables())) {
                $i = 0;     
				foreach ($table['field'] as $field) {
                  $columns[$table['name']][0] = 'z';
                  $columns[$table['name']][] = $field['name'];
                }
			}
	    }	
		  $deletecolumn = 0;
		  $oldcolumns = array();
		  if($this->upgrade == 1564){
		  	$oldcolumns = $this->structure->oldColumns1();
		  }
		  if($this->upgrade > 1564){
		  	$oldcolumns = $this->structure->oldColumns2();
		  }

	        foreach( $oldcolumns as $k => $val){
	            if(array_search($val['field'],$this->structure->columns($val['table']))){
                     $sql = "ALTER TABLE `" . $val['table'] . "` DROP COLUMN `" . $val['field'] . "`";

				                if( !$this->simulate ) {
                                  $this->db->query( $sql );
                                }
                                if( $this->showOps ) {
                                  $text .= '<p><pre>' . $sql .'</pre></p>';
                                }
                      ++$deletecolumn;
	        	}
	        }
	   $text .= $this->header( sprintf( $this->lang['msg_del_column'], $deletecolumn ) );
	  return $text;
	}
    private function msg( $data ){
       return str_replace( $data, '<div class="msg round">' . $data .'</div>', $data);
    }
    private function header( $data ){
       return str_replace( $data, '<div class="header round">' . $data .'</div>', $data);
   }
}
