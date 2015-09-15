<?php echo $header;?>
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>

<div id="container">
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
				    <input type="hidden" name="upgrade" value="<?php echo $upgrade;?>" />
				    <input type="hidden" name="back" value="1" />
				<div>
				<div class="block">
					<input type="submit" name="submit" value="<?php echo $btn_back; ?>" class="submit round green" />
				</div>
			</form>
<?php } else { ?>
    <div class="heading">
      <h1><img src="view/image/user.png" alt="" /> <?php echo $heading; ?></h1>   
    </div> 
</div>
<?php if(!isset($start)){?>
<div class="note round<?php echo $simulate ? ' bg-green' : ' bg-red'; ?>">
<?php echo $text_simulation; ?> <span class="<?php echo $simulate ? 'green">' . $text_on : 'red">' . $text_off; ?></span>
</div>

 <?php if( isset( $update_configuration )){ echo $update_configuration; } ?>
<?php } ?> 
<div id="column-left">
 <?php if( isset( $skip )){?>
   <div class="msg round"> <b><?php echo $msg_config_skipped;?></b> </div>
  <?php } ?>
	<div class="msg round">

		<h2><?php echo $header_step; ?></h2>
		<div class="step round"><?php echo $text_step; ?></div>
		<div class="clr"></div>

      <h1><img src="view/image/user.png" alt="" /> <?php echo $heading_title; ?></h1>
			<form action="<?php echo $action; ?>" method="post">
				                <input type="hidden" name="step" value="<?php echo $step;?>" />
				                <input type="hidden" name="steps" value="<?php echo $steps;?>" />
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
				<div>
				<?php if(isset($text_pause) && $admin == false){?>
				        <input type="hidden" name="simulate" value="1" />	
		                <div class="block">
		                    <div class="pause"><?php echo $text_pause;?></div>
		                    <div class="upload"> <?php echo $text_upload;?></div>
		                </div>
				        <div class="block">
					        <input type="submit" name="preview" value="<?php echo $btn_preview; ?>" class="submit round green" />
					        <input type="submit" name="skip" value="<?php echo $btn_skip; ?>" class="submit round" />
				        </div>
			<?php 	} else { ?>
				        <div class="buttons">
					       <input type="submit" name="submit" value="<?php echo $btn_clean; ?>" class="submit green" />
					       <input type="submit" name="skip" value="<?php echo $btn_skip; ?>" class="submit round" />
				        </div>
					<?php } ?>
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
        <li class="list-group-item"><?php echo $step_images; ?></li>
        <li class="list-group-item"><?php echo $step_configuration; ?></li>
        <li class="list-group-item"><b><?php echo $step_clean_module; ?></b></li>
        <li class="list-group-item"><?php echo $step_clean_table; ?></li>
      </ul>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function(){         
            $(".notice").attr("title", "<?php echo $help_config_1_4;?>");
    });
</script> 	
<?php echo $footer;?>
