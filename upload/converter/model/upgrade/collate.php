<?php
class ModelUpgradeCollate extends Model{
  private $lang;
  private $simulate;
  private $showOps;
  private $version;
  private $collatecounter;
  private $columncollatecounter;
  private $max;
  private $min;

  public function addCollate( $data ) {  
        $this->simulate = ( !empty( $data['simulate'] ) ? true : false );
        $this->showOps  = ( !empty( $data['showOps'] ) ? true : false );
        $this->upgrade  = $data['upgrade'];
        $this->max = 9;
        $this->min = 4;
        $level = $this->structure->getVersion();
        
		    $this->version = $level['level'];
        $this->lang = $this->lmodel->get('upgrade_database');

        $this->collatecounter = 0;
        $this->columncollatecounter = 0;
        $text = '';
       
       $text .= $this->collateTable();
	$text .= '<div class="header round"> ';
        $text .=  sprintf( addslashes($this->lang['msg_collate_count']), $this->collatecounter, '' );
        $text .= ' </div>';
        
       $text .= $this->collateColumn();
	$text .= '<div class="header round"> ';
        $text .=  sprintf( addslashes($this->lang['msg_column_collate_count']), $this->columncollatecounter, '' );
        $text .= ' </div>';
        
        return $text;
  }

  public function collateTable(){
  	 $text = '';
  	    $tables = array();
  	    $tables['start'] = array('address',
			  	                    'category',
			  	                    'category_description',
			  	                    'category_to_store',
			  	                    'customer',
			  	                    'customer_group',
			  	                    'country',
			  	                    'coupon',
			  	                    'coupon_product',
			  	                    'currency',
			  	                    'download',
			  	                    'download_description',
			  	                    'extension',
			  	                    'geo_zone',
			  	                    'information',
			  	                    'information_description',
			  	                    'information_to_store',
			  	                    'language',
			  	                    'length_class',
			  	                    'length_class_description',
			  	                    'manufacturer',
			  	                    'manufacturer_to_store',
			  	                    'order',
			  	                    'order_history',
			  	                    'order_option',
			  	                    'order_product',
			  	                    'order_status',
			  	                    'order_total',
			  	                    'product',
			  	                    'product_description',
			  	                    'product_discount',
			  	                    'product_image',
			  	                    'product_option',
			  	                    'product_option_value',
			  	                    'product_related',
			  	                    'product_special',
			  	                    'product_to_category',
			  	                    'product_to_download',
			  	                    'product_to_store',
			  	                    'review',
			  	                    'setting',
			  	                    'stock_status',
			  	                    'store',
			  	                    'tax_class',
			  	                    'tax_rate',
			  	                    'url_alias',
			  	                    'user',
			  	                    'user_group',
			  	                    'weight_class',
			  	                    'weight_class_description',
			  	                    'zone',
			  	                    'zone_to_geo_zone');
  	                    
  	    $tables['150'] = array('affiliate',
  	                          'affiliate_transaction',
  	                          'attribute',
  	                          'attribute_description',
  	                          'attribute_group',
  	                          'attribute_group_description',
  	                          'banner',
  	                          'banner_image',
  	                          'banner_image_description',
  	                          'category_to_layout',
  	                          'coupon_history',
  	                          'customer_ip',
  	                          'customer_reward',
  	                          'customer_transaction',
  	                          'information_to_layout',
  	                          'option',
  	                          'option_description',
  	                          'option_value',
  	                          'option_value_description',
  	                          'layout',
  	                          'layout_route',
  	                          'product_attribute',
  	                          'product_to_layout',
  	                          'return',
  	                          'return_action',
  	                          'return_history',
  	                          'return_reason',
  	                          'return_status',
  	                          'voucher',
  	                          'voucher_history',
  	                          'voucher_theme',
  	                          'voucher_theme_description'
  	                          );
  	                    
  	    $tables['1513'] = array('tax_rate_to_customer_group',
  	                            'tax_rule'
  	                           );
  	                    
  	    $tables['152'] = array('order_fraud',
  	                           'order_voucher'
  	                          );
  	                    
  	    $tables['153'] = array('customer_group_description'
  	                          );
  	                    
  	    $tables['154'] = array('customer_online'
  	                          );
  	                          
  	  if( $this->version > $this->min ){
  	  	   foreach($tables['start'] as $table){
  	  	   	$sql = "
  	  	   	ALTER TABLE `" . DB_PREFIX . $table ."` COLLATE `utf8_general_ci`";
  	  	   	
				 	 if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ){
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
  	  	          ++$this->collatecounter;
  	  	   }
  	  	   
  	  if( $this->version < $this->max ){
  	  	   foreach($tables['150'] as $table){
  	  	   	$sql =
  	  	   	"ALTER TABLE `" . DB_PREFIX . $table ."` COLLATE `utf8_general_ci`";
  	  	   	
				 	 if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ){
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
  	  	          ++$this->collatecounter;
  	  	   }
  	  }
  	  
  	  	   
  	  if( $this->version < $this->max - 1 ){
  	  	   foreach($tables['1513'] as $table){
  	  	   //	print_r($table);
  	  	   	$sql = "
  	  	   	ALTER TABLE `" . DB_PREFIX . $table ."` COLLATE `utf8_general_ci`";
  	  	   	
				 	 if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ){
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
  	  	          ++$this->collatecounter;
  	  	   }
  	  }
  	  	   
  	  if( $this->version < $this->max - 2 ){
  	  	   foreach($tables['152'] as $table){
  	  	   	$sql = "
  	  	   	ALTER TABLE `" . DB_PREFIX . $table ."` COLLATE `utf8_general_ci`";
  	  	   	
				 	 if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ){
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
  	  	          ++$this->collatecounter;
  	  	   }
  	  }
  	  	   
  	  if( $this->version < $this->max - 3 ){
  	  	   foreach($tables['153'] as $table){
  	  	   	$sql = "
  	  	   	ALTER TABLE `" . DB_PREFIX . $table ."` COLLATE `utf8_general_ci`";
  	  	   	
				 	 if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ){
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
  	  	          ++$this->collatecounter;
  	  	   }
  	  }
  	  	   
  	  if( $this->version < $this->max - 4 ){
  	  	   foreach($tables['154'] as $table){
  	  	   	$sql = "
  	  	   	ALTER TABLE `" . DB_PREFIX . $table ."` COLLATE `utf8_general_ci`";
  	  	   	
				 	 if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ){
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
  	  	          ++$this->collatecounter;
  	  	   }
  	  }
  	  
  	  
  	  }
      return  $text;
  }
  
