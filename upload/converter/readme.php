<?php
$dir = 'language/';

$languages = array();
$open = opendir($dir);
 while(($file = readdir($open) ) != false) {  
   if( is_dir( $dir. $file ) && $file !='.' && $file !='..'){
     $languages[$file] = $dir. $file . '/';
   }
 }
   closedir($open);
$default = 'en-US';
if( !isset($_GET['lang']) ){
   $file = $languages[$default];
   $fp = $default;
}
if( isset($_GET['lang']) ){
  if( file_exists($languages[$_GET['lang']] ) ){
    $file = $languages[$_GET['lang']];
    $fp = $_GET['lang'];
  } else {
    $file = $languages[$default];
    $fp = $default;
  }
}

require_once($file. 'readme.php');
require_once($file. $fp . '.php');
require_once($file. 'common/footer.php');
extract($_);
$ex = explode('-',$code);
$lang = $ex[0];
?>

<!DOCTYPE html>
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<head>
<meta charset="UTF-8" />
<title>README</title>
<link rel="stylesheet" type="text/css" href="view/stylesheet/stylesheet.css" />
<link rel="stylesheet" type="text/css" href="view/stylesheet/tutorial.css" />
</head>
<body>
 <div class="container round">
<form action="readme.php" method="get">

<select name="lang" style="width:20%;">
<option> -- Change language -- </option>
   <?php
    $keys = array_keys($languages);
   for( $i=0;$i<count($keys); $i++ ){?>
      <option value="<?php echo $keys[$i];?>"><?php echo $keys[$i];?></option>
   <?php } ?>
</select>
<input type="submit" name="status" value="OK"/>
</form>

