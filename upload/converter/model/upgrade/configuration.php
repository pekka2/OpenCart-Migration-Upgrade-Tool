<?php
class ModelUpgradeConfiguration extends Model{
    private $lang;
    private $text;
    private $dirAdmin;
    private $sowOps;
    private $upgrade;
   public function editConfig( $data ){
       $this->lang = $this->lmodel->get('upgrade_configuration');
       $this->simulate = ( !empty( $data['simulate'] ) ?  true:false );
       $this->dirAdmin = ( !empty( $data['dirAdmin'] ) ?  'admin':'admin' ) .'/';
       $this->showOps  = ( !empty( $data['showOps'] ) ? true:false );
       $this->upgrade  = $data['upgrade'];

        $this->text = '';

		$modification = 'define(\'DIR_MODIFICATION\', \'' . DIR_MODIFICATION . '\'); // OC 2';
		$upload = 'define(\'DIR_UPLOAD\', \'' . DIR_UPLOAD . '\'); // OC 2';

                $server = $_SERVER['HTTP_HOST'].rtrim(dirname($_SERVER['SCRIPT_NAME']));
                $server = explode('/',$server);
                array_pop($server);
                $server = implode('/',$server);
                
		$http_server = '//define(\'HTTP_SERVER\', \'http://' . $server . '/\'); // OC 1.4+';
		$https_server = '//define(\'HTTPS_SERVER\', \'https://' .$server . '/\'); // OC 1.4+';
		$https_catalog = 'define(\'HTTPS_CATALOG\', \'https://' .$server . '/\'); // OC 1.4+';
		$db_port = 'define(\'DB_PORT\', \'3306\'); // OC 2.0.3 +';

		// frontend
	    $content = file_get_contents( DIR_DOCUMENT_ROOT . 'config.php' );
       $check = $content;

		if( !strpos( $content, 'DIR_UPLOAD' ) || $this->upgrade > 2020  &&  !strpos( $content, 'DB_PORT') ) {

		$this->text .= $this->msg('<p><hr/></p>');
          //   FILE yourstore.com/config.php
			$fp = file( DIR_DOCUMENT_ROOT . 'config.php' );
          
						  if( !strpos( $content, 'HTTP_SERVER' ) ) {
							  array_splice( $fp, 1, 0, $http_server . "\r\n" );
							  array_splice( $fp, 2, 0, $https_server . "\r\n" );
						   }
					   	if( !strpos( $content, 'DIR_UPLOAD' ) ) {
								array_splice( $fp, 18, 0, $modification . "\r\n" );
								array_splice( $fp, 19, 0, $upload . "\r\n" );
						   }
						    if($this->upgrade > 2020 &&  !strpos( $content, 'DB_PORT' )) {
							  array_splice( $fp, 20, 0, $db_port . "\r\n" );
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
												    	$this->text .= '<div class="notice"><pre>'.$http_server.'</pre></div>';
								                }
												$this->text .= $this->msg( sprintf(  $this->lang['msg_config_constant'],  'HTTP_SERVER', 'config.php' ) );
								                if( $this->showOps ){
												    	$this->text .= '<div class="notice"><pre>'.$https_server.'</pre></div>';
								                }
												$this->text .= $this->msg( sprintf(  $this->lang['msg_config_constant'],  'HTTPS_SERVER', 'config.php' ) );
										   }
	                if( $this->showOps ){
					    	$this->text .= '<p><pre>'.$upload.'</pre></p>';
	                }
								$this->text .= $this->msg( sprintf(  $this->lang['msg_config_constant'],  'DIR_UPLOAD', 'config.php' ) );
	                if( $this->showOps ){
					    	$this->text .= '<p><pre>'.$modification.'</pre></p>';
	                }
							$this->text .= $this->msg( sprintf(  $this->lang['msg_config_constant'],  'DIR_MODIFICATION', 'config.php' ) );
								
					    if($this->upgrade > 2020 &&  !strpos( $content, 'DB_PORT' )) {		
		                if( $this->showOps ){
						    	$this->text .= '<p><pre>'.$db_port.'</pre></p>';
		                }
								$this->text .= $this->msg( sprintf(  $this->lang['msg_config_constant'], 'DB_PORT', $file ) );
		            }
					}else{
					//	$this->text .= $this->msg(  sprintf( $this->lang, 'msg_perm_file', 'config.php' ) );
					}
		} else {
			$this->text .= $this->msg( sprintf(  $this->lang['msg_config_uptodate'], 'config.php', '' ) );
		}
		$this->text .= $this->msg('<p><hr/></p>');
		
		// FILE yourstore.com/admin/config.php		
		$file		= $this->dirAdmin . 'config.php';
		$content2	= file_get_contents( DIR_DOCUMENT_ROOT . $file );

		if( !strpos( $content2, 'DIR_UPLOAD' ) ) {
			$fp2 = file( DIR_DOCUMENT_ROOT . $file );
		    if( !strpos( $content2, 'HTTPS_CATALOG' ) ) {
			  array_splice( $fp2, 8, 0, $https_catalog . "\r\n" );
		   }
			array_splice( $fp2, 21, 0, $modification . "\r\n" );
			array_splice( $fp2, 22, 0, $upload . "\r\n" );
		    if($this->upgrade > 2020 &&  !strpos( $content2, 'DB_PORT' )) {
			  array_splice( $fp2, 25, 0, $db_port . "\r\n" );
		   }

			$string = implode( '', $fp2 );

			if( is_writable( DIR_DOCUMENT_ROOT. $file ) ) {
				if( !$this->simulate ) {
					$fw = fopen( DIR_DOCUMENT_ROOT . $file, 'wb' );
					fwrite( $fw, $string );
					fclose( $fw ); 
                    if( !$this->structure->getUpgrade() ){
				       // Upgrade Cache
				       $memory = DIR_UPGRADE . 'upgrade_cache.log';
				       if(file_exists($memory)){
				       	 $cache = unserialize(file_get_contents($memory));
				         $cache['db'] = true;
				         $cache['files'] = false;
					  } else {
				       	$cache = array('db' => true,'files' => false );
					  }
                       if($cache){
					    $fw = fopen( $memory, 'wb' );
					    fwrite( $fw, serialize($cache) );
					    fclose( $fw );
					   }
			        }
				}

				    if( !strpos( $content2, 'HTTPS_CATALOG' ) ) {
		                if( $this->showOps ){
						    	$this->text .= '<p><pre>'.$https_catalog.'</pre></p>';
		                }
						$this->text .= $this->msg( sprintf(  $this->lang['msg_config_constant'],  'HTTPS_CATALOG', $file ) );
				   }
                if( $this->showOps ){
				    	$this->text .= '<p><pre>'.$upload.'</pre></p>';
                }
						$this->text .= $this->msg( sprintf(  $this->lang['msg_config_constant'], 'DIR_UPLOAD', $file ) );
                if( $this->showOps ){
				    	$this->text .= '<p><pre>'.$modification.'</pre></p>';
                }
						$this->text .= $this->msg( sprintf(  $this->lang['msg_config_constant'], 'DIR_MODIFICATION', $file ) );
		    if($this->upgrade > 2020 &&  !strpos( $content2, 'DB_PORT' )) {		
                if( $this->showOps ){
				    	$this->text .= '<p><pre>'.$db_port.'</pre></p>';
                }
						$this->text .= $this->msg( sprintf(  $this->lang['msg_config_constant'], 'DB_PORT', $file ) );
            }

			}else{
				$this->text .= $this->msg(  sprintf( $this->lang['msg_perm_file'], 'config.php', $file ) );
			}
		}else{
			$this->text .= $this->msg( sprintf(  $this->lang['msg_config_uptodate'], $file, '' ) );
		}

	return $this->text;
   }
  public function constantFinishing($data){
       $this->lang = $this->lmodel->get('upgrade_configuration');
       $this->simulate = ( !empty( $data['simulate'] ) ?  true:false );
       $this->dirAdmin = ( !empty( $data['dirAdmin'] ) ?  'admin':'admin' ) .'/';
       $this->showOps  = ( !empty( $data['showOps'] ) ? true:false );

        $this->text = '';

		$http_server = '//define(\'HTTP_SERVER\', \'http://';
		$http_server2 = 'define(\'HTTP_SERVER\', \'http://';
		$https_server = '//define(\'HTTPS_SERVER\', \'https://';
		$https_server2 = 'define(\'HTTPS_SERVER\', \'https://';

		// frontend
		$file = DIR_DOCUMENT_ROOT . 'config.php';
	    $content = file_get_contents( $file );

	    $content = str_replace($http_server,$http_server2,$content);
	    $content = str_replace($https_server,$https_server2,$content);

				if( !$this->simulate ) {
					$fw = fopen( $file, 'wb' );
					fwrite( $fw, $content );
					fclose( $fw );

					  // Upgrade Cache
				       $memory = DIR_UPGRADE . 'upgrade_cache.log';
				       if(file_exists($memory)){
				       	$cache = unserialize(file_get_contents($memory));
				        $cache['db'] = true;
				        $cache['files'] = true;
					  } else {
				       	$cache = array('db' => true,'files' => true );
					  }
                       if($cache){
					    $fw = fopen( $memory, 'wb' );
					    fwrite( $fw, serialize($cache) );
					    fclose( $fw );
					   }
				}

                $server = $_SERVER['HTTP_HOST'].rtrim(dirname($_SERVER['SCRIPT_NAME']));
                $server = explode('/',$server);
                array_pop($server);
                $server = implode('/',$server);
		$http_server = 'define(\'HTTP_SERVER\', \'http://' . $server . '/\'); // OC 1.4+';
		$https_server = 'define(\'HTTPS_SERVER\', \'https://' .$server . '/\'); // OC 1.4+';
                if( $this->showOps ){
				    	$this->text .= '<p><pre>'.$http_server.'</pre></p>';
                }
				$this->text .= $this->msg( sprintf(  $this->lang['msg_config_constant'],  'HTTP_SERVER', 'config.php' ) );

                if( $this->showOps ){
				    	$this->text .= '<p><pre>'.$https_server.'</pre></p>';
                }
				$this->text .= $this->msg( sprintf(  $this->lang['msg_config_constant'],  'HTTPS_SERVER', 'config.php' ) );

      return $this->text;

  }
  private function msg( $data ){
       return str_replace( $data, '<div class="msg round">' . $data .'</div>'."\r\n", $data);
  }
}
