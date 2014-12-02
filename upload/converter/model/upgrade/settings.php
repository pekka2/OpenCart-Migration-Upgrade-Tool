<?php
class ModelUpgradeSettings extends Model{
	/**
	 * Modules
         * Openacart versions 1.4.7 or newer
         *
	 */
  private $settincounter = 0;
  private $module;
  private $converter_modules = array();
  private $converter_serialize_modules = array();
  private $lang;
  private $simulate;
  private $showOps;
  private $settings = array();

  public function getChangeModule( $data ){
        $this->simulate = ( !empty( $data['simulate'] ) ? true : false );
        $this->showOps  = ( !empty( $data['showOps'] ) ? true : false );
        $this->lang = $this->lmodel->get('upgrade_database');

        $text = '';
        $this->module = 1;
 // if( !$this->hasSetting( 'config_complete_status' ) ){
     /* No serialized modules */
     $text .= $this->getChangeModules( 'category' );
     $text .= $this->getChangeModules( 'information' );
     $text .= $this->getChangeModules( 'account' );
     $text .= $this->getChangeModules( 'affiliate' );
     /* Serialized modules */
     $text .= $this->getChangeSerializeModule( 'bestseller' );
     $text .= $this->getChangeSerializeModule( 'latest' );
     $text .= $this->getChangeSerializeModule( 'special' );
     $text .= $this->getFeatured();
     $text .= $this->getCarousel();
     $text .= $this->getSlideshow();
     /* new config settings */
     $text .= $this->getConfigMail();
     $text .= $this->newSettings();
     /* delete old settings */
     $this->deleteSettingGroup( 'manufacturer' );
 // }
     return $text;
  }

