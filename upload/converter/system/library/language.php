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
                        $language = explode(';',$browser_languages[2]);
                        $arr_language = explode('-',$language[0]);
                        $arr_language[1] = strtoupper($arr_language[1]);
             
                        $this->getlang = implode("-",$arr_language);
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
                        $file = DIR_LANGUAGE . $this->default . '/' . $this->default . '.php';
                } else {
                        $file = DIR_LANGUAGE . $this->default . '/' . $filename . '.php';
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
