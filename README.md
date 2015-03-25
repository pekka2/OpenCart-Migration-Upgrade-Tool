/**
 * @version		$Id: readme.txt 2014-10-17 16:35Z mic $
 * @package		OpenCart Shopconverter 1.4.7-2.0.0.0 to 2.0.1.0
 * @author		Pekka Mansikka - http://pm-netti.com & mic - http://pixelnbit.com
 * @copyright	        2014 peku & mic
 * @license		MIT http://opensource.org/licenses/MIT
 */

Readme for the program OpenCart Converter
*****************************************

License		MIT
Author		Pekka Mansikka - http://pm-netti.com & mic - http://pixelnbit.com
Copyright	2014 Pekka Mansikka - http://pm-netti.com & mic - http://pixelnbit.com

Description
-----------
This program coverts an OpenCart shop of version 1.4.x to the latest v.2 version.

Features
--------
* Login as the Administrator
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

 *********************************************************************************************************************************
 * IF SHOW ERRORS IN AFTER LOGIN: 
 * ============================= 
 * Version 1.47-1.5.6.4: Copy file user.php in directory your-store/system/libary to directory your-store/converter/system/library
 * Version 2.0.0.0: Copy file user.php in directory upload/help-2.0/ to directory your-store/converter/system/library
 **********************************************************************************************************************************
Operation
---------
The update can be done in 2 ways:

A. on an already existing shop
B with a new copy of OpenCart 2.0.1.0

If you choose method A, follow these steps:

	1.	copy the whole content of this package inside the folder UPLOAD
		into your shopsoftware root
		It should then look like: ../converter
	2.	start the converter via http://YOURSHOP/converter/index.php
        3.      login
        4.      set full permissions for Top Administrator Group
	5.	follow the instructions on the screen

If you choose method B, follow these steps (NOTICE: This you can use, when change domain root directory):
/* THIS FEATURE IS REMOVE SO FAR. */
	1.	create a new folder (subfolder preferred) inside your shop
	2.	copy the whole content of this package inside the folder UPLOAD
		into your shopsoftware root
		It should then look like: ../converter
	3.	copy from your old shop the folder image (and the whole content)
		into the new folder (if new shop is other server)
        4.      copy database other new database
	5.	copy ../config.php and ../admin/config.php from your old shop
		into the new folder (change DB_DATABASE name)
	6.	start the converter via http://YOURSHOP/converter/index.php
        7.      login
        8.      set full permissions for Top Administrator Group
	9.	follow the instructions on the screen


Permissions:
------------

Directory `converter`, same permission when store/system/logs
Directory `converter/system/language_model`, same permission when store/system/logs
Directory `converter/system/logs`, same permission when store/system/logs
Directory `converter/vqmod/vqcache`, same permission when store/system/logs

STEPS:
=====

Step 1:
------
a. Add new Tables
b. Add new Columns
c. Rename Columns
d. Delete expired columns and tables
e. Add new Settings to table `setting`
f. Move modules to table `module`
g. Delete expired module settings from table `setting`

Step 2:
-------
a. Add constant `DIR_MODIFICATION` and `DIR_UPLOAD` to files config.php
b. Add constant `HTTPS_CATALOG` to file admin/config.php in versions 1.4.7 - 1.5.2.1

Step 3:
-------
a. Change imagepaths in database
b. Rename directory `image/data` to `image/catalog` and directory `image/cache/data` to  `image/cache/catalog`

Upgrade Repeat:
--------------
1. You can 1. upgrade after change tables from trade database, e.g. tables `address`, `customer` and `order`s.
2. Repeat database upgrade. Because those tables is no imagepaths, you can Skip step 2 and step3.
3. If change e.g. tables `banner_image`, `category`, `manufacturer` or `product`s, your need run imagepaths upgrade (step 3).
4. This repreat Upgrade is not add new settings to table setting (step 1 e-g). You are not change table `setting` after first upgrade.

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
