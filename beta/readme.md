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

Requirement/Erforderlich:
- OpenCart min. 1.4.7
-------------------------

Description
-----------
This program coverts an OpenCart shop of version 1.4.7 to the latest v.2 version.

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

Operation
---------
The update can be done in 2 ways:

A. on an already existing shop
B with a new copy of OpenCart 2.0.1.0 from Github (This is NOT  work for Upgrade to in version 2.0.1.0)

If you choose method A, follow these steps:

	1.	copy the whole content of this package inside the folder UPLOAD
		into your shopsoftware root
		It should then look like: ../converter
        2.      go your store settings of in admin and check SEO to status disabled
	3.	start the converter via http://YOURSHOP/converter/index.php
        4.      login
        5.      set full permissions for Top Administrator Group
	6.	follow the instructions on the screen

If you choose method B, follow these steps:

	1.	create a new folder (subfolder preferred) inside your shop
	2.	copy the whole content of this package inside the folder UPLOAD
		into your shopsoftware root
		It should then look like: ../converter
        3.      go your store settings of in admin and check SEO to status disabled
	4.	copy from your old shop the folder image (and the whole content)
		into the new folder (if new shop is other server)
        5.      copy database other new database
	6.	copy ../config.php and ../admin/config.php from your old shop
		into the new folder (change DB_DATABASE name)
	7.	start the converter via http://YOURSHOP/converter/index.php
        8.      login
        9.      set full permissions for Top Administrator Group
	10.	follow the instructions on the screen

Suggestion Updates
------------------
Copy files in directory upload/update_files_2.0.1/ your server  to direcrory catalog/controller/module


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
