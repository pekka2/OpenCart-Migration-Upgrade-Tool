<?php
class ModelUpgradeSettings extends Model{
	/**
	 * Modules
         * Openacart versions 1.5.1 or newer
         *
	 */
  private $settincounter = 0;
  private $converter_modules = array();
  private $lang;

  public function version151orNewer( $data ){


        $this->simulate = ( !empty( $data['simulate'] ) ? true : false );
        $this->showOps  = ( !empty( $data['showOps'] ) ? true : false );

    if( array_search( 'serialized', $this->getDbColumns( 'setting' ) )) {
       $this->lang = $this->lmodel->get('upgrade_database');

        $text = $this->newSettings();

	if( !$this->hasSetting( 'config_mail' ) ) {
		$sql = '
		INSERT INTO
			`' . DB_PREFIX . 'setting`
		SET
			`store_id` = \'0\',
			`group` = \'config\',
			`key` = \'config_mail\',
			`value` = \''. $this->db->escape( 'a:7:{s:8:"protocol";s:4:"mail";s:9:"parameter";s:0:"";s:13:"smtp_hostname";s:0:"";s:13:"smtp_username";s:0:"";s:13:"smtp_password";s:0:"";s:9:"smtp_port";s:0:"";s:12:"smtp_timeout";s:0:"";}') . '\',
			`serialized` = \'1\'';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ) {
                      $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->settingcounter;
		$text .= $this->msg( sprintf( $this->lang['msg_config'], 'config_mail', '' ) );
	}

	$modules = array();

	$sql = '
	SELECT
		*
	FROM
		`' . DB_PREFIX . 'setting`
	WHERE
		`key` LIKE \'%_module%\'';

	$secure = $this->db->query( $sql );


		foreach( $secure->rows as $module ) {
			if( $module['serialized']  == 1 ) {
				$modules[] = array(
					'id'	=> $module['setting_id'],
					'name'	=> $module['key'],
					'group'	=> $module['group'],
					'value'	=> unserialize( $module['value'] )
				);
			}
		}

	$module_layouts = array();

	foreach( $modules as $mod ) {
		 if( $mod['name'] == 'featured_module' ||
                     $mod['name'] == 'bestseller_module' ||
                     $mod['name'] == 'latest_module' ||
                     $mod['name'] == 'special_module' ) {
			// Update Featured module array keys
			$count = count( $mod['value'] );
			for( $i = 0; $i < $count; ++$i ) {
				if( isset( $mod['value'][$i]['image_width'] ) ) {
					$mod['value'][$i]['width'] =  $mod['value'][$i]['image_width'];
				}else{
					$mod['value'][$i]['width'] = 90;
				}

				if( isset( $mod['value'][$i]['image_height'] ) ) { // 10
					$mod['value'][$i]['height'] =  $mod['value'][$i]['image_height'];
				}else{
					$mod['value'][$i]['height'] = 90;
				}
			}

		$sql = '
		UPDATE
			`' . DB_PREFIX . 'setting`
		SET
		      `value` = \'' . serialize( $mod['value'] ) . '\'
		WHERE
			setting_id = \'' . $mod['id'] . '\'';

               if( !$this->simulate ) {
		      $this->db->query( $sql );
	       }
                if( $this->showOps ) {
                      $text .= '<p><pre>' . $sql .'</pre></p>';
                }

			$value = $mod['value'][0];

	 if( $value['status'] == 1 ) {
				$status = str_replace( 'module', 'status', $mod['name'] );

		$sql = '
		SELECT
			*
		FROM
			`' . DB_PREFIX . 'setting`
		WHERE
			`key` = \'' . $status . '\'';

		$modulestatus = $this->db->query( $sql );

		if( count( $modulestatus->row ) == 0 ) {
			$sql = '
			INSERT INTO
				`' . DB_PREFIX . 'setting`
			SET
				`store_id` = \'0\',
				`group` = \'' . $mod['group'] . '\',
				`key` = \'' . $status . '\',
				`value` = \'1\',
				`serialized` = \'0\'';

                    if( !$this->simulate ) {
		           $this->db->query( $sql );
                    }
                if( $this->showOps ) {
                      $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->settingcounter;

		$text .= $this->msg( sprintf( $this->lang['msg_config'], $status, '' ) );
			}
		}

		$count1 = count( $mod['value'] );
	        for( $j = 0; $j < $count1; ++$j ) {
				$code = '';

			if( $mod['name'] != 'account_module' && $mod['name'] != 'category_module' && $mod['name'] !='information_module' && $mod['name'] != 'affiliate_module' ) {
					$code = '.0';
			}

				$module_layouts[] = array(
					'module'	=> $mod['group'],
					'layout'	=> $mod['value'][$j]['layout_id'],
					'position'	=> $mod['value'][$j]['position'],
					'sort_order'=> $mod['value'][$j]['sort_order'],
					'code'		=> $code
				);
		   }
	  }

		$sql = '
		SELECT
			*
		FROM
			`' . DB_PREFIX . 'layout_module`';
               if( !$this->simulate ) {

		$check = $this->db->query( $sql );

               }
                if( $this->showOps ) {
                      $text .= '<p><pre>' . $sql .'</pre></p>';
                }


       if( !isset( $check->row['code'] ) && count( $module_layouts ) > 0 ) {
			$count = count( $module_layouts );
	 for( $i = 0; $i < $count; ++$i ) {
		$sql = '
			INSERT INTO
				   `' . DB_PREFIX . 'layout_module`
			SET
				   `layout_id` = \'' . $module_layouts[$i]['layout'] . '\',
				   `code`= \'' . $module_layouts[$i]['module'] .$module_layouts[$i]['code'] . '\',
				   `position` = \'' . $module_layouts[$i]['position'] . '\',
				   `sort_order` = \'' . $module_layouts[$i]['sort_order'] . '\'';

            if( !$this->simulate ) {
		   $this->db->query( $sql );
            }
            if( $this->showOps ) {
                   $text .= '<p><pre>' . $sql .'</pre></p>';
           }
	$text .= $this->msg( sprintf( $this->lang['msg_config'],  DB_PREFIX . 'layout_module', $module_layouts[$i]['module'] ) );
	 }
       }
     } 

      $text .= $this->deleteSettings();

	$text .= $this->msg( sprintf( $this->lang['msg_new_setting'], $this->settingcounter, DB_PREFIX . 'setting', '') );

	$text .= $this->msg( $this->lang['msg_end_converter_setting'] );

     return $text;
    }
   }

   /*
    * Modules
    * Olds Opencart versions, 1.5.0.5 or parent
    *
    */

  public function version1505orParent( $data ){

        $this->simulate = ( !empty( $data['simulate'] ) ? true : false );
        $this->showOps  = ( !empty( $data['showOps'] ) ? true : false );
        $text = '';

     if( !array_search( 'serialized', $this->getDbColumns( 'setting' ) ) ) {
       $this->lang = $this->lmodel->get('upgrade_database');

	$text .= $this->msg(  $this->lang['msg_converter_setting'] );
    

         $sql = '
               ALTER TABLE
                          `' . DB_PREFIX . 'setting`
               ADD COLUMN
                          serialized tinyint(1) NOT NULL';

               if( !$this->simulate ) {
                      $this->db->query( $sql );
               }
           //  ++$altercounter;

       $text .= $this->msg( sprintf( $this->lang['msg_config'],  'setting', 'column', 'serialized' ) );   

       $text .= $this->newSettings();

            $for_mod = array('account','affiliate','banner', 'bestseller','category','featured','information','latest','slideshow');
            $for_mod2 = array('account','affiliate','category','information');
            $for_mod3 = array('banner','bestseller','featured','latest','slideshow','special');

           $settings = array();

      for($i = 0;$i<count($for_mod);$i++){
     
             $sql = '
             SELECT 
                    * 
             FROM 
                   ' . DB_PREFIX . 'setting 
             WHERE `group` = \'' . $for_mod[$i].'\'';
                                                
             $query = $this->db->query( $sql ) or die (mysql_error());

             foreach($query->rows as $result){
                                                                                    
                   $settings[0][$for_mod[$i]][$result['key']] = $result['value'];
                                                             
             }
        }

              
        for($i=0;$i<count($for_mod);$i++){  

               $this->getSettings($for_mod[$i]);
        }


        for($i=0;$i<count($for_mod2);$i++){  
                       
           if( !$this->simulate ) {
              $this->deleteSettingGroup($for_mod2[$i]);
           }
                if( $this->showOps ) {
                      $text .= '<p><pre>' . $sql .'</pre></p>';
                }
              $this->insertStr( $for_mod2[$i] );
                                              
       }
       for($i=0;$i<count($for_mod3);$i++){  
    
          if( !$this->simulate ) {
             $this->deleteSettingGroup($for_mod3[$i]);
          }
                if( $this->showOps ) {
                      $text .= '<p><pre>' . $sql .'</pre></p>';
                }
             $this->insertArr( $for_mod3[$i] ); 
                                   
        } 

    if( !$this->simulate ) {
           $this->deleteSettingGroup('manufacturer');
    }
                if( $this->showOps ) {
                      $text .= '<p><pre>' . $sql .'</pre></p>';
                }

    $config_mail = array();

    $configs = $this->db->query("SELECT * FROM ".DB_PREFIX . "setting WHERE `key` = 'config_mail_protocol'");
    $configs2 = $this->db->query("SELECT * FROM ".DB_PREFIX . "setting WHERE `key` = 'config_smtp_hostname'");
    $configs3 = $this->db->query("SELECT * FROM ".DB_PREFIX . "setting WHERE `key` = 'config_smtp_username'");
    $configs4 = $this->db->query("SELECT * FROM ".DB_PREFIX . "setting WHERE `key` = 'config_smtp_password'");
    $configs5 = $this->db->query("SELECT * FROM ".DB_PREFIX . "setting WHERE `key` = 'config_smtp_port'");
    $configs6 = $this->db->query("SELECT * FROM ".DB_PREFIX . "setting WHERE `key` = 'config_smtp_timeout'");

   $config_mail['protocol'] = ( !empty( $configs->row['value'] ) ) ? $configs->row['value'] : '';
   $config_mail['parameter'] = '';
   $config_mail['smtp_hostname'] = ( !empty( $configs2->row['value'] ) ) ? $configs2->row['value'] : '';
   $config_mail['smtp_username'] = ( !empty( $configs3->row['value'] ) ) ? $configs3->row['value'] : '';
   $config_mail['smtp_password'] = ( !empty( $configs4->row['value'] ) ) ? $configs4->row['value'] : '';
   $config_mail['smtp_port'] = ( !empty( $configs5->row['value'] ) ) ? $configs5->row['value'] : '';
   $config_mail['smtp_timeout'] = ( !empty( $configs6->row['value'] ) ) ? $configs6->row['value'] : '';

      $sql = "INSERT INTO " . DB_PREFIX . "setting SET
                          `group`=
                          `key` = 'config_mail',
                          `value` = '" . serialize($config_mail) ."',
                          `serialized` = '1'";
          
               if( !$this->simulate ) {
		      $this->db->query( $sql );
               }
                if( $this->showOps ) {
                      $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->settingcounter;
       $text .= $this->msg( sprintf( $this->lang['msg_config'], 'config_mail', '' ) );

       $text .= $this->deleteSettings();

       return $text;
      }
    }

  public function getSettings( $key ){
                                            
         if( isset( $settings[0][$key] ) && isset( $settings[0][$key][$key . '_0_position'] ) || isset($settings[0][$key][$key . '_1_position'] ) ){
                  $keys = array_keys( $settings[0][$key] );
                  $ky = array_search( $key . '_module',$keys );

                   if(count($keys) > $ky+1){        

                         $str = $keys[$ky+1];
                         array_splice($keys,$ky,1,$str); 
                                                                        
                  } else {
                                                                                              
                         array_pop( $keys );

                  }

                  for($i=0;$i<count( $keys );$i++ ){
                           if( strpos( $keys[$i],'_' ) ){
                                                                                            
                               $con{$i} = explode( '_',$keys[$i] );
                               $x = $con{$i};
                                            
                               array_shift( $x );
                               array_shift( $x );
                                      
                               $x = implode( '_',$x );
                                                                                                                  
                              $this->converter_modules[$key .'_module'][$con{$i}[1]][$x] = $settings[0][$key][$keys[$i]];  
                                                                                                            
                         }
                 }
           }                                  
  }

  public function insertStr( $key ){
        $text = '';

          if( count( $this->converter_modules ) > 0 ) {                                    
           if( isset( $this->converter_modules[$key . '_module'][1]['status'] ) ){      
                               
                        $value = $this->converter_modules[$key . '_module'][1]['status'];
                                                               
             } elseif ( isset($this->converter_modules[$key . '_module'][0]['status'] ) ){   
                                                                                    
                     $value = $this->converter_modules[$key . '_module'][0]['status'];                                             
         } 
        if( isset( $value ) ){
  
   /*
    * Insert module status to table setting
    *
    */                                                                   
                  $sql = "INSERT INTO
                            " . DB_PREFIX ."setting SET
                                      `store_id` = '0',
                                      `group`= '" . $key . "',
                                      `key` = '" . $key . "_status',
                                      `value` = '" . $value ."',
                                      `serialized` = '0'";
                                                
	if( !$this->simulate ) {
               $this->db->query( $sql );
        }
                if( $this->showOps ) {
                      $text .= '<p><pre>' . $sql .'</pre></p>';
                }
	++$this->settingcounter;
	$text .= $this->msg( sprintf( $this->lang['msg_config'], $key. $key .'_status' ) );
      }

   /*
    * Insert module layouts to table layout_module
    *
    */ 
       for( $i=min( array_keys( $this->converter_modules[$key . '_module'] ) );$i<count($this->converter_modules[$key . '_module']);$i++ ){
           $sql = "INSERT INTO 
                    " . DB_PREFIX . "layout_module SET
                      `layout_id`= '" . $this->converter_modules[$key . '_module'][$i]['layout_id'] . "',
                      `code` = '" . $key . "',
                      `position`= '" .$this->converter_modules[$key . '_module'][$i]['position'] . "',
                      `sort_order`='" .$this->converter_modules[$key . '_module'][$i]['sort_order'] . "'";
                    
	if( !$this->simulate ) {
               $this->db->query( $sql );
        }
                if( $this->showOps ) {
                      $text .= '<p><pre>' . $sql .'</pre></p>';
                }
	++$this->settingcounter;
	$text .= $this->msg( sprintf( $this->lang['msg_config'],  DB_PREFIX . 'layout_module', $key .'_module' ) );
      }

   }        
    return $text;             
  }
  public function insertArr( $key ){
        $text = '';
  if( count($this->converter_modules) > 0 ) {           
  
   if( isset($this->converter_modules[$key . '_module'] ) && count( $this->converter_modules[$key. '_module'] ) > 0 ){
   
        if(isset($this->converter_modules[$key . '_module'][1]['status'])){

            $value = $this->converter_modules[$key . ' _module'][1]['status'];

        } elseif(isset($this->converter_modules[$key . '_module'][0]['status'])){

             $value = $this->converter_modules[$key . '_module'][0]['status'];
        } 

   /*
    * Insert module status to table setting
    *
    */
        if( isset( $value ) ){
                                                                                            
         $sql = "INSERT INTO
                          " . DB_PREFIX ."setting SET
                            `store_id`   = '0',
                            `group`      = '" . $key . "',
                            `key`        = '" . $key ."_status',
                            `value`      = '" . $value ."',
                            `serialized` = '0'";
                         

	if( !$this->simulate ) {
               $this->db->query( $sql );
        }
                if( $this->showOps ) {
                      $text .= '<p><pre>' . $sql .'</pre></p>';
                }
	++$this->settingcounter;
	$text .= $this->msg( sprintf( $this->lang['msg_config'], $key .'_status' ) );                                                
        }

        $settings = array();
                      
     for( $i=min( array_keys( $this->converter_modules[$key . '_module'] ) );$i<count( $this->converter_modules[$key . '_module'] );$i++ ){


         if (!isset($this->converter_modules[$key . '_module']['product'])) { 
    
   /*
    * Insert module layouts to table layout_module
    *
    */        
                $sql = "
                        INSERT INTO 
                                   " . DB_PREFIX . "layout_module
                        SET
                                   `layout_id`= '" . $this->converter_modules[$key . '_module'][$i]['layout_id'] . "',
                                   `code` = '" . $key . ".0',
                                   `position`= '" .$this->converter_modules[$key . '_module'][$i]['position'] . "',
                                   `sort_order`='" .$this->converter_modules[$key . '_module'][$i]['sort_order'] . "'";

	if( !$this->simulate ) {
               $this->db->query( $sql );
        }
                if( $this->showOps ) {
                      $text .= '<p><pre>' . $sql .'</pre></p>';
                }
	++$this->settingcounter;
	$text .= $this->msg( sprintf( $this->lang['msg_config'], $key. '_module.0' ) );   
                                                                        
      $setting = array();
                                                                            
     if( isset( $this->converter_modules[$key . '_module'][$i]['width'] ) && isset( $this->converter_modules[$key . '_module'][$i]['height'] ) ){
                                                                                                                    
           $setting = array( "width"=> $this->converter_modules[$key . '_module'][$i]['width'],
                            "height"=> $this->converter_modules[$key . '_module'][$i]['height'] );
                                                                                                                     
      }                                                                        
     if( isset( $this->converter_modules[$key . '_module'][$i]['image_width'] ) && isset( $this->converter_modules[$key . '_module'][$i]['image_height'] ) ){
                                                                                                                    
           $setting = array( "width"=> $this->converter_modules[$key . '_module'][$i]['image_width'],
                             "height"=> $this->converter_modules[$key . '_module'][$i]['image_height'] );
                                                                                                                     
      }  
  
      if( isset( $this->converter_modules[$key . '_module'][$i]['banner_id'])){
                                                                                              
           $setting =   array_merge( $setting, array( "banner_id"=> $this->converter_modules[$key . '_module'][$i]['banner_id'] ) );
                                                                                                          
      }

      if( isset( $this->converter_modules[$key . '_module'][$i]['limit'] ) ){
                                                                                              
           $setting =   array_merge( $setting, array( "limit"=> $this->converter_modules[$key . '_module'][$i]['limit'] ) );
                                                                                                          
      }
          $settings[] = $setting;

       if(!empty($settings)){

   /*
    * Insert serialized modules to table setting
    *
    */  

           $sql = "
             INSERT INTO 
                        " . DB_PREFIX . "setting
             SET 
                        `group` = '" . $key . "',
                        `key`   = '" .$key."_module',
                        `value` = '" . serialize($settings) ."',
                        `serialized` = '1'";

	if( !$this->simulate ) {
               $this->db->query( $sql );
        }
                if( $this->showOps ) {
                      $text .= '<p><pre>' . $sql .'</pre></p>';
                }
	++$this->settingcounter;
	$text .= $this->msg( sprintf( $this->lang['msg_config'], $key. '_module' ) );                                                                              
    
                      }                                                                         
              }                                                                      
            }                                   
       }    

        $text .= $this->deleteSettings();
	$text .= $this->msg( sprintf( $this->lang['msg_config'], 'msg_end_converter_setting', '' ) );
    return $text;                                               
    }                     
  }

  public function newSettings(){
        $text ='';
	if( !$this->hasSetting( 'config_ftp_status' ) ) {

		$sql = '
		INSERT INTO
			`' . DB_PREFIX . 'setting`
		SET
			`store_id` = \'0\',
			`group` = \'config\',
			`key` = \'config_ftp_status\',
			`value`= \'0\',
			`serialized` = \'0\'';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ) {
                      $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->settingcounter;
		$text .= $this->msg( sprintf( $this->lang['msg_config'], 'config_customer_ftp_status', '' ) );
	}

	if( !$this->hasSetting( 'config_ftp_root' ) ) {
		$sql = '
		INSERT INTO
			`' . DB_PREFIX . 'setting`
		SET
			`store_id` = \'0\',
			`group` = \'config\',
			`key` = \'config_ftp_root\',
			`serialized` = \'0\'';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ) {
                      $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->settingcounter;
		$text .= $this->msg( sprintf( $this->lang['msg_config'], 'config_ftp_root', '' ) );
	}

	if( !$this->hasSetting( 'config_ftp_password' ) ) {
		$sql = '
		INSERT INTO
			`' . DB_PREFIX . 'setting`
		SET
			`store_id` = \'0\',
			`group` = \'config\',
			`key` = \'config_ftp_password\',
			`serialized` = \'0\'';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ) {
                      $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->settingcounter;
                if( $this->showOps ) {
                      $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		$text .= $this->msg( sprintf( $this->lang['msg_config'], 'config_ftp_password', '' ) );
	}

	if( !$this->hasSetting( 'config_ftp_username' ) ) {
		$sql = '
		INSERT INTO
			`' . DB_PREFIX . 'setting`
		SET
			`store_id` = \'0\',
			`group` = \'config\',
			`key` = \'config_ftp_username\',
			`serialized` = \'0\'';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ) {
                      $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->settingcounter;
		$text .= $this->msg( sprintf( $this->lang['msg_config'], 'config_ftp_username', '' ) );
	}

	if( !$this->hasSetting( 'config_ftp_port' ) ) {
		$sql = '
		INSERT INTO
			`' . DB_PREFIX . 'setting`
		SET
			`store_id` = \'0\',
			`group` = \'config\',
			`key` = \'config_ftp_port\',
			`value` = \'21\',
			`serialized` = \'0\'';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ) {
                      $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->settingcounter;
		$text .= $this->msg( sprintf( $this->lang['msg_config'], 'config_ftp_port', '' ) );
	}

	if( !$this->hasSetting( 'config_ftp_hostname' ) ) {
		$sql = '
		INSERT INTO
			`' . DB_PREFIX . 'setting`
		SET
			`store_id` = \'0\',
			`group` = \'config\',
			`key` = \'config_ftp_hostname\',
			`value`= \'opencart.opencartdemo.com\',
			`serialized`= \'0\'';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ) {
                      $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->settingcounter;
		$text .= $this->msg( sprintf( $this->lang['msg_config'], 'config_ftp_hostname', '' ) );
	}

	if( !$this->hasSetting( 'config_fraud_status_id' ) ) {
		$sql = '
		INSERT INTO
			`' . DB_PREFIX . 'setting`
		SET
			`store_id` = \'0\',
			`group` = \'config\',
			`key` = \'config_fraud_status_id\',
			`value`= \'7\',
			`serialized` = \'0\'';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ) {
                      $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->settingcounter;
		$text .= $this->msg( sprintf( $this->lang['msg_config'], 'config_fraud_status_id', '' ) );
	}

	if( !$this->hasSetting( 'config_fraud_score' ) ) {
		$sql = '
		INSERT INTO
			`' . DB_PREFIX . 'setting`
		SET
			`store_id` = \'0\',
			`group` = \'config\',
			`key` = \'config_fraud_score\',
			`serialized` = \'0\'';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ) {
                      $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->settingcounter;
		$text .= $this->msg( sprintf( $this->lang['msg_config'], 'config_fraud_score', '' ) );
	}

	if( !$this->hasSetting( 'config_fraud_key' ) ) {
		$sql = '
		INSERT INTO
			`' . DB_PREFIX . 'setting`
		SET
			`store_id` = \'0\',
			`group` = \'config\',
			`key` = \'config_fraud_key\',
			`serialized`= \'0\'';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ) {
                      $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->settingcounter;
		$text .= $this->msg( sprintf( $this->lang['msg_config'], 'config_fraud_key', '' ) );
	}

	if( !$this->hasSetting( 'config_fraud_detection' ) ) {
		$sql = '
		INSERT INTO
			`' . DB_PREFIX . 'setting`
		SET
			`store_id` = \'0\',
			`group` = \'config\',
			`key` = \'config_fraud_detection\',
			`serialized` = \'0\'';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ) {
                      $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->settingcounter;
		$text .= $this->msg( sprintf( $this->lang['msg_config'], 'config_fraud_detection', '' ) );
	}

	if( !$this->hasSetting( 'config_mail_alert' ) ) {
		$sql = '
		INSERT INTO
			`' . DB_PREFIX . 'setting`
		SET
			`store_id` = \'0\',
			`group` = \'config\',
			`key` = \'config_mail_alert\',
			`serialized` = \'0\'';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ) {
                      $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->settingcounter;
		$text .= $this->msg( sprintf( $this->lang['msg_config'], 'config_mail_alert', '' ) );
	}

	if( !$this->hasSetting( 'config_meta_title' ) ) {
		$sql = '
		SELECT
			*
		FROM
			`' . DB_PREFIX . 'setting`
		WHERE
			`key` = \'config_title\'';

		$title = $this->db->query( $sql );

		if( isset( $title->row['value'] ) ) {
			$meta_title = $title->row['value'];
		}else{
			$meta_title = 'Your Store';
		}

		$sql = '
		INSERT INTO
			`' . DB_PREFIX . 'setting`
		SET
			`store_id` = \'0\',
			`group` = \'config\',
			`key` = \'config_meta_title\',
			`value` = \'' . $meta_title . '\',
			`serialized` = \'0\'';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ) {
                      $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->settingcounter;
		$text .= $this->msg( sprintf( $this->lang['msg_config'], 'config_meta_title', '' ) );
	}

	if( !$this->hasSetting( 'config_meta_keyword' ) ) {
		$sql = '
		INSERT INTO
			`' . DB_PREFIX . 'setting`
		SET
			`store_id` = \'0\',
			`group` = \'config\',
			`key` = \'config_meta_keyword\',
			`serialized` = \'0\'';

		if( !$this->simulate ) {
                      $this->db->query( $sql );
                }
                if( $this->showOps ) {
                      $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->settingcounter;
		$text .= $this->msg( sprintf( $this->lang['msg_config'], 'config_meta_keyword', '' ) );
	}

	if( !$this->hasSetting( 'config_product_count' ) ) {
		$sql = '
		INSERT INTO
			`' . DB_PREFIX . 'setting`
		SET
			`store_id` = \'0\',
			`group` = \'config\',
			`key` = \'config_product_count\',
			`value` = \'1\',
			`serialized` = \'0\'';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ) {
                      $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->settingcounter;
		$text .= $this->msg( sprintf( $this->lang['msg_config'], 'config_product_count', '' ) );
	}

	if( !$this->hasSetting( 'config_product_limit' ) ) {
		$sql = '
		INSERT INTO
			`' . DB_PREFIX . 'setting`
		SET
			`store_id` = \'0\',
			`group` = \'config\',
			`key` = \'config_product_limit\',
			`value` = \'15\',
			`serialized` = \'0\'';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ) {
                      $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->settingcounter;
		$text .= $this->msg( sprintf( $this->lang['msg_config'], 'config_product_limit', '' ) );
	}

	if( !$this->hasSetting( 'config_product_description_length' ) ) {
		$sql = '
		INSERT INTO
			`' . DB_PREFIX . 'setting`
		SET
			`store_id` = \'0\',
			`group` = \'config\',
			`key` = \'config_product_description_length\',
			`value` = \'100\',
			`serialized` = \'0\'';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ) {
                      $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->settingcounter;
		$text .= $this->msg( sprintf( $this->lang['msg_config'], 'config_product_description_length', '' ) );
	}

	if( !$this->hasSetting( 'config_limit_admin' ) ) {
		$sql = '
		INSERT INTO
			`' . DB_PREFIX . 'setting`
		SET
			`store_id` = \'0\',
			`group` = \'config\',
			`key` = \'config_limit_admin\',
			`value` = \'20\',
			`serialized` = \'0\'';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ) {
                      $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->settingcounter;
		$text .= $this->msg( sprintf( $this->lang['msg_config'], 'config_limit_admin', '' ) );
	}

	if( !$this->hasSetting( 'config_review_mail' ) ) {
		$sql = '
		INSERT INTO
			`' . DB_PREFIX . 'setting`
		SET
			`store_id` = \'0\',
			`group` = \'config\',
			`key` = \'config_review_mail\',
			`value` = \'0\',
			`serialized` = \'0\'';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ) {
                      $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->settingcounter;
		$text .= $this->msg( sprintf( $this->lang['msg_config'], 'config_review_mail', '' ) );
	}

	if( !$this->hasSetting( 'config_voucher_min' ) ) {
		$sql = '
		INSERT INTO
			`' . DB_PREFIX . 'setting`
		SET
			`store_id` = \'0\',
			`group` = \'config\',
			`key` = \'config_voucher_min\',
			`value` = \'1\',
			`serialized`= \'0\'';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ) {
                      $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->settingcounter;
		$text .= $this->msg( sprintf( $this->lang['msg_config'], 'config_voucher_min', '' ) );
	}

	if( !$this->hasSetting( 'config_voucher_max' ) ) {
		$sql = '
		INSERT INTO
			`' . DB_PREFIX . 'setting`
		SET
			`store_id` = \'0\',
			`group` = \'config\',
			`key` = \'config_voucher_max\',
			`value` = \'1000\',
			`serialized` = \'0\'';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ) {
                      $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->settingcounter;
		$text .= $this->msg( sprintf( $this->lang['msg_config'], 'config_voucher_max', '' ) );
	}

	if( !$this->hasSetting( 'config_tax_default' ) ) {
		$sql = '
		INSERT INTO
			`' . DB_PREFIX . 'setting`
		SET
			`store_id` = \'0\',
			`group` = \'config\',
			`key` = \'config_tax_default\',
			`value` = \'shipping\',
			`serialized` = \'0\'';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ) {
                      $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->settingcounter;
		$text .= $this->msg( sprintf( $this->lang['msg_config'], 'config_tax_default', '' ) );
	}

	if( !$this->hasSetting( 'config_tax_customer' ) ) {
		$sql = '
		INSERT INTO
			`' . DB_PREFIX . 'setting`
		SET
			`store_id` = \'0\',
			`group` = \'config\',
			`key` = \'config_tax_customer\',
			`value` = \'shipping\',
			`serialized` = \'0\'';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ) {
                      $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->settingcounter;
		$text .= $this->msg( sprintf( $this->lang['msg_config'], 'config_tax_customer', '' ) );
	}

	if( !$this->hasSetting( 'config_customer_online' ) ) {
		$sql = '
		INSERT INTO
			`' . DB_PREFIX . 'setting`
		SET
			`store_id` = \'0\',
			`group` = \'config\',
			`key` = \'config_customer_online\',
			`value` = \'0\',
			`serialized` = \'0\'';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ) {
                      $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->settingcounter;
		$text .= $this->msg( sprintf( $this->lang['msg_config'], 'config_customer_online', '' ) );
	}

	if( !$this->hasSetting( 'config_customer_group_id' ) ) {
		$sql = '
		INSERT INTO
			`' . DB_PREFIX . 'setting`
		SET
			`store_id` = \'0\',
			`group` = \'config\',
			`key` = \'config_customer_group_id\',
			`value` = \'1\',
			`serialized` = \'0\'';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ) {
                      $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->settingcounter;
		$text .= $this->msg( sprintf( $this->lang['msg_config'], 'config_customer_group_id', '' ) );
	}

	if( !$this->hasSetting( 'config_customer_group_display' ) ) {
		$sql = '
		INSERT INTO
			`' . DB_PREFIX . 'setting`
		SET
			`store_id` = \'0\',
			`group` = \'config\',
			`key` = \'config_customer_group_display\',
			`value` = \'' . $this->db->escape( 'a:1:{i:0;s:1:"1";}' ) . '\',
			`serialized` = \'1\'';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ) {
                      $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->settingcounter;
		$text .= $this->msg( sprintf( $this->lang['msg_config'], 'config_customer_group_display', '' ) );
	}

	if( !$this->hasSetting( 'config_api_id' ) ) {
		$sql = '
		INSERT INTO
			`' . DB_PREFIX . 'setting`
		SET
			`store_id` = \'0\',
			`group` = \'config\',
			`key` = \'config_api_id\',
			`value` =  \'1\',
			`serialized` = \'0\'';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ) {
                      $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->settingcounter;
		$text .= $this->msg( sprintf( $this->lang['msg_config'], 'config_api_id', '' ) );
	}

	if( !$this->hasSetting( 'config_checkout_guest' ) ) {
		$sql = '
		INSERT INTO
			`' . DB_PREFIX . 'setting`
		SET
			`store_id` = \'0\',
			`group` = \'config\',
			`key` = \'config_checkout_guest\',
			`value` = \'1\',
			`serialized` = \'0\'';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ) {
                      $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->settingcounter;
		$this->msg( sprintf( $this->lang['msg_config'], 'config_checkout_guest', '' ) );
	}

	if( !$this->hasSetting( 'config_processing_status' ) ) {
		$sql = '
		INSERT INTO
			`' . DB_PREFIX . 'setting`
		SET
			`store_id` = \'0\',
			`group` = \'config\',
			`key` = \'config_processing_status\',
			`value` = \'' . $this->db->escape( 'a:1:{i:0;s:1:"2";}' ) . '\',
			`serialized` = \'1\'';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ) {
                      $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->settingcounter;
		$text .= $this->msg( sprintf( $this->lang['msg_config'], 'config_processing_status', '' ) );
	}

	if( !$this->hasSetting( 'config_complete_status' ) ) {
		$sql = '
		INSERT INTO
			`' . DB_PREFIX . 'setting`
		SET
			`store_id` = \'0\',
			`group` = \'config\',
			`key` = \'config_complete_status\',
			`value` = \'' . $this->db->escape( 'a:1:{i:0;s:1:"5";}' ) . '\',
			`serialized` = \'1\'';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ) {
                      $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->settingcounter;
		$text .= $this->msg( sprintf( $this->lang['msg_config'], 'config_complete_status', '' ) );
	}

	if( !$this->hasSetting( 'config_order_mail' ) ) {
		$sql = '
		INSERT INTO
			`' . DB_PREFIX . 'setting`
		SET
			`store_id` = \'0\',
			`group` = \'config\',
			`key` = \'config_order_mail\',
			`value` = \'0\',
			`serialized` = \'0\'';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ) {
                      $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->settingcounter;
		$this->msg( sprintf( $this->lang['msg_config'], 'config_order_mail', '' ) );
	}

	if( !$this->hasSetting( 'config_affiliate_approval' ) ) {
		$sql = '
		INSERT INTO
			`' . DB_PREFIX . 'setting`
		SET
			`store_id` = \'0\',
			`group` = \'config\',
			`key` = \'config_affiliate_approval\',
			`value` = \'0\',
			`serialized` = \'0\'';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ) {
                      $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->settingcounter;
		$text .= $this->msg( sprintf( $this->lang['msg_config'], 'config_affiliate_approval', '' ) );
	}

	if( !$this->hasSetting( 'config_affiliate_auto' ) ) {
		$sql = '
		INSERT INTO
			`' . DB_PREFIX . 'setting`
		SET
			`store_id` = \'0\',
			`group` = \'config\',
			`key` = \'config_affiliate_auto\',
			`value` = \'0\',
			`serialized` = \'0\'';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ) {
                      $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->settingcounter;
		$text .= $this->msg( sprintf( $this->lang['msg_config'], 'config_affiliate_auto', '' ) );
	}

	if( !$this->hasSetting( 'config_affiliate_commission' ) ) {
		$sql =  '
		INSERT INTO
			`' . DB_PREFIX . 'setting`
		SET
			`store_id` = \'0\',
			`group` = \'config\',
			`key` = \'config_affiliate_commission\',
			`value` = \'5\',
			`serialized` = \'0\'';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ) {
                      $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->settingcounter;
		$text .= $this->msg( sprintf( $this->lang['msg_config'], 'config_affiliate_commission', '' ) );
	}

	if( !$this->hasSetting( 'config_affiliate_id' ) ) {
		$sql = '
		INSERT INTO
			`' . DB_PREFIX . 'setting`
		SET
			`store_id` = \'0\',
			`group` = \'config\',
			`key` = \'config_affiliate_id\',
			`value` = \'4\',
			`serialized` = \'0\'';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ) {
                      $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->settingcounter;
		$text .= $this->msg( sprintf( $this->lang['msg_config'], 'config_affiliate_id', '' ) );
	}

	if( !$this->hasSetting( 'config_affiliate_mail' ) ) {
		$sql = '
		INSERT INTO
			`' . DB_PREFIX . 'setting`
		SET
			`store_id` = \'0\',
			`group` = \'config\',
			`key` = \'config_affiliate_mail\',
			`value` = \'0\',
			`serialized` = \'0\'';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ) {
                      $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->settingcounter;
		$text .= $this->msg( sprintf( $this->lang['msg_config'], 'config_affiliate_mail', '' ) );
	}

	if( !$this->hasSetting( 'config_return_id' ) ) {
		$sql = '
		INSERT INTO
			`' . DB_PREFIX . 'setting`
		SET
			`store_id` = \'0\',
			`group` = \'config\',
			`key` = \'config_return_id\',
			`value` = \'0\',
			`serialized` = \'0\'';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ) {
                      $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->settingcounter;
		$text .= $this->msg( sprintf( $this->lang['msg_config'], 'config_return_id', '' ) );
	}

	if( !$this->hasSetting( 'config_comment' ) ) {
		$sql = '
		INSERT INTO
			`' . DB_PREFIX . 'setting`
		SET
			`store_id` = \'0\',
			`group` = \'config\',
			`key` = \'config_comment\',
			`serialized` =  \'0\'';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ) {
                      $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->settingcounter;
		$text .= $this->msg( sprintf( $this->lang['msg_config'], 'config_comment', '' ) );
	}

	if( !$this->hasSetting( 'config_open' ) ) {
		$sql = '
		INSERT INTO
			`' . DB_PREFIX . 'setting`
		SET
			`store_id` = \'0\',
			`group` = \'config\',
			`key` = \'config_open\',
			`serialized` = \'0\'';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ) {
                      $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->settingcounter;
		$text .= $this->msg( sprintf( $this->lang['msg_config'], 'config_open', '' ) );
	}

	if( !$this->hasSetting( 'config_image' ) ) {
		$sql = '
		INSERT INTO
			`' . DB_PREFIX . 'setting`
		SET
			`store_id` = \'0\',
			`group` = \'config\',
			`key` = \'config_image\',
			`serialized` = \'0\'';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ) {
                      $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->settingcounter;
		$text .= $this->msg( sprintf( $this->lang['msg_config'], 'config_image', '' ) );
	}

	if( !$this->hasSetting( 'config_geocode' ) ) {
		$sql = '
		INSERT INTO
			`' . DB_PREFIX . 'setting`
		SET
			`store_id` = \'0\',
			`group` = \'config\',
			`key` = \'config_geocode\',
			`serialized` = \'0\'';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ) {
                      $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->settingcounter;
		$text .= $this->msg( sprintf( $this->lang['msg_config'], 'config_geocode', '' ) );
	}

	if( !$this->hasSetting( 'config_file_max_size' ) ) {
		$sql = '
		INSERT INTO
			`' . DB_PREFIX . 'setting`
		SET
			`store_id` = \'0\',
			`group` = \'config\',
			`key` = \'config_file_max_size\',
			`value` = \'300000\',
			`serialized` = \'0\'';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                 }
                if( $this->showOps ) {
                      $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->settingcounter;
		$text .= $this->msg( sprintf( $this->lang['msg_config'], 'config_file_max_size', '' ) );
	}

	if( !$this->hasSetting( 'config_customer_group_display' ) ) {
		$sql = '
		INSERT INTO
			`' . DB_PREFIX . 'setting`
		SET
			`store_id` = \'0\',
			`group` = \'config\',
			`key` = \'config_customer_group_display\',
			`value`= \'' . $this->db->escape( 'a:1:{i:0;s:1:"1";}' ) . '\',
			`serialized`= \'1\'';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ) {
                      $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->settingcounter;
		$text .= $this->msg( sprintf( $this->lang['msg_config'], 'config_customer_group_display', '' ) );
	}

	if( !$this->hasSetting( 'config_file_extension_allowed' ) ) {
		$sql = '
		INSERT INTO
			`' . DB_PREFIX . 'setting`
		SET
			`store_id` = \'0\',
			`group` = \'config\',
			`key` = \'config_file_extension_allowed\',
			`value` = \'' . $this->db->escape( "txt\r\npng\r\njpe\r\njpeg\r\njpg\r\ngif\r\nbmp\r\nico\r\ntiff\r\ntif\r\nsvg\r\nsvgz\r\nzip\r\nrar\r\nmsi\r\ncab\r\nmp3\r\nqt\r\nmov\r\npdf\r\npsd\r\nai\r\neps\r\nps\r\ndoc\r\nrtf\r\nxls\r\nppt\r\nodt\r\nods" ) . '\',
			`serialized` = \'0\'';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ) {
                      $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->settingcounter;
		$text .= $this->msg( sprintf( $this->lang['msg_config'], 'config_file_extension_allowed', '' ) );
	}

	if( !$this->hasSetting( 'config_file_mime_allowed' ) ) {
		$sql = '
		INSERT INTO
			`' . DB_PREFIX . 'setting`
		SET
			`store_id` = \'0\',
			`group` = \'config\',
			`key` = \'config_file_mime_allowed\',
			`value` = \'' . $this->db->escape( 'text/plain\r\nimage/png\r\nimage/jpeg\r\nimage/jpeg\r\nimage/jpeg\r\nimage/gif\r\nimage/bmp\r\nimage/vnd.microsoft.icon\r\nimage/tiff\r\nimage/tiff\r\nimage/svg+xml\r\nimage/svg+xml\r\napplication/zip\r\napplication/x-rar-compressed\r\napplication/x-msdownload\r\napplication/vnd.ms-cab-compressed\r\naudio/mpeg\r\nvideo/quicktime\r\nvideo/quicktime\r\napplication/pdf\r\nimage/vnd.adobe.photoshop\r\napplication/postscript\r\napplication/postscript\r\napplication/postscript\r\napplication/msword\r\napplication/rtf\r\napplication/vnd.ms-excel\r\napplication/vnd.ms-powerpoint\r\napplication/vnd.oasis.opendocument.text\r\napplication/vnd.oasis.opendocument.spreadsheet' ) . '\',
			`serialized` = \'0\'';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ) {
                      $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->settingcounter;
		$text .= $this->msg( sprintf( $this->lang['msg_config'], 'config_file_mime_allowed', '' ) );
	}

	if( !$this->hasSetting( 'config_password' ) ) {
		$sql = '
		INSERT INTO
			`' . DB_PREFIX . 'setting`
		SET
			`store_id` = \'0\',
			`group` = \'config\',
			`key` = \'config_password\',
			`value`= \'1\',
			`serialized` = \'0\'';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ) {
                      $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->settingcounter;
		$text .= $this->msg( sprintf( $this->lang['msg_config'], 'config_password', '' ) );
	}

	if( !$this->hasSetting( 'config_robots' ) ) {
        $sql = '
		INSERT INTO
			`' . DB_PREFIX . 'setting`
		SET
			`store_id` = \'0\',
			`group` = \'config\',
			`key` =  \'config_robots\',
			`value` = \'' . $this->db->escape( "abot\r\ndbot\r\nebot\r\nhbot\r\nkbot\r\nlbot\r\nmbot\r\nnbot\r\nobot\r\npbot\r\nrbot\r\nsbot\r\ntbot\r\nvbot\r\nybot\r\nzbot\r\nbot.\r\nbot/\r\n_bot\r\n.bot\r\n/bot\r\n-bot\r\n:bot\r\n(bot\r\ncrawl\r\nslurp\r\nspider\r\nseek\r\naccoona\r\nacoon\r\nadressendeutschland\r\nah-ha.com\r\nahoy\r\naltavista\r\nananzi\r\nanthill\r\nappie\r\narachnophilia\r\narale\r\naraneo\r\naranha\r\narchitext\r\naretha\r\narks\r\nasterias\r\natlocal\r\natn\r\natomz\r\naugurfind\r\nbackrub\r\nbannana_bot\r\nbaypup\r\nbdfetch\r\nbig brother\r\nbiglotron\r\nbjaaland\r\nblackwidow\r\nblaiz\r\nblog\r\nblo.\r\nbloodhound\r\nboitho\r\nbooch\r\nbradley\r\nbutterfly\r\ncalif\r\ncassandra\r\nccubee\r\ncfetch\r\ncharlotte\r\nchurl\r\ncienciaficcion\r\ncmc\r\ncollective\r\ncomagent\r\ncombine\r\ncomputingsite\r\ncsci\r\ncurl\r\ncusco\r\ndaumoa\r\ndeepindex\r\ndelorie\r\ndepspid\r\ndeweb\r\ndie blinde kuh\r\ndigger\r\nditto\r\ndmoz\r\ndocomo\r\ndownload express\r\ndtaagent\r\ndwcp\r\nebiness\r\nebingbong\r\ne-collector\r\nejupiter\r\nemacs-w3 search engine\r\nesther\r\nevliya celebi\r\nezresult\r\nfalcon\r\nfelix ide\r\nferret\r\nfetchrover\r\nfido\r\nfindlinks\r\nfireball\r\nfish search\r\nfouineur\r\nfunnelweb\r\ngazz\r\ngcreep\r\ngenieknows\r\ngetterroboplus\r\ngeturl\r\nglx\r\ngoforit\r\ngolem\r\ngrabber\r\ngrapnel\r\ngralon\r\ngriffon\r\ngromit\r\ngrub\r\ngulliver\r\nhamahakki\r\nharvest\r\nhavindex\r\nhelix\r\nheritrix\r\nhku www octopus\r\nhomerweb\r\nhtdig\r\nhtml index\r\nhtml_analyzer\r\nhtmlgobble\r\nhubater\r\nhyper-decontextualizer\r\nia_archiver\r\nibm_planetwide\r\nichiro\r\niconsurf\r\niltrovatore\r\nimage.kapsi.net\r\nimagelock\r\nincywincy\r\nindexer\r\ninfobee\r\ninformant\r\ningrid\r\ninktomisearch.com\r\ninspector web\r\nintelliagent\r\ninternet shinchakubin\r\nip3000\r\niron33\r\nisraeli-search\r\nivia\r\njack\r\njakarta\r\njavabee\r\njetbot\r\njumpstation\r\nkatipo\r\nkdd-explorer\r\nkilroy\r\nknowledge\r\nkototoi\r\nkretrieve\r\nlabelgrabber\r\nlachesis\r\nlarbin\r\nlegs\r\nlibwww\r\nlinkalarm\r\nlink validator\r\nlinkscan\r\nlockon\r\nlwp\r\nlycos\r\nmagpie\r\nmantraagent\r\nmapoftheinternet\r\nmarvin/\r\nmattie\r\nmediafox\r\nmediapartners\r\nmercator\r\nmerzscope\r\nmicrosoft url control\r\nminirank\r\nmiva\r\nmj12\r\nmnogosearch\r\nmoget\r\nmonster\r\nmoose\r\nmotor\r\nmultitext\r\nmuncher\r\nmuscatferret\r\nmwd.search\r\nmyweb\r\nnajdi\r\nnameprotect\r\nnationaldirectory\r\nnazilla\r\nncsa beta\r\nnec-meshexplorer\r\nnederland.zoek\r\nnetcarta webmap engine\r\nnetmechanic\r\nnetresearchserver\r\nnetscoop\r\nnewscan-online\r\nnhse\r\nnokia6682/\r\nnomad\r\nnoyona\r\nnutch\r\nnzexplorer\r\nobjectssearch\r\noccam\r\nomni\r\nopen text\r\nopenfind\r\nopenintelligencedata\r\norb search\r\nosis-project\r\npack rat\r\npageboy\r\npagebull\r\npage_verifier\r\npanscient\r\nparasite\r\npartnersite\r\npatric\r\npear.\r\npegasus\r\nperegrinator\r\npgp key agent\r\nphantom\r\nphpdig\r\npicosearch\r\npiltdownman\r\npimptrain\r\npinpoint\r\npioneer\r\npiranha\r\nplumtreewebaccessor\r\npogodak\r\npoirot\r\npompos\r\npoppelsdorf\r\npoppi\r\npopular iconoclast\r\npsycheclone\r\npublisher\r\npython\r\nrambler\r\nraven search\r\nroach\r\nroad runner\r\nroadhouse\r\nrobbie\r\nrobofox\r\nrobozilla\r\nrules\r\nsalty\r\nsbider\r\nscooter\r\nscoutjet\r\nscrubby\r\nsearch.\r\nsearchprocess\r\nsemanticdiscovery\r\nsenrigan\r\nsg-scout\r\nshai''hulud\r\nshark\r\nshopwiki\r\nsidewinder\r\nsift\r\nsilk\r\nsimmany\r\nsite searcher\r\nsite valet\r\nsitetech-rover\r\nskymob.com\r\nsleek\r\nsmartwit\r\nsna-\r\nsnappy\r\nsnooper\r\nsohu\r\nspeedfind\r\nsphere\r\nsphider\r\nspinner\r\nspyder\r\nsteeler/\r\nsuke\r\nsuntek\r\nsupersnooper\r\nsurfnomore\r\nsven\r\nsygol\r\nszukacz\r\ntach black widow\r\ntarantula\r\ntempleton\r\n/teoma\r\nt-h-u-n-d-e-r-s-t-o-n-e\r\ntheophrastus\r\ntitan\r\ntitin\r\ntkwww\r\ntoutatis\r\nt-rex\r\ntutorgig\r\ntwiceler\r\ntwisted\r\nucsd\r\nudmsearch\r\nurl check\r\nupdated\r\nvagabondo\r\nvalkyrie\r\nverticrawl\r\nvictoria\r\nvision-search\r\nvolcano\r\nvoyager/\r\nvoyager-hc\r\nw3c_validator\r\nw3m2\r\nw3mir\r\nwalker\r\nwallpaper\r\nwanderer\r\nwauuu\r\nwavefire\r\nweb core\r\nweb hopper\r\nweb wombat\r\nwebbandit\r\nwebcatcher\r\nwebcopy\r\nwebfoot\r\nweblayers\r\nweblinker\r\nweblog monitor\r\nwebmirror\r\nwebmonkey\r\nwebquest\r\nwebreaper\r\nwebsitepulse\r\nwebsnarf\r\nwebstolperer\r\nwebvac\r\nwebwalk\r\nwebwatch\r\nwebwombat\r\nwebzinger\r\nwhizbang\r\nwhowhere\r\nwild ferret\r\nworldlight\r\nwwwc\r\nwwwster\r\nxenu\r\nxget\r\nxift\r\nxirq\r\nyandex\r\nyanga\r\nyeti\r\nyodao\r\nzao\r\nzippp\r\nzyborg" ) . '\',
			`serialized` = \'0\'';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ) {
                      $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->settingcounter;
		$this->msg( sprintf( $this->lang['msg_config'], 'config_robots', '' ) );
	}

	if( !$this->hasSetting( 'config_secure' ) ) {
		$sql = '
		INSERT INTO
			`' . DB_PREFIX . 'setting`
		SET
			`store_id` = \'0\',
			`group` = \'config\',
			`key` = \'config_secure\',
			`value` = \'0\',
			`serialized` = \'0\'';

		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ) {
                      $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->settingcounter;
		$text .= $this->msg( sprintf( $this->lang['msg_config'], 'config_secure', '' ) );
	}

    return $text;
  }

	/**
	 * delete setting values
	 */
  public function deleteSettings(){
	$settingdelete = 0;
        $text = '';
	if( $this->hasSetting( 'account_module' ) ) {
		$sql = '
		DELETE FROM
			`' . DB_PREFIX . 'setting`
		WHERE
			`key` = \'account_module\'';

		if( !$this->simulate ) {
		      $this->db->query( $sql );
                }
                if( $this->showOps ) {
                      $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$settingdelete;
		$text .= $this->msg( sprintf( $this->lang['msg_config_delete'], 'account_module',  'setting' ) );
	}

	if( $this->hasSetting( 'affiliate_module' ) ) {
		$sql = '
		DELETE FROM
			`' . DB_PREFIX . 'setting`
		WHERE
			`key` = \'affiliate_module\'';

		if( !$this->simulate ) {
		      $this->db->query( $sql );
                }
                if( $this->showOps ) {
                      $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$settingdelete;
		$text .= $this->msg( sprintf( $this->lang['msg_config_delete'], 'affiliate_module',  'setting' ) );
	}

	if( $this->hasSetting( 'category_module' ) ) {
		$sql = '
		DELETE FROM
			`' . DB_PREFIX . 'setting`
		WHERE
			`key` = \'category_module\'';

		if( !$this->simulate ) {
		       $this->db->query( $sql );
                }
                if( $this->showOps ) {
                      $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$settingdelete;
		$text .= $this->msg( sprintf( $this->lang['msg_config_delete'], 'category_module',  'setting' ) );
	}

	if( $this->hasSetting( 'information_module' ) ) {
		$sql = '
		DELETE FROM
			`' . DB_PREFIX . 'setting`
		WHERE
			`key` = \'information_module\'';

		if( !$this->simulate ) {
		       $this->db->query( $sql );
                }
                if( $this->showOps ) {
                      $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$settingdelete;
		$text .= $this->msg( sprintf( $this->lang['msg_config_delete'], 'information_module',  'setting' ) );
	}


       return $text;
     
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

  public function msg( $data ){
       return str_replace( $data, '<div class="msg round">' . $data .'</div>', $data);
  }

  public function getDbColumns( $table ) {
	if( $data =  $this->cache->get( $table ) ) {
		return $data;
	}else{
		global $link;

        if( array_search( DB_PREFIX . $table, $this->getTables() ) || $table == 'address'){
                $colums = $this->db->query("SHOW COLUMNS FROM " . DB_PREFIX . $table . " FROM " . DB_DATABASE);

		$ret		= array();

               foreach( $colums->rows as $field){
                 $ret[] = $field['Field'];
               }
          return $ret;	
         }
    }
  }

  public function getTables() {
       $query = $this->db->query("SHOW TABLES FROM " . DB_DATABASE);

        $table_list = array();
        foreach($query->rows as $table){
                      $table_list[] = $table['Tables_in_'. DB_DATABASE];
          }
        return $table_list;
   }

   public function deleteSettingGroup($group) {
	$sql = '
	DELETE FROM
		`' . DB_PREFIX . 'setting`
	WHERE
		`group` = \'' . $group . '\'';

	$this->db->query( $sql );

  }
}
