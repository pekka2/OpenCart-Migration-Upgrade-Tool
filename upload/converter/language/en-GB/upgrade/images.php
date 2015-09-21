<?php
// Heading
$_['heading_title']         = 'Copying Image Files and Upgrade Image paths';
$_['heading_config']        = 'Edit config.php files';
// entry
$_['entry_imageDir']	   	 = 'Name of image directory';
// btn
$_['btn_back']  	         = 'Back';
$_['btn_preview']	         = 'Continue in preview status';
// Text
$_['text_error']		    = 'Error';
$_['text_finish']		    = 'Finish';
$_['text_finish_note']  	= '<b>Note</b>: do not forget to delete the folder <b>converter</b> afterwards!!';
$_['text_finish_text']	    = 'Congratulation!<br />Your old shop is successfully updated to the latest version. Please use the buttons below to navigate further.';
$_['text_upgrade_images']   = 'Upgrade of Imagepaths';
$_['text_upgrade_info']     = 'Upgrade Info';
// Header
$_['header_step_setting']     = 'New Settings is added succesfully';
// Hrkp
$_['help_imageDir']		    = 'Image directory the folder name if not <i>image</i>';
$_['help_ops']			     = 'Display Operations <small>(display all database operations)</small>';
$_['help_simulate']		     = 'Simulate Converting <small>(simulating operations only)</small>';
$_['help_usage']		     = '<b>How to use this tool?</b><ol type="1"><li>If not already done, download the OpenCart v.2 package from <a href="http://www.opencart.com" target="_blank">OpenCart</a></li><li>Unzip that package locally</li><li>This extension has to be placed in the subfolder <b>converter</b> folder of your shopinstallation (../converter))</li><li>Now you have two (2) options:<ol type="I"><li>Transfer all folders and files from the OpenCart v.2 package <b>into the installed shop</b></li><li><b>Create a new directory</b> and copy all folders and files from the OpenCart v.2 package into</li></ol></li><li>If you have chosen method II copy the folder <b>image</b> and the <b>2 config.php</b> from the old shop</li><li><b>Never use the installer from the OpenCart 2.x package!</b></li><li>Set your options above and click on <b>Continue</b></li><li>If you are finished with this upgrade, do not to forget to delete this script</li></ol>';

// msg
$_['msg_change_path']    	= 'CHANGE total <b>%s</b> image filepath(s) in database successfully';
$_['msg_errors']	        = 'Directory <b>%s</b> is not found! You check path.';
$_['msg_image_path']	    = 'imagepath successfully changed';
$_['msg_image_skipped']  	= 'Adopting image paths skipped';
$_['msg_perm_dir']		    = 'No writing permission to directory <b>%s</b>. To procceed change first (either 0755 or 0777)';
$_['msg_renamed_dir']	    = 'Directory <b>%s</b> renamed successfully to <b>%s</b>';
$_['msg_renamed_total_dir']	= 'RENAMED total <b>%s</b> directories successfully';

// error
$_['error_admin_not_found']	= 'Managed by the directory <b>%s </ b> not  found! Check the directory name.';
$_['error_image_not_found']	= 'Image directory <b>%s</b> not  found! Check the directory name.';
?>
