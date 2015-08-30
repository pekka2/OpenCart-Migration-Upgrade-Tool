<?php
class ModelUpgradeDatabase extends Model{
  private $lang;
  private $simulate;
  private $showOps;
  private $tablecounter;
  public function addTables( $data ) {  
        $this->simulate = ( !empty( $data['simulate'] ) ? true : false );
        $this->showOps  = ( !empty( $data['showOps'] ) ? true : false );
        $this->upgrade2020  = ( !empty( $data['upgrade2020'] ) ? true : false );
        $this->upgrade2030  = ( !empty( $data['upgrade2030'] ) ? true : false );

        $this->lang = $this->lmodel->get('upgrade_database');

        $this->tablecounter = 0;
        $text = '';
	
	if( !array_search( DB_PREFIX . 'affiliate', $this->structure->tables() ) ) {
           $text .= $this->addUpgradeTo150();
        }
	if( !array_search( DB_PREFIX . 'tax_rate_to_customer_group', $this->structure->tables() ) ) {
           $text .= $this->addUpgradeTo1513();
        }
	if( !array_search( DB_PREFIX . 'order_fraud', $this->structure-tables() ) ) {
           $text .= $this->addUpgradeTo152();
        }
	if( !array_search( DB_PREFIX . 'customer_group_description', $this->structure->tables() ) ) {
           $text .= $this->addUpgradeTo153();
        }
	if( array_search( DB_PREFIX . 'customer_online', $this->structure->tables() ) &&
            array_search( DB_PREFIX . 'customer_ip_blacklist', $this->structure->tables() )) {
          // $text .= $this->fixEngineOfTableCustomerOnline();
        }
	if( !array_search( DB_PREFIX . 'customer_online', $this->structure->tables() ) ) {
           $text .= $this->addUpgradeTo154();
        }

	if( !array_search( DB_PREFIX . 'category_path', $this->structure->tables() ) ) {
           $text .= $this->addUpgradeTo155();
        }
	if( !array_search( DB_PREFIX . 'order_recurring', $this->structure->tables() ) ) {
           $text .= $this->addUpgradeTo156();
        }
	if( !array_search( DB_PREFIX . 'module', $this->structure->tables() ) ) {
           $text .= $this->addUpgradeTo2001();
        }
	$text .= '<div class="header round"> ';
        $text .=  sprintf( addslashes($this->lang['msg_table_count']), $this->tablecounter, '' );
        $text .= ' </div>';
        return $text;
  }

  public function addUpgradeTo150() {  

        $text = '';

	if( !array_search( DB_PREFIX . 'affiliate' , $this->structure->tables()) ) {
		$sql = '
		CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'affiliate` (
                `affiliate_id` int(11) NOT NULL AUTO_INCREMENT,
                `firstname` varchar(32) NOT NULL,
                `lastname` varchar(32) NOT NULL,
                `email` varchar(96) NOT NULL,
                `telephone` varchar(32) NOT NULL,
                `fax` varchar(32) NOT NULL,
                `password` varchar(40) NOT NULL,
                `salt` varchar(9) NOT NULL,
                `company` varchar(40) NOT NULL,
                `website` varchar(255) NOT NULL,
                `address_1` varchar(128) NOT NULL,
                `address_2` varchar(128) NOT NULL,
                `city` varchar(128) NOT NULL,
                `postcode` varchar(10) NOT NULL,
                `country_id` int(11) NOT NULL,
                `zone_id` int(11) NOT NULL,
                `code` varchar(64) NOT NULL,
                `commission` decimal(4,2) NOT NULL DEFAULT \'0.00\',
                `tax` varchar(64) NOT NULL,
                `payment` varchar(6) NOT NULL,
                `cheque` varchar(100) NOT NULL,
                `paypal` varchar(64) NOT NULL,
                `bank_name` varchar(64) NOT NULL,
                `bank_branch_number` varchar(64) NOT NULL,
                `bank_swift_code` varchar(64) NOT NULL,
                `bank_account_name` varchar(64) NOT NULL,
                `bank_account_number` varchar(64) NOT NULL,
                `ip` varchar(40) NOT NULL,
                `status` tinyint(1) NOT NULL,
                `approved` tinyint(1) NOT NULL,
                `date_added` datetime NOT NULL,
                PRIMARY KEY (`affiliate_id`)
              ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ){
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->tablecounter;
		$text .= $this->msg( sprintf( $this->lang['msg_table'],   DB_PREFIX . 'affiliate' ) );
	}




	if( !array_search( DB_PREFIX . 'affiliate_transaction' , $this->structure->tables()) ) {
		$sql = '
                CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'affiliate_transaction` (
                `affiliate_transaction_id` int(11) NOT NULL AUTO_INCREMENT,
                `affiliate_id` int(11) NOT NULL,
                `order_id` int(11) NOT NULL,
                `description` text NOT NULL,
                `amount` decimal(15,4) NOT NULL,
                `date_added` datetime NOT NULL,
                PRIMARY KEY (`affiliate_transaction_id`)
              ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ){
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->tablecounter;
		$text .= $this->msg( sprintf( $this->lang['msg_table'],   DB_PREFIX . 'affiliate_transaction' ) );
	}

	if( !array_search( DB_PREFIX . 'attribute' , $this->structure->tables()) ) {
		$sql = '
                CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'attribute` (
                `attribute_id` int(11) NOT NULL AUTO_INCREMENT,
                `attribute_group_id` int(11) NOT NULL,
                `sort_order` int(3) NOT NULL,
                PRIMARY KEY (`attribute_id`)
              ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ){
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }

		$sql = '
                INSERT INTO 
                           `' . DB_PREFIX . 'attribute` (`attribute_id`, `attribute_group_id`, `sort_order`)
                VALUES
                           (1, 6, 1),
                           (2, 6, 5),
                           (3, 6, 3),
                           (4, 3, 1),
                           (5, 3, 2),
                           (6, 3, 3),
                           (7, 3, 4),
                           (8, 3, 5),
                           (9, 3, 6),
                           (10, 3, 7),
                           (11, 3, 8);';


		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ){
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->tablecounter;
		$text .= $this->msg( sprintf( $this->lang['msg_table'],   DB_PREFIX . 'attribute' ) );
	}

	if( !array_search( DB_PREFIX . 'attribute_description' , $this->structure->tables()) ) {
		$sql = '
                CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'attribute_description` (
                `attribute_id` int(11) NOT NULL,
                `language_id` int(11) NOT NULL,
                `name` varchar(64) NOT NULL,
                PRIMARY KEY (`attribute_id`,`language_id`)
              ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ){
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }

