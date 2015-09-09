<?php
class ModelUpgradeColumns extends Model{
    private $lang;
    private $simulate;
    private $showOps;

   public function addColumns( $data ){
        $this->lang = $this->lmodel->get('upgrade_database');

        $this->simulate = ( !empty( $data['simulate'] ) ? true : false );
        $this->showOps  = ( !empty( $data['showOps'] ) ? true : false );

    $altercounter = 0;
    $text = '';
    
    $vars = array(
		array(
         'table'     => 'address',
			'field'		=> 'custom_field',
			'column'	   => ' text NOT NULL'
		),
		array(
         'table'     => 'affiliate',
			'field'		=> 'salt',
			'column'	   => ' varchar(9) NOT NULL AFTER password'
		),
		array(
         'table'     => 'affiliate',
			'field'		=> 'filename',
			'column'	   => ' varchar(128) NOT NULL AFTER salt'
		),
		array(
         'table'     => 'banner_image',
			'field'		=> 'sort_order',
			'column'	   => ' int(3) NOT NULL'
		),
		array(
         'table'     => 'category',
			'field'		=> 'column',
			'column'	   => ' varchar(255) NOT NULL AFTER parent_id'
		),
		array(
         'table'     => 'category',
			'field'		=> 'top',
			'column'	   => ' tinyint(1) NOT NULL AFTER parent_id'
		),
		array(
         'table'     => 'category_description',
			'field'		=> 'meta_title',
			'column'	   => ' varchar(255) NOT NULL AFTER description'
		),
		array(
         'table'     => 'category_description',
			'field'		=> 'meta_keyword',
			'column'	   => ' varchar(255) NOT NULL AFTER meta_description'
		),
		array(
         'table'     => 'coupon',
			'field'		=> 'name',
			'column'	   => ' varchar(128) NOT NULL AFTER coupon_id'
		),
		array(
         'table'     => 'country',
			'field'		=> 'postcode_required',
			'column'	   => ' tinyint(1) NOT NULL AFTER address_format'
		),
		array(
         'table'     => 'custom_field',
			'field'		=> 'status',
			'column'	   => ' tinyint(1) NOT NULL AFTER location'
		),
       array(
    	   'table'     => 'custom_field_customer_group',
	      'field'     => 'required',
    	   'column'    => ' tinyint(1) NOT NULL'
   	),
		array(
         'table'     => 'customer',
			'field'		=> 'custom_field',
			'column'	   => ' text NOT NULL AFTER address_id'
		),
		array(
         'table'     => 'customer',
			'field'		=> 'salt',
			'column'	   => ' varchar(9) NOT NULL AFTER password'
		),
		array(
         'table'     => 'customer',
			'field'		=> 'safe',
			'column'	   => ' tinyint(1) NOT NULL'
		),
		array(
         'table'     => 'customer',
			'field'		=> 'token',
			'column'	   => ' varchar(255) NOT NULL AFTER approved'
		),
		array(
         'table'     => 'customer',
			'field'		=> 'wishlist',
			'column'	   => ' text AFTER cart'
		),
		array(
         'table'     => 'information',
			'field'		=> 'bottom',
			'column'	   => ' int(1) NOT NULL AFTER information_id'
		),
		array(
         'table'     => 'information_description',
			'field'		=> 'meta_title',
			'column'	   => ' varchar(255) NOT NULL'
		),
		array(
         'table'     => 'information_description',
			'field'		=> 'meta_description',
			'column'	   => ' varchar(255) NOT NULL'
		),
		array(
         'table'     => 'information_description',
			'field'		=> 'meta_keyword',
			'column'	   => ' varchar(255) NOT NULL'
		),
		array(
         'table'     => 'customer_group',
			'field'		=> 'approval',
			'column'	   => ' int(1) NOT NULL'
		),
		array(
         'table'     => 'customer_group',
			'field'		=> 'sort_order',
			'column'	   => ' int(3) NOT NULL'
		),
		array(
         'table'     => 'modification',
			'field'		=> 'xml',
			'column'	   => ' mediumtext NOT NULL AFTER link'
		),
		array(
         'table'     => 'option_value',
			'field'		=> 'image',
			'column'	   => ' varchar(255) NOT NULL AFTER option_id'
		),
		array(
         'table'     => 'order',
			'field'		=> 'payment_code',
			'column'	   => ' varchar(128) NOT NULL'
		),
		array(
         'table'     => 'order',
			'field'		=> 'affiliate_id',
			'column'	   => ' int(11) NOT NULL AFTER order_status_id'
		),
		array(
         'table'     => 'order',
			'field'		=> 'custom_field',
			'column'	   => ' text NOT NULL'
		),
		array(
         'table'     => 'order',
			'field'		=> 'commission',
			'column'	   => ' decimal(15,8) NOT NULL AFTER order_status_id'
		),
		array(
         'table'     => 'order',
			'field'		=> 'payment_custom_field',
			'column'	   => ' text NOT NULL'
		),
		array(
         'table'     => 'order',
			'field'		=> 'shipping_custom_field',
			'column'	   => ' text NOT NULL'
		),
		array(
         'table'     => 'order',
			'field'		=> 'shipping_code',
			'column'	   => ' varchar(128) NOT NULL'
		),
		array(
         'table'     => 'order',
			'field'		=> 'forwarded_ip',
			'column'	   => ' varchar(40) NOT NULL'
		),
		array(
         'table'         => 'order',
			'field'		=> 'user_agent',
			'column'	   => ' varchar(255) NOT NULL'
		),
		array(
         'table'     => 'order',
			'field'		=> 'accept_language',
			'column'	   => ' varchar(255) NOT NULL'
		),
		array(
         'table'     => 'order',
			'field'		=> 'marketing_id',
			'column'	   => ' int(11) NOT NULL'
		),
		array(
         'table'     => 'order',
			'field'		=> 'tracking',
			'column'	   => ' varchar(64) NOT NULL'
		),
		array(
         'table'     => 'order',
			'field'		=> 'currency_value',
			'column'	   => ' decimal(15,8) NOT NULL DEFAULT \'1.00000000\''
		),
		array(
         'table'     => 'order_product',
			'field'		=> 'reward',
			'column'	   => ' int(8) NOT NULL'
		),
		array(
         'table'     => 'order_recurring_transaction',
			'field'		=> 'reference',
			'column'	   => ' varchar(255) NOT NULL AFTER order_recurring_id'
		),
		array(
         'table'     => 'order_total',
			'field'		=> 'code',
			'column'	   => ' varchar(32) NOT NULL'
		),
		array(
         'table'     => 'product',
			'field'		=> 'mpn',
			'column'	   => ' varchar(64) NOT NULL AFTER sku'
		),
		array(
         'table'     => 'product',
			'field'		=> 'isbn',
			'column'	   => ' varchar(17) NOT NULL AFTER sku'
		),
		array(
         'table'     => 'product',
			'field'		=> 'jan',
			'column'	   => ' varchar(13) NOT NULL AFTER sku'
		),
		array(
         'table'     => 'product',
			'field'		=> 'ean',
			'column'	   => ' varchar(14) NOT NULL AFTER sku'
		),
		array(
         'table'     => 'product',
			'field'		=> 'upc',
			'column'	   => ' varchar(12) NOT NULL AFTER sku'
		),
		array(
         'table'     => 'product',
			'field'		=> 'minimum',
			'column'	   => ' int(11) NOT NULL AFTER length_class_id'
		),
		array(
         'table'     => 'product',
			'field'		=> 'points',
			'column'	   => ' int(8) NOT NULL AFTER price'
		),
		array(
         'table'     => 'product',
			'field'		=> 'subtract',
			'column'	   => ' tinyint(1) NOT NULL AFTER length_class_id'
		),
		array(
         'table'     => 'product',
			'field'		=> 'sort_order',
			'column'	   => ' int(11) NOT NULL AFTER viewed'
		),
		array(
         'table'     => 'product_image',
			'field'		=> 'sort_order',
			'column'	   => ' int(3) NOT NULL'
		),
		array(
         'table'     => 'product_description',
			'field'		=> 'meta_title',
			'column'	   => ' varchar(255) NOT NULL AFTER description'
		),
		array(
         'table'     => 'product_description',
			'field'		=> 'meta_keyword',
			'column'	   => ' varchar(255) NOT NULL AFTER meta_title'
		),
		array(
         'table'     => 'product_description',
			'field'		=> 'tag',
			'column'	   => ' text NOT NULL AFTER description'
		),
		array(
         'table'     => 'product_option',
			'field'		=> 'option_id',
			'column'	   => ' int(11) NOT NULL AFTER product_id'
		),
		array(
         'table'     => 'product_option',
			'field'		=> 'value',
			'column'	   => ' text NOT NULL'
		),
		array(
         'table'     => 'product_option',
			'field'		=> 'required',
			'column'	   => ' tinyint(1) NOT NULL'
		),
		array(
         'table'     => 'product_option_value',
			'field'		=> 'option_value_id',
			'column'	   => ' int(11) NOT NULL AFTER product_id'
		),
		array(
         'table'     => 'product_option_value',
			'field'		=> 'option_id',
			'column'	   => ' int(11) NOT NULL AFTER product_id'
		),
		array(
         'table'     => 'product_option_value',
			'field'		=> 'weight_prefix',
			'column' 	=> ' varchar(1) NOT NULL AFTER price'
		),
		array(
         'table'     => 'product_option_value',
			'field'		=> 'weight',
			'column'	   => ' decimal(15,8) NOT NULL AFTER price'
		),
		array(
         'table'     => 'product_option_value',
			'field'		=> 'points_prefix',
			'column'	   => ' varchar(1) NOT NULL AFTER price'
		),
		array(
         'table'     => 'product_option_value',
			'field'		=> 'points',
			'column'	   => ' int(8) NOT NULL AFTER price'
		),
		array(
         'table'     => 'product_option_value',
			'field'		=> 'price_prefix',
			'column'	   => ' varchar(1) NOT NULL AFTER price'
		),
		array(
         'table'     => 'product_recurring',
			'field'		=> 'customer_group_id',
			'column'	   => ' int(11) NOT NULL'
		),
		array(
         'table'     => 'product_recurring',
			'field'		=> 'recurring_id',
			'column'	   => ' int(11) NOT NULL'
		),
		array(
         'table'         => 'return',
			'field'		=> 'product_id',
			'column'	   => ' int(11) NOT NULL'
		),
		array(
         'table'     => 'return',
			'field'		=> 'product',
			'column'	   => ' varchar(255) NOT NULL'
		),
		array(
         'table'     => 'return',
			'field'		=> 'model',
			'column'	   => ' varchar(64) NOT NULL'
		),
		array(
         'table'     => 'return',
			'field'		=> 'quantity',
			'column'	   => ' int(4) NOT NULL'
		),
		array(
         'table'     => 'return',
			'field'		=> 'opened',
			'column'	   => ' tinyint(1) NOT NULL'
		),
		array(
         'table'     => 'return',
			'field'		=> 'return_reason_id',
			'column'	   => ' int(11) NOT NULL'
		),
		array(
         'table'     => 'return',
			'field'		=> 'return_action_id',
			'column'	   => ' int(11) NOT NULL'
		),
		array(
         'table'     => 'setting',
			'field'		=> 'code',
			'column'	   => ' varchart(32) NOT NULL'
		),
		array(
         'table'     => 'setting',
			'field'		=> 'serialized',
			'column'	   => ' tinyint(1) NOT NULL'
		),
		array(
         'table'     => 'setting',
			'field'		=> 'store_id',
			'column' 	=> ' int(11) NOT NULL AFTER setting_id'
		),
		array(
         'table'     => 'tax_rate',
			'field'		=> 'name',
			'column'	   => ' varchar(32) NOT NULL AFTER geo_zone_id'
		),
		array(
         'table'     => 'tax_rate',
			'field'		=> 'type',
			'column'	   => ' char(1) NOT NULL AFTER rate'
		),
		array(
         'table'     => 'user',
			'field'		=> 'code',
			'column'	   => ' varchar(40) NOT NULL AFTER email'
		),
		array(
         'table'     => 'user',
			'field'		=> 'image',
			'column'	   => ' varchar(255) NOT NULL AFTER email'
		),
		array(
         'table'     => 'user',
			'field'		=> 'salt',
			'column'	   => ' varchar(9) NOT NULL AFTER password'
		)
	);

  
  foreach( $vars as $k => $v ) {
	   if( array_search( DB_PREFIX . $v['table'], $this->structure->tables() ) || $v['table'] == 'address' ){
             if( !array_search( $v['field'], $this->structure->columns( $v['table'] ) )) {
			$sql = '
			ALTER TABLE
				`' . DB_PREFIX . $v['table'] . '`
			ADD COLUMN
				`' . $v['field'] . '`' . $v['column'];
								if( !$this->simulate ) {
                               $this->db->query( $sql );
                        }
                        if( $this->showOps ) {
                               $text .= '<p><pre>' . $sql .'</pre></p>';
                        }
			++$altercounter;

	       if( $v['table'] == 'category' && $v['field'] == 'top' ){
			$sql = '
			UPDATE
				`' . DB_PREFIX . $v['table'] . '`
			SET
				`top` = \'1\'';
											if( !$this->simulate ) {
			                               $this->db->query( $sql );
			                        }
			                        if( $this->showOps ) {
			                               $text .= '<p><pre>' . $sql .'</pre></p>';
			                        }
          }
			$text .= $this->msg( sprintf( $this->lang['msg_column'], $v['field'],  $v['table'] ) );
                        $this->cache->delete( $v['table'] );

	       }
      }
	}

		// --- UPDATE NEW COLUMNS ---

     $up = $this->db->query("SELECT * FROM ". DB_PREFIX . "product_description");
     if(count($up->rows) > 0){
			  	$sql = "UPDATE `". DB_PREFIX . "product_description` SET `meta_title` = `name`";
              	
								if( !$this->simulate ) {
                               $this->db->query( $sql );
                        }
                        if( $this->showOps ) {
                               $text .= '<p><pre>' . $sql .'</pre></p>';
                        }
     }
     $ups = $this->db->query("SELECT * FROM ". DB_PREFIX . "category_description");
     if(count($ups->rows) > 0){
			  	$sql = "UPDATE `". DB_PREFIX . "category_description` SET `meta_title` = `name`";
	           	
								if( !$this->simulate ) {
                               $this->db->query( $sql );
                        }
                        if( $this->showOps ) {
                               $text .= '<p><pre>' . $sql .'</pre></p>';
                        }
        }
     $ups2 = $this->db->query("SELECT * FROM ". DB_PREFIX . "product_option");
     if(count($ups2->rows) > 0){
			  	$sql = "UPDATE `". DB_PREFIX . "product_option` SET `value` = `option_value`";
	           	
								if( !$this->simulate ) {
                               $this->db->query( $sql );
                        }
                        if( $this->showOps ) {
                               $text .= '<p><pre>' . $sql .'</pre></p>';
                        }
     }
			  $sql = "UPDATE `". DB_PREFIX . "setting` SET `code` = `group`";
	           
								if( !$this->simulate ) {
                               $this->db->query( $sql );
                        }
                        if( $this->showOps ) {
                               $text .= '<p><pre>' . $sql .'</pre></p>';
                        }
	        
	       // Tables tax_rate, tax_rule and tax_rate_to_customer_group   
	       // Move data
          if( array_search( 'description', $this->structure->columns( 'tax_rate' ) ) ) {
          	$sql = "UPDATE `". DB_PREFIX . "tax_rate` SET `name` = `description`";
								if( !$this->simulate ) {
                               $this->db->query( $sql );
                        }
                        if( $this->showOps ) {
                               $text .= '<p><pre>' . $sql .'</pre></p>';
                        }
	                                              
     $sql = "
             INSERT INTO
                        `" . DB_PREFIX . "tax_rule`  (`tax_class_id`, `tax_rate_id`, `based`,`priority`)
             SELECT
                        `tax_class_id`, `tax_rate_id`, 'shipping, `priority`
             FROM
                        `" . DB_PREFIX . "tax_rate`";    

								if( !$this->simulate ) {
                               $this->db->query( $sql );
                        }
                        
                        if( $this->showOps ) {
                               $text .= '<p><hr></p>';
                               $text .= '<p><pre>' . $sql .'</pre></p>';
                        }
                        
               $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_group`");
               foreach($query->rows as $row){                                    
     $sql = "
             INSERT INTO
                        `" . DB_PREFIX . "tax_rate_to_customer_group`  (`tax_rate_id`, `customer_group_id`)
             SELECT
                        `tax_rate_id`, '" . $row['customer_group_id'] ."'
             FROM
                        `" . DB_PREFIX . "tax_rate`"; 
                       
								if( !$this->simulate ) {
                               $this->db->query( $sql );
                        }
                        if( $this->showOps ) {
                               $text .= '<p><hr></p>';
                               $text .= '<p><pre>' . $sql .'</pre></p>';
                        } 
               }
             }

	if( array_search( DB_PREFIX . 'return_product', $this->structure->tables() ) ) {

       if( count( $query->rows ) > 0 ){                               
     $sql = "
             UPDATE
                        `" . DB_PREFIX . "return`  (`product_id`, `name`,`model`,`quantity`,`opened`,`return_reason_id`,`return_action_id`,`comment` )
             SELECT
                        `product_id`, `name`,`model`,`quantity`,`opened`,`return_reason_id`,`return_action_id`,`comment` 
             FROM
                        `" . DB_PREFIX . "return_product`"; 

					  if( !$this->simulate ) {
                         $this->db->query( $sql );
                  }
                  if( $this->showOps ) {
                         $text .= '<p><pre>' . $sql .'</pre></p>';
                  }

		  $text .= $this->msg( sprintf( $this->lang['msg_text'], $this->data['msg_new_data'],  DB_PREFIX . 'return' ) );

             }
       }
	$text .= '<div class="header round"> ';
	$text .= sprintf( $this->lang['msg_col_counter'], $altercounter, '' );
        $text .= ' </div>';
      return $text;
  }

  private function msg( $data ){
       $data = str_replace( $data, '<div class="msg round"> ' . $data .' </div>', $data);
       return $data;
  }

}
