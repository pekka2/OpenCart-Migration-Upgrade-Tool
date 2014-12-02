<?php
$root	= dirname( dirname( __FILE__ ) ) . '/';

require( $root . 'config.php' );

function getDbColumns( ) {

                $link = mysql_connect( DB_HOSTNAME, DB_USERNAME, DB_PASSWORD );
                $db_selected = mysql_select_db( DB_DATABASE );

                $fields = mysql_query("SHOW COLUMNS FROM " . DB_PREFIX . "user");
		
		$ret = array();

               while( $field = mysql_fetch_assoc($fields)){
                 $ret[] = $field['Field'];
               }

  return $ret;
}
if( !array_search('salt', getDbColumns() ) ){

  $user_salt = 0;

} else {

  $user_salt = 1;


}
if( isset($_POST['config']) ){
$output  = '<?php' . "\r\n";
$output .= 'define(\'HTTP_SERVER\', \'http://' . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['SCRIPT_NAME']), '/.\\') . '/' . '\');' . "\r\n";
$output .= 'define(\'HTTPS_SERVER\', \'https://' . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['SCRIPT_NAME']), '/.\\') . '/' . '\');' . "\r\n";
$output .= "\r\n";

$output .= '// DIR' . "\r\n";
$output .= 'define(\'DIR_DOCUMENT_ROOT\', \'' . $root . '/\');' . "\r\n";
$output .= 'define(\'DIR_APPLICATION\', \'' . $root . 'converter/\');' . "\r\n";
$output .= 'define(\'DIR_SYSTEM\', \'' . $root . 'converter/system/\');' . "\r\n";
$output .= 'define(\'DIR_DATABASE\', \'' . $root . 'converter/system/database/\');' . "\r\n";
$output .= 'define(\'DIR_LANGUAGE\', \'' . $root . 'converter/language/\');' . "\r\n";
$output .= 'define(\'DIR_TEMPLATE\', \'' . $root . 'converter/view/template/\');' . "\r\n";
$output .= 'define(\'DIR_CONFIG\', \'' . $root . 'converter/system/config/\');' . "\r\n";
$output .= 'define(\'DIR_IMAGE\', \'' . $root . 'converter/image/\');' . "\r\n";
$output .= 'define(\'DIR_STORE_IMAGE\', \'' . DIR_IMAGE . '\');' . "\r\n";
$output .= 'define(\'DIR_UPLOAD\', \'' . DIR_SYSTEM . 'upload/\');' . "\r\n";
$output .= 'define(\'DIR_MODIFICATION\', \'' . DIR_SYSTEM . 'modification/\');' . "\r\n";
$output .= 'define(\'DIR_CACHE\', \'' . $root . 'converter/system/cache/\');' . "\r\n";
$output .= 'define(\'DIR_LANGUAGE_MODEL\', \'' . $root . 'converter/system/language_model/\');' . "\r\n";
$output .= 'define(\'DIR_LOGS\', \''. $root . 'converter/system/logs/\');' . "\r\n";
$output .= "\r\n";
$output .= '// DB' . "\r\n";
$output .= 'define(\'DB_DRIVER\', \'mysqli\');' . "\r\n";
$output .= 'define(\'DB_HOSTNAME\', \'' . DB_HOSTNAME . '\');' . "\r\n";
$output .= 'define(\'DB_USERNAME\', \'' . DB_USERNAME . '\');' . "\r\n";
$output .= 'define(\'DB_PASSWORD\', \'' . DB_PASSWORD . '\');' . "\r\n";
$output .= 'define(\'DB_DATABASE\', \'' . DB_DATABASE . '\');' . "\r\n";
$output .= 'define(\'DB_PREFIX\', \'' . DB_PREFIX . '\');' . "\r\n";
$output .= 'define(\'DB_USER_SALT\', \'' . $user_salt . '\');' . "\r\n";
$output .= '?>';

$fw = fopen("config.php","w") or die (error());
fwrite($fw,$output);
fclose($fw);

}

if( !file_exists('config.php') ) {
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8" />
<title>DB Converter OpenCart 1.5.x - 2.x</title>
<link rel="stylesheet" type="text/css" href="view/stylesheet/stylesheet.css" />
<script type="text/javascript" src="view/javascript/jquery/jquery-1.7.1.min.js"></script>

</head>
<body>
 <div class="container round">
 
<div class="msg round">
<form action="start.php" method="post" id="config">
<input type="hidden" name="config" value="1"/>
<h2><a onclick="$('#config').submit();" class="button center">Start by Creating file config.php</a></h2>
</form>
</div>
</div>
</body></html>
<?php } else {

  header("Location:index.php");

}
?>
