<?php echo $header; ?>
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  
<div id="container">
	<div class="content">
<div class="msg round">
    <div class="heading">
      <h1><img src="view/image/user.png" alt="" /> <?php echo $heading_database; ?></h1>   

    </div> 
</div>

<?php if(!isset($start) && !isset($back)){?>
<div class="note round<?php echo $simulate ? ' bg-green' : ' bg-red'; ?>">
<?php echo $text_simulation; ?> <span class="<?php echo $simulate ? 'green">' . $text_on : 'red">' . $text_off; ?></span>
</div>

   <?php if( isset( $add_settings ) ) { echo $add_settings; } ?>
 <?php } ?>
</div>

		<div id="column-left">
<div class="msg round">

<?php if(!isset($start) && !isset($back)){?>
		<h2><?php echo $header_step_setting; ?></h2>
 <?php } ?>
		<div class="step round"><?php echo $text_step; ?></div>
		
		<div class="clr"></div>
		
      <h1><img src="view/image/user.png" alt="" /> <?php echo $text_configuration_info; ?></h1>  
    
		<div class="clr"></div>
		<div class="block">
			<form action="<?php echo $action; ?>" method="post">
				<input type="hidden" name="step" value="<?php echo $step;?>" />
				<input type="hidden" name="steps" value="<?php echo $steps;?>" />
				<input type="hidden" name="simulate" value="<?php echo $simulate; ?>" />
				<input type="hidden" name="showOps" value="<?php echo $showOps; ?>" />
				<input type="hidden" name="upgrade" value="<?php echo $upgrade; ?>" />
				<div class="block">
					<?php echo $entry_adminDir; ?>
					&nbsp;
					<input type="text" value="admin" name="dirAdmin" title="<?php echo $help_adminDir; ?>" size="20" />
			      <image src="view/image/help_28x28.png" title="<?php echo $help_adminDir; ?>" alt="help" class="help_28x28"/>
				</div>
				<div class="buttons">
					<input type="submit" name="submit" value="<?php echo $btn_config; ?>" class="submit round green" />
					<input type="submit" name="skip" value="<?php echo $btn_skip; ?>" class="submit round" />
				</div>
			</form>
		</div>
		<div class="clr"></div>
	</div>
</div>
    <div id="column-right">   
      <ul class="list-group">
        <li class="list-group-item"><?php echo $step_start; ?></li>
        <li class="list-group-item"><?php echo $step_collate; ?></li>
        <li class="list-group-item"><?php echo $step_column; ?><</li>
        <li class="list-group-item"><?php echo $step_data; ?></li>
        <li class="list-group-item"><?php echo $step_module; ?></li>
        <li class="list-group-item"><?php echo $step_setting; ?></li>
        <li class="list-group-item"><b><?php echo $step_configuration; ?></b></li>
        <li class="list-group-item"><?php echo $step_images; ?></li>
        <li class="list-group-item"><?php echo $step_clean_module; ?></li>
        <li class="list-group-item"><?php echo $step_clean_table; ?></li>
      </ul>
    </div>
</div>

<?php echo $footer; ?>
