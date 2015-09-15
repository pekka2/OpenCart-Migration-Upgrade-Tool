<?php echo $header; ?>

  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
<div id="container">
<div id="content">
   
      <h1><img src="view/image/logo-30x30.png" alt="" /> <?php echo $heading_title; ?></h1> 

		<div class="buttons">
			<a href="<?php echo $logout;?>" class="button right"><?php echo $btn_logout; ?></a>
			<a href="<?php echo $permission;?>" class="button right"><?php echo $btn_permission; ?></a>
			<a href="<?php echo $upgrade_info;?>" class="button right"><?php echo $btn_info; ?></a>
			<a href="<?php echo $upgrade_start;?>" class="button right"><?php echo $btn_start; ?></a>
		</div>
		     
     <?php if( isset($upgrade_log) ){
     	     if( !$upgrade_log['files'] ){ ?>
     <div id="upgrade-log">
     	<h1><?php echo $text_unfinished;?></h1>
     	<h3><?php echo $text_finishing;?></h3>
     	<div class="buttons">
     		<a class="button" href="<?php echo $clean;?>"><?php echo $btn_finish;?></a>
     	</div>
     </div>
     	 <?php    }?>
     <?php } ?>

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
		<div class="text_description"><?php echo $text_step_2_3;?></div>
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

<?php echo $footer; ?>
