<?php
class ModelUpgradeImages extends Model{
    private $lang;

/**
 * adopting image paths
 */
  public function getTables() {
       $query = $this->db->query("SHOW TABLES FROM " . DB_DATABASE);

        $table_list = array();
        foreach($query->rows as $table){
                      $table_list[] = $table['Tables_in_'. DB_DATABASE];
          }
        return $table_list;
  }
   public function imagePaths( $data ) {
       $this->lang = $this->lmodel->get('upgrade_images');

      $text = '';
        $this->simulate = ( !empty( $data['simulate'] ) ? true : false );
        $this->showOps  = ( !empty( $data['showOps'] ) ? true : false );
      $this->dirImage = $data['dirImage'] . '/';
      $this->dirOld = ( !empty( $data['dirOld'] ) ? true : false );
      $this->permission = $data['permission'];

    if( is_writable( DIR_STORE_IMAGE ) ) {
          $images = $this->getImages();
          $imageInfo = $images[0];
          $text .= $images[1];
//print_r($imageInfo[1]);
            $copy = 0;
            $imagepath = 0;

     foreach( $imageInfo as $img ){

  /*
   * Replace path, if page is updated
   *
   */

       $img['path'] = str_replace('data','catalog',
                      str_replace('banners/','',
                      str_replace('manufacturer/','',
                      str_replace('product/','', $img['path'] 
                                  )
                              )
                          )
                      );

       if( $this->dirOld || $this->simulate ){
         $img['path'] = str_replace( 'catalog','data', $img['path'] );
       }

         if( file_exists( $img['path'] )  && is_dir( $img['newdirpath'] ) ||  file_exists( $img['path']) && $this->simulate ){

         /*
          * Copy files to Catalog directories banners, product etc.
          */
              if( !file_exists( $img['newpath'] ) || $this->simulate){
                 if( !$this->simulate ){

                   copy( $img['path'] , $img['newpath'] );

                 }

		 $text .= $this->msg( sprintf( $this->lang['msg_image_copied'], $img['newpath'], '' ) );
		 ++$copy;
             }
           /*
            * Update paths to database
            */
                        $sql = '
				UPDATE
					`' . DB_PREFIX . $img['table'] . '`
				SET
					`image` = \'' . $img['updatepath'] . '\'
				WHERE
					`' . $img['field'] . '` = \'' . (int) $img['id'] . '\'';

                 if( !$this->simulate ){
				$this->db->query( $sql );
                 }
                
				++$imagepath;
       
          }
      } 

	if( $this->hasSetting( 'config_logo' ) ) {
		$sql = '
		UPDATE
			`' . DB_PREFIX . 'setting`
		SET
			`value` = 
                REPLACE
                        ( value , \'data\', \'catalog\' )
		WHERE
			`key` = \'config_logo\'';

                 if( !$this->simulate ){
		$this->db->query( $sql );
                 }
		++$imagepath;
	}

	if( $this->hasSetting( 'config_icon' ) ) {
		$sql = '
		UPDATE
			`' . DB_PREFIX . 'setting`
		SET
			`value` =
                REPLACE
                        ( value , \'data\', \'catalog\' )
		WHERE
			`key` = \'config_icon\'';

                 if( !$this->simulate ){
		$this->db->query( $sql );
                 }
		++$imagepath;
	}
		
   } else{
	$text .= '<div class="header round">';
	$text .= sprintf( $this->lang['msg_perm_dir'], DIR_STORE_IMAGE );
        $text .= '</div>';
   }
	$text .= '<div class="header round">';
	$text .= sprintf( $this->lang['msg_copy_image'], $copy );
        $text .= '</div>';
	$text .= '<div class="header round">';
	$text .= sprintf( $this->lang['msg_change_path'], $imagepath );
        $text .= '</div>';

     return $text;

   }
   public function getImages(){

      if( !$this->dirOld ){
                     $imgdata = 'catalog';
                     $root    = DIR_DOCUMENT_ROOT;
       } else {
                     $imgdata = 'data';
                     $root    = $this->dirOld;
      }
/*
 * Create array of Store images
 * 
 */
   $imageInfo = array();

  if( array_search( DB_PREFIX . 'banner_image' , $this->getTables()) ) {
     $sql = '
	    SELECT
	          *
	    FROM
		 `' . DB_PREFIX . 'banner_image`';

	$query = $this->db->query( $sql );

   foreach($query->rows as $images){   
     if( $images['image'] !='' ){    
    $imageInfo[] = array(
                      "table"      => 'banner_image',
                      "field"      => 'banner_image_id',
                      "id"         => $images['banner_image_id'],
                      "path"       => $root . $this->dirImage . str_replace('catalog',$imgdata,$images['image']),
                      "updatepath" => str_replace('data','catalog/banners', str_replace('demo/','',$images['image'] )),
                      "newpath"    => DIR_STORE_IMAGE . 'catalog/banners/'. basename($images['image']),
                      "newdirpath" => DIR_STORE_IMAGE . 'catalog/banners/'
         );
     }
   }
 }

    $sql = '
	   SELECT
		 *
	   FROM
		`' . DB_PREFIX . 'product_image`';

	$query = $this->db->query(  $sql );

   


   foreach($query->rows as $images){
     if( $images['image'] !='' ){    
     $imageInfo[] = array(
                      "table"      => 'product_image',
                      "field"      => 'product_image_id',
                      "id"         => $images['product_image_id'],
                      "updatepath" => str_replace('data','catalog', $images['image'] ),
                      "path"       => $root . $this->dirImage . str_replace('catalog', $imgdata , $images['image']),
                      "newpath"    => DIR_STORE_IMAGE . str_replace('data','catalog', $images['image']),
                      "newdirpath" => DIR_STORE_IMAGE . 'catalog/'
                     );
    }
   }
     $sql = '
	    SELECT
		  *
	    FROM
		  `' . DB_PREFIX . 'category`';

	$query = $this->db->query( $sql );

   foreach($query->rows as $images){
     if( $images['image'] !='' ){
    $imageInfo[] = array(
                      "table"      => 'category',
                      "field"      => 'category_id',
                      "id"         => $images['category_id'],
                      "updatepath" => str_replace('data','catalog', $images['image']),
                      "path"       => $root . $this->dirImage . $images['image'],
                      "newpath"    => DIR_STORE_IMAGE . str_replace('data','catalog', $images['image']),
                      "newdirpath" => DIR_STORE_IMAGE . 'catalog/'
                     );
    }
   }
   $sql = '
	  SELECT
	 	*
	  FROM
		`' . DB_PREFIX . 'manufacturer`';

      $query = $this->db->query( $sql );

   foreach($query->rows as $images){
     if( $images['image'] !='' ){
    $imageInfo[] = array(
                      "table"      => 'manufacturer',
                      "field"      => 'manufacturer_id',
                      "id"         => $images['manufacturer_id'],
                      "updatepath" => str_replace('data','catalog/manufacturer', str_replace('demo/','', $images['image'] )),
                      "path"       => $root . $this->dirImage . $images['image'],
                      "newpath"    => DIR_STORE_IMAGE . 'catalog/manufacturer/'. basename( $images['image'] ),
                      "newdirpath" => DIR_STORE_IMAGE . 'catalog/manufacturer/'
                     );
    }
   }

   $sql = '
	  SELECT
		*
	  FROM
		`' . DB_PREFIX . 'product`';

       $query = $this->db->query( $sql );

   foreach($query->rows as $images){
     if( $images['image'] !='' ){
    $imageInfo[] = array(
                      "table"      => 'product',
                      "field"      => 'product_id',
                      "id"         => $images['product_id'],
                      "updatepath" => str_replace('data','catalog/product', str_replace('demo/','', $images['image'] )),
                      "path"       => $root . $this->dirImage . $images['image'],
                      "newpath"    => DIR_STORE_IMAGE . 'catalog/product/' .  basename($images['image']),
                      "newdirpath" => DIR_STORE_IMAGE . 'catalog/product/'
                     );
    }
   }
  $text = $this->setDir();
  $rename = array(
                array( "curr" => DIR_STORE_IMAGE . 'data/', "comp" => DIR_STORE_IMAGE . 'catalog/'),
                array( "curr"  => DIR_STORE_IMAGE . 'cache/data/', "comp" => DIR_STORE_IMAGE . 'cache/catalog/' )
          );
  $text .= $this->setRename($rename);

    return array($imageInfo,$text);
   }

   private function setDir(){
      $text = '';
      if( !is_dir( DIR_STORE_IMAGE . 'data' ) ){
          $dir = 'catalog';
      } else {
          $dir = 'data';
      }
         /*
          * Not Found directory Image/Data/+
          * Create directory Banners, Manufactuer and Product
          *
          */
       $dirpath = 0;
        if( !is_dir( DIR_STORE_IMAGE . $dir . '/banners' ) ){

          $php = '
                  @mkdir(
                         \''. DIR_STORE_IMAGE . $dir . '/banners\' , octdec( '. $this->permission.' )
                   );';
             if( !$this->simulate ) {

                  @mkdir( DIR_STORE_IMAGE . $dir . '/banners' , octdec( $this->permission ) );

             }	 	
      if( $this->showOps ) {
		  $text .= '<p><pre>' . $php . '</pre></p>';
		
	} 
             ++$dirpath;
		$text .= $this->msg( sprintf( $this->lang['msg_newDir'],DIR_STORE_IMAGE . $dir . '/banners' ) );	 

        }
        if( !is_dir( DIR_STORE_IMAGE . $dir . '/manufacturer' ) ){

          $php = '
                  @mkdir(
                         \''. DIR_STORE_IMAGE . $dir . '/manufacturer\' , octdec( '. $this->permission.' )
                   );';
             if( !$this->simulate ) {

                  @mkdir( DIR_STORE_IMAGE . $dir . '/manufacturer' , octdec( $this->permission ) );

             } 	
      if( $this->showOps ) {
		  $text .= '<p><pre>' . $php . '</pre></p>';
		
	} 
             ++$dirpath;
		$text .= $this->msg( sprintf( $this->lang['msg_newDir'], DIR_STORE_IMAGE . $dir . '/manufacturer') );	 

        }
        if( !is_dir( DIR_STORE_IMAGE . $dir . '/product' ) ){

          $php = '
                  @mkdir(
                         \''. DIR_STORE_IMAGE . $dir . '/product\' , octdec( '. $this->permission.' )
                   );';
             if( !$this->simulate ) {

                  @mkdir( DIR_STORE_IMAGE . $dir . '/product' , octdec( $this->permission ) );

             } 	
      if( $this->showOps ) {
		  $text .= '<p><pre>' . $php . '</pre></p>';
		
	} 
             ++$dirpath;
	    $text .= $this->msg( sprintf( $this->lang['msg_newDir'], DIR_STORE_IMAGE . $dir . '/product' ) );	 

        }

	$text .= '<div class="header round">';
	$text .= sprintf( $this->lang['msg_new_dir'], $dirpath, '' );
        $text .= '</div>';
        // End Data and Cache
 
    return $text;
   }

   private function setRename($data){

         /*
          * Rename directories Cache/Data
          *
          */
           $text = '';
            $dir = 0;
   foreach($data as $directory){
  if( is_dir($directory['curr']) && !is_dir( $directory['comp'] ) ){

          $php = '
              @rename( 
                   \'' . $directory['curr'] . '\',
                   \'' . $directory['comp'] . '\'
               );';
      if( !$this->simulate ) {

          @rename( $directory['curr'], $directory['comp'] );
      }	 	
      if( $this->showOps ) {
		  $text .= '<p><pre>' . $php . '</pre></p>';
		
	} 
        ++$dir;
         $text .= $this->msg( sprintf( $this->lang['msg_renamed_dir'], $directory['curr'], $directory['comp'] ) );
        
     }
    }
	$text .= '<div class="header round">';
	$text .= sprintf( $this->lang['msg_renamed_total_dir'], $dir, '' );
        $text .= '</div>';
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
}
