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
    <div class="heading">
      <h1><img src="view/image/home.png" alt="" /> <?php echo $heading_title; ?></h1>

<div class="buttons">

<?php if(isset($upgrade_access)){?>
<a href="<?php echo $upgrade_info;?>" class="button right"><?php echo $button_upgrade; ?></a>
<?php }  else {?>
<a onclick="$('#access').submit();" class="button right"><?php echo $button_save; ?></a>
<?php } ?></div>
    </div>
    <form action="<?php echo $action;?>" method="post" id="access">
 <?php if( !isset($upgrade_access) ){ ?>
    <select name="user_group">
    <?php
     foreach($user_group_info as $user_group){
      if( $user_group['name'] == 'Top Administrator' ){?>
      <option value="<?php echo $user_group['user_group_id'];?>" selected="selected"><?php echo $user_group['name'];?></option>
     <?php } else {?>
     <option value="<?php echo $user_group['user_group_id'];?>"><?php echo $user_group['name'];?></option>
     <?php } 
   }
  }?>
   </select>

   <?php
   if(isset($upgrade_access)){ ?>
      <span class="round"><h4><?php echo $text_new_permissions;?></h4></span>
      <?php
       foreach($upgrade_access as $route){?>
       <?php echo $route;?></br/>
      <?php
     } 
   }?>
  </ul>
  </form>

</div>
<?php echo $footer; ?>
