<?php echo $header; ?>
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  
<div id="container">
<div id="content">
  <div class="msg roud">
      <h1><img src="view/image/user.png" alt="" /> <?php echo $heading_title; ?></h1>   
     <div class="buttons">
      <a href="<?php echo $database;?>" class="button right"><?php echo $btn_database; ?></a>
     </div>

   <h2 class="round"><?php echo $text_database_info;?></h2>
   
  <table class="info">
    <tr class="info-top">
       <td>
          <?php echo $text_your_database;?>  </td><td class="td-right"><?php echo $text_database;?> 
       </td>
    </tr>
    </tr>
    <tr>
       <td>
          <?php echo $text_your_version;?> </td><td class="td-right"><?php echo $your_database_version;?>
       </td>
    </tr>
    <tr>
       <td>
          <?php echo $text_your_oc_tables;?> </td><td class="td-right"><?php echo $your_database_opencart_tables;?>
       </td>
    </tr>
    <tr>
       <td>
          <?php echo $text_your_other_tables;?> </td><td class="td-right"><?php echo $your_database_other_tables;?>
       </td>
    </tr>
    <tr class="total">
       <td>
          <?php echo $text_your_db_tables;?> </td><td class="td-right"><?php echo $your_db_total_tables;?>
       </td>
    </tr>
    
    <?php if(isset($upgrade)){?>
    <tr>
      <td>
       <?php echo $text_to_version;?>  </td><td class="td-right"><?php echo $upgrade;?>
    </td>
  </tr>
<?php } ?>
    <tr>
      <td>
       <?php echo $text_oc2_db_tables;?>  </td><td class="td-right"><?php echo $upgrade_database_tables;?>
    </td>
  </tr>
    <tr>
      <td>
       <?php echo $text_missing_tables;?> </td><td class="td-right"> <?php echo $missing;?>
    </td>
    </tr>
    <?php if(isset($text_tables_complete)){?>
    <tr>
      <td colspan="2">
 <h3> <?php  echo   $text_tables_complete;?></h3>
    </td>
    </tr>
    <?php } ?>
    </table>
  </div>
</div>
</div>
<?php echo $footer; ?>