  public function getFeatured(){
        $text = '';
        $str = '';
        $status = 0;
         $module = array();

	if( $this->config->get( 'featured_status' ) && $this->hasLayout( 'featured' ) ) {
        /*
         * version 2.0.0.0 or newer
         */
         $product = explode(',',$this->config->get('featured_product') );

          $mmodule = $this->config->get( 'featured_module' );

         $sql = '
			SELECT * FROM
				   `' . DB_PREFIX . 'layout_module`
			WHERE
				  `code` LIKE \'featured%\'';
  
	  $query = $this->db->query( $sql );

          $layout_id = $query->row['layout_id'];
          $layout_module_id = $query->row['layout_module_id'];
          

            $code = $query->row['code'];
          $part = explode('.', $code);
         if( isset($part[1]) ){
             $part[1] = $this->module;
             $code = implode('.',$part);
         }

         $sql = '
			SELECT * FROM
				   `' . DB_PREFIX . 'layout`
			WHERE
				   `layout_id`= \'' . $query->row['layout_id'] . '\'';
  
		  $query = $this->db->query( $sql );

          $name = $query->row['name'];
          $module['name'] = 'Featured - ' . $name;
          $module['width'] = $mmodule[0]['width'];
          $module['height'] = $mmodule[0]['height'];
          $module['limit'] = $mmodule[0]['limit'];
          $module['product'] = $product;
          $module['status'] = $this->config->get('featured_status');

         $str = serialize($module);
        }
	if( $this->hasSetting( 'featured_product' ) && !$this->hasSetting( 'featured_0_position' ) && !$this->hasLayout( 'featured' )) {
        /*
         * version 1.5.1 or newer
         */
         $product = explode(',',$this->config->get('featured_product') );

          $mmodule = $this->config->get( 'featured_module' );
          $layout_id = $mmodule[0]['layout_id'];
          $sort_order = $mmodule[0]['sort_order'];
          $position = $mmodule[0]['position'];
          
         if( !isset( $module[0]['limit'] )){
          $limit = 4;
          } else {
           $limit = $module[0]['limit'];
          }

         $sql = '
			SELECT * FROM
				   `' . DB_PREFIX . 'layout`
			WHERE
				   `layout_id`= \'' . $layout_id . '\'';
  
		  $query = $this->db->query( $sql );

            $name = $query->row['name'];
          $module['name'] = 'Featured - ' . $name;
          $module['width'] = $mmodule[0]['image_width'];
          $module['height'] = $mmodule[0]['image_height'];
          $module['limit'] = $limit;
          $module['product'] = $product;
          $module['status'] = $mmodule[0]['status'];
         $str = serialize($module);
        }
	if( $this->hasSetting( 'featured_0_status' ) && $this->hasSetting( 'featured_0_position' )) {
        /*
         * version 1.5.0 - 1.5.0.5
         */

         $product = explode(',',$this->config->get('featured_product') );

          $layout_id = $this->config->get( 'featured_0_layout_id' );
          $sort_order = $this->config->get( 'featured_0_sort_order' );
          $position = $this->config->get( 'featured_0_position' );
          
         $name = 'Home';
     

         $sql = '
			SELECT * FROM
				   `' . DB_PREFIX . 'layout`
			WHERE
				   `layout_id`= \'' . $layout_id . '\'';
  
		  $query = $this->db->query( $sql );
         if(isset($query->row['name'])){
            $name = $query->row['name'];
          }
           
          $module['name'] = 'Featured - ' . $name;
          $module['product'] = $product;
          $module['limit']   = count( $product );
          $module['width']   = $this->config->get( 'featured_0_image_width' );
          $module['height']  = $this->config->get( 'featured_0_image_height' );
          $module['status'] = $this->config->get( 'featured_0_status' );

         $str = serialize($module);
        }
 	
	if( $this->hasSetting( 'featured_status' ) && $this->hasSetting( 'featured_position' )) {
        /*
         * version 1.4.7 - 1.4.9.5
         */

          $product = $this->getFeaturedProducts( 4 );

          $name = 'Home';
          $module['name'] = 'Featured - ' . $name;
          $module['product'] = $product;
          $module['limit']   = 4;
          $module['width']   = 200;
          $module['height']  = 200;
          $module['status'] = $this->config->get( 'featured_status' );
          $layout_id = 1;
          $sort_order = $this->config->get( 'featured_sort_order' );
          $position = 'content_bottom';
      
         $str = serialize($module);

        }
          if( $str ){

                  $this->deleteSettingGroup( 'featured' );

              if( !$this->hasLayout( 'featured' ) ) {
                $sql = '
			INSERT INTO
				   `' . DB_PREFIX . 'layout_module`
			SET
				   `layout_id` = \'' . $layout_id . '\',
				   `code`= \'featured.' . $this->module . '\',
				   `position` = \'' . $position . '\',
				   `sort_order` = \'' . $sort_order . '\'';

                 } else {
                $sql = '
			UPDATE
				   `' . DB_PREFIX . 'layout_module`
			SET
				   `code`= \'' . $code . '\'
                        WHERE
                                  `layout_module_id` = \''. $layout_module_id . '\'';

               }
            if( !$this->simulate ){
		   $this->db->query( $sql );
           }
            if( $this->showOps ) {
                   $text .= '<p><pre>' . $sql .'</pre></p>';
           }
	$text .= $this->msg( sprintf( $this->lang['msg_config'], 'featured_module',  DB_PREFIX . 'layout_module' ) );

         $sql = '
		INSERT INTO
			`' . DB_PREFIX . 'module`
		SET
                        `module_id` = \'' . $this->module . '\',
			`name` = \'' . $name . '\',
			`code` = \'featured\',
			`setting` = \'' . $str . '\'';
            ++$this->module;
		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ) {
                      $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->settingcounter;
		$text .= $this->msg( sprintf( $this->lang['msg_config'], 'featured_module', DB_PREFIX . 'module' ) );
         }
   return $text;
  }