  public function collateColumn() {  
        $text = '';
    $cols = array(
		array(
         'table'      => 'address',
			'field'		 => 'company',
			'column'	    => ' varchar(32) NOT NULL'
		),
		array(
         'table'      => 'address',
			'field'		 => 'firstname',
			'column'	    => ' varchar(32) NOT NULL'
		),
		array(
         'table'      => 'address',
			'field'		 => 'lastname',
			'column'	    => ' varchar(32) NOT NULL'
		),
		array(
         'table'      => 'address',
			'field'		 => 'address_1',
			'column'	    => ' varchar(128) NOT NULL'
		),
		array(
         'table'      => 'address',
			'field'		 => 'address_2',
			'column'     => ' varchar(128) NOT NULL'
		),
		array(
         'table'      => 'address',
			'field'		 => 'postcode',
			'column'	    => ' varchar(10) NOT NULL'
		),
		array(
         'table'      => 'address',
			'field'	    => 'city',
			'column'	    => ' varchar(128) NOT NULL AFTER'
		),
		array(
         'table'      => 'category',
			'field'		 => 'image',
			'column'	    => ' varchar(255) NOT NULL'
		),
		array(
         'table'      => 'category_description',
			'field'		 => 'name',
			'column'	    => ' varchar(32) NOT NULL'
		),
		array(
         'table'      => 'category_description',
			'field'		 => 'meta_description',
			'column'	    => ' varchar(255) NOT NULL'
		),
		array(
         'table'      => 'category_description',
			'field'		 => 'meta_keywords',
			'column'	    => ' varchar(255) NOT NULL'
		),
		array(
         'table'      => 'category_description',
			'field'		 => 'description',
			'column'	    => ' text NOT NULL'
		),
		array(
         'table'      => 'country',
			'field'		 => 'name',
			'column'	    => ' varchar(128) NOT NULL'
		),
		array(
         'table'      => 'country',
			'field'		 => 'iso_code_2',
			'column'	    => ' varchar(2) NOT NULL'
		),
		array(
         'table'      => 'country',
			'field'		 => 'iso_code_3',
			'column'	    => ' varchar(3) NOT NULL'
		),
		array(
         'table'      => 'coupon',
			'field'		 => 'code',
			'column'	    => ' varchar(10) NOT NULL'
		),
		array(
         'table'      => 'currency',
			'field'		 => 'title',
			'column'	    => ' varchar(32) NOT NULL'
		),
		array(
         'table'      => 'currency',
			'field'		 => 'code',
			'column'	    => ' varchar(3) NOT NULL'
		),
		array(
         'table'      => 'currency',
			'field'		 => 'symbol_left',
			'column'	    => ' varchar(12) NOT NULL'
		),
		array(
         'table'      => 'currency',
			'field'		 => 'symbol_right',
			'column'	    => ' varchar(12) NOT NULL'
		),
		array(
         'table'      => 'customer',
			'field'		 => 'firstname',
			'column'	    => ' varchar(32) NOT NULL'
		),
		array(
         'table'      => 'customer',
			'field'		 => 'lastname',
			'column'	    => ' varchar(32) NOT NULL'
		),
		array(
         'table'      => 'customer',
			'field'		 => 'email',
			'column'	    => ' varchar(96) NOT NULL'
		),
		array(
         'table'      => 'customer',
			'field'		 => 'telephone',
			'column'	    => ' varchar(32) NOT NULL'
		),
		array(
         'table'      => 'customer',
			'field'		 => 'fax',
			'column'	    => ' varchar(32) NOT NULL'
		),
		array(
         'table'      => 'customer',
			'field'		 => 'cart',
			'column'	    => ' text NOT NULL'
		),
		array(
         'table'      => 'download',
			'field'		 => 'filename',
			'column'	    => ' varchar(128) NOT NULL'
		),
		array(
         'table'      => 'download',
			'field'		 => 'mask',
			'column'	    => ' varchar(128) NOT NULL'
		),
		array(
         'table'      => 'download',
			'field'		 => 'filename',
			'column'	    => ' varchar(128) NOT NULL'
		),
		array(
         'table'      => 'download_description',
			'field'		 => 'name',
			'column'	    => ' varchar(64) NOT NULL'
		),
		array(
         'table'      => 'extension',
			'field'		 => 'type',
			'column'	    => ' varchar(32) NOT NULL'
		),
		array(
         'table'      => 'extension',
			'field'		 => 'key',
			'column'	    => ' varchar(32) NOT NULL'
		),
		array(
         'table'      => 'geo_zone',
			'field'		 => 'name',
			'column'	    => ' varchar(32) NOT NULL'
		),
		array(
         'table'      => 'geo_zone',
			'field'		 => 'description',
			'column'	    => ' varchar(255) NOT NULL'
		),
		array(
         'table'      => 'information_description',
			'field'		 => 'title',
			'column'	    => ' varchar(64) NOT NULL'
		),
		array(
         'table'      => 'information_description',
			'field'		 => 'description',
			'column'	    => ' text NOT NULL'
		),
		array(
         'table'      => 'language',
			'field'		 => 'name',
			'column'	    => ' varchar(32) NOT NULL'
		),
		array(
         'table'      => 'language',
			'field'		 => 'code',
			'column'	    => ' varchar(5) NOT NULL'
		),
		array(
         'table'      => 'language',
			'field'		 => 'locale',
			'column'	    => ' varchar(255) NOT NULL'
		),
		array(
         'table'      => 'language',
			'field'		 => 'image',
			'column'	    => ' varchar(64) NOT NULL'
		),
		array(
         'table'      => 'language',
			'field'		 => 'directory',
			'column'	    => ' varchar(32) NOT NULL'
		),
		array(
         'table'      => 'length_class_description',
			'field'		 => 'title',
			'column'	    => ' varchar(32) NOT NULL'
		),
		array(
         'table'      => 'length_class_description',
			'field'		 => 'unit',
			'column'	    => ' varchar(4) NOT NULL'
		),
		array(
         'table'      => 'manufacturer',
			'field'		 => 'name',
			'column'	    => ' varchar(64) NOT NULL'
		),
		array(
         'table'      => 'manufacturer',
			'field'		 => 'image',
			'column'	    => ' varchar(255) NOT NULL'
		),
		array(
         'table'      => 'order',
			'field'		 => 'store_name',
			'column'	    => ' varchar(64) NOT NULL'
		),
		array(
         'table'      => 'order',
			'field'		 => 'store_url',
			'column'	    => ' varchar(255) NOT NULL'
		),
		array(
         'table'      => 'order',
			'field'		 => 'firstname',
			'column'	    => ' varchar(32) NOT NULL'
		),
		array(
         'table'      => 'order',
			'field'		 => 'lastname',
			'column'	    => ' varchar(32) NOT NULL'
		),
		array(
         'table'      => 'order',
			'field'		 => 'telephone',
			'column'	    => ' varchar(32) NOT NULL'
		),
		array(
         'table'      => 'order',
			'field'		 => 'fax',
			'column'	    => ' varchar(32) NOT NULL'
		),
		array(
         'table'      => 'order',
			'field'		 => 'email',
			'column'	    => ' varchar(96) NOT NULL'
		),
		array(
         'table'      => 'order',
			'field'		 => 'payment_firstname',
			'column'	    => ' varchar(32) NOT NULL'
		),
		array(
         'table'      => 'order',
			'field'		 => 'payment_lastname',
			'column'	    => ' varchar(32) NOT NULL'
		),
		array(
         'table'      => 'order',
			'field'		 => 'payment_company',
			'column'	    => ' varchar(40) NOT NULL'
		),
		array(
         'table'      => 'order',
			'field'		 => 'payment_address_1',
			'column'	    => ' varchar(128) NOT NULL'
		),
		array(
         'table'      => 'order',
			'field'		 => 'payment_address_2',
			'column'	    => ' varchar(128) NOT NULL'
		),
		array(
         'table'      => 'order',
			'field'		 => 'payment_city',
			'column'	    => ' varchar(128) NOT NULL'
		),
		array(
         'table'      => 'order',
			'field'		 => 'payment_postcode',
			'column'	    => ' varchar(10) NOT NULL'
		),
		array(
         'table'      => 'order',
			'field'		 => 'payment_country',
			'column'	    => ' varchar(128) NOT NULL'
		),
		array(
         'table'      => 'order',
			'field'		 => 'payment_zone',
			'column'	    => ' varchar(128) NOT NULL'
		),
		array(
         'table'      => 'order',
			'field'		 => 'payment_method',
			'column'	    => ' varchar(128) NOT NULL'
		),
		array(
         'table'      => 'order',
			'field'		 => 'payment_address_format',
			'column'	    => ' text NOT NULL'
		),
		array(
         'table'      => 'order',
			'field'		 => 'shipping_firstname',
			'column'	    => ' varchar(32) NOT NULL'
		),
		array(
         'table'      => 'order',
			'field'		 => 'shipping_lastname',
			'column'	    => ' varchar(32) NOT NULL'
		),
		array(
         'table'      => 'order',
			'field'		 => 'shipping_company',
			'column'	    => ' varchar(32) NOT NULL'
		),
		array(
         'table'      => 'order',
			'field'		 => 'shipping_address_1',
			'column'	    => ' varchar(128) NOT NULL'
		),
		array(
         'table'      => 'order',
			'field'		 => 'shipping_address_2',
			'column'	    => ' varchar(128) NOT NULL'
		),
		array(
         'table'      => 'order',
			'field'		 => 'shipping_city',
			'column'	    => ' varchar(128) NOT NULL'
		),
		array(
         'table'      => 'order',
			'field'		 => 'shipping_postcode',
			'column'	    => ' varchar(10) NOT NULL'
		),
		array(
         'table'      => 'order',
			'field'		 => 'shipping_zone',
			'column'	    => ' varchar(128) NOT NULL'
		),
		array(
         'table'      => 'order',
			'field'		 => 'shipping_country',
			'column'	    => ' varchar(128) NOT NULL'
		),
		array(
         'table'      => 'order',
			'field'		 => 'shipping_method',
			'column'	    => ' varchar(128) NOT NULL'
		),
		array(
         'table'      => 'order',
			'field'		 => 'shipping_address_format',
			'column'	    => ' text NOT NULL'
		),
		array(
         'table'      => 'order',
			'field'		 => 'comment',
			'column'	    => ' text NOT NULL'
		),
		array(
         'table'      => 'order_history',
			'field'		 => 'comment',
			'column'	    => ' text NOT NULL'
		),
		array(
         'table'      => 'order_option',
			'field'		 => 'name',
			'column'	    => ' varchar(255) NOT NULL'
		),
		array(
         'table'      => 'order_option',
			'field'		 => 'value',
			'column'	    => ' varchar(255) NOT NULL'
		),
		array(
         'table'      => 'order_product',
			'field'		 => 'name',
			'column'	    => ' varchar(255) NOT NULL'
		),
		array(
         'table'      => 'order_option',
			'field'		 => 'prefix',
			'column'	    => ' char(1) NOT NULL'
		),
		array(
         'table'      => 'order_status',
			'field'		 => 'name',
			'column'	    => ' varchar(32) NOT NULL'
		),
		array(
         'table'      => 'order_total',
			'field'		 => 'title',
			'column'	    => ' varchar(255) NOT NULL'
		),
		array(
         'table'      => 'product',
			'field'		 => 'model',
			'column'	    => ' varchar(64) NOT NULL'
		),
		array(
         'table'      => 'product',
			'field'		 => 'sku',
			'column'	    => ' varchar(64) NOT NULL'
		),
		array(
         'table'      => 'product',
			'field'		 => 'location',
			'column'	    => ' varchar(128) NOT NULL'
		),
		array(
         'table'      => 'product',
			'field'		 => 'image',
			'column'	    => ' varchar(255) NOT NULL'
		),
		array(
         'table'      => 'product_description',
			'field'		 => 'name',
			'column'	    => ' varchar(255) NOT NULL'
		),
		array(
         'table'      => 'product_description',
			'field'		 => 'meta_description',
			'column'	    => ' varchar(255) NOT NULL'
		),
		array(
         'table'      => 'product_description',
			'field'		 => 'meta_keywords',
			'column'	    => ' varchar(255) NOT NULL'
		),
		array(
         'table'      => 'product_description',
			'field'		 => 'description',
			'column'	    => ' text NOT NULL'
		),
		array(
         'table'      => 'product_image',
			'field'		 => 'image',
			'column'	    => ' varchar(255) NOT NULL'
		),
		array(
         'table'      => 'review',
			'field'		 => 'author',
			'column'	    => ' varchar(64) NOT NULL'
		),
		array(
         'table'      => 'review',
			'field'		 => 'text',
			'column'	    => ' text NOT NULL'
		),
		array(
         'table'      => 'setting',
			'field'		 => 'group',
			'column'	    => ' varchar(32) NOT NULL'
		),
		array(
         'table'      => 'setting',
			'field'		 => 'key',
			'column'	    => ' varchar(64) NOT NULL'
		),
		array(
         'table'      => 'setting',
			'field'		 => 'value',
			'column'	    => ' text NOT NULL'
		),
		array(
         'table'      => 'stock_status',
			'field'		 => 'name',
			'column'	    => ' varchar(32) NOT NULL'
		),
		array(
         'table'      => 'store',
			'field'		 => 'name',
			'column'	    => ' varchar(64) NOT NULL'
		),
		array(
         'table'      => 'store',
			'field'		 => 'url',
			'column'	    => ' varchar(225) NOT NULL'
		),
		array(
         'table'      => 'tax_class',
			'field'		 => 'title',
			'column'	    => ' varchar(32) NOT NULL'
		),
		array(
         'table'      => 'tax_class',
			'field'		 => 'description',
			'column'	    => ' varchar(255) NOT NULL'
		),
		array(
         'table'      => 'tax_rate',
			'field'		 => 'description',
			'column'	    => ' varchar(255) NOT NULL'
		),
		array(
         'table'      => 'url_alias',
			'field'		 => 'query',
			'column'	    => ' varchar(255) NOT NULL'
		),
		array(
         'table'      => 'url_alias',
			'field'		 => 'keyword',
			'column'	    => ' varchar(255) NOT NULL'
		),
		array(
         'table'      => 'user',
			'field'		 => 'firstname',
			'column'	    => ' varchar(32) NOT NULL'
		),
		array(
         'table'      => 'user',
			'field'		 => 'lastname',
			'column'	    => ' varchar(32) NOT NULL'
		),
		array(
         'table'      => 'user',
			'field'		 => 'email',
			'column'	    => ' varchar(96) NOT NULL'
		),
		array(
         'table'      => 'user',
			'field'		 => 'ip',
			'column'	    => ' varchar(40) NOT NULL'
		),
		array(
         'table'      => 'user_group',
			'field'		 => 'name',
			'column'	    => ' varchar(64) NOT NULL'
		),
		array(
         'table'      => 'user_group',
			'field'		 => 'permission',
			'column'	    => ' text NOT NULL'
		),
		array(
         'table'      => 'weight_class_description',
			'field'		 => 'title',
			'column'	    => ' varchar(32) NOT NULL'
		),
		array(
         'table'      => 'weight_class_description',
			'field'		 => 'unit',
			'column'	    => ' varchar(4) NOT NULL'
		),
		array(
         'table'      => 'zone',
			'field'		 => 'name',
			'column'	    => ' varchar(128) NOT NULL'
		),
		array(
         'table'      => 'zone',
			'field'		 => 'code',
			'column'	    => ' varchar(32) NOT NULL'
		)
		);
 
    $cols2 = array(
		array(
         'table'      => 'affiliate',
			'field'		 => 'firstname',
			'column'	    => ' varchar(32) NOT NULL'
		), 
		array(
         'table'      => 'affiliate',
			'field'		 => 'lastname',
			'column'	    => ' varchar(32) NOT NULL'
		), 
		array(
         'table'      => 'affiliate',
			'field'		 => 'email',
			'column'	    => ' varchar(96) NOT NULL'
		), 
		array(
         'table'      => 'affiliate',
			'field'		 => 'telephone',
			'column'	    => ' varchar(32) NOT NULL'
		), 
		array(
         'table'      => 'affiliate',
			'field'		 => 'fax',
			'column'	    => ' varchar(32) NOT NULL'
		), 
		array(
         'table'      => 'affiliate',
			'field'		 => 'company',
			'column'	    => ' varchar(32) NOT NULL'
		), 
		array(
         'table'      => 'affiliate',
			'field'		 => 'website',
			'column'	    => ' varchar(255) NOT NULL'
		), 
		array(
         'table'      => 'affiliate',
			'field'		 => 'address_1',
			'column'	    => ' varchar(128) NOT NULL'
		), 
		array(
         'table'      => 'affiliate',
			'field'		 => 'address_2',
			'column'	    => ' varchar(128) NOT NULL'
		), 
		array(
         'table'      => 'affiliate',
			'field'		 => 'city',
			'column'	    => ' varchar(32) NOT NULL'
		), 
		array(
         'table'      => 'affiliate',
			'field'		 => 'postcode',
			'column'	    => ' varchar(10) NOT NULL'
		), 
		array(
         'table'      => 'affiliate',
			'field'		 => 'code',
			'column'	    => ' varchar(64) NOT NULL'
		), 
		array(
         'table'      => 'affiliate',
			'field'		 => 'tax',
			'column'	    => ' varchar(64) NOT NULL'
		), 
		array(
         'table'      => 'affiliate',
			'field'		 => 'payment',
			'column'	    => ' varchar(6) NOT NULL'
		), 
		array(
         'table'      => 'affiliate',
			'field'		 => 'bank_name',
			'column'	    => ' varchar(64) NOT NULL'
		), 
		array(
         'table'      => 'affiliate',
			'field'		 => 'cheque',
			'column'	    => ' varchar(100) NOT NULL'
		), 
		array(
         'table'      => 'affiliate',
			'field'		 => 'paypal',
			'column'	    => ' varchar(64) NOT NULL'
		), 
		array(
         'table'      => 'affiliate',
			'field'		 => 'bank_branch_number',
			'column'	    => ' varchar(64) NOT NULL'
		), 
		array(
         'table'      => 'affiliate',
			'field'		 => 'bank_swift_code',
			'column'	    => ' varchar(64) NOT NULL'
		), 
		array(
         'table'      => 'affiliate',
			'field'		 => 'bank_account_name',
			'column'	    => ' varchar(64) NOT NULL'
		), 
		array(
         'table'      => 'affiliate',
			'field'		 => 'bank_account_number',
			'column'	    => ' varchar(15) NOT NULL'
		), 
		array(
         'table'      => 'affiliate',
			'field'		 => 'ip',
			'column'	    => ' varchar(64) NOT NULL'
		), 
		array(
         'table'      => 'affiliate_transaction',
			'field'		 => 'description',
			'column'	    => ' text NOT NULL'
		), 
		array(
         'table'      => 'attribute_description',
			'field'		 => 'name',
			'column'	    => ' varchar(64) NOT NULL'
		),  
		array(
         'table'      => 'attribute_group_description',
			'field'		 => 'name',
			'column'	    => ' varchar(64) NOT NULL'
		),  
		array(
         'table'      => 'banner',
			'field'		 => 'name',
			'column'	    => ' varchar(64) NOT NULL'
		),  
		array(
         'table'      => 'banner_image',
			'field'		 => 'link',
			'column'	    => ' varchar(255) NOT NULL'
		), 
		array(
         'table'      => 'banner_image',
			'field'		 => 'image',
			'column'	    => ' varchar(255) NOT NULL'
		), 
		array(
         'table'      => 'banner_image_description',
			'field'		 => 'title',
			'column'	    => ' varchar(64) NOT NULL'
		), 
		array(
         'table'      => 'category_description',
			'field'		 => 'meta_keyword',
			'column'	    => ' varchar(255) NOT NULL'
		),
		array(
         'table'      => 'customer',
			'field'		 => 'wishlist',
			'column'	    => ' text NOT NULL'
		), 
		array(
         'table'      => 'extension',
			'field'		 => 'code',
			'column'	    => ' varchar(32) NOT NULL'
		),  
		array(
         'table'      => 'layout',
			'field'		 => 'name',
			'column'	    => ' varchar(64) NOT NULL'
		),  
		array(
         'table'      => 'layout_route',
			'field'		 => 'route',
			'column'	    => ' varchar(255) NOT NULL'
		),  
		array(
         'table'      => 'option',
			'field'		 => 'type',
			'column'	    => ' varchar(32) NOT NULL'
		), 
		array(
         'table'      => 'option_description',
			'field'		 => 'name',
			'column'	    => ' varchar(128) NOT NULL'
		), 
		array(
         'table'      => 'option_value_description',
			'field'		 => 'name',
			'column'	    => ' varchar(128) NOT NULL'
		),  
		array(
         'table'      => 'order_total',
			'field'		 => 'code',
			'column'	    => ' varchar(32) NOT NULL'
		),  
		array(
         'table'      => 'product',
			'field'		 => 'upc',
			'column'	    => ' varchar(12) NOT NULL'
		), 
		array(
         'table'      => 'product_attribute',
			'field'		 => 'text',
			'column'	    => ' text NOT NULL'
		), 
		array(
         'table'      => 'product_description',
			'field'		 => 'meta_keyword',
			'column'	    => ' varchar(255) NOT NULL'
		),
		array(
         'table'      => 'return',
			'field'		 => 'firstname',
			'column'	    => ' varchar(32) NOT NULL'
		),
		array(
         'table'      => 'return',
			'field'		 => 'lastname',
			'column'	    => ' varchar(32) NOT NULL'
		),
		array(
         'table'      => 'return',
			'field'		 => 'telephone',
			'column'	    => ' varchar(32) NOT NULL'
		),
		array(
         'table'      => 'return',
			'field'		 => 'email',
			'column'	    => ' varchar(96) NOT NULL'
		),
		array(
         'table'      => 'return',
			'field'		 => 'comment',
			'column'	    => ' text NOT NULL'
		),
		array(
         'table'      => 'return_action',
			'field'		 => 'name',
			'column'	    => ' varchar(64) NOT NULL'
		),
		array(
         'table'      => 'return_history',
			'field'		 => 'comment',
			'column'	    => ' text NOT NULL'
		),
		array(
         'table'      => 'return_reason',
			'field'		 => 'name',
			'column'	    => ' varchar(128) NOT NULL'
		),
		array(
         'table'      => 'return_status',
			'field'		 => 'name',
			'column'	    => ' varchar(32) NOT NULL'
		)
	 );           
	 $text = '';             
  	  if( $this->version > $this->min ){
	      foreach( $cols as $k => $v ) {
						              if( array_search( $v['field'], $this->structure->columns( $v['table'] ) )) { 	
				$sql = "
				        ALTER TABLE `" . DB_PREFIX . $v['table'] . "` MODIFY `" . $v['field'] . "`" . $v['column'] . " COLLATE `utf8_general_ci`";
										              if( !$this->simulate )  {
							                               $this->db->query( $sql );
							                        }
							                        if( $this->showOps ) {
							                               $text .= '<p><pre>' . $sql .'</pre></p>';
							                        }
							                        ++$this->columncollatecounter;
								    	   }
        }
              
		  	  if( $this->version < $this->max){
					      foreach( $cols2 as $k => $v ) {
						              if( array_search( $v['field'], $this->structure->columns( $v['table'] ) )) { 	
				$sql = "
				        ALTER TABLE `" . DB_PREFIX . $v['table'] . "` MODIFY `" . $v['field'] . "`" . $v['column'] . " COLLATE `utf8_general_ci`";
											              if( !$this->simulate )  {
								                               $this->db->query( $sql );
								                        }
								                        if( $this->showOps ) {
								                               $text .= '<p><pre>' . $sql .'</pre></p>';
								                        }
								                        ++$this->columncollatecounter;
								    	   }
					      }
		    }	  
    }	  
    return $text;                  
  }
}
