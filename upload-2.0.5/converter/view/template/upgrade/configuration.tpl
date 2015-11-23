<?php echo $header;?>

<div class="note round<?php echo $simulate ? ' bg-green' : ' bg-red'; ?>">
<?php echo $text_simulation; ?> <span class="<?php echo $simulate ? 'green">' . $text_on : 'red">' . $text_off; ?></span>
</div>

 <?php if( isset( $step2 )){?>
    <?php echo $upgrade_data;?>
  <?php } ?>
 <?php if( isset( $skip )){?>
   <div class="msg round"> <b><?php echo $msg_config_skipped;?></b> </div>
  <?php } ?>
	<div class="msg round">

    <div class="heading">
      <h1><img src="view/image/user.png" alt="" /> <?php echo $text_images_info; ?></h1>   

    </div> 
		<div class="step round"><?php echo $text_step_3_3; ?></div>
		<div class="clr"></div>
		<h2><?php echo $header_step_3; ?></h2>
		<div class="block"><?php echo $text_intro_step_3; ?></div>
		<div class="block">
			<form action="<?php echo $imagepaths; ?>" method="post">
				<input type="hidden" name="lang" value="<?php echo $langCur; ?>" />
				<input type="hidden" name="step3" value="update" />
				<input type="hidden" name="simulate" value="<?php echo $simulate; ?>" />
				<input type="hidden" name="dirOld" value="<?php echo $dirOld; ?>" />
				<input type="hidden" name="dirImage" value="<?php echo $images; ?>" />
				<input type="hidden" name="showOps" value="<?php echo $showOps; ?>" />
				<div>
					<?php echo $entry_perms; ?>
					<select name="permission">
						<?php
						if( file_exists( $data ) ) {
							$perm2 = substr( sprintf( '%o', fileperms( $path ) ), -4 );
						}else{
							$perm2 = $permission;
						}

						foreach( $perm as $p ) { ?>
							<option value="0<?php echo $p;?>"<?php echo ( $p == $permission ) ? ' selected="selected"' : ''; ?>><?php echo $p . ( ( $p == $permission ) ? ' ' . $text_curr_setting : '' ); ?></option>
							<?php
						} ?>
					</select>
					&nbsp;
					<?php echo $help_perms; ?>
				</div>
				<?php
				if( $perm2 != $permission ) {
					displayError( sprintf( $error_perm, 'image/cache/data', $perm2, $perm ) );
				} ?>
				<div class="block">
					<input type="submit" name="submit" value="<?php echo $btn_continue; ?>" class="submit round green" />
					<input type="submit" name="skip" value="<?php echo $btn_skip; ?>" class="submit round" />
					<input type="submit" name="cancel" value="<?php echo $btn_cancel; ?>" class="submit round red" />
				</div>
			</form>
		</div>
		<div class="clr"></div>
	</div>
<?php echo $footer;?>
