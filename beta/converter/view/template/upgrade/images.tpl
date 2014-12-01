<?php echo $header;?>


  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>

<div class="note round<?php echo $simulate ? ' bg-green' : ' bg-red'; ?>">
<?php echo $text_simulation; ?> <span class="<?php echo $simulate ? 'green">' . $text_on : 'red">' . $text_off; ?></span>
</div>

 <?php if( isset( $step3 )){?>
    <?php echo $upgrade_data;?>
  <?php } ?>
 <?php if( isset( $skip )){?>
   <div class="msg round"> <b><?php echo $msg_image_skipped;?></b> </div>
  <?php } ?>
  <div class="msg round">

		<div class="step round"><?php echo $text_finish; ?></div>
		<div class="clr"></div>
		<div class="block-big"><?php echo $text_finish_text; ?></div>
		<div class="block-big">
			<div class="error round"><?php echo $text_finish_note; ?></div>
		</div>
 </div>


<?php echo $footer;?>
