<?php
class ModelUpgradeModule extends Model{

  private $module;
  private $converter_modules = array();
  private $converter_serialize_modules = array();
  private $lang;
  private $simulate;
  private $showOps;
  private $migration;
  private $settings = array();

  public function getChangeModule( $data ){
        $this->simulate = ( !empty( $data['simulate'] ) ? true : false );
        $this->showOps  = ( !empty( $data['showOps'] ) ? true : false );
        $this->migration  = ( !empty( $data['modules'] ) ? true : false );
        if( !isset($data['theme'] ) ) $data['theme'] = '';
        $this->theme  = ( !empty( $data['theme'] ) ? $data['theme'] : false );
        $this->lang = $this->lmodel->get('upgrade_database');

        $text = '';
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
        $this->module = 1;
     /* No serialized modules */
     $text .= $this->getChangeModules( 'category' );
     $text .= $this->getChangeModules( 'information' );
     $text .= $this->getChangeModules( 'account' );
     $text .= $this->getChangeModules( 'affiliate' );
     /* Serialized modules */
     $text .= $this->getChangeSerializeModule( 'banner' );
     $text .= $this->getChangeSerializeModule( 'featured' );
     $text .= $this->getChangeSerializeModule( 'carousel' );
     $text .= $this->getChangeSerializeModule( 'slideshow' );
     $text .= $this->getChangeSerializeModule( 'bestseller' );
     $text .= $this->getChangeSerializeModule( 'latest' );
     $text .= $this->getChangeSerializeModule( 'special' );

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
        $module = array();
   $modules1 = false;
   $modules2 = false;
   $modules3 = false;
     if( $this->config->get( $mod . '_module' ) ||  $this->config->get( $mod . '_0_status' ) ||  $this->config->get( $mod . '_status' )) {
         
       $i = 0;
       $modules1 = ( !is_null( $this->config->get( $mod . '_module' ) ) && is_null( $this->config->get( $mod . '_0_status' ) ) ? true:false);
       
       if( !$modules1 ){

       $modules2 = ( !is_null(  $this->config->get( $mod . '_0_status' ) ) ? true:false);

       }
       if( !$modules2 ){

       $modules3 = ( !is_null(  $this->config->get( $mod . '_width' )) ||  !is_null(  $this->config->get( $mod . '_position' )) ? true:false);

       }
       if( $modules1 == true){
                              $modulesx = $this->config->get( $mod . '_module' );
                   $k = array_keys($modulesx);
                   for($i=0;$i<count($k);$i++){
                                          if(!isset($modulesx[$k[$i]]['banner_id'])){
                                                       $modulesx[$k[$i]]['banner_id'] = '';
                                          }
                                          if(!isset($modulesx[$k[$i]]['sort_order'])){
                                                       $modulesx[$k[$i]]['sort_order'] = '0';
                                          }
                                          if(!isset($modulesx[$k[$i]]['layout_id'])){
                                                       $modulesx[$k[$i]]['layout_id'] = '1';
                                          }
                                          if(!isset($modulesx[$k[$i]]['position'])){
                                                       $modulesx[$k[$i]]['position'] = 'content_bottom';
                                          }
                                          if(!isset($modulesx[$k[$i]]['limit'])){
                                                       $modulesx[$k[$i]]['limit'] = '4';
                                          }
                                          if(isset($modulesx[$k[$i]]['image_width'])){
                                                       $modulesx[$k[$i]]['width'] = $modulesx[$k[$i]]['image_width'];
                                          }
                                          if(isset($modulesx[$k[$i]]['image_height'])){
                                                       $modulesx[$k[$i]]['height'] = $modulesx[$k[$i]]['image_height'];
                                          }
                                     
                                          
                   $modules[$i] = array('width' => $modulesx[$k[$i]]['width'],
                                                   'height' => $modulesx[$k[$i]]['height'],
                                                  'banner_id' => $modulesx[$k[$i]]['banner_id'],
                                                   'limit' => $modulesx[$k[$i]]['limit'],
                                                   'sort_order' => $modulesx[$k[$i]]['sort_order'],
                                                   'layout_id' => $modulesx[$k[$i]]['layout_id'],
                                                   'status' => '1',
                                                   'position' => $modulesx[$k[$i]]['position']);
                                   
       }
     }

       if( $modules2 == true){
            if( $this->config->get( $mod . '_0_image_width') ){
                                    $width = $this->config->get( $mod . '_0_image_width');
                                    $height = $this->config->get( $mod . '_0_image_height');
            }
       
            if( $this->config->get( $mod . '_0_width') ){
                                    $width = $this->config->get( $mod . '_0_width');
                                    $height = $this->config->get( $mod . '_0_height');
        }

       $modules = array(
                               array('width' => $width,
                                  'height' => $height,
                                  'limit' => $this->config->get( $mod . '_0_limit'),
                                  'banner_id' => $this->config->get( $mod . '_0_banner_id'),
                                  'layout_id' => $this->config->get( $mod . '_0_layout_id' ),
                                  'position' => $this->config->get( $mod . '_0_position' ),
                                  'sort_order' => $this->config->get( $mod . '_0_sort_order' ),
                                  'status' => $this->config->get( $mod . '_0_status' ) 
                                  )
                         );
    }
       if( $modules3 == true){
       $position = str_replace('right', 'column_right', $this->config->get( $mod . '_position' ) );
       $position = str_replace('left', 'column_left', $position );
       $position = str_replace('home', 'content_top', $position );
       $modules = array(
                               array('width' => '200',
                                  'height' => '200',
                                  'limit' => $this->config->get( $mod . '_limit'),
                                  'banner_id' => '',
                                  'layout_id' => '1',
                                  'position' => $position ,
                                  'sort_order' => $this->config->get( $mod . '_sort_order' ),
                                  'status' => $this->config->get( $mod . '_status' ) 
                                  )
                         );
    }
          $i = 0;
          $count = count( $modules );
          $sql = '
                       SELECT * FROM
                                                  `' . DB_PREFIX . 'layout_module`
                       WHERE
                                  `code` LIKE \'' . $mod .'%\' ORDER BY `code` DESC';
  
    if( array_search( DB_PREFIX . 'layout_module' , $this->structure->tables()) ) {                                
    $query = $this->db->query( $sql );
        if( count($query->rows) >  $count){
                            $array = $modules;
                            $modules = array_merge($modules,$array);
       }
    }

          $count = count( $modules );
          foreach( $modules as $modul ){
       
    if( array_search( DB_PREFIX . 'layout' , $this->structure->tables()) ) {
         $sql = '
		SELECT * FROM
			     `' . DB_PREFIX . 'layout`
		WHERE
		             `layout_id`= \'' . $modul['layout_id'] . '\'';
		  $query = $this->db->query( $sql );
     
         $name = $query->row['name'];
       } else{
         $name = 'Home';
       }
         if( isset($modul['banner_id']) ){
                 $banner_name = $this->getBannerName($modul['banner_id']);
         }else{
         $banner_name = '';
        } 
             
       $module = array(
                      'name'   => $name . $banner_name,
                      'width'  => $modul['width'],
                      'height' => $modul['height'],
                      'status' => ( !empty( $modul['status'] )  ? true:false) );
        if( $mod == 'featured' ){
             if( $this->config->get('featured_product')){
               $product = explode(',',$this->config->get('featured_product') );
             } else {
               $product = $this->getFeaturedProducts( 4 );
             }
          $module['product']  = $product;
         }
         if( isset($modul['banner_id']) && $mod !='featured' && $mod !='bestseller'  && $mod !='latest' && $mod !='special'){
            $module['banner_id']  = $modul['banner_id'];
         } elseif($this->config->get( $mod . '_0_banner_id') ){
            $module['banner_id']  = $this->config->get( $mod . '_0_banner_id' );
       }

         if( isset($modul['limit']) && $mod !='carousel' && $mod !='slideshow' && $mod !='banner'){
           $module['limit']  = $modul['limit'];
         } elseif( $mod == 'featured' ){
             $module['limit']  = '4';
         } elseif($this->config->get( $mod . '_0_limit' ) ){
             $module['limit']  = $this->config->get( $mod . '_0_limit' );
         }
     
  if( $this->structure->hasLayout( $mod ) ){
 
        /*
         * version 2.0.0.0
         */
         $sql = '
                       SELECT * FROM
                                                  `' . DB_PREFIX . 'layout_module`
                       WHERE
                                  `code` LIKE \'' . $mod .'%\' ORDER BY `layout_module_id` ASC';
                                  
    $query = $this->db->query( $sql );
    
    $layouts = array();
   foreach($query->rows as $rows){
                          $layouts[$mod][] = $rows['layout_module_id'];
   } 
  if(isset($layouts[$mod][$i])){
             $sql = '
                       UPDATE
                             `' . DB_PREFIX . 'layout_module`
                       SET
                             `code`= \'' . $mod . '.' . $this->module . '\'
                WHERE
                      `layout_module_id` = \''. $layouts[$mod][$i] . '\'';

            if( !$this->simulate) {
				   $this->db->query( $sql );
            }
            if( $this->showOps ) {
                   $text .= '<p><pre>' . $sql .'</pre></p>';
            }

        $text .= $this->msg( sprintf( $this->lang['msg_config'], $mod . '_module',  DB_PREFIX . 'layout_module' ) );
                                     
        $i++;
  }
         $sql = '
		INSERT INTO
			`' . DB_PREFIX . 'module`
		SET
                        `module_id` = \'' . $this->module . '\',
			`name` = \'' . ucwords($mod) .  ' - '. $name . '\',
			`code` = \'' . $mod . '\',
			`setting` = \'' . serialize($module) . '\'';
            ++$this->module;if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ) {
                      $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->settingcounter;
		$text .= $this->msg( sprintf( $this->lang['msg_config'], 'slideshow_module', DB_PREFIX . 'module' ) );

        
            }
        if($this->config->get( $mod . '_status' ) ){
                               $modul['status'] =$this->config->get( $mod . '_status' );
          }
         if( !$this->structure->hasLayout( $mod )  ) {
  
               $sql = '
			INSERT INTO
				`' . DB_PREFIX . 'layout_module`
			SET
                               `layout_id` = \'' . $modul['layout_id'] . '\',
                               `code`= \'' .  $mod . '.' . $this->module . '\',
                               `position` = \'' . $modul['position'] . '\',
                               `sort_order` = \'' . $modul['sort_order'] . '\'';
            if( !$this->simulate ) {
		   $this->db->query( $sql );
            }
            if( $this->showOps ) {
                   $text .= '<p><pre>' . $sql .'</pre></p>';
            }

	$text .= $this->msg( sprintf( $this->lang['msg_config'], $mod . '_module',  DB_PREFIX . 'layout_module' ) );
             

         if( !isset($modul['layout_id']) ){
          $modul['layout_id'] = 1;
         }   

         $sql = '
		INSERT INTO
			`' . DB_PREFIX . 'module`
		SET
                        `module_id` = \'' . $this->module . '\',
			`name` = \'' . ucwords($mod) .  ' - '. $name . '\',
			`code` = \'' . $mod . '\',
			`setting` = \'' . serialize($module) . '\'';
            ++$this->module;
		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ) {
                      $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->settingcounter;
		$text .= $this->msg( sprintf( $this->lang['msg_config'], 'slideshow_module', DB_PREFIX . 'module' ) );
                 
         }

       }
     }

   return $text;
  }

  private function getChangeModules( $mod ){
     /*
      * This is modules 'category', 'account', 'affiliate' and 'information'
      */
        $text = '';
        $str = '';
        $status = 0;
         $module = array();
	if( $this->config->get( $mod . '_module' ) && !$this->structure->hasSetting( $mod . '_0_position' ) && !$this->structure->hasSetting( $mod . '_1_position' )) {
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

	if( $this->config->get( $mod . '_0_layout_id' ) ||  $this->config->get( $mod . '_1_layout_id' )) {
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
         return '-' . $result->row['name'];
        }
     }
  }
  public function msg( $data ){
       return str_replace( $data, '<div class="msg round">' . $data .'</div>', $data);
  }
}
