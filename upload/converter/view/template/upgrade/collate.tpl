<?php echo $header; ?>
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  
<div id="container">
	<div class="content">
					      <h1><img src="view/image/user.png" alt="" /> <?php echo $heading_title; ?></h1>  

<?php if(!isset($start)){?> 
						<div class="note round<?php echo $simulate ? ' bg-green' : ' bg-red'; ?>">
						<?php echo $text_simulation; ?> <span class="<?php echo $simulate ? 'green">' . $text_on : 'red">' . $text_off; ?></span>
						</div>
					   <?php if( isset( $upgrade_data ) ) { echo $upgrade_data; } ?>
<?php } ?> 
		<div id="column-left">
					<div class="msg round">
<?php if(!isset($start)){?> 
					<h2><?php echo $header_step_1; ?></h2>
<?php } ?> 
										<div class="step round"><?php echo $text_step; ?></div>
										<div class="clr"></div>
							
									    <div class="heading">
									      <h1><img src="view/image/user.png" alt="" /> <?php echo $text_collate_info; ?></h1>   
									    </div> 
										<div class="block">
											<form action="<?php echo $action; ?>" method="post">
												<input type="hidden" name="step" value="<?php echo $step;?>" />
												<input type="hidden" name="steps" value="<?php echo $steps;?>" />
												<input type="hidden" name="upgrade" value="<?php echo $upgrade; ?>" />
				    <input type="hidden" name="upgrade" value="<?php echo $upgrade;?>" />

<?php if(!isset($start)){?> 
												<input type="hidden" name="simulate" value="<?php echo $simulate; ?>" />
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
												
												<div class="buttons">
													<input type="submit" name="submit" value="<?php echo $btn_collate; ?>" class="submit round green" />
													<input type="submit" name="skip" value="<?php echo $btn_skip; ?>" class="submit round" />
												</div>
											</form>
										</div>
										<div class="clr"></div>
										</div>
		</div>
    <div id="column-right" class="upgrade">   
      <ul class="list-group">
        <li class="list-group-item"><?php echo $step_start; ?></li>
        <li class="list-group-item"><b><?php echo $step_collate; ?></b></li>
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
<?php echo $footer; ?>
