<?php
class ModelUpgradeDatabase extends Model{
  private $lang;
  private $simulate;
  private $showOps;
  private $tablecounter;
  public function getTables() {
     

       $query = $this->db->query("SHOW TABLES FROM " . DB_DATABASE);

        $table_list = array();
        foreach($query->rows as $table){
                      $table_list[] = $table['Tables_in_'. DB_DATABASE];
          }
        return $table_list;
  }

  public function addTables( $data ) {  
        $this->simulate = ( !empty( $data['simulate'] ) ? true : false );
        $this->showOps  = ( !empty( $data['showOps'] ) ? true : false );

        $this->lang = $this->lmodel->get('upgrade_database');

        $this->tablecounter = 0;
        $text = '';
	
	if( !array_search( DB_PREFIX . 'tax_rate_to_customer_group', $this->getTables() ) ) {
           $text .= $this->addUpgradeTo1513();
        }
	if( !array_search( DB_PREFIX . 'order_fraud', $this->getTables() ) ) {
           $text .= $this->addUpgradeTo152();
        }
	if( !array_search( DB_PREFIX . 'customer_group_description', $this->getTables() ) ) {
           $text .= $this->addUpgradeTo153();
        }
	if( array_search( DB_PREFIX . 'customer_online', $this->getTables() ) &&
            array_search( DB_PREFIX . 'customer_ip_blacklist', $this->getTables() )) {
           $text .= $this->fixEngineOfTableCustomerOnline();
        }
	if( !array_search( DB_PREFIX . 'customer_online', $this->getTables() ) ) {
           $text .= $this->addUpgradeTo154();
        }

	if( !array_search( DB_PREFIX . 'category_path', $this->getTables() ) ) {
           $text .= $this->addUpgradeTo155();
        }
	if( !array_search( DB_PREFIX . 'order_recurring', $this->getTables() ) ) {
           $text .= $this->addUpgradeTo156();
        }
	if( !array_search( DB_PREFIX . 'event', $this->getTables() ) ) {
           $text .= $this->addUpgradeTo2000();
        }
	$text .= '<div class="header round"> ';
        $text .=  sprintf( addslashes($this->lang['msg_table_count']), $this->tablecounter, '' );
        $text .= ' </div>';
        return $text;
  }
  public function addUpgradeTo1513() {  
        $text = '';

	if( !array_search( DB_PREFIX . 'tax_rate_to_customer_group', $this->getTables() ) ) {
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

	if( !array_search( DB_PREFIX . 'tax_rule', $this->getTables() ) ) {
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
	if( !array_search( DB_PREFIX . 'order_fraud', $this->getTables() ) ) {
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

	if( !array_search( DB_PREFIX . 'order_voucher', $this->getTables() ) ) {

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
  	if( !array_search( DB_PREFIX . 'customer_group_description', $this->getTables() ) ) {
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
                FROM  `' . DB_PREFIX . 'customer_group';
 
                $query = $this->db->query($sql);

                if( count( $query->rows ) > 0 ){
                  foreach( $query->rows as $id ){

		  $sql = '
		  INSERT INTO
		 	   `' . DB_PREFIX . 'customer_group_description` (`customer_group_id`, `language_id`, `name`, `description`)
		  VALUES
			   (' . $id['customer_group_id'] . ', 1, \'' . $id['name'] . '\', \'Group '. $id['customer_group_id'] . '\')';

		  if( !$this->simulate ) {
                     $this->db->query( $sql );
                  }
                 if( $this->showOps ) {
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		  $text .= $this->msg( sprintf( $this->lang['msg_text'],   'customer_group_description', $this->lang['msg_new_data'] ) );

                }
             }
	}
		$text .= $this->version( sprintf( $this->lang['msg_upgrade_to_version'],   '1.5.3.1', '' ) );
    return $text;
  }
  public function addUpgradeTo154() {
        $text = '';
	if( !array_search( DB_PREFIX . 'customer_online', $this->getTables() ) ) {
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

	if( !array_search( DB_PREFIX . 'category_filter', $this->getTables() ) ) {
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

	if( !array_search( DB_PREFIX . 'category_path', $this->getTables() ) ) {
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

      if( !array_search( DB_PREFIX . 'coupon_category', $this->getTables() ) ) {
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

	if( !array_search( DB_PREFIX . 'customer_ban_ip', $this->getTables() ) ) {
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

	if( !array_search( DB_PREFIX . 'customer_history', $this->getTables() ) ) {
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
       if( !array_search( DB_PREFIX . 'custom_field', $this->getTables() ) ) {
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

	if( !array_search( DB_PREFIX . 'custom_field_description', $this->getTables() ) ) {
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

	if( !array_search( DB_PREFIX . 'custom_field_customer_group', $this->getTables() ) ) {
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

	if( !array_search( DB_PREFIX . 'custom_field_value', $this->getTables() ) ) {
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

	if( !array_search( DB_PREFIX . 'custom_field_value_description', $this->getTables() ) ) {
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

	if( !array_search( DB_PREFIX . 'filter', $this->getTables() ) ) {
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

	if( !array_search( DB_PREFIX . 'filter_description', $this->getTables() ) ) {
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

	if( !array_search( DB_PREFIX . 'filter_group', $this->getTables() ) ) {
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

	if( !array_search( DB_PREFIX . 'filter_group_description', $this->getTables() ) ) {
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

	if( !array_search( DB_PREFIX . 'product_filter', $this->getTables() ) ) {
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
	if( !array_search( DB_PREFIX . 'order_recurring', $this->getTables() ) ) {
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

	if( !array_search( DB_PREFIX . 'order_recurring_transaction', $this->getTables() ) ) {
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

	if( !array_search( DB_PREFIX . 'product_recurring', $this->getTables() ) ) {
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
  public function addUpgradeTo2000() {
        $text = '';
	if( !array_search( DB_PREFIX . 'affiliate_activity', $this->getTables() ) ) {
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

	if( !array_search( DB_PREFIX . 'affiliate_login', $this->getTables() ) ) {
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

	if( !array_search( DB_PREFIX . 'api', $this->getTables() ) ){
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
	}

	if( !array_search( DB_PREFIX . 'customer_activity', $this->getTables() ) ) {
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

	if( !array_search( DB_PREFIX . 'customer_login', $this->getTables() ) ) {
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

        if( array_search( DB_PREFIX . 'custom_field_to_customer_group', $this->getTables() ) ) {
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

	if( !array_search( DB_PREFIX . 'event', $this->getTables() ) ) {
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

	if( !array_search( DB_PREFIX . 'layout_module', $this->getTables() ) ) {
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

	if( !array_search( DB_PREFIX . 'location', $this->getTables() ) ) {
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

	if( !array_search( DB_PREFIX . 'marketing', $this->getTables() ) ) {
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

	if( !array_search( DB_PREFIX . 'modification', $this->getTables() ) ) {
		$sql = '
		CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'modification` (
                 `modification_id` int(11) NOT NULL AUTO_INCREMENT,
                 `name` varchar(64) NOT NULL,
                 `code` varchar(64) NOT NULL,
                 `author` varchar(64) NOT NULL,
                 `version` varchar(32) NOT NULL,
                 `link` varchar(255) NOT NULL,
                 `xml` text NOT NULL,
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

	if( !array_search( DB_PREFIX . 'order_custom_field', $this->getTables() ) ) {
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

	if( !array_search( DB_PREFIX . 'recurring', $this->getTables() ) ) {
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

	if( !array_search( DB_PREFIX . 'recurring_description', $this->getTables() ) ) {
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

	if( !array_search( DB_PREFIX . 'upload', $this->getTables() ) ) {
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
		$text .= $this->version( sprintf( $this->lang['msg_upgrade_to_version'],   '2.0.0.1', '' ) );
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
       return str_replace( $data, '<div class="msg-version round">' . $data .'</div>', $data);
  }
}
