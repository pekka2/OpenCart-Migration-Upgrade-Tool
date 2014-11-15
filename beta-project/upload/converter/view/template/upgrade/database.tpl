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
					<?php echo $entry_adminDir; ?>
					&nbsp;
					<input type="text" value="admin" name="dirAdmin" title="<?php echo $help_adminDir; ?>" size="20" />
				</div>
				<div class="block">
					<?php echo $entry_oldDir; ?>
					&nbsp;
					<input type="text" value="" name="dirOld" title="<?php echo $help_oldDir; ?>" placeholder="<?php echo $_SERVER['DOCUMENT_ROOT'];?>" size="60" />
<?php echo sprintf($text_exa_store_path, $_SERVER['SCRIPT_FILENAME']);?>
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
   <?php if( isset( $add_setting151newer ) ) { echo $add_setting151newer; } ?>
   <?php if( isset( $add_setting1505parent ) ) { echo $add_setting1505parent; } ?>
   <?php if( isset( $delete_settings ) ) { echo $delete_settings; } ?>

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
				<input type="hidden" name="dirOld" value="<?php echo $dirOld; ?>" />
				<input type="hidden" name="showOps" value="<?php echo $showOps; ?>" />
				<input type="hidden" name="dirImage" value="<?php echo $images; ?>" />
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
