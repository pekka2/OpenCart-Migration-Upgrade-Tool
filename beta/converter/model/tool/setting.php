<?php
class ModelToolSetting extends Database {
  public function version() {

	$php	= file( DIR_ROOT . 'index.php' );
	$line	= '';
	$str	= 'define';

	for( $i = 0; $i < 7; ++$i ){
		if( substr_count( $php[$i], $str ) ) {
			$line = $php[$i];
		}
	}

	$version = explode( '\'', $line );
	$version = $version[3];

	return $version;

	
   }
  public function simulation() {

	$php	= file( DIR_ROOT . 'index.php' );
	$line	= '';
	$str	= 'define';

	for( $i = 0; $i < 7; ++$i ){
		if( substr_count( $php[$i], $str ) ) {
			$line = $php[$i];
		}
	}

	$version = explode( '\'', $line );
	$version = $version[3];

	return $version;

	
   }
}
