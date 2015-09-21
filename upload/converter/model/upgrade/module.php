<?php
class ModelUpgradeModule extends Model{

  private $module;
  private $converter_modules = array();
  private $converter_serialize_modules = array();
  private $lang;
  private $simulate;
  private $showOps;
  private $migration;
  private $upgrade;
  private $settings = array();
  private $layout_module_ids = array();
  private $module_ids = array();

  public function getChangeModule( $data ){
        $this->simulate = ( !empty( $data['simulate'] ) ? true : false );
        $this->showOps  = ( !empty( $data['showOps'] ) ? true : false );
        $this->migration  = ( !empty( $data['modules'] ) ? true : false );
        if( !isset($data['theme'] ) ) $data['theme'] = '';
        $this->theme  = ( !empty( $data['theme'] ) ? $data['theme'] : false );
        $this->lang = $this->lmodel->get('upgrade_database');
        $this->upgrade = $data['upgrade'];

        $text = '';
     if($this->upgrade !=1564){
        if($this->migration){
     	    $sql = '
     	        TRUNCATE
     	        TABLE
     	               `' . DB_PREFIX . 'module`';
     	               
					      if( !$this->simulate ) {
                     $this->db->query( $sql );
                }
                if( $this->showOps ) {
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		      $text .= $this->msg(sprintf($this->lang['msg_truncate'], DB_PREFIX . 'module','')  );
     	     $sql = '
     	        TRUNCATE
     	        TABLE
     	               `' . DB_PREFIX . 'layout_module`';
     	               
					      if( !$this->simulate ) {
                     $this->db->query( $sql );
                }
                if( $this->showOps ) {
                $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		      $text .= $this->msg(sprintf($this->lang['msg_truncate'], DB_PREFIX . 'layout_module','')  );
        }
     }
        $this->module = 1;
        $text .= $this->getChangeSerializeModule( 'latest' );
        $text .= $this->getChangeSerializeModule( 'banner' );
        $text .= $this->getChangeSerializeModule( 'carousel' );
        $text .= $this->getChangeSerializeModule( 'bestseller' );
        $text .= $this->getChangeSerializeModule( 'slideshow' );
        $text .= $this->getChangeSerializeModule( 'featured' );
        $text .= $this->getChangeSerializeModule( 'special' );
        /* No serialized modules */
        $text .= $this->getChangeModules( 'category' );
        $text .= $this->getChangeModules( 'information' );
        $text .= $this->getChangeModules( 'account' );
        $text .= $this->getChangeModules( 'affiliate' );
        /* Serialized modules */
       $addons = array('banner','featured','carousel','slideshow','bestseller','latest','special');
      
     if($this->upgrade ==1564){
        $text .= $this->getFeatured_1564();
     }
     return $text;
  }

  private function getFeaturedProducts($limit = '4') {
	$info = array();

      if( array_search( DB_PREFIX . 'product_featured', $this->structure->tables() ) ){

        $i= 0;
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_featured");

            if( count( $query->row > 0 ) ){
		foreach($query->rows as $product){
                 $info[$i] = $product['product_id'];
                 $i++;
                }
            }
      }

       return $info;
  }

  private function getChangeSerializeModule( $mod ){
     /*
      * This is modules 'bestseller', 'carousel', 'featured', 'latest', 'slideshow' and 'special'
      ******************************************************************************************/
        $text = '';
        $str = '';
        $table_layout_module = array();
        $table_module = array();
        $table_setting = array();
        $modules = array();
    $module1 = false;
    $modulex = !empty($this->structure->hasSetting( $mod . '_module' ) ? true:false);
    $module2 = !empty($this->structure->hasSetting( $mod . '_0_position') ? true:false);
    $module2b = !empty($this->structure->hasSetting( $mod . '_1_position') ? true:false);
    $module3 = !empty($this->structure->hasSetting( $mod . '_position') ? true:false);
    if($modulex && !$module3){
      $module1 = true;
    }

    if( array_search('group', $this->structure->columns('setting'))){
     $sql = "SELECT * FROM `" . DB_PREFIX . "setting` WHERE `group` = '" . $mod . "'";
    } else{
     $sql = "SELECT * FROM `" . DB_PREFIX . "setting` WHERE `code` = '" . $mod . "'";
    }
    
       $results = $this->db->query($sql);
    if(count($results->rows) > 0){
     $array = array();
        foreach($results->rows as $result){
              if(!$module1){
                    $part = explode('_', $result['key']);
                    $i = 0;
                  if(isset($part[1])){
                      if($module2 || $module2b){
                          if(isset($part[2])){
                              $part[2] = str_replace('banner','banner_id', $part[2]);
                              $part[2] = str_replace('sort','sort_order',$part[2]);
                              $part[2] = str_replace('layout','layout_id',$part[2]);
                              if(isset($part[3])){
                                $part[2] = str_replace('image','image_' . $part[3],$part[2]);
                              }

                            $modules[$part[0]] [$part[1]] [$part[2]] = $result['value'];
                          }
                      }
                      if($module3){
                        $part[1] = str_replace('sort', 'sort_order', $part[1]);
                        $modules[$part[0]][0][$part[1]] = $result['value'];
                      }
                  } 
              } else{
                if($result['serialized'] == 1){
                 $modules = unserialize($result['value']);
               } else{
                /*echo "<br>";
                echo $result['key'];
                echo "<br>";*/
               }
              }
          }
       }

       $i = 0;
      if($this->upgrade > 1564){
                     foreach($modules as $key => $value){
        if($module1){
                        $table_layout_module[$i] = array("layout_id" =>  $value['layout_id'],
                                                         "code" => $mod,
                                                         "position" => $value['position'],
                                                         "sort_order" => $value['sort_order']); 
        }
        if($module2 || $module2b){
                        $table_layout_module[$i] = array("layout_id" =>  $value[0]['layout_id'],
                                                         "code" => $mod,
                                                         "position" => $value[0]['position'],
                                                         "sort_order" => $value[0]['sort_order']); 
        }
        if($module3){ 
                        $table_layout_module[$i] = array("layout_id" =>  1,
                                                         "code" => $mod,
                                                         "position" => $this->getPosition($value[0]['position']),
                                                         "sort_order" => $value[0]['sort_order']); 
        } 

        if($module1){
            if( isset($value[0]['banner_id']) ){
                 $banner_name = $this->getBannerName($value['banner_id']);
            } else {
                 $banner_name = '';
            }
               $name = $this->addModuleName( $value, $banner_name );
        }
        if($module2 || $module2b){
            if( isset($value[0]['banner_id']) ){
                 $banner_name = $this->getBannerName($value[0]['banner_id']);
            } else {
            $banner_name = '';
            }
              $name = $this->addModuleName( $value[0], $banner_name );
        }

        if( isset($value[0]['banner_id']) ){
              if($module1){
                                                         $setting = array("name" => $name,
                                                                          "banner_id" => $value['banner_id'],
                                                                          "width" => $this->getDimension($value,'width'),
                                                                          "height" => $this->getDimension($value,'height'),
                                                                          "status" => $value['status']);
             }
              if($module2 || $module2b){
                                                         $setting = array("name" => $name,
                                                                          "banner_id" => $value[0]['banner_id'],
                                                                          "width" => $this->getDimension($value[0],'width'),
                                                                          "height" => $this->getDimension($value[0],'height'),
                                                                          "status" => $value[0]['status']);
             }
          }else{
              if($module1){
                                                         $setting = array("name" => $name,
                                                                          "width" => $this->getDimension($value,'width'),
                                                                          "height" => $this->getDimension($value,'height'),
                                                                          "status" => $value['status']);
             }
             if($module2 || $module2b){
                                                         $setting = array("name" => $name,
                                                                          "width" => $this->getDimension($value[0],'width'),
                                                                          "height" => $this->getDimension($value[0],'height'),
                                                                          "status" => $value[0]['status']);
            }
           if($module3){
                                                         $setting = array("name" => $name,
                                                                          "width" => 120,
                                                                          "height" => 120,
                                                                          "status" => $value[0]['status']);
           }
           if($mod == 'featured'){
              echo "##ok";
                  if( $this->structure->hasSetting( 'featured_product') ){
                      $products = $this->db->query("SELECT `value` FROM `" . DB_PREFIX . "setting` WHERE `key` = 'featured_product'");
                      $product = explode(',',$products->row['value']);
                  } else {
                      $product = $this->getFeaturedProducts( 4 );
                  }
                    $setting['product'] = $product;
              }
          }
                        $table_module[$i] = array("name" => $name,
                                                  "code" => $mod ,
                                                  "setting" => serialize($setting)); 

                        }
                  }
         if( $this->simulate) {
                 if(!in_array($mod,$this->layout_module_ids) && count($table_layout_module) > 0 ){
                  array_push($this->layout_module_ids,$mod);
                 }
                 $layout_module_id = count($this->layout_module_ids);
          } else {
                  $layout_module_id = $this->structure->getLayoutModuleId();
          }
                $sql = '';
          foreach($table_layout_module as $key => $layout_module){

                      $sql = "INSERT INTO `" . DB_PREFIX . "layout_module` (`layout_module_id`, `layout_id`, `code`, `position`, `sort_order`) VALUES
                       (" . $layout_module_id . ",'" . $layout_module['layout_id'] . "','" . $layout_module['code'] . "','" . $layout_module['position'] . "', '"  . $layout_module['sort_order'] . "'";
                if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ) {
                      $text .= '<p><pre>' . $sql .'</pre></p>';
                }  
        $text .= $this->msg( sprintf( $this->lang['msg_config'],  $mod,  DB_PREFIX . 'layout_module' ) );
          } 
               if( $this->simulate ) {
                 if(!in_array($mod,$this->module_ids)){
                  array_push($this->module_ids,$mod);
                 }
                 $module_id = count($this->module_ids) + 1;
                } else {
                  $module_id = $this->structure->geModuleId();
                }
                  $sql2 = '';
          foreach($table_module as $key => $my_module){

                      $sql2 = "INSERT INTO `" . DB_PREFIX . "module` (`module_id`, `name`, `code`, `setting`) VALUES
                       (" . $module_id . ",'" . $my_module['name'] . "','" . $my_module['code']. '.'. $layout_module_id . "','" . $my_module['setting'] . "'";
                if( !$this->simulate ) {
                       $this->db->query( $sql2 );
                }
                if( $this->showOps ) {
                      $text .= '<p><pre>' . $sql2 .'</pre></p>';
                }  
    $text .= $this->msg( sprintf( $this->lang['msg_config'], $mod, DB_PREFIX . 'module' ) );
          } 
     return  $text;
  }
  private function getPosition($data){
        if($data !=''){
         $data = str_replace('right','column_right',$data);
         $data = str_replace('left','column_left',$data);
         $data = str_replace('home','content_top',$data);
       } else {
          $data = 'content_top';
       }
       return $data;
  }
  private function getDimension($data,$way){
    if(isset($data[$way])){
      return $data[$way];
    }
    if(isset($data['image_'.$way])){
      return $data['image_'.$way];
    }

  }
  private function addModuleName($data,$banner_name){
    if( array_search( DB_PREFIX . 'layout' , $this->structure->tables()) ) {
        $sql = "SELECT * FROM `" . DB_PREFIX . "layout` WHERE `layout_id` = '" . $data['layout_id'] . "'";

       $query = $this->db->query( $sql );
     if($banner_name !=''){
       $name = $query->row['name'] . ' > ' . $banner_name;
     }else{
       $name = $query->row['name'];
     }

       } else{
         $name = 'Home >';
      }
      return $name;
  }

  private function getChangeModules( $mod ){
     /*
      * This is modules 'category', 'account', 'affiliate' and 'information'
      */
        $text = '';
        $str = '';
        $status = 0;
         $module = array();
	if( $this->config->get( $mod . '_module' )  && !$this->structure->hasSetting( $mod . '_0_position' ) && !$this->structure->hasSetting( $mod . '_1_position' ) ) {
        /*
         * version 1.5.1 or newer
         */

          $module = $this->config->get( $mod . '_module' );
          $status = $module[0]['status'];
          $layout_id = $module[0]['layout_id'];
          $sort_order = $module[0]['sort_order'];
          $position = $module[0]['position'];

         foreach($module as $one){
          $sql = '
			INSERT INTO
				   `' . DB_PREFIX . 'layout_module`
			SET
				   `layout_id` = \'' . $one['layout_id'] . '\',
				   `code`= \'' . $mod . '\',
				   `position` = \'' . $one['position'] . '\',
                                   `sort_order` = \'' . $one['sort_order'] . '\'';

            if( !$this->simulate ) {
		   $this->db->query( $sql );
            }
            if( $this->showOps ) {
                   $text .= '<p><pre>' . $sql .'</pre></p>';
            }
	   $text .= $this->msg( sprintf( $this->lang['msg_config'], $mod . '_module',  DB_PREFIX . 'layout_module' ) );
         }


         $sql = '
		INSERT INTO
			`' . DB_PREFIX . 'setting`
		SET
			`store_id` = \'0\',
			`code` = \'' . $mod . '\',
			`key` = \'' . $mod . '_status\',
			`value` = \'' . $status . '\',
			`serialized`= \'0\'';
		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ) {
                      $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->settingcounter;
		$text .= $this->msg( sprintf( $this->lang['msg_config'], $mod . '_status', DB_PREFIX . 'setting' ) );
  }

	if( $this->config->get( $mod . '_0_layout_id' ) ||  $this->config->get( $mod . '_1_layout_id' ) ) {
         /* version 1.5.0 - 1.5.0.5 */
          $module = $this->config->get( $mod . '_module' );
          if( $this->config->get( $mod . '_0_layout_id' )){
              $pos = strpos($module, ',' );
              $up = array( 0 =>'0');
           
          } elseif( $this->config->get( $mod . '_1_layout_id' )){
       
           $pos = strpos($module,',');

               if($pos){
                  $up = explode(',',$module);
               } else {
                  $up = array( 0 =>'1');
               }
          }

             for( $i=0;$i<count($up);$i++){
                 if( $i == 0){
                   $status = $this->config->get( $mod .'_' . $up[$i] .'_status');
                }

                if(!$this->config->get( $mod . '_' . $up[$i] . '_sort_order') ){
                 $sort_order = 1;
                }else{
                  $sort_order = $this->config->get( $mod . '_' . $up[$i] . '_sort_order');
                }

                 $sql = '
			INSERT INTO
				   `' . DB_PREFIX . 'layout_module`
			SET
				   `layout_id` = \'' . $this->config->get( $mod .'_' . $up[$i] .'_layout_id') . '\',
				   `code`= \'' . $mod . '\',
				   `position` = \'' . $this->config->get( $mod .'_' . $up[$i] .'_position') . '\',
                                   `sort_order` = \'' . $sort_order . '\'';

            if( !$this->simulate ) {
		              $this->db->query( $sql );
            }
            if( $this->showOps ) {
                   $text .= '<p><pre>' . $sql .'</pre></p>';
            }

	$text .= $this->msg( sprintf( $this->lang['msg_config'], $mod . '_module',  DB_PREFIX . 'layout_module' ) );
        }

        $sql = '
		INSERT INTO
			`' . DB_PREFIX . 'setting`
		SET
			`store_id` = \'0\',
			`code` = \'' . $mod . '\',
			`key` = \'' . $mod . '_status\',
			`value` = \'' . $status . '\',
			`serialized`= \'0\'';
		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ) {
                      $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->settingcounter;
		$text .= $this->msg( sprintf( $this->lang['msg_config'], $mod . '_status', DB_PREFIX . 'setting' ) );
          } 

	if( $this->structure->hasSetting( $mod . '_sort_order' ) &&  $this->config->get( $mod . '_position' )) {
           /* versions 1.4.7 - 1.4.9.5 */
             $status = $this->config->get( $mod .'_status');
             $position = $this->config->get( $mod .'_position');

             $position = str_replace('left','column_left',$position );
             $position = str_replace('right','column_right',$position );
             $position = str_replace('home','content_top',$position );
             $sort_order = $this->config->get( $mod . '_position' );

    $module = array(
		array(
      'module'        => $mod,
			'position'	=> $position,
			'layout_id'	=> 1,
      'sort_order'    => $sort_order
		),
		array(
      'module'        => 'category',
			'position'	=> $position,
			'layout_id'	=> 2,
      'sort_order'    => 1
		),
		array(
      'module'        => 'category',
			'position'	=> $position,
			'layout_id'	=> 3,
      'sort_order'    => 1
		),
		array(
      'module'        => 'information',
			'position'	=> $position,
			'layout_id'	=> 8,
      'sort_order'    => 1
		),
		array(
      'module'        => 'information',
			'position'	=> $position,
			'layout_id'	=> 9,
                        'sort_order'    => 1
		),
		array(
      'module'        => 'information',
			'position'	=> $position,
			'layout_id'	=> 11,
                        'sort_order'    => 1
		),
		array(
      'module'        => 'account',
			'position'	=> 'column_left',
			'layout_id'	=> 6,
      'sort_order'    => 1
		),
		array(
      'module'        => 'affiliate',
			'position'	=> 'column_left',
			'layout_id'	=> 10,
      'sort_order'    => 1
		)
              );

    foreach( $module as $k => $v ) {

	if( !$this->structure->hasLayout( $v['module'] ) ) {
                 $sql = '
			INSERT INTO
				   `' . DB_PREFIX . 'layout_module`
			SET
				   `layout_id` = \'' . $v['layout_id'] . '\',
				   `code`= \'' . $v['module'] . '\',
				   `position` = \'' . $v['position'] . '\',
                                   `sort_order` = \'' . $v['sort_order'] . '\'';

            if( !$this->simulate ) {
		   $this->db->query( $sql );
            }
            if( $this->showOps ) {
                   $text .= '<p><pre>' . $sql .'</pre></p>';
            }

	$text .= $this->msg( sprintf( $this->lang['msg_config'], $mod . '_module',  DB_PREFIX . 'layout_module' ) );
         }
   }


    $setting = array(
		array(
                        'key'        => $mod . '_status',
			'group'      => $mod,
			'value'	     => 1
		),
		array(
                        'key'        => 'account_status',
			'group'      => 'account',
			'value'	     => 1
		),
		array(
                        'key'        => 'affiliate_status',
			'group'      => 'affiliate',
			'value'	     => 1
		)
          );
    foreach( $setting as $k => $v ) {

	   if( !$this->structure->hasSetting( $v['key'] ) ) {
         $sql = '
		INSERT INTO
			`' . DB_PREFIX . 'setting`
		SET
			`store_id` = \'0\',
			`code` = \'' . $v['group'] . '\',
			`key` = \'' . $v['key'] . '\',
			`value` = \'' . $v['value'] . '\',
			`serialized`= \'0\'';
		            if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ) {
                      $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->settingcounter;
		$text .= $this->msg( sprintf( $this->lang['msg_config'], $mod . '_status', DB_PREFIX . 'setting' ) );
        }
    }
    $exten = array(
                array('mod'        => 'account'
		),
		array(
                      'mod'        => 'affiliate'
		)
          );
    foreach( $exten as $k => $v ) {

	if( !$this->structure->hasExtension( $v['mod'] ) ) {
         $sql = '
		INSERT INTO
			`' . DB_PREFIX . 'extension`
		SET
			`type` = \'module\',
			`code` = \'' . $v['mod'] . '\'';
		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ) {
                      $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->settingcounter;
		$text .= $this->msg( sprintf( $this->lang['msg_config'], $v['mod'], DB_PREFIX . 'extension' ) );
        }
    }
   } 
    return $text;
   }

   private function getConfigMail(){
     $text = '';
    if( $this->config->get('config_mail_protocol') && !$this->structure->hasSetting('config_mail') ){
      $config_mail = array();

       $configs = $this->config->get('config_mail_protocol');
       $configs2 = $this->config->get('config_smtp_hostname');
       $configs3 = $this->config->get('config_smtp_username');
       $configs4 = $this->config->get('config_smtp_password');
       $configs5 = $this->config->get('config_smtp_port');
       $configs6 = $this->config->get('config_smtp_timeout');

       $config_mail['protocol'] = ( !empty( $configs ) ) ? $configs : '';
       $config_mail['parameter'] = $this->config->get('config_mail_parameter');
       $config_mail['smtp_hostname'] = ( !empty( $configs2 ) ) ? $configs2 : '';
       $config_mail['smtp_username'] = ( !empty( $configs3 ) ) ? $configs3 : '';
       $config_mail['smtp_password'] = ( !empty( $configs4 ) ) ? $configs4 : '';
       $config_mail['smtp_port'] = ( !empty( $configs5 ) ) ? $configs5 : '';
       $config_mail['smtp_timeout'] = ( !empty( $configs6 ) ) ? $configs6 : '';
 

      $sql = "
             INSERT INTO 
                        " . DB_PREFIX . "setting
             SET
                          `code`= 'config',
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


   }
       return $text;
   }
  public function getFeatured_1564(){
        $text = '';
        $str = '';
        $status = 0;
         $module = array();
  if( $this->structure->hasSetting( 'featured_module' ) ) {
        /*
         * version 1.5.1 or newer
         */

         $sql = '
         SELECT * FROM 
                     ' . DB_PREFIX .'setting
         WHERE
                   `key` = \'featured_module\'';

         $query = $this->db->query( $sql );

         // $module = unserialize( $query->row['value'] );

          $module = $query->row['value'];


         if( !isset( $module[0]['limit'] )){
           $this->array_splice_assoc($module[0],'layout_id', 0, array( 'limit'=> 5 ) );
           $str = serialize($module);
         }
       }
       if( $str ){
         $sql = '
    UPDATE
      `' . DB_PREFIX . 'setting`
    SET
      `value` = \'' . $str . '\'
               WHERE
      `key` = \'featured_module\'';

                if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ) {
                     $text .= '<p><pre>' . $sql .'</pre></p>';
                }
    ++$settingcounter;
   $text .= $this->msg( sprintf( 'featured_module', DB_PREFIX . 'setting' ), 'config' );
        }
        return $text;
  }
  private function array_splice_assoc(&$input, $offset, $length, $replacement) {
        $replacement = (array) $replacement;
        $key_indices = array_flip(array_keys($input));
        if (isset($input[$offset]) && is_string($offset)) {
                $offset = $key_indices[$offset];
        }
        if (isset($input[$length]) && is_string($length)) {
                $length = $key_indices[$length] - $offset;
        }

        $input = array_slice($input, 0, $offset, TRUE)
                + $replacement
                + array_slice($input, $offset + $length, NULL, TRUE);
   }
  private function getBannerName($banner_id){
			  $sql = '
			 	 SELECT
				 	*
				 FROM
					`' . DB_PREFIX . 'banner`
				 WHERE
					`banner_id` = \'' . $banner_id . '\'';
      if( array_search( DB_PREFIX . 'banner', $this->structure->tables() ) ){
	$result = $this->db->query( $sql );
        if( isset($result->row['name']) ){
         return $result->row['name'];
        }
     }
  }
  public function msg( $data ){
       return str_replace( $data, '<div class="msg round">' . $data .'</div>', $data);
  }
}
