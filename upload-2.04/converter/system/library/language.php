<?php
class Language {
	private $default = 'en-US';
	public $lang = '';
	private $data = array();

	public function __construct() {
		$this->getlang = '';
	}

	public function get($key) {
		return (isset($this->data[$key]) ? $this->data[$key] : $key);
                $this->getLang();
	}
	public function getLang() {
                        $browser_language = $this->http_language();
                        $languages = $browser_language[0];

                        if( is_dir( DIR_LANGUAGE . $languages[0] ) ){
                             $this->lang = $languages[0];
                        } elseif ( isset($browser_language[2]) ) {
                             $languages2 = explode(';',$browser_language[2][0]);

                             if( is_dir( DIR_LANGUAGE . $languages2[0] ) && $languages2[0] != $this->default ){
                                $this->lang = $languages2[0];
                              } else {
                                $this->lang = $this->default;
                              }
                        } elseif ( isset($browser_language[1]) ) {
                            $languages3 = explode(';',$browser_language[1][0]);
                             if( is_dir( DIR_LANGUAGE . $languages3[0] ) && $languages3[0] != $this->default ){
                               $this->lang = $languages3[0];
                              } else {
                               $this->lang = $this->default;
                              }
                        } else {
                               $this->lang = $this->default;
                        }
                      return $this->lang;
	}
        public function http_language(){
            $array = array();
                foreach (explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']) as $lang) {
                        $pattern = '/^(?P<primarytag>[a-zA-Z]{2,8})'.
                                   '(?:-(?P<subtag>[a-zA-Z]{2,8}))?(?:(?:;q=)'.
                                   '(?P<quantifier>\d\.\d))?$/';
                         $splits = array();

                  if (preg_match($pattern, $lang, $splits)) {
                     $array[] = $splits;
                   } 
               }
              return $array;
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


		$file = DIR_LANGUAGE . $this->default . '/' . $filename . '.php';

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
