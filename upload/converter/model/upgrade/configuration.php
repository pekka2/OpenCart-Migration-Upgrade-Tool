<?php
class ModelUpgradeConfiguration extends Model{
    private $lang;
   public function editConfig( $simulate, $dirOld = '', $dirAdmin ){
       $this->lang = $this->lmodel->get('upgrade_configuration');
        $text = '';

		$modification = 'define(\'DIR_MODIFICATION\', \'' . DIR_MODIFICATION . '\'); // OC 2';
		$upload = 'define(\'DIR_UPLOAD\', \'' . DIR_UPLOAD . '\'); // OC 2';

		// frontend
	    $content = file_get_contents( DIR_DOCUMENT_ROOT . 'config.php' );

	    if( $dirOld ) {
	    	if( strpos( $content, $dirOld ) !== false ) {
		    	$content = str_replace( $dirOld, $root, $content );
		    	file_put_contents( $root . 'config.php', $content );
				$text .= $this->msg( sprintf(  $this->lang, 'config_replace',  'config.php', '' ) );
			}
	    }

		if( !strpos( $content, 'DIR_UPLOAD' ) ) {
			$fp = file( DIR_DOCUMENT_ROOT . 'config.php' );
			array_splice( $fp, 18, 0, $modification . "\r\n" );
			array_splice( $fp, 19, 0, $upload . "\r\n" );
			$content = implode( '', $fp );

			if( is_writable( DIR_DOCUMENT_ROOT . 'config.php' ) ) {
				if( !$simulate ) {
		    	$content = str_replace( '/system/database/', '/system/library/db/', $content );
					$fw = fopen( DIR_DOCUMENT_ROOT . 'config.php', 'wb' );
					fwrite( $fw, $content );
					fclose( $fw );
				}

				$text .= $this->msg( sprintf(  $this->lang['msg_config_added'],  'DIR_UPLOAD', 'config.php' ) );
				$text .= $this->msg( sprintf(  $this->lang['msg_config_added'],  'DIR_MODIFICATION', 'config.php' ) );
			}else{
				$text .= $this->msg->displayError(  $this->lang, 'msg_perm_file', 'config.php' );
			}
		}else{
			$text .= $this->msg( sprintf(  $this->lang['msg_config_uptodate'], 'config.php', '' ) );
		}

		// backend
		$file		= $dirAdmin . '/config.php';
		$content	= file_get_contents( DIR_DOCUMENT_ROOT . $file );

		if( $dirOld ) {
			if( strpos( $content, $dirOld ) !== false ) {
		    	$content = str_replace( $dirOld, $root, $content );
			if( !$simulate ) {
		    	   file_put_contents( DIR_DOCUMENT_ROOT . $file, $content );
                        }
		    	 $text .= $this->msg( sprintf(  $this->lang['msg_config_replace'], 'config.php', '' ) );
			}
	    }

		if( !strpos( $content, 'DIR_UPLOAD' ) ) {
			$fp2 = file( DIR_DOCUMENT_ROOT . $file );
			array_splice( $fp2, 21, 0, $modification . "\r\n" );
			array_splice( $fp2, 22, 0, $upload . "\r\n" );
			$content = implode( '', $fp2 );

			if( is_writable( DIR_DOCUMENT_ROOT. $file ) ) {
				if( !$simulate ) {
		    	$content = str_replace( '/system/database/', '/system/library/db/', $content );
					$fw = fopen( DIR_DOCUMENT_ROOT . $file, 'wb' );
					fwrite( $fw, $content );
					fclose( $fw );
				}

				$text .= $this->msg( sprintf(  $this->lang['msg_config_constant'], 'DIR_UPLOAD', $file ) );
				$text .= $this->msg( sprintf(  $this->lang['msg_config_constant'], 'DIR_MODIFICATION', $file ) );
			}else{
				$text .= $this->msg->displayError(  $this->lang['msg_perm_file'], 'config.php', $file );
			}
		}else{
			$text .= $this->msg( sprintf(  $this->lang['msg_config_uptodate'], $file, '' ) );
		}

	return $text;
   }
  public function msg( $data ){
       return str_replace( $data, '<div class="msg round">' . $data .'</div>', $data);
  }
}
