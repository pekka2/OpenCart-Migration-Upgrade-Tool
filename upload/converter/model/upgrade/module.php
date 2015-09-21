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
  private function moduleStructure($mod){
        $module1 = false;
        $modules = array();
    $modulex = !empty($this->structure->hasSetting( $mod . '_module' ) ? true:false);
    $module2 = !empty($this->structure->hasSetting( $mod . '_0_position') ? true:false);
    $module2b = !empty($this->structure->hasSetting( $mod . '_1_position') ? true:false);
    $module3 = !empty($this->structure->hasSetting( $mod . '_position') ? true:false);
    if( $modulex && !$module3 ){
      $module1 = true;
    }
    if( array_search('group', $this->structure->columns('setting'))){
     $sql = "SELECT * FROM `" . DB_PREFIX . "setting` WHERE `group` = '" . $mod . "'";
    } else{
     $sql = "SELECT * FROM `" . DB_PREFIX . "setting` WHERE `code` = '" . $mod . "'";
    }
       $results = $this->db->query($sql);

    if(count($results->rows) > 0){
     $row = 0;
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
                         if( in_array($part[0],$array) ){
                         $row++;
                         }
                      }
                }
                if($module3){
                    $part[1] = str_replace('sort', 'sort_order', $part[1]);
                    $part[1] = str_replace('left', 'column_left', $part[1]);
                    $part[1] = str_replace('right', 'column_right', $part[1]);
                    $part[1] = str_replace('home', 'content_top', $part[1]);
                    $modules[$part[0]][0][$part[1]] = $result['value'];
                         $row++;
                }
                  if( !in_array($part[0],$array) ){
                      array_push($array, $part[0]);
                  }
            } 
          } else{
                if($result['serialized'] == 1){
                 $modules[$mod] = unserialize($result['value']);
               } 
          }
        }
     }
     return $modules;

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
     $modules = $this->moduleStructure($mod);

     if( isset($modules[$mod])){
  
        foreach($modules[$mod] as $key => $value){
  
            if($this->upgrade > 1564){
              if(!isset($value['layout_id'])) $value['layout_id'] = 1;
                        $table_layout_module[] = array("layout_id" => $value['layout_id'],
                                                         "code" => $mod,
                                                         "position" => $value['position'],
                                                         "sort_order" => $value['sort_order']); 

                      if( isset($value[0]['banner_id']) ){
                        $banner_name = $this->getBannerName($value[0]['banner_id']);
                      } else {
                        $banner_name = '';
                      } 

                $name = $this->addModuleName( $value, $banner_name );

                      if( isset($value[0]['banner_id']) ){
                                                         $setting = array("name" => $name,
                                                                          "banner_id" => $value['banner_id'],
                                                                          "width" => $this->getDimension($value,'width'),
                                                                          "height" => $this->getDimension($value,'height'),
                                                                          "status" => $value['status']);
                      }else{
                                                         $setting = array("name" => $name,
                                                                          "width" => $this->getDimension($value,'width'),
                                                                          "height" => $this->getDimension($value,'height'),
                                                                          "status" => $value['status']);
                      if($mod == 'featured'){
                          if( $this->structure->hasSetting( 'featured_product') ){
                             $products = $this->db->query("SELECT `value` FROM `" . DB_PREFIX . "setting` WHERE `key` = 'featured_product'");
                             $product = explode(',',$products->row['value']);
                          } else {
                             $product = $this->getFeaturedProducts( 4 );
                          }
                            $setting['product'] = $product;
                      }
            }
       }
        if($this->upgrade > 1564){
                        $table_module[] = array("name" => $name,
                                                "code" => $mod,
                                                "setting" => serialize($setting)); 

                     }
               if( $this->simulate ) {
                 if(!in_array($mod,$this->layout_module_ids)){
                  array_push($this->layout_module_ids,$mod);
                 }
                 $layout_module_id = count($this->layout_module_ids);
                } else {
                  $layout_module_id = $this->structure->getLayoutModuleId();
                }
                $id = $layout_module_id;
                $sql = '';
          foreach($table_layout_module as $key => $layout_module){

                      $sql = "INSERT INTO `" . DB_PREFIX . "layout_module` (`layout_module_id`, `layout_id`, `code`, `position`, `sort_order`) VALUES
                       (" . $id . ",'" . $layout_module['layout_id'] . "','" . $this->db->escape($layout_module['code']) . "','" . $this->db->escape($layout_module['position']) . "', '"  . $layout_module['sort_order'] . "')";
                        echo $sql.'<br>';
                if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ) {
                      $text .= '<p><pre>' . $sql .'</pre></p>';
                }  
                $id++;
        $text .= $this->msg( sprintf( $this->lang['msg_config'],  $mod,  DB_PREFIX . 'layout_module' ) );
          } 
               if( $this->simulate ) {
                 if(!in_array($mod,$this->module_ids)){
                  array_push($this->module_ids,$mod);
                 }
                 $module_id = count($this->module_ids);
                } else {
                  $module_id = $this->structure->getModuleId();
                }
                  $sql2 = '';
          foreach($table_module as $key => $my_module){

                      $sql2 = "INSERT INTO `" . DB_PREFIX . "module` (`module_id`, `name`, `code`, `setting`) VALUES
                       (" . $module_id . ",'" . $my_module['name'] . "','" . $this->db->escape($my_module['code']) . '.'. $layout_module_id . "','" . $this->db->escape($my_module['setting']) . "')";
                if( !$this->simulate ) {
                       $this->db->query( $sql2 );
                }
                if( $this->showOps ) {
                      $text .= '<p><pre>' . $sql2 .'</pre></p>';
                }  
                $layout_module_id++;
                $module_id++;
    $text .= $this->msg( sprintf( $this->lang['msg_config'], $mod, DB_PREFIX . 'module' ) );
          } 
        }
      }
     return  $text;
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
      /* category, account, information and affiliate module */
        $text = '';
        $table_layout_module = array();
        $table_setting = array();

     $modules = $this->moduleStructure($mod);
      if( isset($modules[$mod])){
  
        foreach($modules[$mod] as $key => $value){
  
            if($this->upgrade > 1564){
              if(!isset($value['layout_id'])) $value['layout_id'] = 1;
                        $table_layout_module[] = array("layout_id" => $value['layout_id'],
                                                       "code" => $mod,
                                                       "position" => $value['position'],
                                                       "sort_order" => $value['sort_order']); 
             }
           }
            if( $this->simulate ) {
                 if(!in_array($mod,$this->layout_module_ids)){
                  array_push($this->layout_module_ids,$mod);
                 }
                 $layout_module_id = count($this->layout_module_ids);
                } else {
                  $layout_module_id = $this->structure->getLayoutModuleId();
                }
                $id = $layout_module_id;
                $sql = '';
          foreach($table_layout_module as $key => $layout_module){
                      $sql = "INSERT INTO `" . DB_PREFIX . "layout_module` (`layout_module_id`, `layout_id`, `code`, `position`, `sort_order`) VALUES
                       (" . $id . ",'" . $layout_module['layout_id'] . "','" . $this->db->escape($layout_module['code']) . "','" . $this->db->escape($layout_module['position']) . "', '"  . $layout_module['sort_order'] . "')";
                if( !$this->simulate ) {
                       $this->db->query( $sql );
                }
                if( $this->showOps ) {
                      $text .= '<p><pre>' . $sql .'</pre></p>';
                }  
                $id++;
        $text .= $this->msg( sprintf( $this->lang['msg_config'],  $mod,  DB_PREFIX . 'layout_module' ) );
          } 
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
