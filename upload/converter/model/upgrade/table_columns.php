<?php
class ModelUpgradeTableColumns extends Model{
    private $lang;
    private $simulate;
    private $showOps;

   public function addColumns( $data ){
        $this->lang = $this->lmodel->get('upgrade_database');

        $this->simulate = ( !empty( $data['simulate'] ) ? true : false );
        $this->showOps  = ( !empty( $data['showOps'] ) ? true : false );
        $this->upgrade2020  = ( !empty( $data['upgrade2020'] ) ? true : false );
        $this->upgrade2030  = ( !empty( $data['upgrade2030'] ) ? true : false );

    $vars = array(
		array(
                        'table'         => 'address',
			'field'		=> 'custom_field',
			'column'	=> ' text NOT NULL'
		),
		array(
                        'table'         => 'affiliate',
			'field'		=> 'salt',
			'column'	=> ' varchar(9) NOT NULL AFTER password'
		),
		array(
                        'table'         => 'affiliate',
			'field'		=> 'filename',
			'column'	=> ' varchar(128) NOT NULL AFTER salt'
		),
		array(
                        'table'         => 'banner_image',
			'field'		=> 'sort_order',
			'column'	=> ' int(3) NOT NULL'
		),
		array(
                        'table'         => 'category',
			'field'		=> 'column',
			'column'	=> ' varchar(255) NOT NULL AFTER parent_id'
		),
		array(
                        'table'         => 'category',
			'field'		=> 'top',
			'column'	=> ' tinyint(1) NOT NULL AFTER parent_id'
		),
		array(
                        'table'         => 'category_description',
			'field'		=> 'meta_title',
			'column'	=> ' varchar(255) NOT NULL AFTER description'
		),
		array(
                        'table'         => 'category_description',
			'field'		=> 'meta_keyword',
			'column'	=> ' varchar(255) NOT NULL AFTER meta_description'
		),
		array(
                        'table'         => 'coupon',
			'field'		=> 'name',
			'column'	=> ' varchar(128) NOT NULL AFTER coupon_id'
		),
		array(
                        'table'         => 'country',
			'field'		=> 'postcode_required',
			'column'	=> ' tinyint(1) NOT NULL AFTER address_format'
		),
		array(
                        'table'         => 'custom_field',
			'field'		=> 'status',
			'column'	=> ' tinyint(1) NOT NULL AFTER location'
		),
                array(
                	'table'         => 'custom_field_customer_group',
            	        'field'         => 'required',
                	'column'        => ' tinyint(1) NOT NULL'
         	),
		array(
                        'table'         => 'customer',
			'field'		=> 'custom_field',
			'column'	=> ' text NOT NULL AFTER address_id'
		),
		array(
                        'table'         => 'customer',
			'field'		=> 'salt',
			'column'	=> ' varchar(9) NOT NULL AFTER password'
		),
		array(
                        'table'         => 'customer',
			'field'		=> 'safe',
			'column'	=> ' tinyint(1) NOT NULL'
		),
		array(
                        'table'         => 'customer',
			'field'		=> 'token',
			'column'	=> ' varchar(255) NOT NULL AFTER approved'
		),
		array(
                        'table'         => 'customer',
			'field'		=> 'wishlist',
			'column'	=> ' text AFTER cart'
		),
		array(
                        'table'         => 'information',
			'field'		=> 'bottom',
			'column'	=> ' int(1) NOT NULL AFTER information_id'
		),
		array(
                        'table'         => 'information_description',
			'field'		=> 'meta_title',
			'column'	=> ' varchar(255) NOT NULL'
		),
		array(
                        'table'         => 'information_description',
			'field'		=> 'meta_description',
			'column'	=> ' varchar(255) NOT NULL'
		),
		array(
                        'table'         => 'information_description',
			'field'		=> 'meta_keyword',
			'column'	=> ' varchar(255) NOT NULL'
		),
		array(
                        'table'         => 'customer_group',
			'field'		=> 'approval',
			'column'	=> ' int(1) NOT NULL'
		),
		array(
                        'table'         => 'customer_group',
			'field'		=> 'sort_order',
			'column'	=> ' int(3) NOT NULL'
		),
		array(
                        'table'         => 'modification',
			'field'		=> 'xml',
			'column'	=> ' mediumtext NOT NULL AFTER link'
		),
		array(
                        'table'         => 'option_value',
			'field'		=> 'image',
			'column'	=> ' varchar(255) NOT NULL AFTER option_id'
		),
		array(
                        'table'         => 'order',
			'field'		=> 'payment_code',
			'column'	=> ' varchar(128) NOT NULL'
		),
		array(
                        'table'         => 'order',
			'field'		=> 'affiliate_id',
			'column'	=> ' int(11) NOT NULL AFTER order_status_id'
		),
		array(
                        'table'         => 'order',
			'field'		=> 'custom_field',
			'column'	=> ' text NOT NULL'
		),
		array(
                        'table'        => 'order',
			'field'		=> 'commission',
			'column'	=> ' decimal(15,8) NOT NULL AFTER order_status_id'
		),
		array(
                        'table'         => 'order',
			'field'		=> 'payment_custom_field',
			'column'	=> ' text NOT NULL'
		),
		array(
                        'table'         => 'order',
			'field'		=> 'shipping_custom_field',
			'column'	=> ' text NOT NULL'
		),
		array(
                        'table'         => 'order',
			'field'		=> 'shipping_code',
			'column'	=> ' varchar(128) NOT NULL'
		),
		array(
                        'table'         => 'order',
			'field'		=> 'forwarded_ip',
			'column'	=> ' varchar(40) NOT NULL'
		),
		array(
                        'table'         => 'order',
			'field'		=> 'user_agent',
			'column'	=> ' varchar(255) NOT NULL'
		),
		array(
                        'table'         => 'order',
			'field'		=> 'accept_language',
			'column'	=> ' varchar(255) NOT NULL'
		),
		array(
                        'table'         => 'order',
			'field'		=> 'marketing_id',
			'column'	=> ' int(11) NOT NULL'
		),
		array(
                        'table'         => 'order',
			'field'		=> 'tracking',
			'column'	=> ' varchar(64) NOT NULL'
		),
		array(
                        'table'         => 'order',
			'field'		=> 'currency_value',
			'column'	=> ' decimal(15,8) NOT NULL DEFAULT \'1.00000000\''
		),
		array(
                        'table'         => 'order_product',
			'field'		=> 'reward',
			'column'	=> ' int(8) NOT NULL'
		),
		array(
                        'table'         => 'order_recurring_transaction',
			'field'		=> 'reference',
			'column'	=> ' varchar(255) NOT NULL AFTER order_recurring_id'
		),
		array(
                        'table'         => 'product',
			'field'		=> 'mpn',
			'column'	=> ' varchar(64) NOT NULL AFTER sku'
		),
		array(
                        'table'         => 'product',
			'field'		=> 'isbn',
			'column'	=> ' varchar(17) NOT NULL AFTER sku'
		),
		array(
                        'table'         => 'product',
			'field'		=> 'jan',
			'column'	=> ' varchar(13) NOT NULL AFTER sku'
		),
		array(
                        'table'         => 'product',
			'field'		=> 'ean',
			'column'	=> ' varchar(14) NOT NULL AFTER sku'
		),
		array(
                        'table'         => 'product',
			'field'		=> 'upc',
			'column'	=> ' varchar(12) NOT NULL AFTER sku'
		),
		array(
                        'table'         => 'product',
			'field'		=> 'minimum',
			'column'	=> ' int(11) NOT NULL AFTER length_class_id'
		),
		array(
                        'table'         => 'product',
			'field'		=> 'points',
			'column'	=> ' int(8) NOT NULL AFTER price'
		),
		array(
                        'table'         => 'product',
			'field'		=> 'subtract',
			'column'	=> ' tinyint(1) NOT NULL AFTER length_class_id'
		),
		array(
                        'table'         => 'product',
			'field'		=> 'sort_order',
			'column'	=> ' int(11) NOT NULL AFTER viewed'
		),
		array(
                        'table'         => 'product_image',
			'field'		=> 'sort_order',
			'column'	=> ' int(3) NOT NULL'
		),
		array(
                        'table'         => 'product_description',
			'field'		=> 'meta_title',
			'column'	=> ' varchar(255) NOT NULL AFTER description'
		),
		array(
                        'table'         => 'product_description',
			'field'		=> 'meta_keyword',
			'column'	=> ' varchar(255) NOT NULL AFTER meta_title'
		),
		array(
                        'table'         => 'product_description',
			'field'		=> 'tag',
			'column'	=> ' text NOT NULL AFTER description'
		),
		array(
                        'table'         => 'product_option',
			'field'		=> 'option_id',
			'column'	=> ' int(11) NOT NULL AFTER product_id'
		),
		array(
                        'table'         => 'product_option',
			'field'		=> 'required',
			'column'	=> ' tinyint(1) NOT NULL'
		),
		array(
                        'table'         => 'product_option_value',
			'field'		=> 'option_value_id',
			'column'	=> ' int(11) NOT NULL AFTER product_id'
		),
		array(
                        'table'         => 'product_option_value',
			'field'		=> 'option_id',
			'column'	=> ' int(11) NOT NULL AFTER product_id'
		),
		array(
                        'table'         => 'product_option_value',
			'field'		=> 'weight_prefix',
			'column'	=> ' varchar(1) NOT NULL AFTER price'
		),
		array(
                        'table'         => 'product_option_value',
			'field'		=> 'weight',
			'column'	=> ' decimal(15,8) NOT NULL AFTER price'
		),
		array(
                        'table'         => 'product_option_value',
			'field'		=> 'points_prefix',
			'column'	=> ' varchar(1) NOT NULL AFTER price'
		),
		array(
                        'table'         => 'product_option_value',
			'field'		=> 'points',
			'column'	=> ' int(8) NOT NULL AFTER price'
		),
		array(
                        'table'         => 'product_option_value',
			'field'		=> 'price_prefix',
			'column'	=> ' varchar(1) NOT NULL AFTER price'
		),
		array(
                        'table'         => 'product_recurring',
			'field'		=> 'customer_group_id',
			'column'	=> ' int(11) NOT NULL'
		),
		array(
                        'table'         => 'product_recurring',
			'field'		=> 'recurring_id',
			'column'	=> ' int(11) NOT NULL'
		),
		array(
                        'table'         => 'return',
			'field'		=> 'product_id',
			'column'	=> ' int(11) NOT NULL'
		),
		array(
                        'table'         => 'return',
			'field'		=> 'product',
			'column'	=> ' varchar(255) NOT NULL'
		),
		array(
                        'table'         => 'return',
			'field'		=> 'model',
			'column'	=> ' varchar(64) NOT NULL'
		),
		array(
                        'table'         => 'return',
			'field'		=> 'quantity',
			'column'	=> ' int(4) NOT NULL'
		),
		array(
                        'table'         => 'return',
			'field'		=> 'opened',
			'column'	=> ' tinyint(1) NOT NULL'
		),
		array(
                        'table'         => 'return',
			'field'		=> 'return_reason_id',
			'column'	=> ' int(11) NOT NULL'
		),
		array(
                        'table'         => 'return',
			'field'		=> 'return_action_id',
			'column'	=> ' int(11) NOT NULL'
		),
		array(
                        'table'         => 'setting',
			'field'		=> 'serialized',
			'column'	=> ' tinyint(1) NOT NULL'
		),
		array(
                        'table'         => 'setting',
			'field'		=> 'store_id',
			'column'	=> ' int(11) NOT NULL AFTER setting_id'
		),
		array(
                        'table'         => 'tax_rate',
			'field'		=> 'type',
			'column'	=> ' char(1) NOT NULL AFTER rate'
		),
		array(
                        'table'         => 'user',
			'field'		=> 'code',
			'column'	=> ' varchar(40) NOT NULL AFTER email'
		),
		array(
                        'table'         => 'user',
			'field'		=> 'image',
			'column'	=> ' varchar(255) NOT NULL AFTER email'
		),
		array(
                        'table'         => 'user',
			'field'		=> 'salt',
			'column'	=> ' varchar(9) NOT NULL AFTER password'
		)
	);

    $altercounter = 0;
    $text = '';
  
    foreach( $vars as $k => $v ) {
     
	   if( array_search( DB_PREFIX . $v['table'], $this->getTables() ) || $v['table'] == 'address' ){
             if( !array_search( $v['field'], $this->getDbColumns( $v['table'] ) )) {

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
	if( !$this->simulate ) {

                       $up = $this->db->query("SELECT * FROM ". DB_PREFIX . "product_description");
                                      foreach($up->rows as $pro){
                                      $this->db->query("UPDATE `". DB_PREFIX . "product_description`
                                                             SET `meta_title` = '" . $this->db->escape($pro['name']) . "'
                                                             WHERE `product_id` = '" . $pro['product_id'] . "'");
                                      }
                       $ups = $this->db->query("SELECT * FROM ". DB_PREFIX . "category_description");
                                      foreach($ups->rows as $cat){
                                      $this->db->query("UPDATE `". DB_PREFIX . "category_description`
                                                             SET `meta_title` = '" . $this->db->escape($cat['name']) . "'
                                                             WHERE `category_id` = '" . $cat['category_id'] . "'");
                                      }
	 }
	$text .= '<div class="header round"> ';
	$text .= sprintf( $this->lang['msg_col_counter'], $altercounter, '' );
        $text .= ' </div>';
        $text .= $this->deleteColumns();
        $text .= $this->changeColumns();

      return $text;
  }

  public function deleteColumns(){
      /**
       * Delete Columns
       * */

    $delcols = array(
		array(
                        'table'         => 'address',
			'field'		=> 'company_id'
		),
		array(
                        'table'         => 'address',
			'field'		=> 'tax_id'
		),
		array(
                        'table'         => 'custom_field',
			'field'		=> 'position'
		),
		array(
                        'table'         => 'custom_field',
			'field'		=> 'required'
		),
		array(
                        'table'         => 'customer_group',
			'field'		=> 'company_id_display'
		),
		array(
                        'table'         => 'customer_group',
			'field'		=> 'company_id_required'
		),
		array(
                        'table'         => 'customer_group',
			'field'		=> 'name'
		),
		array(
                        'table'         => 'customer_group',
			'field'		=> 'tax_id_display'
		),
		array(
                        'table'         => 'customer_group',
			'field'		=> 'tax_id_required'
		),
		array(
                        'table'         => 'download',
			'field'		=> 'remaining'
		),
		array(
                        'table'         => 'order',
			'field'		=> 'coupon_id'
		),
		array(
                        'table'         => 'order',
			'field'		=> 'coupon_id'
		),
		array(
                        'table'         => 'order',
			'field'		=> 'invoice_date'
		),
		array(
                        'table'         => 'order',
			'field'		=> 'payment_tax_id'
		),
		array(
                        'table'         => 'order',
			'field'		=> 'reward'
		),
		array(
                        'table'         => 'order',
			'field'		=> 'value'
		),
		array(
                        'table'         => 'order_product',
			'field'		=> 'subtract'
		),
		array(
                        'table'         => 'order_total',
			'field'		=> 'text'
		),
		array(
                        'table'         => 'product',
			'field'		=> 'cost'
		),
		array(
                        'table'         => 'product',
			'field'		=> 'maximum'
		),
		array(
                        'table'         => 'product_recurring',
			'field'		=> 'store_id'
		),
		array(
                        'table'         => 'product_option',
			'field'		=> 'sort_order'
		),
		array(
                        'table'         => 'tax_rate',
			'field'		=> 'description'
		),
		array(
                        'table'         => 'tax_rate',
			'field'		=> 'priority'
                   ),
		array(
                        'table'         => 'tax_rate',
			'field'		=> 'tax_class_id'
                   )
               );

    $deletecol = 0;
    $text = '';
    foreach( $delcols as $k => $v ) {
	   if( array_search( DB_PREFIX . $v['table'], $this->getTables() ) ) {
		if( array_search( $v['field'], $this->getDbColumns( $v['table'] ) ) ) {

			$sql = '
			ALTER TABLE
				`' . DB_PREFIX . $v['table'] . '`
			DROP COLUMN
				`' . $v['field'] . '`';

			if( !$this->simulate ) {
                               $this->db->query( $sql );
                        }
                        if( $this->showOps ) {
                              $text .= '<p><pre>' . $sql .'</pre></p>';
                        }
			++$deletecol;
               $text .= $this->msg( sprintf( $this->lang['msg_delete_column'], $v['field'],  $v['table'] ) );
			if( !$this->simulate ) {
                             $this->cache->delete( $v['table'] );
                        }
		}
	 }
      }
        
	$text .= '<div class="header round"> ';
	$text .= sprintf( $this->lang['msg_del_column'], $deletecol );
        $text .= ' </div>';

        return $text;
  }
	
      /**
       * Change Columns
       * */
  public function changeColumns(){
    $text = '';
  $changecols = array(
		array(
                        'table'         => 'category_description',
			'field'		=> 'meta_keyword',
			'oldfield'	=> 'meta_keywords',
			'column'	=> ' varchar(255) NOT NULL'
		),
		array(
                        'table'         => 'extension',
			'field'		=> 'code',
			'oldfield'	=> 'key',
			'column'	=> ' varchar(32) NOT NULL'
		),
		array(
                        'table'         => 'order',
			'field'		=> 'invoice_no',
			'oldfield'	=> 'invoice_id',
			'column'	=> ' int(11) NOT NULL'
		),
		array(
                        'table'         => 'order',
			'field'		=> 'currency_code',
			'oldfield'	=> 'currency',
			'column'	=> ' varchar(3) NOT NULL'
		),
		array(
                        'table'         => 'order_recurring',
			'field'		=> 'reference',
			'oldfield'	=> 'profile_reference',
			'column'	=> ' varchar(255) NOT NULL'
		),
		array(
                        'table'         => 'order_recurring',
			'field'		=> 'recurring_name',
			'oldfield'	=> 'profile_name',
			'column'	=> ' varchar(255) NOT NULL'
		),
		array(
                        'table'         => 'order_recurring',
			'field'		=> 'recurring_description',
			'oldfield'	=> 'profile_description',
			'column'	=> ' varchar(255) NOT NULL'
		),
		array(
                        'table'         => 'order_recurring',
			'field'		=> 'recurring_id',
			'oldfield'	=> 'profile_id',
			'column'	=> ' int(11) NOT NULL'
		),
		array(
                        'table'         => 'order_recurring',
			'field'		=> 'date_added',
			'oldfield'	=> 'created',
			'column'	=> ' datetime NOT NULL'
		),
		array(
                        'table'         => 'order_recurring_transaction',
			'field'		=> 'date_added',
			'oldfield'	=> 'created',
			'column'	=> ' datetime NOT NULL'
		),
		array(
                        'table'         => 'product_description',
			'field'		=> 'meta_keyword',
			'oldfield'	=> 'meta_keywords',
			'column'	=> ' varchar(255) NOT NULL'
		),
		array(
                        'table'         => 'product_option',
			'field'		=> 'value',
			'oldfield'	=> 'option_value',
			'column'	=> ' text NOT NULL'
		),
		array(
                        'table'         => 'setting',
			'field'		=> 'code',
			'oldfield'	=> 'group',
			'column'	=> ' varchar(32) NOT NULL'
		)
        );
	


  $changetype = array(
		array(
                        'table'         => 'affiliate',
			'field'		=> 'status',
			'type'		=> 'inyint(',
			'column'	=> ' tinyint(1) NOT NULL DEFAULT 0'
		),
		array(
                        'table'         => 'affiliate',
			'field'		=> 'approved',
			'type'		=> 'inyint(',
			'column'	=> ' tinyint(1) NOT NULL DEFAULT 0'
		),
		array(
                        'table'         => 'banner',
			'field'		=> 'status',
			'type'		=> 'inyint(',
			'column'	=> ' tinyint(1) NOT NULL DEFAULT 0'
		),
		array(
                        'table'         => 'category',
			'field'		=> 'top',
			'type'		=> 'inyint(',
			'column'	=> ' tinyint(1) NOT NULL DEFAULT 0'
		),
		array(
                        'table'         => 'category',
			'field'		=> 'status',
			'type'		=> 'inyint(',
			'column'	=> ' tinyint(1) NOT NULL DEFAULT 0'
		),
		array(
                        'table'         => 'country',
			'field'		=> 'postcode_required',
			'type'		=> 'inyint(',
			'column'	=> ' tinyint(1) NOT NULL DEFAULT 0'
		),
		array(
                        'table'         => 'country',
			'field'		=> 'status',
			'type'		=> 'inyint(',
			'column'	=> ' tinyint(1) NOT NULL DEFAULT 1'
		),
		array(
                        'table'         => 'coupon',
			'field'		=> 'logged',
			'type'		=> 'inyint(',
			'column'	=> ' tinyint(1) NOT NULL DEFAULT 0'
		),
		array(
                        'table'         => 'coupon',
			'field'		=> 'shipping',
			'type'		=> 'inyint(',
			'column'	=> ' tinyint(1) NOT NULL DEFAULT 0'
		),
		array(
                        'table'         => 'coupon',
			'field'		=> 'status',
			'type'		=> 'inyint(',
			'column'	=> ' tinyint(1) NOT NULL DEFAULT 0'
		),
		array(
                        'table'         => 'currency',
			'field'		=> 'status',
			'type'		=> 'inyint(',
			'column'	=> ' tinyint(1) NOT NULL DEFAULT 0'
		),
		array(
                        'table'         => 'customer',
			'field'		=> 'newsletter',
			'type'		=> 'inyint(',
			'column'	=> ' tinyint(1) NOT NULL DEFAULT 0'
		),
		array(
                        'table'         => 'customer',
			'field'		=> 'status',
			'type'		=> 'inyint(',
			'column'	=> ' tinyint(1) NOT NULL DEFAULT 0'
		),
		array(
                        'table'         => 'customer',
			'field'		=> 'approved',
			'type'		=> 'inyint(',
			'column'	=> ' tinyint(1) NOT NULL DEFAULT 0'
		),
		array(
                        'table'         => 'information',
			'field'		=> 'status',
			'type'		=> 'inyint(',
			'column'	=> ' tinyint(1) NOT NULL DEFAULT \'1\''
		),
		array(
                        'table'         => 'language',
			'field'		=> 'status',
			'type'		=> 'inyint(',
			'column'	=> ' tinyint(1) NOT NULL DEFAULT 0'
		),
		array(
                        'table'         => 'order_history',
			'field'		=> 'notify',
			'type'		=> 'inyint(',
			'column'	=> ' tinyint(1) NOT NULL DEFAULT 0'
		),
		array(
                        'table'         => 'customer',
			'field'		=> 'approved',
			'type'		=> 'inyint(',
			'column'	=> ' tinyint(1) NOT NULL DEFAULT 0'
		),
		array(
                        'table'         => 'product',
			'field'		=> 'shipping',
			'type'		=> 'inyint(',
			'column'	=> ' tinyint(1) NOT NULL DEFAULT \'1\''
		),
		array(
                        'table'         => 'product',
			'field'		=> 'subtract',
			'type'		=> 'inyint(',
			'column'	=> ' tinyint(1) NOT NULL DEFAULT \'1\''
		),
		array(
                        'table'         => 'product',
			'field'		=> 'status',
			'type'		=> 'inyint(',
			'column'	=> ' tinyint(1) NOT NULL DEFAULT 0'
		),
		array(
                        'table'         => 'product_option',
			'field'		=> 'required',
			'type'		=> 'inyint(',
			'column'	=> ' tinyint(1) NOT NULL DEFAULT 0'
		),
		array(
                        'table'         => 'product_option_value',
			'field'		=> 'subtract',
			'type'		=> 'inyint(',
			'column'	=> ' tinyint(1) NOT NULL DEFAULT 0'
		),
		array(
                        'table'         => 'return_history',
			'field'		=> 'notify',
			'type'		=> 'inyint(',
			'column'	=> ' tinyint(1) NOT NULL DEFAULT 0'
		),
		array(
                        'table'         => 'review',
			'field'		=> 'status',
			'type'		=> 'inyint(',
			'column'	=> ' tinyint(1) NOT NULL DEFAULT 0'
		),
		array(
                        'table'         => 'user',
			'field'		=> 'status',
			'type'		=> 'inyint(',
			'column'	=> ' tinyint(1) NOT NULL DEFAULT 0'
		),
		array(
                        'table'         => 'voucher',
			'field'		=> 'status',
			'type'		=> 'inyint(',
			'column'	=> ' tinyint(1) NOT NULL DEFAULT 0'
		),
		array(
                        'table'         => 'zone',
			'field'		=> 'status',
			'type'		=> 'inyint(',
			'column'	=> ' tinyint(1) NOT NULL DEFAULT \'1\''
		)
       );

     $changecounter = 0;

	$keyset = array(
		array(
                        'table'         => 'category',
			'index'		=> 'parent_id',
			'field'		=> 'parent_id'
		),
		array(
                        'table'         => 'product_discount',
			'index'		=> 'product_id',
			'field'		=> 'product_id'
		),
		array(
                        'table'         => 'product_image',
			'index'		=> 'product_id',
			'field'		=> 'product_id'
		),
		array(
                        'table'         => 'product_special',
			'index'		=> 'product_id',
			'field'		=> 'product_id'
		),
		array(
                        'table'         => 'review',
			'index'		=> 'product_id',
			'field'		=> 'product_id'
		),
		array(
                        'table'         => 'url_alias',
			'index'         => 'query',
			'field'         => 'query'
		),
		array(
                        'table'         => 'url_alias',
			'index'	        => 'keyword',
			'field'         => 'keyword'
		)
         );

          foreach( $keyset as $k => $v ) {

        if( array_search( DB_PREFIX . $v['table'] , $this->getTables()) ) {
	     if( !$this->getColumnKey( $v['field'], $v['table'] ) ) { 
			$sql = '
			ALTER TABLE
				`' . DB_PREFIX . $v['table'] . '`
                        ADD INDEX
			         `' .  $v['index'].'` (' . $v['field'] . ')';

			if( !$this->simulate ) {
                               $this->db->query( $sql );
                        }
                        if( $this->showOps ) {
                              $text .= '<p><pre>' . $sql .'</pre></p>';
                        }
			++$changecounter;
			$text .= $this->msg( sprintf( $this->lang['msg_change_column'], $v['field'],  $v['table'] ) );
		}
             }
         }
	if( array_search( 'currency_value', $this->getDbColumns( 'order' ) ) ){
	  $sql = '
                 ALTER TABLE
                           `' . DB_PREFIX . 'order`
                 ALTER 
                           `currency_value`
                 SET DEFAULT  \'1.00000000\'';
		
	        if( !$this->simulate ) {
                               $this->db->query( $sql );
                 }
                 if( $this->showOps ) {
                              $text .= '<p><pre>' . $sql .'</pre></p>';
                 }
	  ++$changecounter;


	  $text .= $this->msg( sprintf( $this->lang['msg_change_column'], 'currency_value',  DB_PREFIX . 'order' ) );
         }


     foreach( $changecols as $k => $v ) {
        if( array_search( DB_PREFIX . $v['table'] , $this->getTables()) ) {

	   if( array_search( DB_PREFIX . $v['table'], $this->getTables() ) || $v['table'] == 'address' ) {
                      
		if( array_search( $v['oldfield'], $this->getDbColumns( $v['table'] ) ) && !array_search( $v['field'], $this->getDbColumns( $v['table'] ) ) ) {
			$sql = '
			ALTER TABLE
				  `' . DB_PREFIX . $v['table'] . '`
			CHANGE
				  `' . $v['oldfield'] . '` `' . $v['field'] . '` ' . $v['column'];

			if( !$this->simulate ) {
                               $this->db->query( $sql );
                        }
                        if( $this->showOps ) {
                               $text .= '<p><pre>' . $sql .'</pre></p>';
                        }
			++$changecounter;
			$text .= $this->msg( sprintf( $this->lang['msg_change_column'], $v['field'],  $v['table'] ) );
			if( !$this->simulate ) {
                           $this->cache->delete( $v['table'] );
                        }
		} 
           }
	 }
     }

     foreach( $changetype as $k => $v ) {
           $this->cache->delete( $v['table'] );
        if( array_search( DB_PREFIX . $v['table'] , $this->getTables()) ) {
	   if( array_search( $v['field'], $this->getDbColumns( $v['table'] ) ) && !$this->getColumnType( $v['field'], $v['type'], $v['table']) ) {
			$sql = '
			ALTER TABLE
				`' . DB_PREFIX . $v['table'] . '`
			MODIFY
				' . $v['field'] . $v['column'];

			if( !$this->simulate ) {
                               $this->db->query( $sql );
                        }
                        if( $this->showOps ) {
                               $text .= '<p><pre>' . $sql .'</pre></p>';
                        }
			++$changecounter;
			$text .= $this->msg( sprintf( $this->lang['msg_change_column'], $v['field'],  $v['table'] ) );
			if( !$this->simulate ) {
                          $this->cache->delete( $v['table'] );
                        }
	   }
        }
     }


	$text .= '<div class="header round"> ';
	$text .= sprintf( $this->lang['msg_change_counter'], $changecounter );
        $text .= ' </div>';

        return $text;
  }	

    /* Tax tables change */

  public function changeTaxRate( $data ){

        $this->simulate = ( !empty( $data['simulate'] ) ? true : false );
        $this->showOps  = ( !empty( $data['showOps'] ) ? true : false );
      $text = '';

	if( array_search( 'tax_class_id', $this->getDbColumns( 'tax_rate' ) ) && !array_search( 'name', $this->getDbColumns( 'tax_rate' )) ) {

		$sql = '
		SELECT
			*
		FROM
			`' . DB_PREFIX . 'tax_class` AS tc
		LEFT JOIN
			`' . DB_PREFIX . 'tax_rate` AS tr
			ON(tc.tax_class_id = tr.tax_class_id)';

		$taxes = $this->db->query( $sql );

		$sql = '
		SELECT
			*
		FROM
			`' . DB_PREFIX . 'tax_rate`
		ORDER BY
			`tax_rate_id` ASC';
		$rates = $this->db->query( $sql );

	foreach( $taxes->rows as $tax ){
			$sql = '
			SELECT
				*
			FROM
				`' . DB_PREFIX . 'tax_rate`
			WHERE
				`tax_class_id` = \''. $tax['tax_class_id'] . '\'';

			$result = $this->db->query( $sql );

			if( !isset( $result->row['tax_class_id'] ) ) {
				$sql = '
				INSERT INTO
					`' . DB_PREFIX . 'tax_rule`
				SET
					`tax_class_id` = \'' . $tax['tax_class_id'] . '\',
					`tax_rate_id` = \'' . $tax['tax_rate_id'] . '\',
					`based` = \'shipping\',
					`priority` = \'' . $tax['priority'] . '\'';

				if( !$this->simulate ) {
                                       $this->db->query( $sql );
                                }
                                if( $this->showOps ) {
                                      $text .= '<p><pre>' . $sql .'</pre></p>';
                                }
			}
	}

	foreach( $rates->rows as $rate ) {
			$sql = '
			SELECT
				*
			FROM
				`' . DB_PREFIX . 'tax_rate_to_customer_group`
			WHERE
				`tax_rate_id` = \'' . $rate['tax_rate_id'] . '\'';

	   if( array_search( DB_PREFIX . 'tax_rate_to_customer_group', $this->getTables() ) ) {
			$result = $this->db->query( $sql );

			if( !isset( $result->row['tax_rate_id'] ) ) {
				$sql = '
				INSERT INTO
					`' . DB_PREFIX . 'tax_rate_to_customer_group`
				SET
					`tax_rate_id` = \'' . $rate['tax_rate_id'] . '\',
					`customer_group_id` = \'1\'';

				if( !$this->simulate ) {
                                       $this->db->query( $sql );
                                }
                                if( $this->showOps ) {
                                       $text .= '<p><pre>' . $sql .'</pre></p>';
                                }
			}
             }
	}

		/* Change Column 1:*/
		$sql = '
		ALTER TABLE
			`' . DB_PREFIX . 'tax_rate`
		CHANGE
			`description` `name` varchar(255) NOT NULL';

		if( array_search( 'description', $this->getDbColumns( 'tax_rate' ) ) ) {

		  if( !$this->simulate ) {
                         $this->db->query( $sql );
                   }
                  if( $this->showOps ) {
                         $text .= '<p><pre>' . $sql .'</pre></p>';
                  }
		  $text .=  $this->msg( sprintf( $this->lang['msg_column'], 'value',  DB_PREFIX . 'tax_rate' ) );

                }
		$sql = '
		UPDATE
			`' . DB_PREFIX . 'tax_rate`
		SET
			`type` = \'P\'';

		if( array_search( 'type', $this->getDbColumns( 'tax_rate' ) ) ) {

		  if( !$this->simulate ) {
                         $this->db->query( $sql );
                  }
                  if( $this->showOps ) {
                        $text .= '<p><pre>' . $sql .'</pre></p>';
                  }
		  ++$deletecol;
		  $text .= $this->msg( sprintf( $this->lang['msg_delete'],  DB_PREFIX . 'tax_rate' ) );

                }
	}
        return $text;

  }
  public function changeOptions(){
           $text = '';               
      if( array_search( DB_PREFIX . 'store_description' , $this->getTables()) ) {
        /* Opencart version 1.4.x is Found */
        /* Change product options to Qphoria way */
    $sql = "
              SELECT
                     MIN(product_option_id) AS option_id
              FROM
                     `" . DB_PREFIX . "product_option`;";
            if( !$this->simulate ) {
	      $query = $this->db->query( $sql );
                             $option = $query->row['option_id'];
                } else {
                                       $option = 0;
              }

      if( isset($option->row['option_id']) && !$this->hasOption($option)  || $this->simulate){
     $sql = "
            INSERT INTO
                       `" . DB_PREFIX . "option` (`option_id`, `type`, `sort_order`)
            SELECT
                       `product_option_id`, 'select', `sort_order`
            FROM
                       `" . DB_PREFIX . "product_option`;";

                if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ){
                     $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		$text .= $this->msg( sprintf( $this->lang['msg_table'],   DB_PREFIX . 'option' ) );
                                              
     $sql = "
             INSERT INTO
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
		$text .= $this->msg( sprintf( $this->lang['msg_table'],   DB_PREFIX . 'option_description' ) );
                                              
     $sql = "
             INSERT INTO
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
		$text .= $this->msg( sprintf( $this->lang['msg_table'],   DB_PREFIX . 'option_value' ) );

      $sql = "
              INSERT INTO
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
		$text .= $this->msg( sprintf( $this->lang['msg_table'],   DB_PREFIX . 'option_value_description' ) );
              }
        return $text;
     }
  }
	/** delete tables */
  public function deleteTables( $data ){
  
        $this->simulate = ( !empty( $data['simulate'] ) ? true : false );
        $this->showOps  = ( !empty( $data['showOps'] ) ? true : false );

    $deletetab = 0;
    $text = '';

  $droptable = array(
                     'coupon_description',
                     'customer_field',
                     'customer_ip_blacklist',
                     'order_download',
                     'order_field',
                     'order_misc',
                     'product_featured',
                     'product_option_description',
                     'product_option_value_description',
		     'product_profile',
                     'product_tag',
                     'product_tags',
                     'profile',
		     'profile_description',
                     'store_description'
		);

  if( $this->upgrade2030 ){
                         $droptable[] = 'order_fraud';
  }
       foreach( $droptable as $table ) {
	  if( array_search( DB_PREFIX . $table, $this->getTables() ) ) {
		$sql = 'DROP TABLE `' . DB_PREFIX . $table . '`';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ) {
                       $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$deletetab;
		$text .= $this->msg( sprintf( $this->lang['msg_delete'],  DB_PREFIX . 'product_tag' ) );

                        $this->cache->delete( $table );
	  }
 
       }
	if( array_search( DB_PREFIX . 'return_product', $this->getTables() ) ) {

                $sql = '
                SELECT 
                       *
                FROM  `' . DB_PREFIX . 'return_product`';
 
                $query = $this->db->query($sql);

       if( count( $query->rows ) > 0 ){
       /*
        * Change content from table return_product
        * to table return
        */
               foreach( $query->rows as $id ){

		  $sql = '
		  UPDATE
		 	   `' . DB_PREFIX . 'return`
                  SET
                           `product_id`       = \'' . $id['product_id'] .'\',
                           `product`          = \'' . $id['name'] .'\',
                           `model`            = \'' . $id['model'] .'\',
                           `quantity`         = \'' . $id['quantity'] .'\',
                           `opened`           = \'' . $id['opened'] .'\',
                           `return_reason_id` = \'' . $id['return_reason_id'] .'\',
                           `return_action_id` = \'' . $id['return_action_id'] .'\',
                           `comment`          = \'' . $id['comment'] .'\'
                 WHERE
                           `return_id`        = \'' . $id['return_id'] .'\'';
		  

		  if( !$this->simulate ) {
                         $this->db->query( $sql );
                  }
                  if( $this->showOps ) {
                         $text .= '<p><pre>' . $sql .'</pre></p>';
                  }

		  $text .= $this->msg( sprintf( $this->lang['msg_text'], $this->data['msg_new_data'],  DB_PREFIX . 'return' ) );

                }
             }
		$sql = 'DROP TABLE `' . DB_PREFIX . 'return_product`';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                } 
                if( $this->showOps ) {
                       $text .= '<p><pre>' . $sql .'</pre></p>';
                }

		++$deletetab;
		$text .= $this->msg( sprintf( $this->lang['msg_delete'], 'return_product', '' ) );
 
	}
       

	$text .= '<div class="header round"> ';
	$text .= sprintf( $this->lang['msg_delete_table'], $deletetab, '' );
        $text .= ' </div>';

       return $text;

  }

  private function msg( $data ){
       $data = str_replace( $data, '<div class="msg round"> ' . $data .' </div>', $data);
       return $data;
  }

  public function getDbColumns( $table ) {
	if( $data =  $this->cache->get( $table ) ) {
		return $data;
	}else{

        if( array_search( DB_PREFIX . $table, $this->getTables() ) || $table == 'address'){
                $colums = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . $table . "`");

		$ret		= array();

               foreach( $colums->rows as $field){
                 $ret[] = $field['Field'];
               }
          return $ret;	
         }
    }
  }

  private function getColumnKey( $column, $table ) {

     if( array_search( DB_PREFIX . $table, $this->getTables() ) || $table == 'address'){
                $fields = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . $table . "`");

        if( array_search( $column, $this->getDbColumns( $table ) ) ){
           foreach( $fields->rows as $field){
              if($field['Field'] == $column){
   
                 return ( !empty( $field['Key'] ) ? true : false );
    
             }
          }
        }  	
     }
  }

  private function getColumnType( $column, $type, $table ) {
 
     if( array_search( DB_PREFIX . $table, $this->getTables() ) || $table == 'address'){
            $fields = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . $table . "`" );

        if( array_search( $column, $this->getDbColumns( $table ) ) ){
           foreach( $fields->rows as $field){
              if($field['Field'] == $column ){
  
                 return strpos($field['Type'], $type);
    
              }
           }
        }
     }
  }
  private function hasOption( $val ) {
                        
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

  public function getTables() {
       $query = $this->db->query("SHOW TABLES FROM `" . DB_DATABASE . "`");

        $table_list = array();
        foreach($query->rows as $table){
                      $table_list[] = $table['Tables_in_'. DB_DATABASE];
          }
        return $table_list;
   }
}
