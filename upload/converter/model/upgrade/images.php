<?php
class ModelUpgradeImages extends Model{
    private $lang;

/**
 * adopting image paths
 */
   public function imagePaths( $data ) {
       $this->lang = $this->lmodel->get('upgrade_images');

      $text = '';
      $this->simulate = ( !empty( $data['simulate'] ) ? true : false );
      $this->showOps  = ( !empty( $data['showOps'] ) ? true : false );
      $this->dirImage = $data['dirImage'] . '/';

    if( is_writable( DIR_STORE_IMAGE ) ) {
          $images = $this->getImages();
          $imageInfo = $images[0];
          $text .= $images[1];

            $copy = 0;
            $imagepath = 0;

     foreach( $imageInfo as $img ){

  /*
   * Replace path, if page is updated
   *
   */

       $img['path'] = str_replace('data/','catalog/',$img['path']);

       if( $this->simulate ){
         $img['path'] = str_replace( 'catalog/','data/', $img['path'] );
       }

           /*
            * Update paths to database
            */
                        $sql = '
				UPDATE
					`' . DB_PREFIX . $img['table'] . '`
				SET
					`image` = \'' . $this->db->escape($img['updatepath']) . '\'
				WHERE
					`' . $img['field'] . '` = \'' . (int) $img['id'] . '\'';

                 if( !$this->simulate ){
				$this->db->query( $sql );
                 }
                
				++$imagepath;
       
          }

	if( $this->structure->hasSetting( 'config_logo' ) ) {
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

	if( $this->structure->hasSetting( 'config_icon' ) ) {
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
	$text .= '<div class="error round">';
	$text .= sprintf( $this->lang['msg_perm_dir'], DIR_STORE_IMAGE );
        $text .= '</div>';
   }
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

  if( array_search( DB_PREFIX . 'banner_image' , $this->structure->tables()) ) {
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
                      "path"       => $root . $this->dirImage . str_replace('data','catalog',$images['image']),
                      "updatepath" => str_replace('data','catalog', $images['image'] ),
                      "newpath"    => DIR_STORE_IMAGE . str_replace('data','catalog', $images['image']),
                      "newdirpath" => DIR_STORE_IMAGE . 'catalog/'
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
                      "updatepath" => str_replace('data','catalog', $images['image'] ),
                      "path"       => $root . $this->dirImage . $images['image'],
                      "newpath"    => DIR_STORE_IMAGE .  str_replace('data','catalog', $images['image']),
                      "newdirpath" => DIR_STORE_IMAGE . 'catalog/'
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
                      "updatepath" => str_replace('data','catalog', $images['image'] ),
                      "path"       => $root . $this->dirImage . $images['image'],
                      "newpath"    => DIR_STORE_IMAGE . str_replace('data','catalog', $images['image']),
                      "newdirpath" => DIR_STORE_IMAGE . 'catalog/'
                     );
    }
   }

  $rename = array(
                array( "curr" => DIR_STORE_IMAGE . 'data/', "comp" => DIR_STORE_IMAGE . 'catalog/'),
                array( "curr"  => DIR_STORE_IMAGE . 'cache/data/', "comp" => DIR_STORE_IMAGE . 'cache/catalog/' )
          );
  $text = $this->setRename($rename);

    return array($imageInfo,$text);
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
  public function msg( $data ){
       return str_replace( $data, '<div class="msg round">' . $data .'</div>', $data);
  }
}