  private function getFeaturedProducts($limit = '4') {
	$info = array();

      if( array_search( DB_PREFIX . 'product_featured', $this->getTables() ) ){

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
  public function getCarousel(){
        $text = '';
        $str = '';
        $status = 0;
         $module = array();
	if( $this->config->get( 'carousel_module' )  && $this->hasLayout( 'carousel' )) {
        /*
         * version 2.0.0.0
         */

          $mmodule = $this->config->get( 'carousel_module' );

         $sql = '
			SELECT * FROM
				   `' . DB_PREFIX . 'layout_module`
			WHERE
				  `code` LIKE \'carousel%\'';
  
	  $query = $this->db->query( $sql );

          $layout_module_id = $query->row['layout_module_id'];
          

            $code = $query->row['code'];

          $part = explode('.', $code);

         if( isset($part[1]) ){
             $part[1] = $this->module;
             $code = implode('.',$part);
         }

         $sql = '
			SELECT * FROM
				   `' . DB_PREFIX . 'layout`
			WHERE
				   `layout_id`= \'' . $query->row['layout_id'] . '\'';
  
		  $query = $this->db->query( $sql );

            $name = $query->row['name'];

          $module['name'] = 'Carousel - ' . $name;
          $module['banner_id'] = $mmodule[0]['banner_id'];
          $module['width'] = $mmodule[0]['width'];
          $module['height'] = $mmodule[0]['height'];
          $module['status'] = $this->config->get('carousel_status');
         $str = serialize($module);
        }
	if( $this->config->get( 'carousel_module' )  && !$this->hasLayout( 'carousel' )) {
        /*
         * version 1.5.1 or newer
         */

          $mmodule = $this->config->get( 'carousel_module' );

          $layout_id = $mmodule[0]['layout_id'];
          $sort_order = $mmodule[0]['sort_order'];
          $position = $mmodule[0]['position'];
          

         $sql = '
			SELECT * FROM
				   `' . DB_PREFIX . 'layout`
			WHERE
				   `layout_id`= \'' . $layout_id . '\'';
  
		  $query = $this->db->query( $sql );

            $name = $query->row['name'];

          $module['name'] = 'Carousel - ' . $name;
          $module['banner_id'] = $mmodule[0]['banner_id'];
          $module['width'] = $mmodule[0]['width'];
          $module['height'] = $mmodule[0]['height'];
          $module['status'] = $mmodule[0]['status'];
         $str = serialize($module);
        }

          if( $str ){
		if( !$this->simulate ) {
                  $this->deleteSettingGroup( 'carousel' );
                }

              if( !$this->hasLayout( 'carousel' ) ) {
               $sql = '
			INSERT INTO
				   `' . DB_PREFIX . 'layout_module`
			SET
				   `layout_id` = \'' . $layout_id . '\',
				   `code`= \'carousel.' . $this->module . '\',
				   `position` = \'' . $position . '\',
				   `sort_order` = \'' . $sort_order . '\'';

                 } else {
                $sql = '
			UPDATE
				   `' . DB_PREFIX . 'layout_module`
			SET
				   `code`= \'' . $code . '\'
                        WHERE
                                  `layout_module_id` = \''. $layout_module_id . '\'';

               }
            if( !$this->simulate ) {
		   $this->db->query( $sql );
            }
            if( $this->showOps ) {
                   $text .= '<p><pre>' . $sql .'</pre></p>';
            }

	$text .= $this->msg( sprintf( $this->lang['msg_config'], 'carousel_module',  DB_PREFIX . 'layout_module' ) );
     

         $sql = '
		INSERT INTO
			`' . DB_PREFIX . 'module`
		SET
                        `module_id` = \'' . $this->module . '\',
			`name` = \'' . $name . '\',
			`code` = \'carousel\',
			`setting` = \'' . $str . '\'';
            ++$this->module;
		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ) {
                      $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->settingcounter;
		$text .= $this->msg( sprintf( $this->lang['msg_config'], 'carousel_module', DB_PREFIX . 'module' ) );
  }
   return $text;
  }

  public function getSlideshow(){

        $text = '';
        $str = '';
        $status = 0;
         $module = array();
	if( $this->hasSetting( 'slideshow_module' )  && $this->hasLayout( 'slideshow' )) {
        /*
         * version 2.0.0.0
         */

          $mmodule = $this->config->get( 'slideshow_module' );

         $sql = '
			SELECT * FROM
				   `' . DB_PREFIX . 'layout_module`
			WHERE
				  `code` LIKE \'slideshow%\'';
  
	  $query = $this->db->query( $sql );

          $layout_module_id = $query->row['layout_module_id'];
          

            $code = $query->row['code'];

          $part = explode('.', $code);

         if( isset($part[1]) ){
             $part[1] = $this->module;
             $code = implode('.',$part);
         }
          
         $sql = '
			SELECT * FROM
				   `' . DB_PREFIX . 'layout`
			WHERE
				   `layout_id`= \'' . $query->row['layout_id'] . '\'';
  
		  $query = $this->db->query( $sql );

          $name = $query->row['name'];

          $module['name'] = 'Slideshow - ' . $name;
          $module['banner_id'] = $mmodule[0]['banner_id'];
          $module['width'] = $mmodule[0]['width'];
          $module['height'] = $mmodule[0]['height'];
          $module['status'] = $this->config->get('slideshow_status');

         $str = serialize($module);
        }

	if( $this->hasSetting( 'slideshow_module' ) && !$this->hasSetting( 'slideshow_0_position' ) && !$this->hasLayout( 'slideshow' )) {
        /*
         * version 1.5.1 or newer
         */

          $mmodule = $this->config->get( 'slideshow_module' );
          $layout_id = $mmodule[0]['layout_id'];
          $sort_order = $mmodule[0]['sort_order'];
          $position = $mmodule[0]['position'];
          
         $sql = '
			SELECT * FROM
				   `' . DB_PREFIX . 'layout`
			WHERE
				   `layout_id`= \'' . $layout_id . '\'';
  
		  $query = $this->db->query( $sql );

          $name = $query->row['name'];

          $module['name'] = 'Slideshow - ' . $name;
          $module['banner_id'] = $mmodule[0]['banner_id'];
          $module['width'] = $mmodule[0]['width'];
          $module['height'] = $mmodule[0]['height'];
          $module['status'] = $mmodule[0]['status'];

         $str = serialize($module);
        }

	if( $this->hasSetting( 'slideshow_0_width' ) && $this->hasSetting( 'slideshow_0_position' )) {
         /* version 1.5.0x */
          $layout_id = $this->config->get( 'slideshow_0_layout_id' );
          $sort_order = $this->config->get( 'slideshow_0_sort_order' );
          $position = $this->config->get( 'slideshow_0_position' );
          

         $sql = '
			SELECT * FROM
				   `' . DB_PREFIX . 'layout`
			WHERE
				   `layout_id`= \'' . $layout_id . '\'';
  
		  $query = $this->db->query( $sql );

          $name = $query->row['name'];
          $module['name'] = 'Slideshow - ' . $name;
          $module['banner_id']   = $this->config->get( 'slideshow_0_banner_id' );
          $module['width']   = $this->config->get( 'slideshow_0_width' );
          $module['height']  = $this->config->get( 'slideshow_0_height' );
          $module['status']  = $this->config->get( 'slideshow_0_status' );

          $str = serialize($module);
         }
          if( $str ){
                  $this->deleteSettingGroup( 'slideshow' );

              if( !$this->hasLayout( 'slideshow' ) ) {
               $sql = '
			INSERT INTO
				   `' . DB_PREFIX . 'layout_module`
			SET
				   `layout_id` = \'' . $layout_id . '\',
				   `code`= \'slideshow.' . $this->module . '\',
				   `position` = \'' . $position . '\',
				   `sort_order` = \'' . $sort_order . '\'';

                 } else {
                $sql = '
			UPDATE
				   `' . DB_PREFIX . 'layout_module`
			SET
				   `code`= \'' . $code . '\'
                        WHERE
                                  `layout_module_id` = \''. $layout_module_id . '\'';

               }
            if( !$this->simulate ) {
		   $this->db->query( $sql );
            }
            if( $this->showOps ) {
                   $text .= '<p><pre>' . $sql .'</pre></p>';
           }
	$text .= $this->msg( sprintf( $this->lang['msg_config'], 'slideshow_module',  DB_PREFIX . 'layout_module' ) );
      

         $sql = '
		INSERT INTO
			`' . DB_PREFIX . 'module`
		SET
                        `module_id` = \'' . $this->module . '\',
			`name` = \'' . $name . '\',
			`code` = \'slideshow\',
			`setting` = \'' . $str . '\'';
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
   return $text;
  }


  public function getChangeSerializeModule($mod){
     /*
      * This is modules 'bestseller', 'latest' and 'special'
      */
        $text = '';
        $str = '';
        $status = 0;
        $module = array();

	if( $this->config->get( $mod . '_module' ) && $this->hasLayout( $mod )) {
        /*
         * version 2.0.0.0
         */
          $mmodule = $this->config->get( $mod . '_module' );
          
         $sql = '
			SELECT * FROM
				   `' . DB_PREFIX . 'layout_module`
			WHERE
				  `code` LIKE \'' . $mod .'%\'';
  
	  $query = $this->db->query( $sql );

          $layout_module_id = $query->row['layout_module_id'];
          

            $code = $query->row['code'];

          $part = explode('.', $code);

         if( isset($part[1]) ){
             $part[1] = $this->module;
             $code = implode('.',$part);
         }

         $sql = '
			SELECT * FROM
				   `' . DB_PREFIX . 'layout`
			WHERE
				   `layout_id`= \'' . $query->row['layout_id'] . '\'';
  
		  $query = $this->db->query( $sql );

          $name = $query->row['name'];
          $module['name'] = $mod . ' - ' . $name;
          $module['width'] = $mmodule[0]['image_width'];
          $module['height'] = $mmodule[0]['image_height'];
          $module['limit'] = $mmodule[0]['limit'];
          $module['status'] = $this->config->get( $mod . '_status');

         $str = serialize($module);
        }

	if( $this->config->get( $mod . '_module' ) && !$this->hasSetting( $mod . '_0_position' ) && !$this->hasLayout( $mod )) {
        /*
         * version 1.5.1 or newer
         */
          $mmodule = $this->config->get( $mod . '_module' );

          $layout_id = $mmodule[0]['layout_id'];
          $sort_order = $mmodule[0]['sort_order'];
          $position = $mmodule[0]['position'];
          

         $sql = '
			SELECT * FROM
				   `' . DB_PREFIX . 'layout`
			WHERE
				   `layout_id`= \'' . $layout_id . '\'';
  
		  $query = $this->db->query( $sql );

          $name = $query->row['name'];
          $module['name'] = $mod . ' - ' . $name;
          $module['width'] = $mmodule[0]['image_width'];
          $module['height'] = $mmodule[0]['image_height'];
          $module['limit'] = $mmodule[0]['limit'];
          $module['status'] = $mmodule[0]['status'];

         $str = serialize($module);
        }

	if( $this->config->get( $mod . '_0_status' ) && $this->hasSetting( $mod . '_0_position' )) {
        /*
         * version 1.5.0 - 1.5.0.5
         */

          $layout_id = $this->config->get( $mod . '_0_layout_id' );
          $sort_order = $this->config->get( $mod . '_0_sort_order' );
          $position = $this->config->get( $mod . '_0_position' );
          

         $sql = '
			SELECT * FROM
				   `' . DB_PREFIX . 'layout`
			WHERE
				   `layout_id`= \'' . $layout_id . '\'';
  
		  $query = $this->db->query( $sql );

            $name = $query->row['name'];
          $module['name'] = $mod . ' - ' . $name;
          $module['limit'] = $this->config->get( $mod . '_0_limit' );
          $module['width'] = $this->config->get( $mod . '_0_image_width' );
          $module['height'] = $this->config->get( $mod . '_0_image_height' );
          $module['status'] = $this->config->get( $mod . '_0_status' );

         $str = serialize($module);
        }

	if( $this->config->get( $mod . '_sort_order' ) && !$this->hasSetting( $mod . '_module' )) {
        /*
         * version 1.4.7 - 1.4.9.5
         */

          $layout_id = 1;
          $sort_order = $this->config->get( $mod . '_sort_order' );

          $position = 'content_bottom';

         $name = 'Home';
          $module['name'] = $mod . ' - ' . $name;
          $module['limit'] = 4;
          $module['width'] = 200;
          $module['height'] = 200;
          $module['status'] = $this->config->get( $mod . '_status' );
          if( $mod == 'latest' ){
          $position = 'content_top';
          $module['limit'] = 8;
          }

         $str = serialize($module);
        }
          if( $str ){

               $this->deleteSettingGroup( $mod );

              if( !$this->hasLayout( $mod ) ) {
             $sql = '
		     INSERT INTO
			        `' . DB_PREFIX . 'layout_module`
		     SET
				`layout_id` = \'' . $layout_id . '\',
			        `code`= \'' . $mod . '.' . $this->module . '\',
				`position` = \'' . $position . '\',
				`sort_order` = \'' . $sort_order . '\'';

                 } else {
                $sql = '
			UPDATE
				   `' . DB_PREFIX . 'layout_module`
			SET
				   `code`= \'' . $code . '\'
                        WHERE
                                  `layout_module_id` = \''. $layout_module_id . '\'';

               }
            if( !$this->simulate ) {
		   $this->db->query( $sql );
            }
            if( $this->showOps ) {
                   $text .= '<p><pre>' . $sql .'</pre></p>';
           }
	$text .= $this->msg( sprintf( $this->lang['msg_config'], $mod . '_module',  DB_PREFIX . 'layout_module' ) );

       
         $sql = '
		INSERT INTO
			`' . DB_PREFIX . 'module`
		SET
                        `module_id` = \'' . $this->module . '\',
			`name` = \'' . $name . '\',
			`code` = \'' . $mod . '\',
			`setting` = \'' . $str . '\''; 
            ++$this->module;
		if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ) {
                      $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->settingcounter;
		$text .= $this->msg( sprintf( $this->lang['msg_config'], $mod . '_module', DB_PREFIX . 'module' ) );
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
	if( $this->config->get( $mod . '_module' ) && !$this->hasSetting( $mod . '_0_position' ) && !$this->hasSetting( $mod . '_1_position' )) {
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


         $this->deleteSettingGroup( $mod );
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

        $this->deleteSettingGroup( $mod );

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

	if( $this->hasSetting( $mod . '_sort_order' ) &&  $this->config->get( $mod . '_position' )) {
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

	if( !$this->hasLayoutModule( $v['module'], $v['layout_id'] ) ) {
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

        $this->deleteSettingGroup( $mod );

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

	if( !$this->hasSetting( $v['key'] ) ) {
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

	if( !$this->hasExtension( $v['mod'] ) ) {
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
    if( $this->config->get('config_mail_protocol') && !$this->hasSetting('config_mail') ){
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

   private function newSettings(){
  
        $text ='';
	if( !$this->hasSetting( 'config_ftp_status' ) ) {

		$sql = '
		INSERT INTO
			`' . DB_PREFIX . 'setting`
		SET
			`store_id` = \'0\',
			`code` = \'config\',
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
			`code` = \'config\',
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
			`code` = \'config\',
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
			`code` = \'config\',
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
			`code` = \'config\',
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
			`code` = \'config\',
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
			`code` = \'config\',
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
			`code` = \'config\',
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
			`code` = \'config\',
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
			`code` = \'config\',
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
			`code` = \'config\',
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
			`code` = \'config\',
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
			`code` = \'config\',
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
			`code` = \'config\',
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
			`code` = \'config\',
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
			`code` = \'config\',
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
			`code` = \'config\',
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
			`code` = \'config\',
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
			`code` = \'config\',
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
			`code` = \'config\',
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
			`code` = \'config\',
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
			`code` = \'config\',
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
			`code` = \'config\',
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
			`code` = \'config\',
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
			`code` = \'config\',
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
			`code` = \'config\',
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
			`code` = \'config\',
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
			`code` = \'config\',
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
			`code` = \'config\',
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
			`code` = \'config\',
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
			`code` = \'config\',
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
			`code` = \'config\',
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
			`code` = \'config\',
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
			`code` = \'config\',
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
			`code` = \'config\',
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
			`code` = \'config\',
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
			`code` = \'config\',
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
			`code` = \'config\',
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
			`code` = \'config\',
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
			`code` = \'config\',
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
			`code` = \'config\',
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
			`code` = \'config\',
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
			`code` = \'config\',
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
			`code` = \'config\',
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
			`code` = \'config\',
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
			`code` = \'config\',
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
			`code` = \'config\',
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
		$text .= $this->msg( sprintf( $this->lang['msg_config'], 'config_secure', 'setting' ) );
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
   private function hasLayout( $val ) {
 
	$sql = '
	SELECT
		*
	FROM
		`' . DB_PREFIX . 'layout_module`
	WHERE
		`code` LIKE  \'' . $val . '%\'';

        if( array_search( DB_PREFIX . 'layout_module', $this->getTables() ) ){
	$result = $this->db->query( $sql );
	if( count( $result->row ) == 0 ) {
		return false;
	}
       } else {
		return false;
	}

	return true;
   }
   private function hasExtension( $val ) {
    if( array_search( 'code', $this->getDbColumns( 'extension' ) ) ){
      $field = 'code';
    }
    if( array_search( 'key', $this->getDbColumns( 'extension' ) ) ){
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
   public function hasLayoutModule( $module, $layout_id ) {
	$sql = '
	SELECT
		*
	FROM
		`' . DB_PREFIX . 'layout_module`
	WHERE
                `layout_id` = \'' . $layout_id . '\'
        AND
		`code` = \'' . $module . '\'';
      if( !$this->simulate ) {
	$result = $this->db->query( $sql );

	if( count( $result->row ) == 0 ) {
		return false;
	}

	return true;
     }
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
                $colums = $this->db->query("SHOW COLUMNS FROM " . DB_PREFIX . $table );

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
		`code` = \'' . $group . '\'';
     if( !$this->simulate ){
	$this->db->query( $sql );
     }

  }
}