		$sql = '
                INSERT INTO
                           `' . DB_PREFIX . 'attribute_description` (`attribute_id`, `language_id`, `name`)
                VALUES
                           (1, 1, \'Description\'),
                           (2, 1, \'No. of Cores\'),
                           (4, 1, \'test 1\'),
                           (5, 1, \'test 2\'),
                           (6, 1, \'test 3\'),
                           (7, 1, \'test 4\'),
                           (8, 1, \'test 5\'),
                           (9, 1, \'test 6\'),
                           (10, 1, \'test 7\'),
                           (11, 1, \'test 8\'),
                           (3, 1, \'Clockspeed\');';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ){
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->tablecounter;
		$text .= $this->msg( sprintf( $this->lang['msg_table'],   DB_PREFIX . 'attribute_description' ) );
	}

	if( !array_search( DB_PREFIX . 'attribute_group' , $this->structure->tables()) ) {
		$sql = '
                CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'attribute_group` (
                `attribute_group_id` int(11) NOT NULL AUTO_INCREMENT,
                `sort_order` int(3) NOT NULL,
                PRIMARY KEY (`attribute_group_id`)
              ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;';


		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ){
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }

		$sql = '
                INSERT INTO
                           `' . DB_PREFIX . 'attribute_group` (`attribute_group_id`, `sort_order`)
                VALUES
                           (3, 2),
                           (4, 1),
                           (5, 3),
                           (6, 4);';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ){
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->tablecounter;
		$text .= $this->msg( sprintf( $this->lang['msg_table'],   DB_PREFIX . 'attribute_group' ) );
	}


	if( !array_search( DB_PREFIX . 'attribute_group_description' , $this->structure->tables()) ) {
		$sql = '
                CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'attribute_group_description` (
                `attribute_group_id` int(11) NOT NULL,
                `language_id` int(11) NOT NULL,
                `name` varchar(64) NOT NULL,
                PRIMARY KEY (`attribute_group_id`,`language_id`)
              ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ){
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }

		$sql = '
                INSERT INTO
                           `' . DB_PREFIX . 'attribute_group_description` (`attribute_group_id`, `language_id`, `name`)
                VALUES
                           (3, 1, \'Memory\'),
                           (4, 1, \'Technical\'),
                           (5, 1, \'Motherboard\'),
                           (6, 1, \'Processor\');';


		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ){
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->tablecounter;
		$text .= $this->msg( sprintf( $this->lang['msg_table'],   DB_PREFIX . 'attribute_group_description' ) );
	}

	if( !array_search( DB_PREFIX . 'banner' , $this->structure->tables()) ) {
		$sql = '
                CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'banner` (
                `banner_id` int(11) NOT NULL AUTO_INCREMENT,
                `name` varchar(64) NOT NULL,
                `status` tinyint(1) NOT NULL,
                PRIMARY KEY (`banner_id`)
              ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ){
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->tablecounter;
		$text .= $this->msg( sprintf( $this->lang['msg_table'],   DB_PREFIX . 'banner' ) );
	}

	if( !array_search( DB_PREFIX . 'banner_image' , $this->structure->tables()) ) {
		$sql = '
                CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'banner_image` (
                `banner_image_id` int(11) NOT NULL AUTO_INCREMENT,
                `banner_id` int(11) NOT NULL,
                `link` varchar(255) NOT NULL,
                `image` varchar(255) NOT NULL,
                `sort_order` int(3) NOT NULL DEFAULT \'0\',
                PRIMARY KEY (`banner_image_id`)
              ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ){
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->tablecounter;
		$text .= $this->msg( sprintf( $this->lang['msg_table'],   DB_PREFIX . 'banner_image' ) );
	}

	if( !array_search( DB_PREFIX . 'banner_image_description' , $this->structure->tables()) ) {
		$sql = '
                CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'banner_image_description` (
                `banner_image_id` int(11) NOT NULL,
                `language_id` int(11) NOT NULL,
                `banner_id` int(11) NOT NULL,
                `title` varchar(64) NOT NULL,
                PRIMARY KEY (`banner_image_id`,`language_id`)
              ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ){
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->tablecounter;
		$text .= $this->msg( sprintf( $this->lang['msg_table'],   DB_PREFIX . 'banner_image_description' ) );
	}


	if( !array_search( DB_PREFIX . 'category_to_layout' , $this->structure->tables()) ) {
		$sql = '
                CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'category_to_layout` (
                `category_id` int(11) NOT NULL,
                `store_id` int(11) NOT NULL,
                `layout_id` int(11) NOT NULL,
                PRIMARY KEY (`category_id`,`store_id`)
              ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ){
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->tablecounter;
		$text .= $this->msg( sprintf( $this->lang['msg_table'],   DB_PREFIX . 'category_to_layout' ) );
	}


	if( !array_search( DB_PREFIX . 'coupon_history' , $this->structure->tables()) ) {
		$sql = '
                CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'coupon_history` (
                `coupon_history_id` int(11) NOT NULL AUTO_INCREMENT,
                `coupon_id` int(11) NOT NULL,
                `order_id` int(11) NOT NULL,
                `customer_id` int(11) NOT NULL,
                `amount` decimal(15,4) NOT NULL,
                `date_added` datetime NOT NULL,
                PRIMARY KEY (`coupon_history_id`)
              ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ){
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->tablecounter;
		$text .= $this->msg( sprintf( $this->lang['msg_table'],   DB_PREFIX . 'coupon_history' ) );
	}

	if( !array_search( DB_PREFIX . 'customer_ip' , $this->structure->tables()) ) {
		$sql = '
                CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'customer_ip` (
                `customer_ip_id` int(11) NOT NULL AUTO_INCREMENT,
                `customer_id` int(11) NOT NULL,
                `ip` varchar(40) NOT NULL,
                `date_added` datetime NOT NULL,
                PRIMARY KEY (`customer_ip_id`),
                KEY `ip` (`ip`)
              ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ){
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->tablecounter;
		$text .= $this->msg( sprintf( $this->lang['msg_table'],   DB_PREFIX . 'customer_ip' ) );
	}

	if( !array_search( DB_PREFIX . 'customer_reward' , $this->structure->tables()) ) {
		$sql = '
                CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'customer_reward` (
                `customer_reward_id` int(11) NOT NULL AUTO_INCREMENT,
                `customer_id` int(11) NOT NULL DEFAULT \'0\',
                `order_id` int(11) NOT NULL DEFAULT \'0\',
                `description` text NOT NULL,
                `points` int(8) NOT NULL DEFAULT \'0\',
                `date_added` datetime NOT NULL,
                PRIMARY KEY (`customer_reward_id`)
              ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ){
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->tablecounter;
		$text .= $this->msg( sprintf( $this->lang['msg_table'],   DB_PREFIX . 'customer_reward' ) );
	}

	if( !array_search( DB_PREFIX . 'customer_transaction' , $this->structure->tables()) ) {
		$sql = '
                CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'customer_transaction` (
                `customer_transaction_id` int(11) NOT NULL AUTO_INCREMENT,
                `customer_id` int(11) NOT NULL,
                `order_id` int(11) NOT NULL,
                `description` text NOT NULL,
                `amount` decimal(15,4) NOT NULL,
                `date_added` datetime NOT NULL,
                PRIMARY KEY (`customer_transaction_id`)
              ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ){
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->tablecounter;
		$text .= $this->msg( sprintf( $this->lang['msg_table'],   DB_PREFIX . 'customer_transaction' ) );
	}

	if( !array_search( DB_PREFIX . 'information_to_layout' , $this->structure->tables()) ) {
		$sql = '
                CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'information_to_layout` (
                `information_id` int(11) NOT NULL,
                `store_id` int(11) NOT NULL,
                `layout_id` int(11) NOT NULL,
                PRIMARY KEY (`information_id`,`store_id`)
              ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ){
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->tablecounter;
		$text .= $this->msg( sprintf( $this->lang['msg_table'],   DB_PREFIX . 'information_to_layout' ) );
	}

	if( !array_search( DB_PREFIX . 'layout' , $this->structure->tables()) ) {
		$sql = '
                CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'layout` (
                `layout_id` int(11) NOT NULL AUTO_INCREMENT,
                `name` varchar(64) NOT NULL,
                PRIMARY KEY (`layout_id`)
              ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ){
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }

		$sql = '
                INSERT INTO
                           `' . DB_PREFIX . 'layout` (`layout_id`, `name`)
                VALUES
                           (1, \'Home\'),
                           (2, \'Product\'),
                           (3, \'Category\'),
                           (4, \'Default\'),
                           (5, \'Manufacturer\'),
                           (6, \'Account\'),
                           (7, \'Checkout\'),
                           (8, \'Contact\'),
                           (9, \'Sitemap\'),
                           (10, \'Affiliate\'),
                           (11, \'Information\');';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ){
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->tablecounter;
		$text .= $this->msg( sprintf( $this->lang['msg_table'],   DB_PREFIX . 'layout' ) );
	}

	if( !array_search( DB_PREFIX . 'layout_route' , $this->structure->tables()) ) {
		$sql = '
                CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'layout_route` (
                `layout_route_id` int(11) NOT NULL AUTO_INCREMENT,
                `layout_id` int(11) NOT NULL,
                `store_id` int(11) NOT NULL,
                `route` varchar(255) NOT NULL,
                PRIMARY KEY (`layout_route_id`)
              ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;';


		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ){
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }

		$sql = '
                INSERT INTO
                           `' . DB_PREFIX . 'layout_route` (`layout_route_id`, `layout_id`, `store_id`, `route`)
                VALUES
                           (38, 6, 0, \'account/%\'),
                           (17, 10, 0, \'affiliate/%\'),
                           (44, 3, 0, \'product/category\'),
                           (42, 1, 0, \'common/home\'),
                           (20, 2, 0, \'product/product\'),
                           (24, 11, 0, \'information/information\'),
                           (23, 7, 0, \'checkout/%\'),
                           (31, 8, 0, \'information/contact\'),
                           (32, 9, 0, \'information/sitemap\'),
                           (34, 4, 0, \'\'),
                           (45, 5, 0, \'product/manufacturer\');';


		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ){
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->tablecounter;
		$text .= $this->msg( sprintf( $this->lang['msg_table'],   DB_PREFIX . 'layout_route' ) );
	}

	if( !array_search( DB_PREFIX . 'option' , $this->structure->tables()) ) {
		$sql = '
                CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'option` (
                `option_id` int(11) NOT NULL AUTO_INCREMENT,
                `type` varchar(32) NOT NULL,
                `sort_order` int(3) NOT NULL,
                PRIMARY KEY (`option_id`)
              ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ){
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }

		$sql = '
                INSERT INTO
                           `' . DB_PREFIX . 'option` (`option_id`, `type`, `sort_order`)
                VALUES
                           (1, \'radio\', 1),
                           (2, \'checkbox\', 2),
                           (4, \'text\', 3),
                           (5, \'select\', 4),
                           (6, \'textarea\', 5),
                           (7, \'file\', 6),
                           (8, \'date\', 7),
                           (9, \'time\', 8),
                           (10, \'datetime\', 9),
                           (11, \'select\', 10),
                           (12, \'date\', 11);';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ){
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->tablecounter;
		$text .= $this->msg( sprintf( $this->lang['msg_table'],   DB_PREFIX . 'option' ) );
	}


	if( !array_search( DB_PREFIX . 'option_description' , $this->structure->tables()) ) {
		$sql = '
                CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'option_description` (
                `option_id` int(11) NOT NULL,
                `language_id` int(11) NOT NULL,
                `name` varchar(128) NOT NULL,
                PRIMARY KEY (`option_id`,`language_id`)
              ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;';


		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ){
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }

		$sql = '
                INSERT INTO
                           `' . DB_PREFIX . 'option_description` (`option_id`, `language_id`, `name`)
                VALUES
                           (1, 1, \'Radio\'),
                           (2, 1, \'Checkbox\'),
                           (4, 1, \'Text\'),
                           (6, 1, \'Textarea\'),
                           (8, 1, \'Date\'),
                           (7, 1, \'File\'),
                           (5, 1, \'Select\'),
                           (9, 1, \'Time\'),
                           (10, 1, \'Date &amp; Time\'),
                           (12, 1, \'Delivery Date\'),
                           (11, 1, \'Size\');';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ){
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->tablecounter;
		$text .= $this->msg( sprintf( $this->lang['msg_table'],   DB_PREFIX . 'option_description' ) );
	}

	if( !array_search( DB_PREFIX . 'option_value' , $this->structure->tables()) ) {
		$sql = '
                CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'option_value` (
                `option_value_id` int(11) NOT NULL AUTO_INCREMENT,
                `option_id` int(11) NOT NULL,
                `image` varchar(255) NOT NULL,
                `sort_order` int(3) NOT NULL,
                PRIMARY KEY (`option_value_id`)
              ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ){
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }

		$sql = '
                INSERT INTO
                           `' . DB_PREFIX . 'option_value` (`option_value_id`, `option_id`, `image`, `sort_order`)
                VALUES
                           (43, 1, \'\', 3),
                           (32, 1, \'\', 1),
                           (45, 2, \'\', 4),
                           (44, 2, \'\', 3),
                           (42, 5, \'\', 4),
                           (41, 5, \'\', 3),
                           (39, 5, \'\', 1),
                           (40, 5, \'\', 2),
                           (31, 1, \'\', 2),
                           (23, 2, \'\', 1),
                           (24, 2, \'\', 2),
                           (46, 11, \'\', 1),
                           (47, 11, \'\', 2),
                           (48, 11, \'\', 3);';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ){
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->tablecounter;
		$text .= $this->msg( sprintf( $this->lang['msg_table'],   DB_PREFIX . 'option_value' ) );
	}


	if( !array_search( DB_PREFIX . 'option_value_description' , $this->structure->tables()) ) {
		$sql = '
                CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'option_value_description` (
                `option_value_id` int(11) NOT NULL,
                `language_id` int(11) NOT NULL,
                `option_id` int(11) NOT NULL,
                `name` varchar(128) NOT NULL,
                PRIMARY KEY (`option_value_id`,`language_id`)
              ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ){
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }

		$sql = '
                INSERT INTO
                           `' . DB_PREFIX . 'option_value_description` (`option_value_id`, `language_id`, `option_id`, `name`)
                VALUES
                           (43, 1, 1, \'Large\'),
                           (32, 1, 1, \'Small\'),
                           (45, 1, 2, \'Checkbox 4\'),
                           (44, 1, 2, \'Checkbox 3\'),
                           (31, 1, 1, \'Medium\'),
                           (42, 1, 5, \'Yellow\'),
                           (41, 1, 5, \'Green\'),
                           (39, 1, 5, \'Red\'),
                           (40, 1, 5, \'Blue\'),
                           (23, 1, 2, \'Checkbox 1\'),
                           (24, 1, 2, \'Checkbox 2\'),
                           (48, 1, 11, \'Large\'),
                           (47, 1, 11, \'Medium\'),
                           (46, 1, 11, \'Small\');';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ){
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->tablecounter;
		$text .= $this->msg( sprintf( $this->lang['msg_table'],   DB_PREFIX . 'option_value_description' ) );
	}


	if( !array_search( DB_PREFIX . 'product_attribute' , $this->structure->tables()) ) {
		$sql = '
                CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'product_attribute` (
                `product_id` int(11) NOT NULL,
                `attribute_id` int(11) NOT NULL,
                `language_id` int(11) NOT NULL,
                `text` text NOT NULL,
                PRIMARY KEY (`product_id`,`attribute_id`,`language_id`)
              ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;';


		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ){
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->tablecounter;
		$text .= $this->msg( sprintf( $this->lang['msg_table'],   DB_PREFIX . 'product_attribute' ) );
	}


	if( !array_search( DB_PREFIX . 'product_reward' , $this->structure->tables()) ) {
		$sql = '
                CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'product_reward` (
                `product_reward_id` int(11) NOT NULL AUTO_INCREMENT,
                `product_id` int(11) NOT NULL DEFAULT \'0\',
                `customer_group_id` int(11) NOT NULL DEFAULT \'0\',
                `points` int(8) NOT NULL DEFAULT \'0\',
                PRIMARY KEY (`product_reward_id`)
              ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;';


		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ){
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->tablecounter;
		$text .= $this->msg( sprintf( $this->lang['msg_table'],   DB_PREFIX . 'product_reward' ) );
	}


	if( !array_search( DB_PREFIX . 'product_to_layout' , $this->structure->tables()) ) {
		$sql = '
                CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'product_to_layout` (
                `product_id` int(11) NOT NULL,
                `store_id` int(11) NOT NULL,
                `layout_id` int(11) NOT NULL,
                PRIMARY KEY (`product_id`,`store_id`)
              ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;';


		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ){
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->tablecounter;
		$text .= $this->msg( sprintf( $this->lang['msg_table'],   DB_PREFIX . 'product_to_layout' ) );
	}

	if( !array_search( DB_PREFIX . 'return' , $this->structure->tables()) ) {
		$sql = '
                CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'return` (
                `return_id` int(11) NOT NULL AUTO_INCREMENT,
                `order_id` int(11) NOT NULL,
                `product_id` int(11) NOT NULL,
                `customer_id` int(11) NOT NULL,
                `firstname` varchar(32) NOT NULL,
                `lastname` varchar(32) NOT NULL,
                `email` varchar(96) NOT NULL,
                `telephone` varchar(32) NOT NULL,
                `product` varchar(255) NOT NULL,
                `model` varchar(64) NOT NULL,
                `quantity` int(4) NOT NULL,
                `opened` tinyint(1) NOT NULL,
                `return_reason_id` int(11) NOT NULL,
                `return_action_id` int(11) NOT NULL,
                `return_status_id` int(11) NOT NULL,
                `comment` text,
                `date_ordered` date NOT NULL DEFAULT \'0000-00-00\',
                `date_added` datetime NOT NULL,
                `date_modified` datetime NOT NULL,
                PRIMARY KEY (`return_id`)
              ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ){
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->tablecounter;
		$text .= $this->msg( sprintf( $this->lang['msg_table'],   DB_PREFIX . 'return' ) );
	}


	if( !array_search( DB_PREFIX . 'return_action' , $this->structure->tables()) ) {
		$sql = '
                CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'return_action` (
               `return_action_id` int(11) NOT NULL AUTO_INCREMENT,
               `language_id` int(11) NOT NULL DEFAULT \'0\',
               `name` varchar(64) NOT NULL,
               PRIMARY KEY (`return_action_id`,`language_id`)
             ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ){
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }

		$sql = '
                INSERT INTO 
                           `' . DB_PREFIX . 'return_action` (`return_action_id`, `language_id`, `name`)
                VALUES
                           (1, 1, \'Refunded\'),
                           (2, 1, \'Credit Issued\'),
                           (3, 1, \'Replacement Sent\');';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ){
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->tablecounter;
		$text .= $this->msg( sprintf( $this->lang['msg_table'],   DB_PREFIX . 'return_action' ) );
	}

	if( !array_search( DB_PREFIX . 'return_history' , $this->structure->tables()) ) {
		$sql = '
                CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'return_history` (
                `return_history_id` int(11) NOT NULL AUTO_INCREMENT,
                `return_id` int(11) NOT NULL,
                `return_status_id` int(11) NOT NULL,
                `notify` tinyint(1) NOT NULL,
                `comment` text NOT NULL,
                `date_added` datetime NOT NULL,
                PRIMARY KEY (`return_history_id`)
              ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;';


		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ){
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->tablecounter;
		$text .= $this->msg( sprintf( $this->lang['msg_table'],   DB_PREFIX . 'return_history' ) );
	}


	if( !array_search( DB_PREFIX . 'return_reason' , $this->structure->tables()) ) {
		$sql = '
                CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'return_reason` (
                `return_reason_id` int(11) NOT NULL AUTO_INCREMENT,
                `language_id` int(11) NOT NULL DEFAULT \'0\',
                `name` varchar(128) NOT NULL,
                PRIMARY KEY (`return_reason_id`,`language_id`)
              ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ){
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->tablecounter;
		 $sql = '
                SELECT 
                       *
                FROM  `' . DB_PREFIX . 'language`';

          $languages = $this->db->query($sql);
        foreach($languages as $language){

		$sql = '
					INSERT INTO `' . DB_PREFIX . 'return_reason` (`return_reason_id`, `language_id`, `name`) VALUES
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
       }
		$text .= $this->msg( sprintf( $this->lang['msg_table'],   DB_PREFIX . 'return_reason' ) );
	}

	if( !array_search( DB_PREFIX . 'return_status' , $this->structure->tables()) ) {
		$sql = '
                CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'return_status` (
                `return_status_id` int(11) NOT NULL AUTO_INCREMENT,
                `language_id` int(11) NOT NULL DEFAULT \'0\',
                `name` varchar(32) NOT NULL,
                PRIMARY KEY (`return_status_id`,`language_id`)
              ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;';


		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ){
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }

		$sql = '
                 INSERT INTO
                            `' . DB_PREFIX . 'return_status` (`return_status_id`, `language_id`, `name`)
                 VALUES
                           (1, 1, \'Pending\'),
                           (3, 1, \'Complete\'),
                           (2, 1, \'Awaiting Products\');';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ){
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->tablecounter;
		$text .= $this->msg( sprintf( $this->lang['msg_table'],   DB_PREFIX . 'return_status' ) );
	}


	if( !array_search( DB_PREFIX . 'voucher' , $this->structure->tables()) ) {
		$sql = '
                CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'voucher` (
                `voucher_id` int(11) NOT NULL AUTO_INCREMENT,
                `order_id` int(11) NOT NULL,
                `code` varchar(10) NOT NULL,
                `from_name` varchar(64) NOT NULL,
                `from_email` varchar(96) NOT NULL,
                `to_name` varchar(64) NOT NULL,
                `to_email` varchar(96) NOT NULL,
                `voucher_theme_id` int(11) NOT NULL,
                `message` text NOT NULL,
                `amount` decimal(15,4) NOT NULL,
                `status` tinyint(1) NOT NULL,
                `date_added` datetime NOT NULL,
                PRIMARY KEY (`voucher_id`)
              ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;';


		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ){
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->tablecounter;
		$text .= $this->msg( sprintf( $this->lang['msg_table'],   DB_PREFIX . 'voucher' ) );
	}
	if( !array_search( DB_PREFIX . 'voucher_history' , $this->structure->tables()) ) {
		$sql = '
                CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'voucher_history` (
                `voucher_history_id` int(11) NOT NULL AUTO_INCREMENT,
                `voucher_id` int(11) NOT NULL,
                `order_id` int(11) NOT NULL,
                `amount` decimal(15,4) NOT NULL,
                `date_added` datetime NOT NULL,
                PRIMARY KEY (`voucher_history_id`)
              ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ){
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->tablecounter;
		$text .= $this->msg( sprintf( $this->lang['msg_table'],   DB_PREFIX . 'voucher_history' ) );
	}

	if( !array_search( DB_PREFIX . 'voucher_theme' , $this->structure->tables()) ) {
		$sql = '
                CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'voucher_theme` (
                `voucher_theme_id` int(11) NOT NULL AUTO_INCREMENT,
                `image` varchar(255) NOT NULL,
                PRIMARY KEY (`voucher_theme_id`)
              ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ){
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->tablecounter;
		$text .= $this->msg( sprintf( $this->lang['msg_table'],   DB_PREFIX . 'voucher_theme' ) );
	}

	if( !array_search( DB_PREFIX . 'voucher_theme_description' , $this->structure->tables()) ) {
		$sql = '
                CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'voucher_theme_description` (
                `voucher_theme_id` int(11) NOT NULL,
                `language_id` int(11) NOT NULL,
                `name` varchar(32) NOT NULL,
                PRIMARY KEY (`voucher_theme_id`,`language_id`)
              ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ){
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }


		$sql = '
                INSERT INTO
                           `' . DB_PREFIX . 'voucher_theme_description` (`voucher_theme_id`, `language_id`, `name`)
                VALUES
                           (6, 1, \'Christmas\'),
                           (7, 1, \'Birthday\'),
                           (8, 1, \'General\');';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ){
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->tablecounter;
		$text .= $this->msg( sprintf( $this->lang['msg_table'],   DB_PREFIX . 'voucher_theme_description' ) );
	}
		$text .= $this->version( sprintf( $this->lang['msg_upgrade_to_version'],   '1.5.0', '' ) );

    return $text;
  }

  public function addUpgradeTo1513() {  
        $text = '';

	if( !array_search( DB_PREFIX . 'tax_rate_to_customer_group', $this->structure->tables() ) ) {
		$sql = '
		CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'tax_rate_to_customer_group` (
		`tax_rate_id` int(11) NOT NULL,
		`customer_group_id` int(11) NOT NULL,
		PRIMARY KEY (`tax_rate_id`,`customer_group_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ){
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->tablecounter;
		$text .= $this->msg( sprintf( $this->lang['msg_table'],   DB_PREFIX . 'tax_rate_to_customer_group' ) );
	}

	if( !array_search( DB_PREFIX . 'tax_rule', $this->structure->tables() ) ) {
		$sql =
		'CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'tax_rule` (
        `tax_rule_id` int(11) NOT NULL AUTO_INCREMENT,
        `tax_class_id` int(11) NOT NULL,
        `tax_rate_id` int(11) NOT NULL,
        `based` varchar(10) NOT NULL,
        `priority` int(5) NOT NULL DEFAULT \'1\',
        PRIMARY KEY (`tax_rule_id`)
      ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ){
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->tablecounter;
		$text .= $this->msg( sprintf( $this->lang['msg_table'],   DB_PREFIX . 'tax_rule' ) );
	}
		$text .= $this->version( sprintf( $this->lang['msg_upgrade_to_version'],   '1.5.1.3', '' ) );
    return $text;
  }

  public function addUpgradeTo152() {  

        $text = '';
        if( !$this->upgrade2030 ){
	if( !array_search( DB_PREFIX . 'order_fraud', $this->structure->tables() ) ) {
		$sql = '
		CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'order_fraud` (
		`order_id` int(11) NOT NULL,
		`customer_id` int(11) NOT NULL,
		`country_match` varchar(3) NOT NULL,
		`country_code` varchar(2) NOT NULL,
		`high_risk_country` varchar(3) NOT NULL,
		`distance` int(11) NOT NULL,
		`ip_region` varchar(255) NOT NULL,
		`ip_city` varchar(255) NOT NULL,
		`ip_latitude` decimal(10,6) NOT NULL,
		`ip_longitude` decimal(10,6) NOT NULL,
		`ip_isp` varchar(255) NOT NULL,
		`ip_org` varchar(255) NOT NULL,
		`ip_asnum` int(11) NOT NULL,
		`ip_user_type` varchar(255) NOT NULL,
		`ip_country_confidence` varchar(3) NOT NULL,
		`ip_region_confidence` varchar(3) NOT NULL,
		`ip_city_confidence` varchar(3) NOT NULL,
		`ip_postal_confidence` varchar(3) NOT NULL,
		`ip_postal_code` varchar(10) NOT NULL,
		`ip_accuracy_radius` int(11) NOT NULL,
		`ip_net_speed_cell` varchar(255) NOT NULL,
		`ip_metro_code` int(3) NOT NULL,
		`ip_area_code` int(3) NOT NULL,
		`ip_time_zone` varchar(255) NOT NULL,
		`ip_region_name` varchar(255) NOT NULL,
		`ip_domain` varchar(255) NOT NULL,
		`ip_country_name` varchar(255) NOT NULL,
		`ip_continent_code` varchar(2) NOT NULL,
		`ip_corporate_proxy` varchar(3) NOT NULL,
		`anonymous_proxy` varchar(3) NOT NULL,
		`proxy_score` int(3) NOT NULL,
		`is_trans_proxy` varchar(3) NOT NULL,
		`free_mail` varchar(3) NOT NULL,
		`carder_email` varchar(3) NOT NULL,
		`high_risk_username` varchar(3) NOT NULL,
		`high_risk_password` varchar(3) NOT NULL,
		`bin_match` varchar(10) NOT NULL,
		`bin_country` varchar(2) NOT NULL,
		`bin_name_match` varchar(3) NOT NULL,
		`bin_name` varchar(255) NOT NULL,
		`bin_phone_match` varchar(3) NOT NULL,
		`bin_phone` varchar(32) NOT NULL,
		`customer_phone_in_billing_location` varchar(8) NOT NULL,
		`ship_forward` varchar(3) NOT NULL,
		`city_postal_match` varchar(3) NOT NULL,
		`ship_city_postal_match` varchar(3) NOT NULL,
		`score` decimal(10,5) NOT NULL,
		`explanation` text NOT NULL,
		`risk_score` decimal(10,5) NOT NULL,
		`queries_remaining` int(11) NOT NULL,
		`maxmind_id` varchar(8) NOT NULL,
		`error` text NOT NULL,
		`date_added` datetime NOT NULL,
		PRIMARY KEY (`order_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ) {
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->tablecounter;
		$text .= $this->msg( sprintf( $this->lang['msg_table'],   DB_PREFIX . 'order_fraud' ) );
	}
            }

	if( !array_search( DB_PREFIX . 'order_voucher', $this->structure->tables() ) ) {

		$sql = '
		CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'order_voucher` (
		`order_voucher_id` int(11) NOT NULL AUTO_INCREMENT,
		`order_id` int(11) NOT NULL,
		`voucher_id` int(11) NOT NULL,
		`description` varchar(255) NOT NULL,
		`code` varchar(10) NOT NULL,
		`from_name` varchar(64) NOT NULL,
		`from_email` varchar(96) NOT NULL,
		`to_name` varchar(64) NOT NULL,
		`to_email` varchar(96) NOT NULL,
		`voucher_theme_id` int(11) NOT NULL,
		`message` text NOT NULL,
		`amount` decimal(15,4) NOT NULL,
		PRIMARY KEY (`order_voucher_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ) {
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->tablecounter;
		$text .= $this->msg( sprintf( $this->lang['msg_table'],   DB_PREFIX . 'order_voucher' ) );
	}

		$text .= $this->version( sprintf( $this->lang['msg_upgrade_to_version'],   '1.5.2', '' ) );
    return $text;
  }

  public function addUpgradeTo153() {
        $text = '';
  	if( !array_search( DB_PREFIX . 'customer_group_description', $this->structure->tables() ) ) {
		$sql = '
		CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'customer_group_description` (
		`customer_group_id` int(11) NOT NULL,
		`language_id` int(11) NOT NULL,
		`name` varchar(32) NOT NULL,
		`description` text NOT NULL,
		PRIMARY KEY (`customer_group_id`,`language_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci';

		if( !$this->simulate ) {
                   $this->db->query( $sql );
                }
                if( $this->showOps ) {
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->tablecounter;
		$text .= $this->msg( sprintf( $this->lang['msg_table'],   DB_PREFIX . 'customer_group_description' ) );

                $sql = '
                SELECT 
                       *
                FROM  `' . DB_PREFIX . 'customer_group`';
 
                $query = $this->db->query($sql);

                if( count( $query->rows ) > 0 ){
                  $sql = '
                SELECT 
                       *
                FROM  `' . DB_PREFIX . 'language`';

                $languages = $this->db->query($sql);
                
       if( count( $query->rows ) > 0 ){
          $customers = array();
            foreach( $query->rows as $id ){
            	$customers[] = array("customer_group_id" => $id['customer_group_id'],
            	                     "name" => $id['name'],
            	                     "description" => "Group ". $id['customer_group_id'] );
            }	
            $i = 0;
            
       	     foreach($languages->rows as $language){
		  $sql = '
		  INSERT INTO
		 	   `' . DB_PREFIX . 'customer_group_description` (`customer_group_id`, `language_id`, `name`, `description`)
		  VALUES
			   (' . $customers[$i]['customer_group_id'] . ',
			    \'' . $language['language_id'] . '\', 
			    \'' . $customers[$i]['name'] . '\',
			    \''. $customers[$i]['description'] . '\')';
					         if( !$this->simulate ) {
				                     $this->db->query( $sql );
				                  }
				                  if( $this->showOps ) {
						                $text .= '<p><pre>' . $sql .'</pre></p>';
				                  }
		  $text .= $this->msg( sprintf( $this->lang['msg_text'],   'customer_group_description', $this->lang['msg_new_data'] ) );
             $i++;
              }
            }
	}
		$text .= $this->version( sprintf( $this->lang['msg_upgrade_to_version'],   '1.5.3.1', '' ) );
    return $text;
  }

  public function addUpgradeTo154() {
        $text = '';
	if( !array_search( DB_PREFIX . 'customer_online', $this->structure->tables() ) ) {
		$sql = '
		CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'customer_online` (
		`ip` varchar(40) NOT NULL,
		`customer_id` int(11) NOT NULL,
		`url` text NOT NULL,
		`referer` text NOT NULL,
		`date_added` datetime NOT NULL,
		PRIMARY KEY (`ip`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci';

		if( !$this->simulate ) {
                     $this->db->query( $sql );
                }
                if( $this->showOps ) {
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->tablecounter;
		$text .= $this->msg( sprintf( $this->lang['msg_table'],   DB_PREFIX . 'customer_online' ) );
	}
		$text .= $this->version( sprintf( $this->lang['msg_upgrade_to_version'],   '1.5.4', '' ) );
    return $text;
  }
  public function addUpgradeTo155() {
        $text = '';

	if( !array_search( DB_PREFIX . 'category_filter', $this->structure->tables() ) ) {
		$sql = '
		CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'category_filter` (
		`category_id` int(11) NOT NULL,
		`filter_id` int(11) NOT NULL,
		PRIMARY KEY (`category_id`,`filter_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ){
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++ $this->tablecounter;
		$text .= $this->msg( sprintf( $this->lang['msg_table'],   DB_PREFIX . 'category_filter' ) );
	}

	if( !array_search( DB_PREFIX . 'category_path', $this->structure->tables() ) ) {
		$sql = '
		CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'category_path` (
		  `category_id` int(11) NOT NULL,
		  `path_id` int(11) NOT NULL,
		  `level` int(11) NOT NULL,
		  PRIMARY KEY (`category_id`,`path_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci';

		if( !$this->simulate ) {
                   $this->db->query( $sql );
                }
                if( $this->showOps ) {
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++ $this->tablecounter;
		$text .= $this->msg( sprintf( $this->lang['msg_table'],   DB_PREFIX . 'category_path' ) );

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

			$text .= $this->msg( sprintf( $this->lang['msg_table'],   DB_PREFIX . 'category_path', sprintf( $this->lang['msg_cat_path'], $i ) ) );
		}
	}

      if( !array_search( DB_PREFIX . 'coupon_category', $this->structure->tables() ) ) {
		$sql = '
		CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'coupon_category` (
		`coupon_id` int(11) NOT NULL,
		`category_id` int(11) NOT NULL,
		PRIMARY KEY (`coupon_id`,`category_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci';

		if( !$this->simulate ) {
                   $this->db->query( $sql );
                }
                if( $this->showOps ) {
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->tablecounter;
		$text .= $this->msg( sprintf( $this->lang['msg_table'],   DB_PREFIX . 'coupon_category' ) );
	}

	if( !array_search( DB_PREFIX . 'customer_ban_ip', $this->structure->tables() ) ) {
		$sql = '
                 CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'customer_ban_ip` (
                  `customer_ban_ip_id` int(11) NOT NULL AUTO_INCREMENT,
                  `ip` varchar(40) NOT NULL,
                 PRIMARY KEY (`customer_ban_ip_id`),
                 KEY `ip` (`ip`)
                 ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;';

		if( !$this->simulate ) {
                  $this->db->query( $sql );
                }
               if( $this->showOps ) {
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++ $this->tablecounter;
		$text .= $this->msg( sprintf( $this->lang['msg_table'],   DB_PREFIX . 'customer_ban_ip' ) );
	}

	if( !array_search( DB_PREFIX . 'customer_history', $this->structure->tables() ) ) {
		$sql = '
		CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'customer_history` (
		`customer_history_id` int(11) NOT NULL AUTO_INCREMENT,
		`customer_id` int(11) NOT NULL,
		`comment` text NOT NULL,
		`date_added` datetime NOT NULL,
		PRIMARY KEY (`customer_history_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci';

		if( !$this->simulate ) {
                    $this->db->query( $sql );
                }
                if( $this->showOps ) {
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->tablecounter;
		$text .= $this->msg( sprintf( $this->lang['msg_table'],   DB_PREFIX . 'customer_history' ) );
	}
       if( !array_search( DB_PREFIX . 'custom_field', $this->structure->tables() ) ) {
		$sql = '
		CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'custom_field` (
                `custom_field_id` int(11) NOT NULL AUTO_INCREMENT,
                `type` varchar(32) NOT NULL,
                `value` text NOT NULL,
                `location` varchar(7) NOT NULL,
                `status` tinyint(1) NOT NULL,
                `sort_order` int(3) NOT NULL,
                PRIMARY KEY (`custom_field_id`)
                ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1';

		if( !$this->simulate ) {
                    $this->db->query( $sql );
                }
                if( $this->showOps ) {
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->tablecounter;
		$text .= $this->msg( sprintf( $this->lang['msg_table'],   DB_PREFIX . 'custom_field' ) );
	}

	
	if( !array_search( DB_PREFIX . 'custom_field_customer_group', $this->structure->tables() ) ) {
		$sql = '
		CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'custom_field_customer_group` (
		`custom_field_id` int(11) NOT NULL,
		`customer_group_id` int(11) NOT NULL,
		PRIMARY KEY (`custom_field_id`,`customer_group_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci';

		if( !$this->simulate ) {
                     $this->db->query( $sql );
                }
                if( $this->showOps ) {
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->tablecounter;
		$text .= $this->msg( sprintf( $this->lang['msg_table'],   DB_PREFIX . 'custom_field_customer_group' ) );
	}
	if( !array_search( DB_PREFIX . 'custom_field_description', $this->structure->tables() ) ) {
		$sql = '
		CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'custom_field_description` (
		`custom_field_id` int(11) NOT NULL,
		`language_id` int(11) NOT NULL,
		`name` varchar(128) NOT NULL,
		PRIMARY KEY (`custom_field_id`,`language_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci';

		if( !$this->simulate ) {
                    $this->db->query( $sql );
                }
                if( $this->showOps ) {
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->tablecounter;
		$text .= $this->msg( sprintf( $this->lang['msg_table'],   DB_PREFIX . 'custom_field_description' ) );
	}

	if( !array_search( DB_PREFIX . 'custom_field_value', $this->structure->tables() ) ) {
		$sql = '
		CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'custom_field_value` (
		`custom_field_value_id` int(11) NOT NULL AUTO_INCREMENT,
		`custom_field_id` int(11) NOT NULL,
		`sort_order` int(3) NOT NULL,
		PRIMARY KEY (`custom_field_value_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci';

		if( !$this->simulate ) {
                      $this->db->query( $sql );
                }
                if( $this->showOps ) {
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->tablecounter;
		$text .= $this->msg( sprintf( $this->lang['msg_table'],   DB_PREFIX . 'custom_field_value' ) );
	}

	if( !array_search( DB_PREFIX . 'custom_field_value_description', $this->structure->tables() ) ) {
		$sql = '
		CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'custom_field_value_description` (
		`custom_field_value_id` int(11) NOT NULL,
		`language_id` int(11) NOT NULL,
		`custom_field_id` int(11) NOT NULL,
		`name` varchar(128) NOT NULL,
		PRIMARY KEY (`custom_field_value_id`,`language_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci';

		if( !$this->simulate ) {
                      $this->db->query( $sql );
                }
                if( $this->showOps ) {
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->tablecounter;
		$text .= $this->msg( sprintf( $this->lang['msg_table'],   DB_PREFIX . 'custom_field_value_description' ) );
	}

	if( !array_search( DB_PREFIX . 'filter', $this->structure->tables() ) ) {
		$sql = '
		CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'filter` (
		`filter_id` int(11) NOT NULL AUTO_INCREMENT,
		`filter_group_id` int(11) NOT NULL,
		`sort_order` int(3) NOT NULL,
		PRIMARY KEY (`filter_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ) {
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->tablecounter;
		$text .= $this->msg( sprintf( $this->lang['msg_table'],   DB_PREFIX . 'filter' ) );
	}

	if( !array_search( DB_PREFIX . 'filter_description', $this->structure->tables() ) ) {
		$sql = '
		CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'filter_description` (
		`filter_id` int(11) NOT NULL,
		`language_id` int(11) NOT NULL,
		`filter_group_id` int(11) NOT NULL,
		`name` varchar(64) NOT NULL,
		PRIMARY KEY (`filter_id`,`language_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci';

		if( !$this->simulate ) {
                        $this->db->query( $sql );
                }
                if( $this->showOps ) {
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->tablecounter;
		$text .= $this->msg( sprintf( $this->lang['msg_table'],   DB_PREFIX . 'filter_description' ) );
	}

	if( !array_search( DB_PREFIX . 'filter_group', $this->structure->tables() ) ) {
		$sql = '
		CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'filter_group` (
		`filter_group_id` int(11) NOT NULL AUTO_INCREMENT,
		`sort_order` int(3) NOT NULL,
		PRIMARY KEY (`filter_group_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci';

		if( !$this->simulate ) {
                     $this->db->query( $sql );
                }
                if( $this->showOps ) {
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->tablecounter;
		$text .= $this->msg( sprintf( $this->lang['msg_table'],   DB_PREFIX . 'filter_group' ) );
	}

	if( !array_search( DB_PREFIX . 'filter_group_description', $this->structure->tables() ) ) {
		$sql = '
		CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'filter_group_description` (
		`filter_group_id` int(11) NOT NULL,
		`language_id` int(11) NOT NULL,
		`name` varchar(64) NOT NULL,
		PRIMARY KEY (`filter_group_id`,`language_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci';

		if( !$this->simulate ) {
                      $this->db->query( $sql );
                }
                if( $this->showOps ) {
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->tablecounter;
		$text .= $this->msg( sprintf( $this->lang['msg_table'],   DB_PREFIX . 'filter_group_description' ) );
	}

	if( !array_search( DB_PREFIX . 'product_filter', $this->structure->tables() ) ) {
		$sql = '
		CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'product_filter` (
		`product_id` int(11) NOT NULL,
		`filter_id` int(11) NOT NULL,
		PRIMARY KEY (`product_id`,`filter_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ) {
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->tablecounter;
		$text .= $this->msg( sprintf( $this->lang['msg_table'],   DB_PREFIX . 'product_filter' ) );
	}
		$text .= $this->version( sprintf( $this->lang['msg_upgrade_to_version'],   '1.5.5.1', '' ) );
    return $text;
  }
  public function addUpgradeTo156() {
        $text = '';
	if( !array_search( DB_PREFIX . 'order_recurring', $this->structure->tables() ) ) {
		$sql = '
		CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'order_recurring` (
                `order_recurring_id` int(11) NOT NULL AUTO_INCREMENT,
                `order_id` int(11) NOT NULL,
                `reference` varchar(255) NOT NULL,
                `product_id` int(11) NOT NULL,
                `product_name` varchar(255) NOT NULL,
                `product_quantity` int(11) NOT NULL,
                `recurring_id` int(11) NOT NULL,
                `recurring_name` varchar(255) NOT NULL,
                `recurring_description` varchar(255) NOT NULL,
                `recurring_frequency` varchar(25) NOT NULL,
                `recurring_cycle` smallint(6) NOT NULL,
                `recurring_duration` smallint(6) NOT NULL,
                `recurring_price` decimal(10,4) NOT NULL,
                `trial` tinyint(1) NOT NULL,
                `trial_frequency` varchar(25) NOT NULL,
                `trial_cycle` smallint(6) NOT NULL,
                `trial_duration` smallint(6) NOT NULL,
                `trial_price` decimal(10,4) NOT NULL,
                `status` tinyint(4) NOT NULL,
                `date_added` datetime NOT NULL,
                PRIMARY KEY (`order_recurring_id`)
                ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                } if( $this->showOps ) {
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->tablecounter;
		$text .= $this->msg( sprintf( $this->lang['msg_table'],   DB_PREFIX . 'order_recurring' ) );
	}

	if( !array_search( DB_PREFIX . 'order_recurring_transaction', $this->structure->tables() ) ) {
		$sql = '
		CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'order_recurring_transaction` (
                `order_recurring_transaction_id` int(11) NOT NULL AUTO_INCREMENT,
                `order_recurring_id` int(11) NOT NULL,
                `reference` varchar(255) NOT NULL,
                `type` varchar(255) NOT NULL,
                `amount` decimal(10,4) NOT NULL,
                `date_added` datetime NOT NULL,
                PRIMARY KEY (`order_recurring_transaction_id`)
                ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ) {
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->tablecounter;
		$text .= $this->msg( sprintf( $this->lang['msg_table'],   DB_PREFIX . 'order_recurring_transaction' ) );
	}

	if( !array_search( DB_PREFIX . 'product_recurring', $this->structure->tables() ) ) {
		$sql = '
		CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'product_recurring` (
		`product_id` int(11) NOT NULL,
		`recurring_id` int(11) NOT NULL,
		`customer_group_id` int(11) NOT NULL,
		PRIMARY KEY (`product_id`,`recurring_id`,`customer_group_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ) {
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->tablecounter;
		$text .= $this->msg( sprintf( $this->lang['msg_table'],   DB_PREFIX . 'product_recurring' ) );
	}
		$text .= $this->version( sprintf( $this->lang['msg_upgrade_to_version'],   '1.5.6.4', '' ) );
    return $text;
  }
  public function addUpgradeTo2001() {
        $text = '';
	if( !array_search( DB_PREFIX . 'affiliate_activity', $this->structure->tables() ) ) {
		$sql = '
		CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'affiliate_activity` (
		`activity_id` int(11) NOT NULL AUTO_INCREMENT,
		`affiliate_id` int(11) NOT NULL,
		`key` varchar(64) NOT NULL,
		`data` text NOT NULL,
		`ip` varchar(40) NOT NULL,
		`date_added` datetime NOT NULL,
		PRIMARY KEY (`activity_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8';

		if( !$this->simulate ) { 
                       $this->db->query( $sql );
                } if( $this->showOps ) {
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->tablecounter;
		$text .= $this->msg( sprintf( $this->lang['msg_table'],   DB_PREFIX . 'affiliate_activity' ) );
	}

	if( !array_search( DB_PREFIX . 'affiliate_login', $this->structure->tables() ) ) {
		$sql = '
                CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'affiliate_login` (
                 `affiliate_login_id` int(11) NOT NULL AUTO_INCREMENT,
                 `email` varchar(96) NOT NULL,
                 `ip` varchar(40) NOT NULL,
                 `total` int(4) NOT NULL,
                 `date_added` datetime NOT NULL,
                 `date_modified` datetime NOT NULL,
                 PRIMARY KEY (`affiliate_login_id`),
                 KEY `email` (`email`),
                 KEY `ip` (`ip`)
                 ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;';

		if( !$this->simulate ) { 
                       $this->db->query( $sql );
                } if( $this->showOps ) {
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->tablecounter;
		$text .= $this->msg( sprintf( $this->lang['msg_table'],   DB_PREFIX . 'affiliate_login' ) );
	}

	if( !array_search( DB_PREFIX . 'api', $this->structure->tables() ) ){
		$sql = '
		CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'api` (
		  `api_id` int(11) NOT NULL AUTO_INCREMENT,
		  `username` varchar(64) NOT NULL,
		  `firstname` varchar(64) NOT NULL,
		  `lastname` varchar(64) NOT NULL,
		  `password` text NOT NULL,
		  `status` tinyint(1) NOT NULL,
		  `date_added` datetime NOT NULL,
		  `date_modified` datetime NOT NULL,
		  PRIMARY KEY (`api_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8';

		if( !$this->simulate ) {
                   $this->db->query( $sql );
                }
                if( $this->showOps ) {
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->tablecounter;
		$text .= $this->msg( sprintf( $this->lang['msg_table'],   DB_PREFIX . 'api' ) );

		  $sql = '
		  INSERT INTO
		 	   `' . DB_PREFIX . 'api` (`api_id`, `username`, `password`, `status`, `date_added`, `date_modified`)
		  VALUES
			   (1, \'localhost\', \'abcdefghijk\', 1, NOW(), NOW())';

		  if( !$this->simulate ) {
                     $this->db->query( $sql );
                  }
                 if( $this->showOps ) {
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		  $text .= $this->msg( sprintf( $this->lang['msg_text'],   'api', $this->lang['msg_new_data'] ) );
	}

	if( !array_search( DB_PREFIX . 'customer_activity', $this->structure->tables() ) ) {
		$sql = '
		CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'customer_activity` (
		`activity_id` int(11) NOT NULL AUTO_INCREMENT,
		`customer_id` int(11) NOT NULL,
		`key` varchar(64) NOT NULL,
		`data` text NOT NULL,
		`ip` varchar(40) NOT NULL,
		`date_added` datetime NOT NULL,
		PRIMARY KEY (`activity_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8';

		if( !$this->simulate ) {
                  $this->db->query( $sql );
                }
                if( $this->showOps ) {
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++ $this->tablecounter;
		$text .= $this->msg( sprintf( $this->lang['msg_table'],   DB_PREFIX . 'customer_activity' ) );
	}

	if( !array_search( DB_PREFIX . 'customer_login', $this->structure->tables() ) ) {
		$sql = '
                CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'customer_login` (
                 `customer_login_id` int(11) NOT NULL AUTO_INCREMENT,
                 `email` varchar(96) NOT NULL,
                 `ip` varchar(40) NOT NULL,
                 `total` int(4) NOT NULL,
                 `date_added` datetime NOT NULL,
                 `date_modified` datetime NOT NULL,
                 PRIMARY KEY (`customer_login_id`),
                 KEY `email` (`email`),
                 KEY `ip` (`ip`)
                 ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;';

		if( !$this->simulate ) {
                  $this->db->query( $sql );
                }
                if( $this->showOps ) {
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++ $this->tablecounter;
		$text .= $this->msg( sprintf( $this->lang['msg_table'],   DB_PREFIX . 'customer_login' ) );
	}

        if( array_search( DB_PREFIX . 'custom_field_to_customer_group', $this->structure->tables() ) ) {
               $sql = '
                RENAME TABLE
                      `' . DB_PREFIX . 'custom_field_to_customer_group`
                TO
                      `' . DB_PREFIX . 'custom_field_customer_group`';

		if( !$this->simulate ) {
                     $this->db->query( $sql );
                }
                if( $this->showOps ) {
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->tablecounter;
		$text .= $this->msg( sprintf( $this->lang['msg_table'],   DB_PREFIX . 'custom_field_customer_group' ) );
	}

	if( !array_search( DB_PREFIX . 'event', $this->structure->tables() ) ) {
		$sql = '
		CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'event` (
		`event_id` int(11) NOT NULL AUTO_INCREMENT,
		`code` varchar(32) NOT NULL,
		`trigger` text NOT NULL,
		`action` text NOT NULL,
		PRIMARY KEY (`event_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8';

		if( !$this->simulate ) {
                      $this->db->query( $sql );
                } if( $this->showOps ) {
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->tablecounter;
		$text .= $this->msg( sprintf( $this->lang['msg_table'],   DB_PREFIX . 'event' ) );
	}

	if( !array_search( DB_PREFIX . 'layout_module', $this->structure->tables() ) ) {
		$sql = '
		CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'layout_module` (
		`layout_module_id` int(11) NOT NULL AUTO_INCREMENT,
		`layout_id` int(11) NOT NULL,
		`code` varchar(64) NOT NULL,
		`position` varchar(14) NOT NULL,
		`sort_order` int(3) NOT NULL,
		PRIMARY KEY (`layout_module_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                } if( $this->showOps ) {
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->tablecounter;

		$text .= $this->msg( sprintf( $this->lang['msg_table'],  'layout_module' ) );
	}

	if( !array_search( DB_PREFIX . 'location', $this->structure->tables() ) ) {
		$sql = '
		CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'location` (
		`location_id` int(11) NOT NULL AUTO_INCREMENT,
		`name` varchar(32) NOT NULL,
		`address` text NOT NULL,
		`telephone` varchar(32) NOT NULL,
		`fax` varchar(32) NOT NULL,
		`geocode` varchar(32) NOT NULL,
		`image` varchar(255) DEFAULT NULL,
		`open` text NOT NULL,
		`comment` text NOT NULL,
		PRIMARY KEY (`location_id`),
		KEY `name` (`name`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                } if( $this->showOps ) {
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->tablecounter;
		$text .= $this->msg( sprintf( $this->lang['msg_table'],   DB_PREFIX . 'location' ) );
	}

	if( !array_search( DB_PREFIX . 'marketing', $this->structure->tables() ) ) {
		$sql = '
		CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'marketing` (
		`marketing_id` int(11) NOT NULL AUTO_INCREMENT,
		`name` varchar(32) NOT NULL,
		`description` text NOT NULL,
		`code` varchar(64) NOT NULL,
		`clicks` int(5) NOT NULL DEFAULT \'0\',
		`date_added` datetime NOT NULL,
		PRIMARY KEY (`marketing_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                } if( $this->showOps ) {
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->tablecounter;
		$text .= $this->msg( sprintf( $this->lang['msg_table'],   DB_PREFIX . 'marketing' ) );
	}

	if( !array_search( DB_PREFIX . 'modification', $this->structure->tables() ) ) {
		$sql = '
		CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'modification` (
                 `modification_id` int(11) NOT NULL AUTO_INCREMENT,
                 `name` varchar(64) NOT NULL,
                 `code` varchar(64) NOT NULL,
                 `author` varchar(64) NOT NULL,
                 `version` varchar(32) NOT NULL,
                 `link` varchar(255) NOT NULL,
                 `xml` mediumtext NOT NULL,
                 `status` tinyint(1) NOT NULL,
                 `date_added` datetime NOT NULL,
                PRIMARY KEY (`modification_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                } if( $this->showOps ) {
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->tablecounter;
		$text .= $this->msg( sprintf( $this->lang['msg_table'],   DB_PREFIX . 'modification' ) );
	}


	if( !array_search( DB_PREFIX . 'module', $this->structure->tables() ) ) {
		$sql = '
		CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'module` (
                 `module_id` int(11) NOT NULL AUTO_INCREMENT,
                 `name` varchar(64) NOT NULL,
                `code` varchar(32) NOT NULL,
                `setting` text NOT NULL,
                PRIMARY KEY (`module_id`)
               ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                } if( $this->showOps ) {
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->tablecounter;
		$text .= $this->msg( sprintf( $this->lang['msg_table'],   DB_PREFIX . 'module' ) );
	}

	if( !array_search( DB_PREFIX . 'order_custom_field', $this->structure->tables() ) ) {
		$sql = '
		CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'order_custom_field` (
		`order_custom_field_id` int(11) NOT NULL AUTO_INCREMENT,
		`order_id` int(11) NOT NULL,
		`custom_field_id` int(11) NOT NULL,
		`custom_field_value_id` int(11) NOT NULL,
		`name` varchar(255) NOT NULL,
		`value` text NOT NULL,
		`type` varchar(32) NOT NULL,
		`location` varchar(16) NOT NULL,
		PRIMARY KEY (`order_custom_field_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                } if( $this->showOps ) {
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->tablecounter;
		$text .= $this->msg( sprintf( $this->lang['msg_table'],   DB_PREFIX . 'order_custom_field' ) );
	}

	if( !array_search( DB_PREFIX . 'recurring', $this->structure->tables() ) ) {
		$sql = '
		CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'recurring` (
		`recurring_id` int(11) NOT NULL AUTO_INCREMENT,
		`price` decimal(10,4) NOT NULL,
		`frequency` enum(\'day\',\'week\',\'semi_month\',\'month\',\'year\') NOT NULL,
		`duration` int(10) unsigned NOT NULL,
		`cycle` int(10) unsigned NOT NULL,
		`trial_status` tinyint(4) NOT NULL,
		`trial_price` decimal(10,4) NOT NULL,
		`trial_frequency` enum(\'day\',\'week\',\'semi_month\',\'month\',\'year\') NOT NULL,
		`trial_duration` int(10) unsigned NOT NULL,
		`trial_cycle` int(10) unsigned NOT NULL,
		`status` tinyint(4) NOT NULL,
		`sort_order` int(11) NOT NULL,
		PRIMARY KEY (`recurring_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                } if( $this->showOps ) {
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->tablecounter;
		$text .= $this->msg( sprintf( $this->lang['msg_table'],   DB_PREFIX . 'recurring' ) );
	}

	if( !array_search( DB_PREFIX . 'recurring_description', $this->structure->tables() ) ) {
		$sql = '
		CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'recurring_description` (
		`recurring_id` int(11) NOT NULL,
		`language_id` int(11) NOT NULL,
		`name` varchar(255) NOT NULL,
		PRIMARY KEY (`recurring_id`,`language_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                } if( $this->showOps ) {
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->tablecounter;
		$text .= $this->msg( sprintf( $this->lang['msg_table'],   DB_PREFIX . 'recurring_description' ) );
	}

	if( !array_search( DB_PREFIX . 'upload', $this->structure->tables() ) ) {
		$sql = '
		CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'upload` (
		`upload_id` int(11) NOT NULL AUTO_INCREMENT,
		`name` varchar(255) NOT NULL,
		`filename` varchar(255) NOT NULL,
		`code` varchar(255) NOT NULL,
		`date_added` datetime NOT NULL,
		PRIMARY KEY (`upload_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                } if( $this->showOps ) {
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->tablecounter;
		$text .= $this->msg( sprintf( $this->lang['msg_table'],   DB_PREFIX . 'upload' ) );
	}
	
        if( !$this->hasLayout( 'Compare' )  && array_search( DB_PREFIX . 'layout', $this->structure->tables()) ){
            $sql = 'SELECT MAX(layout_id) as maxim
                     FROM `' . DB_PREFIX . 'layout`'; 

               $query = $this->db->query( $sql );
                $layout_id = $query->row['maxim']+1;
                $layout_id2 = $layout_id+1;

              if( $this->showOps ) {
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
            $sql = '
                   INSERT INTO
                              `' . DB_PREFIX . 'layout` (`layout_id`, `name`)
                   VALUES
                              (' . $layout_id .', \'Compare\'),
                              (' . $layout_id2 . ', \'Search\')';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                } if( $this->showOps ) {
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }

            $sql = 'SELECT MAX(layout_route_id) as layout_route
                     FROM `' . DB_PREFIX . 'layout_route`'; 

               $query = $this->db->query( $sql );
                $layout_route_id = $query->row['layout_route']+1;
                $layout_route_id2 = $layout_route_id+1;

            $sql = '
                   INSERT INTO
                              `' . DB_PREFIX . 'layout_route` (`layout_route_id`, `layout_id`, `route`)
                   VALUES
                              (' . $layout_route_id . ', ' . $layout_id . ', \'product/compare\'),
                              (' . $layout_route_id2 . ', ' . $layout_id2 . ', \'product/search\')';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                } if( $this->showOps ) {
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
	}
		$text .= $this->version( sprintf( $this->lang['msg_upgrade_to_version'],   '2.0.1.0', '' ) );
    return $text;
  }
  public function fixEngineOfTableCustomerOnline(){
     $text = '';
     $schema = mysql_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD) or die (mysql_error());
     $db_selected = mysql_select_db('information_schema',$schema);
    if (!$db_selected) {
     die ('Can\'t use foo : ' . mysql_error());
    }

       $sql = "
              SELECT * FROM
                           TABLES
              WHERE
                           TABLE_SCHEMA = '" . DB_DATABASE . "'
              AND
                           TABLE_NAME = '" . DB_PREFIX ."customer_online'
              AND
                           ENGINE ='innoDB'";
      $info = mysql_query($sql,$schema);


   if( !empty( $info->row ) ){    
         
            $sql = '
                   ALTER TABLE
                             ' . DB_PREFIX . 'customer_online
                   ENGINE = \'MyISAM\'';

                if( !$this->simulate ){
                       $this->db->query($sql);
                } if( $this->showOps ) {
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		$text .= $this->msg( sprintf( $this->lang['msg_table_engine'],   'customer_online', 'engine' ) );

     }

     return $text;
  }
  public function msg( $data ){
       return str_replace( $data, '<div class="msg round">' . $data .'</div>', $data);
  }
  public function version( $data ){
       return str_replace( $data, '<div class="success round">' . $data .'</div>', $data);
  }
}
