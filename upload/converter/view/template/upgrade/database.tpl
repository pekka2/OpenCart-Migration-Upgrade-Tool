<?php echo $header; ?>
	  <ul class="breadcrumb">
	    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
	    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
	    <?php } ?>
	  </ul>

<div id="container" class="start">
	<div class="container">
		<div id="column-left">
		   <h1><img src="view/image/user.png" alt="" /> <?php echo $heading_title; ?></h1>
			<h3> <?php echo $text_version;?>   </h3>   

		 <div class="step round"><?php echo $text_step; ?></div><div class="clr"></div>
	    <div class="heading"> <h1><img src="view/image/user.png" alt="" /> <?php echo $text_table_info; ?></h1></div>
    
		<div class="block"><b><?php echo $text_intro_1; ?></b></div>
		<div class="block"><?php echo $text_intro_2; ?></div>
		<div class="block"><?php echo $text_intro_2; ?></div>
							<div class="block-big">
											<form action="<?php echo $action;?>" method="post">
														<input type="hidden" name="step" value="2" />
														<input type="hidden" name="steps" value="<?php echo $steps;?>" />
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
															<div class="block">
																<label>
																	<input type="radio" value="1564" name="upgrade"/>
																	<?php echo $entry_up_1564; ?>
																</label>
															</div>
															<div class="block">
																<label>
																	<input type="radio" value="2020" name="upgrade" />
																	<?php echo $entry_up_201_202; ?>
																</label>
															</div>
															<div class="block">
																<label>
																	<input type="radio" value="2031" name="upgrade" checked="checked"/>
																	<?php echo $entry_up_2030; ?>
																</label>
															</div>
															<div class="block">
																<label>
																	<input type="radio" value="2100" name="upgrade"/>
																	<?php echo $entry_up_2100; ?>
																</label>
															</div>
															<div class="buttons">
																<input type="submit" name="submit" value="<?php echo $btn_start; ?>" class="submit round" />
															</div>
													</form>
							</div>
		<div class="clr"></div>
		<div id="help" class="help round"><?php echo $help_usage; ?></div>
	</div>
    <div id="column-right" class="start-upgrade">
      <ul class="list-group">
        <li class="list-group-item"><b><?php echo $step_start; ?></b></li>
        <li class="list-group-item"><?php echo $step_collate; ?></li>
        <li class="list-group-item"><?php echo $step_column; ?></li>
        <li class="list-group-item"><?php echo $step_data; ?></li>
        <li class="list-group-item"><?php echo $step_module; ?></li>
        <li class="list-group-item"><?php echo $step_setting; ?></li>
        <li class="list-group-item"><?php echo $step_images; ?></li>
        <li class="list-group-item"><?php echo $step_configuration; ?></li>
        <li class="list-group-item"><?php echo $step_clean_module; ?></li>
        <li class="list-group-item"><?php echo $step_clean_table; ?></li>
      </ul>
    </div>
  </div>
  </div>
<?php echo $footer; ?>
