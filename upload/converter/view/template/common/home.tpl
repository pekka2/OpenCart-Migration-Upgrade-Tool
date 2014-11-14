<?php echo $header; ?>

<div class="msg round">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
    <div class="heading">
      <h1><img src="view/image/home.png" alt="" /> <?php echo $heading_title; ?></h1>
<span class="buttons">
<a href="<?php echo $upgrade_info;?>" class="button right"><?php echo $button_upgrade; ?></a>
<a href="<?php echo $permission;?>" class="button right"><?php echo $button_permission; ?></a>
<a href="<?php echo $language;?>" class="button right"><?php echo $button_language; ?></a>
</span>
</div>

    <div class="content">
     <pre>

Description
-----------
This program coverts an OpenCart shop of version 1.5.x to the latest v.2 version.

Features
--------
* Multilinguale
* Convert existing / new shopinstallations
* Converting database entries
* Adding new database tables
* Adding new database fields
* Removing obsolete database tables
* Removing obsolete database fields
* Adjusting config paths
* Adjusting image paths
* Simulation mode
* Optional display of ongoing operations

Installation
------------
Simply copy all folders (and included files) from the folder UPLOAD
into the root directory of your shop.

Operation
---------
The update can be done in 2 ways:

A. on an already existing shop
B with a new copy of OpenCart 2.x

If you choose method A, follow these steps:

	1.	copy the whole content of this package inside the folder UPLOAD
		into your shopsoftware root
		It should then look like: ../converter
	2.	start the converter via http://YOURSHOP/converter/index.php
        3.      login
        4.      set full permissions for Top Administrator Group
	5.	follow the instructions on the screen

If you choose method B, follow these steps:

	1.	create a new folder (subfolder preferred) inside your shop
	2.	copy the whole content of this package inside the folder UPLOAD
		into your shopsoftware root
		It should then look like: ../converter
	3.	copy from your old shop the folder image (and the whole content)
		into the new folder (if new shop is other server)
        4.      copy database other new database
	5.	copy ../config.php and ../admin/config.php from your old shop
		into the new folder (change DB_DATABASE name)
	5.	start the converter via http://YOURSHOP/converter/index.php
        7.      login
        8.      set full permissions for Top Administrator Group
	9.	follow the instructions on the screen


Guarantee / Warranty / Non-Warranty Clause
------------------------------------------
This program is distributed as it is.
There is no warranty for the loss of data, wether during install nor later.
If you detect an error which could lead to a malfunction of your stable system, we offer
our best effort to help you in this case in a reasonable timeframe (see also support).

Support
-------
Is avaliable per email / in the forums at the websites stated in the header of this file.
If you need a customized converter, contact the authors for further help.
</pre>
    </div>
</div>
<?php echo $footer; ?>
