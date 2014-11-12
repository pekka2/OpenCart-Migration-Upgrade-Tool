<?php echo $header; ?>
<div class="msg round">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>


    <div class="heading">
      <h1><img src="view/image/user.png" alt="" /> <?php echo $heading_title; ?></h1>   


     <div class="buttons">
      <a href="<?php echo $database;?>" class="button right"><?php echo $button_database; ?></a>
     </div>
    </div> 

   <h2 class="round"><?php echo $text_database_info;?></h2>
   
    <div class="block">
       <label>
          <?php echo $text_your_db_tables;?> <?php echo $your_database_tables;?>
       </label>
    </div>
    <div class="block">
      <label>
       <?php echo $text_oc2_db_tables;?> <?php echo $upgrade_database_tables;?>
    </label>
    <div class="block">
      <label>
       <?php echo $text_missing_tables;?> <?php echo $missing;?>
    </label>
    <div class="block">
      <label>
       <?php echo $text_expired_tables;?> <?php echo $expirend;?>
    </label>
    <?php if(isset($text_tables_complete)){?>
    <div class="block">
      <label>
 <h3> <?php  echo   $text_tables_complete;?></h3>
    </label>
    <?php } ?>
    </div>

</div>
<?php echo $footer; ?>