<div class="tutorial" style="background:#666666;padding:3%;margin-top:20px;">
    <div class="content">
     <h1><?php echo $text_title_help;?></h1>
     
     
		<div class="sub_title"><?php echo $sub_title_1;?></div>
		<div class="text_description"><?php echo $text_description;?></div>
		<div class="sub_title"><?php echo $sub_title_2;?></div>
		<div class="text_description"><?php echo $text_features_1;?></div>
		<div class="text_description"><?php echo $text_features_2;?></div>
		<div class="text_description"><?php echo $text_features_3;?></div>
		<div class="text_description"><?php echo $text_features_4;?></div>
		<div class="text_description"><?php echo $text_features_5;?></div>
		<div class="text_description"><?php echo $text_features_6;?></div>
		<div class="text_description"><?php echo $text_features_7;?></div>
		<div class="text_description"><?php echo $text_features_8;?></div>
		<div class="text_description"><?php echo $text_features_9;?></div>
		<div class="text_description"><?php echo $text_features_10;?></div>
		<div class="text_description"><?php echo $text_features_11;?></div>
		<div class="sub_title"><?php echo $sub_title_3;?></div>
		<div class="text_description"><?php echo $text_install;?></div>
		<div class="sub_title"><?php echo $sub_title_4;?></div>
		<div class="sub_title"><?php echo $sub_title_6;?></div>
		<div class="text_description"><?php echo $text_functions;?></div>
		<div class="sub_title"><?php echo $sub_title_7;?></div>
		<div class="text_description"><?php echo $text_permissions;?></div>
		<pre><?php include("dir.txt");?></pre>
		<div class="sub_title"><?php echo $text_steps;?></div>
		<div class="sub_title"><?php echo $sub_title_8;?></div>
		<div class="text_description"><?php echo $text_step_1_1;?></div>
		<div class="text_description"><?php echo $text_step_1_2;?></div>
		<div class="text_description"><?php echo $text_step_1_3?></div>
		<div class="text_description"><?php echo $text_step_1_4;?></div>
		<div class="text_description"><?php echo $text_step_1_5;?></div>
		<div class="text_description"><?php echo $text_step_1_6;?></div>
		<div class="text_description"><?php echo $text_step_1_7;?></div>
		<div class="text_description"><?php echo $text_step_1_8;?></div>
		<div class="text_description"><?php echo $text_step_1_9;?></div>
		<div class="text_description"><?php echo $text_step_1_10;?></div>
		<div class="sub_title"><?php echo $sub_title_9;?></div>
		<div class="text_description"><?php echo $text_step_2_1;?></div>
		<div class="text_description"><?php echo $text_step_2_2;?></div>
		<div class="sub_title"><?php echo $sub_title_10;?></div>
		<div class="text_description"><?php echo $text_step_3_1;?></div>
		<div class="text_description"><?php echo $text_step_3_2;?></div>
		<div class="sub_title"><?php echo $sub_title_11;?></div>
		<div class="text_description"><?php echo $text_plan_1_1;?></div>
		<div class="text_description"><?php echo $text_plan_1_2;?></div>
		<div class="text_description"><?php echo $text_plan_1_3;?></div>
		<div class="text_description"><?php echo $text_plan_1_4;?></div>
		<div class="text_description"><?php echo $text_plan_1_5;?></div>
		<div class="text_description"><?php echo $text_plan_2_1;?></div>
		<div class="text_description"><?php echo $text_plan_2_2;?></div>
		<div class="text_description"><?php echo $text_plan_2_3;?></div>
		<div class="text_description"><?php echo $text_plan_2_4;?></div>
		<div class="text_description"><?php echo $text_plan_2_5;?></div>
		<div class="text_description"><?php echo $text_plan_2_6;?></div>
		<div class="text_description"><?php echo $text_plan_2_7;?></div>
		<div class="text_description"><?php echo $text_plan_2_8;?></div>
		<div class="text_description"><?php echo $text_plan_3_1;?></div>
		<div class="sub_title"><?php echo $sub_title_12;?></div>
		<div class="text_description"><?php echo $text_repeat_1_1;?></div>
		<div class="text_description"><?php echo $text_repeat_2_1;?></div>
		<div class="text_description"><?php echo $text_repeat_2_2;?></div>
		<div class="text_description"><?php echo $text_repeat_2_3;?></div>
		<div class="text_description"><?php echo $text_repeat_2_4;?></div>
		<div class="text_description"><?php echo $text_repeat_2_5;?></div>
		<div class="text_description"><?php echo $text_repeat_2_6;?></div>
		<div class="text_description"><?php echo $text_repeat_3_1;?></div>
		<div class="text_description"><?php echo $text_repeat_3_2;?></div>
		<div class="text_description"><?php echo $text_repeat_3_3;?></div>
		<div class="text_description"><?php echo $text_repeat_4_1;?></div>
		<div class="text_description"><?php echo $text_repeat_4_2;?></div>
		<div class="text_description"><?php echo $text_repeat_4_3;?></div>
		<div class="text_description"><?php echo $text_repeat_4_4;?></div>
		<div class="text_description"><?php echo $text_repeat_4_5;?></div>
		<div class="text_description"><?php echo $text_repeat_4_6;?></div>
		<div class="text_description"><?php echo $text_repeat_4_7;?></div>
		<div class="text_description"><?php echo $text_repeat_4_8;?></div>
		<div class="text_description"><?php echo $text_repeat_4_9;?></div>
		<div class="text_description"><?php echo $text_repeat_4_10;?></div>
		<div class="text_description"><?php echo $text_repeat_4_11;?></div>
		<div class="text_description"><?php echo $text_repeat_4_12;?></div>
		<div class="text_description"><?php echo $text_repeat_4_13;?></div>
		<div class="text_description"><?php echo $text_repeat_4_14;?></div>
		<div class="text_description"><?php echo $text_repeat_4_15;?></div>
		<div class="text_description"><?php echo $text_repeat_4_16;?></div>
		<div class="text_description"><?php echo $text_repeat_4_17;?></div>
		<div class="sub_title"><?php echo $sub_title_14;?></div>
		<div class="text_description"><?php echo $text_email;?></div>
		<div class="sub_title"><?php echo $sub_title_15;?></div>
		<div class="text_description"><?php echo $text_warrantly;?></div>
		<hr/>
		<div class="text_description"><?php echo $text_top_help;?></div>
		<div class="text_description"><?php echo $text_author;?></div>
		<div class="text_description"><?php echo $text_top_help_2;?></div>
		
    </div>
  </div>
</div>
<div class="footer">
    <div class="note round">
			<b><a href="../index.php" class="link" title="<?php echo $text_to_my_store; ?>"><?php echo $text_to_my_store; ?></a></b>
			<a href="https://github.com/pekka2/OpenCart-Migration-Upgrade-Tool" class="link" title="Home of Converter" target="_blank">Home of Converter</a>
			<a href="http://www.opencart.com/index.php?route=extension/extension&amp;filter_username=peku" class="link" title="Extensions from Peku" target="_blank">Extensions from Peku</a>
			<a href="http://www.opencart.com/index.php?route=extension/extension&amp;filter_username=osworx" class="link" title="Extensions from OSWorX" target="_blank">Extensions from OSWorX</a>
			<a href="http://osworx.net" class="link" title="OSWorX" target="_blank">More of OSWorX</a>
		</div>
		<div style="color: #606060;"><?php echo $text_footer;?></div>
	</div>
</div>
</div>
</body></html>
