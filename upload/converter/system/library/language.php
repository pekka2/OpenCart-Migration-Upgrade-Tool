<?php
class Language {
	private $default = 'en-US';
	public $getlang = '';
	private $data = array();

	public function __construct($directory) {
		$this->directory = $directory;
	}

	public function get($key) {
		return (isset($this->data[$key]) ? $this->data[$key] : $key);
                                              $this->getLang();
	}
	public function getLang() {
                        $browser_languages = explode( ',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
                        if( isset($browser_languages[2]) ){
                               $language = explode(';',$browser_languages[2]);
                        } elseif( isset($browser_languages[1]) ) {
                                $language = explode(';',$browser_languages[1]);
                         } else {
                             $browser_languages2 = explode( ';', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
                              if( isset($browser_languages2[2]) ){
                                      $language = explode(';',$browser_languages2[2]);
                               } elseif( isset($browser_languages2[1]) ) {
                                       $language = explode(';',$browser_languages2[1]);
                               } else {
                                       $language = $browser_languages2[0]);
                               }
                    }
                        $arr_language = explode('-',$language[0]);
                        if(isset($arr_language[1])){
                                $arr_language[1] = strtoupper($arr_language[1]);
                        $this->getlang = implode("-",$arr_language);
                        } else {
                        	$this->getlang = $this->default;
                        }
             
	}

	public function load($filename) {
		$file = DIR_LANGUAGE . $this->getlang . '/' . $filename . '.php';

		if (file_exists($file)) {
			$_ = array();

			require($file);

			$this->data = array_merge($this->data, $_);

			return $this->data;
		}

                 if( preg_match( '/\//', $filename) ){
                        $file = DIR_LANGUAGE . $this->default . '/' . $filename . '.php';
                } else {
                        $file = DIR_LANGUAGE . $this->default . '/' .  $this->default . '.php';
                }

		if (file_exists($file)) {
			$_ = array();

			require($file);

			$this->data = array_merge($this->data, $_);

			return $this->data;
		} else {
			exit('<b>Error: Could not load language ' . $file . '!</b>');
		}
	}
}
?>
