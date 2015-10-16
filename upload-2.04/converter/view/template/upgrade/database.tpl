<?php echo $header; ?>
<?php if( !isset($step1 ) ){?>
<script type="text/javascript">
	/* <![CDATA[ */
    function toggle_visibility(id) {
        var e = document.getElementById(id);
        if ( e.style.display == 'block' ) {
            e.style.display = 'none';
        }else{
            e.style.display = 'block';
        }
    };
    /* ]]> */
</script>
<div class="msg round">

  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>


    <div class="heading">
      <h1><img src="view/image/user.png" alt="" /> <?php echo $heading_title; ?></h1>   

    </div> 
	<div class="msg round">
        <?php if( !isset( $upgrade_data ) || isset( $simulate ) ){?>
		<div class="step round"><?php echo $text_step_1_3; ?></div><div class="clr"></div>
		<div class="block"><b><?php echo $text_intro_1; ?></b></div>
		<div class="block"><?php echo $text_intro_2; ?></div>
		<div class="block"><?php echo $text_intro_2; ?></div>
		<div class="block-big">
			<form action="<?php echo $database;?>" method="post">

				<div class="block">
					<label>
						<input type="checkbox" value="1" name="simulate" checked="checked" />
						<?php echo $help_simulate; ?>
					</label>
				</div>
				<div class="block">
					<label>
						<input type="checkbox" value="1" name="showOps" checked="checked"  />
						<?php echo $help_ops; ?>
					</label>
				</div>
				
				<div class="block">
					<label>
						<input type="checkbox" value="1" name="upgrade2020" />
						<?php echo $entry_up_201_202; ?>
					</label>
				</div>
				<div class="block">
					<label>
						<input type="checkbox" value="1" name="upgrade2030" />
						<?php echo $entry_up_2030; ?>
					</label>
				</div>
				<div class="block">
					<label>
						<input type="checkbox" value="1" name="upgrade2101" />
						<?php echo $entry_up_2101; ?>
					</label>
				</div>
				<div class="block">
					<label>
						<input type="checkbox" value="true" name="modules" />
						<?php echo $entry_migration_module; ?>
					</label>
				</div>
				<div class="block">
					<label><?php echo $text_update_theme;?>
					<select name="theme">
                                              <option><?php echo $text_skip_theme;?></option> 
                                           <?php foreach($themes as $theme){?>
                                            <?php if($theme['name'] == $config_theme){?>
                                              <option value="<?php echo $theme['name'];?>" selected="selected"><?php echo $theme['name'];?></option> 
                                            <?php } else{?>
                                              <option value="<?php echo $theme['name'];?>"><?php echo $theme['name'];?></option> 
                                            <?php } ?>
                                            <?php } ?>
                                          </select>
					</label>
				</div>
				<div class="block">
					<?php echo $entry_adminDir; ?>
					&nbsp;
					<input type="text" value="admin" name="dirAdmin" title="<?php echo $help_adminDir; ?>" size="20" />
				</div>
				<div class="block">
					<?php echo $entry_imageDir; ?>
					&nbsp;
					<input type="text" value="image" name="dirImage" title="<?php echo $help_imageDir; ?>" size="20" />
				</div>
				<div class="buttons">
					<input type="hidden" name="lang" value="<?php echo $langCur; ?>" />
					<input type="hidden" name="step1" value="update" />
					<input type="submit" name="submit" value="<?php echo $btn_start; ?>" class="submit round" />
				</div>
			</form>
		</div>
		<div class="clr"></div>
		<div class="note block-big"><a onclick="toggle_visibility('help');"><?php echo $text_toggle_help; ?></a></div>
		<div id="help" class="help round"><?php echo $help_usage; ?></div>
	</div>
       <?php } ?>
    </div>
       <?php } ?>
<?php if( isset( $step1 ) ){?>

<div class="note round<?php echo $simulate ? ' bg-green' : ' bg-red'; ?>">
<?php echo $text_simulation; ?> <span class="<?php echo $simulate ? 'green">' . $text_on : 'red">' . $text_off; ?></span>
</div>

   <?php if( isset( $upgrade_data ) ) { echo $upgrade_data; } ?>
   <?php if( isset( $change_taxrate ) ) { echo $change_taxrate; } ?>
   <?php if( isset( $add_columns ) ) { echo $add_columns; } ?>
   <?php if( isset( $drop_columns ) ) { echo $drop_columns; } ?>
   <?php if( isset( $change_columns ) ) { echo $change_columns; } ?>
   <?php if( isset( $drop_tables ) ) { echo $drop_tables; } ?>
   <?php if( isset( $add_settings ) ) { echo $add_settings; } ?>

<div class="msg round">

    <div class="heading">
      <h1><img src="view/image/user.png" alt="" /> <?php echo $text_upgrade_info; ?></h1>   

    </div> 
		<div class="step round"><?php echo $text_step_2_3; ?></div>
		<div class="clr"></div>
		<h2><?php echo $header_step_2; ?></h2>
		<div class="block"><?php echo $text_update_config; ?></div>
		<div class="block">
			<form action="<?php echo $configuration; ?>" method="post">
				<input type="hidden" name="lang" value="<?php echo $langCur; ?>" />
				<input type="hidden" name="step2" value="update" />
				<input type="hidden" name="simulate" value="<?php echo $simulate; ?>" />
				<input type="hidden" name="showOps" value="<?php echo $showOps; ?>" />
				<input type="hidden" name="dirImage" value="<?php echo $images; ?>" />
				<input type="hidden" name="upgrade2020" value="<?php echo $upgrade2020; ?>" />
				<input type="hidden" name="upgrade2030" value="<?php echo $upgrade2030; ?>" />
				<input type="hidden" name="upgrade2101" value="<?php echo $upgrade2101; ?>" />
				<div class="buttons">
					<input type="submit" name="submit" value="<?php echo $btn_config; ?>" class="submit round green" />
					<input type="submit" name="skip" value="<?php echo $btn_skip; ?>" class="submit round" />
				</div>
			</form>
		</div>
		<div class="clr"></div>
	</div>
<?php } ?>
</div>
<?php echo $footer; ?>
