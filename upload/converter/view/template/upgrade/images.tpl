<?php echo $header;?>
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  
<div id="container">
	  <div class="content">
	<div class="content">
	<div class="msg round">

     <?php  if ($error_warning) { ?>

<div id="column-left" class="error-warning">
         <div class="warning"><?php echo $error_warning; ?></div>
			     <form action="<?php echo $previous; ?>" method="post">
				    <input type="hidden" name="step" value="<?php echo $step;?>" />
				    <input type="hidden" name="steps" value="<?php echo $steps;?>" />
				    <input type="hidden" name="simulate" value="<?php echo $simulate; ?>" />
				    <input type="hidden" name="showOps" value="<?php echo $showOps; ?>" />
				    <input type="hidden" name="back" value="1" />
				<div>
				<div class="block">
					<input type="submit" name="submit" value="<?php echo $btn_back; ?>" class="submit round green" />
				</div>
			</form>
<?php } else { ?>
	<?php if(!isset($start)){?>
    <div class="heading">
      <h1><img src="view/image/user.png" alt="" /> <?php echo $heading_config; ?></h1>   
    </div> 
</div>
<div class="note round<?php echo $simulate ? ' bg-green' : ' bg-red'; ?>">
<?php echo $text_simulation; ?> <span class="<?php echo $simulate ? 'green">' . $text_on : 'red">' . $text_off; ?></span>
</div>
 <?php if( isset( $update_configuration )){ echo $update_configuration; } ?>
 
<?php } ?>
<div id="column-left">
 <?php if( isset( $skip )){?>
   <div class="msg round"> <b><?php echo $msg_config_skipped;?></b> </div>
  <?php } ?>
	<div class="msg round images">

	<?php if(!isset($start)){?>
		<h2><?php echo $header_step; ?></h2>
    <?php } ?>

		<div class="step round"><?php echo $text_step; ?></div>
		<div class="clr"></div>

      <h1><img src="view/image/user.png" alt="" /> <?php echo $heading_title; ?></h1>   

			<form action="<?php echo $action; ?>" method="post">
				                <input type="hidden" name="step" value="<?php echo $step;?>" />
				                <input type="hidden" name="steps" value="<?php echo $steps;?>" />
 <?php if(!isset($start)){?>
				                <input type="hidden" name="simulate" value="<?php echo $simulate; ?>" />
				                <input type="hidden" name="dirImage" value="<?php echo $images; ?>" />
				                <input type="hidden" name="showOps" value="<?php echo $showOps; ?>" />
<?php } else{ ?>
															<div class="block">
																<label>
																	<input type="checkbox" value="true" name="simulate" checked="checked" />
																	<?php echo $help_simulate; ?>
																</label>
															</div>
															<div class="block">
																<label>
																	<input type="checkbox" value="true" name="showOps" />
																	<?php echo $help_ops; ?>
																</label>
															</div>
<?php } ?>
				<div>
				
				        <div class="block">
					       <?php echo $entry_imageDir; ?>&nbsp;
					       <input type="text" value="image" name="dirImage" title="<?php echo $help_imageDir; ?>" size="20" />
					       <image src="view/image/help_28x28.png" title="<?php echo $help_imageDir; ?>" alt="help" class="help_28x28"/>
				        </div>
				        <div class="buttons">
					       <input type="submit" name="submit" value="<?php echo $btn_images; ?>" class="submit green" />
					       <input type="submit" name="skip" value="<?php echo $btn_skip; ?>" class="submit round" />
				        </div>
			</form>
		</div>
<?php } ?>
		<div class="clr"></div>
	</div>
</div>
    <div id="column-right">   
      <ul class="list-group">
        <li class="list-group-item"><?php echo $step_start; ?></li>
        <li class="list-group-item"><?php echo $step_collate; ?></li>
        <li class="list-group-item"><?php echo $step_column; ?></li>
        <li class="list-group-item"><?php echo $step_data; ?></li>
        <li class="list-group-item"><?php echo $step_module; ?></li>
        <li class="list-group-item"><?php echo $step_setting; ?></li>
        <li class="list-group-item"><?php echo $step_configuration; ?></li>
        <li class="list-group-item"><b><?php echo $step_images; ?></b></li>
        <li class="list-group-item"><?php echo $step_clean_module; ?></li>
        <li class="list-group-item"><?php echo $step_clean_table; ?></li>
      </ul>
    </div>
</div>
	
<?php echo $footer;?>
