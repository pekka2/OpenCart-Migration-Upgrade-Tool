<?php echo $header; ?>

<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <div class="box">
     <div class="heading">
      <h1><img src="view/image/error.png" alt="" /> <?php echo $heading_title; ?></h1>
     </div>
      <div style="height:100px;border: 1px solid #DDDDDD; background: #F7F7F7; text-align: center; padding: 25 px 30px 25px 5px;">
     <br/>
     <?php echo $text_not_found; ?>
    </div>
  </div>
</div>

<?php echo $footer; ?>
