<?php
class ModelUpgradeConfiguration extends Model{
    private $lang;
    private $text;
   public function editConfig( $data ){
       $this->lang = $this->lmodel->get('upgrade_configuration');
       $this->simulate = ( !empty( $data['simulate'] ) ? true : false );
       $this->dirAdmin = ( !empty( $data['dirAdmin'] ) ? true : 'admin' ) .'/';
       $this->dirImage =  ( !empty( $data['dirImage'] ) ? true : 'image' ) . '/';
       $this->dirOld = ( !empty( $data['dirOld'] ) ? true : false );
       $this->showOps  = ( !empty( $data['showOps'] ) ? true : false );
       $this->upgrade2020  = ( !empty( $data['upgrade2020'] ) ? true : false );
       $this->upgrade2030  = ( !empty( $data['upgrade2030'] ) ? true : false );
       $this->upgrade2101  = ( !empty( $data['upgrade2101'] ) ? true : false );

        $this->text = '';

		$modification = 'define(\'DIR_MODIFICATION\', \'' . DIR_MODIFICATION . '\'); // OC 2';
		$upload = 'define(\'DIR_UPLOAD\', \'' . DIR_UPLOAD . '\'); // OC 2';
		$modification2 = 'define(\'DIR_MODIFICATION\', \'' . DIR_DOCUMENT_ROOT . 'system/storage/modification/\'); // OC 2.1';
		$upload2 = 'define(\'DIR_UPLOAD\', \'' . DIR_DOCUMENT_ROOT . 'system/storage/upload/\'); // OC 2.1';

                $server = $_SERVER['HTTP_HOST'].rtrim(dirname($_SERVER['SCRIPT_NAME']));
                $server = explode('/',$server);
                array_pop($server);
                $server = implode('/',$server);
                
		$http_server = 'define(\'HTTP_SERVER\', \'http://' . $server . '/\'); // OC 1.4+';
		$https_server = 'define(\'HTTPS_SERVER\', \'https://' .$server . '/\'); // OC 1.4+';
		$https_catalog = 'define(\'HTTPS_CATALOG\', \'https://' .$server . '/\'); // OC 1.4+';
		$db_port = 'define(\'DB_PORT\', \'3306\'); // OC 2.0.3 +';

		// frontend
	    $content = file_get_contents( DIR_DOCUMENT_ROOT . 'config.php' );
       $check = $content;

		if( !strpos( $content, 'DIR_UPLOAD' ) || $this->upgrade2030  &&  !strpos( $content, 'DB_PORT' ) || !strpos( $content, 'storage/') ) {
      
			$fp = file( DIR_DOCUMENT_ROOT . 'config.php' );
		if( $this->upgrade2101 ){
		   for($i=0;$i<count($fp);$i++){
           // Replace
         	  if(substr(VERSION,0,3) == '2.0'){
       	        $fp[$i] = str_replace('/system/modification', '/system/storage/modification', $fp[$i]);
       	        $fp[$i] = str_replace('/system/upload', '/system/storage/upload', $fp[$i]);
       	        }
       	     $fp[$i] = str_replace('/system/logs', '/system/storage/logs', $fp[$i]);
       	     $fp[$i] = str_replace('/system/cache', '/system/storage/cache', $fp[$i]);
           }
               if( !strpos( $content, 'DIR_UPLOAD' ) ) {
         	        if(substr(VERSION,0,3) != '2.0'){
								array_splice( $fp, 18, 0, $modification2 . "\r\n" );
								array_splice( $fp, 19, 0, $upload2 . "\r\n" );
					}
			   }
        }
		$this->text .= $this->msg('<p><hr/></p>');
          //   FILE yourstore.com/config.php
          
						  if( !strpos( $content, 'HTTP_SERVER' ) ) {
							  array_splice( $fp, 1, 0, $http_server . "\r\n" );
							  array_splice( $fp, 2, 0, $https_server . "\r\n" );
						   }
					   	  if( !strpos( $content, 'DIR_UPLOAD' ) ){
					   	  	if($this->upgrade2020 || $this->upgrade2030) {
								array_splice( $fp, 18, 0, $modification . "\r\n" );
								array_splice( $fp, 19, 0, $upload . "\r\n" );
							}
						   }
						   if(!strpos( $content, 'DB_PORT' )) {
						    if($this->upgrade2101 || $this->upgrade2030 ){ 
							  array_splice( $fp, 20, 0, $db_port . "\r\n" );
						    }
						   }

			$content = implode( '', $fp );

		if( is_writable( DIR_DOCUMENT_ROOT . 'config.php' ) ) {
							if( !$this->simulate ) {
								$fw = fopen( DIR_DOCUMENT_ROOT . 'config.php', 'wb' );
								fwrite( $fw, $content );
								fclose( $fw ); 
							}
							
										    if( !strpos( $check, 'HTTP_SERVER' ) ) {
								                if( $this->showOps ){
												    	$this->text .= '<p><pre>'.$http_server.'</pre></p>';
								                }
												$this->text .= $this->msg( sprintf(  $this->lang['msg_config_constant'],  'HTTP_SERVER', 'config.php' ) );
								                if( $this->showOps ){
												    	$this->text .= '<p><pre>'.$https_server.'</pre></p>';
								                }
												$this->text .= $this->msg( sprintf(  $this->lang['msg_config_constant'],  'HTTPS_SERVER', 'config.php' ) );
										   }
	                if( $this->showOps ){
					   	if($this->upgrade2020 || $this->upgrade2030) {
					    	$this->text .= '<p><pre>'.$upload.'</pre></p>';
					    }
	                	if($this->upgrade2101){
					    	$this->text .= '<p><pre>'.$upload2.'</pre></p>';
					    }
	                }
					$this->text .= $this->msg( sprintf(  $this->lang['msg_config_constant'],  'DIR_UPLOAD', 'config.php' ) );
	                if( $this->showOps ){
					   	 if($this->upgrade2020 || $this->upgrade2030) {
					    	$this->text .= '<p><pre>'.$modification.'</pre></p>';
					    }
	                	if($this->upgrade2101){
					    	$this->text .= '<p><pre>'.$modification2.'</pre></p>';
					    }
	                }
							$this->text .= $this->msg( sprintf(  $this->lang['msg_config_constant'],  'DIR_MODIFICATION', 'config.php' ) );
								
					    if($this->upgrade2101 || $this->upgrade2030){
					    	if(!strpos( $check, 'DB_PORT' )) {		
		                      if( $this->showOps ){
						    	$this->text .= '<p><pre>'.$db_port.'</pre></p>';
						      }
								$this->text .= $this->msg( sprintf(  $this->lang['msg_config_constant'], 'DB_PORT', 'config.php' ) );
		                    }
		                  }
	/*	} else {
			$this->text .= $this->msg( sprintf(  $this->lang['msg_config_uptodate'], 'config.php', '' ) );
		}*/
		$this->text .= $this->msg('<p><hr/></p>');
		
		// FILE yourstore.com/admin/config.php		
		$file		= $this->dirAdmin . 'config.php';
		$content2	= file_get_contents( DIR_DOCUMENT_ROOT . $file );
		$fp2 = file( DIR_DOCUMENT_ROOT . $file );

    if($this->upgrade2101){
		for( $i=0;$i<count($fp2);$i++ ){
           // Replace
         	if( substr(VERSION,0,3) == '2.0' ){
       	     $fp2[$i] = str_replace('/system/modification', '/system/storage/modification', $fp2[$i]);
       	     $fp2[$i] = str_replace('/system/upload', '/system/storage/upload', $fp2[$i]);
           }
       	     $fp2[$i] = str_replace('/system/logs', '/system/storage/logs', $fp2[$i]);
       	     $fp2[$i] = str_replace('/system/cache', '/system/storage/cache', $fp2[$i]);
        }  
            if( !strpos( $content2, 'DIR_UPLOAD' )) {
         	        if(substr(VERSION,0,3) != '2.0'){
								array_splice( $fp, 18, 0, $modification2 . "\r\n" );
								array_splice( $fp, 19, 0, $upload2 . "\r\n" );
					}
			}
      }
		    if( !strpos( $content2, 'HTTPS_CATALOG' ) ) {
			  array_splice( $fp2, 8, 0, $https_catalog . "\r\n" );
		   }
		  if( !strpos( $content2, 'DIR_UPLOAD' || !strpos($content2, 'DB_PORT') || strpos($conten2,'/storage')) ) {
			if($this->upgrade2020 || $this->upgrade2030 || $this->upgrade2101) {
			array_splice( $fp2, 21, 0, $modification . "\r\n" );
			array_splice( $fp2, 22, 0, $upload . "\r\n" );
	        }
		    if($this->upgrade2101 || $this->upgrade2030){
		    	if(!strpos( $content2, 'DB_PORT' )) {
			      array_splice( $fp2, 25, 0, $db_port . "\r\n" );
			  }
		   }

			$string = implode( '', $fp2 );

			if( is_writable( DIR_DOCUMENT_ROOT. $file ) ) {
				if( !$this->simulate ) {
					$fw = fopen( DIR_DOCUMENT_ROOT . $file, 'wb' );
					fwrite( $fw, $string );
					fclose( $fw );
				}

				    if( !strpos( $content2, 'HTTPS_CATALOG' ) ) {
		                if( $this->showOps ){
						    	$this->text .= '<p><pre>'.$https_catalog.'</pre></p>';
		                }
						$this->text .= $this->msg( sprintf(  $this->lang['msg_config_constant'],  'HTTPS_CATALOG', $file ) );
				   }
                if( $this->showOps ){
					   	 if($this->upgrade2020 || $this->upgrade2030) {
				    	$this->text .= '<p><pre>'.$upload.'</pre></p>';
					    }
	                	if($this->upgrade2101){
					    	$this->text .= '<p><pre>'.$upload2.'</pre></p>';
					    }
                }
						$this->text .= $this->msg( sprintf(  $this->lang['msg_config_constant'], 'DIR_UPLOAD', $file ) );
                if( $this->showOps ){
					   	 if($this->upgrade2020 || $this->upgrade2030) {
				    	$this->text .= '<p><pre>'.$modification.'</pre></p>';
					    }
	                	if($this->upgrade2101){
					    	$this->text .= '<p><pre>'.$modification2.'</pre></p>';
					    }
                }
						$this->text .= $this->msg( sprintf(  $this->lang['msg_config_constant'], 'DIR_MODIFICATION', $file ) );
		    if($this->upgrade2101 || $this->upgrade2030){
		    	if(!strpos( $content2, 'DB_PORT' )) {		
                if( $this->showOps ){
				    	$this->text .= '<p><pre>'.$db_port.'</pre></p>';
                }
						$this->text .= $this->msg( sprintf(  $this->lang['msg_config_constant'], 'DB_PORT', $file ) );
				}
            }

			}else{
				$this->text .= $this->msg(  sprintf( $this->lang['msg_perm_file'], 'config.php', $file ) );
			}
		}else{
			$this->text .= $this->msg( sprintf(  $this->lang['msg_config_uptodate'], $file, '' ) );
		} 

					}else{
						$this->text .= $this->msg(  sprintf( $this->lang, 'msg_perm_file', 'config.php' ) );
					}
		} else {
			$this->text .= $this->msg( sprintf(  $this->lang['msg_config_uptodate'], 'config.php', '' ) );
		}
	return $this->text;
   }
  public function msg( $data ){
       return str_replace( $data, '<div class="msg round">' . $data .'</div>'."\r\n", $data);
  }
}
