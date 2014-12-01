<?php echo $header; ?>
<div class="msg round">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning round"><?php echo $error_warning; ?></div>
  <?php } ?>
  <?php if ($success) { ?>
  <div class="success round"><?php echo $success; ?></div>
  <?php } ?>

    <div class="heading">
      <h1><img src="view/image/user.png" alt="" /> <?php echo $heading_title; ?></h1> 
<span class="buttons">
<a href="<?php echo $cancel;?>" class="button right"><?php echo $button_cancel; ?></a> 
</span>
    </div> 

   <form action="<?php echo $action;?>" method="post">
    <div class="block-big"><b><?php echo $text_admin_language;?></b></div>
              <div class="block">
<?php echo $entry_admin_language; ?>
              <select name="config_admin_language">
                  <?php foreach ($languages as $language) { ?>
                  <?php if ($language['code'] == $config_admin_language) { ?>
                  <option value="<?php echo $language['code']; ?>" selected="selected"><?php echo $language['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $language['code']; ?>"><?php echo $language['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
          </div>
<input type="submit" name="save" class="submit round" value="<?php echo $button_save; ?>"/> 
   </form>
<br/><br/><br/>
</div>
<?php echo $footer; ?>
