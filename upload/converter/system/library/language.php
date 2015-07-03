<?php
class Language {
	private $default = 'en-US';
	public $lang = '';
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
                      
                        if(is_dir(DIR_LANGUAGE . $browser_languages[0])){
                             $this->lang = $browser_languages[0];
                        } else {
                             $this->lang = $this->default;
                        }
                      return $this->lang;
             
	}

	public function load($filename) {
                $language = $this->getLang();
		$file = DIR_LANGUAGE . $language . '/' . $filename . '.php';

		if (file_exists($file)) {
			$_ = array();

			require($file);

			$this->data = array_merge($this->data, $_);

			return $this->data;
		}

                if( preg_match( '/\//', $filename) ){
                        $file = DIR_LANGUAGE . $language . '/' . $filename . '.php';
                } else {
                        $file = DIR_LANGUAGE . $this->default . '/' .  $filename . '.php';
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
