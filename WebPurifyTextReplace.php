<?php
/*
please see http://www.webpurify.com/wp-plugin.php for more information.
*/

load_plugin_textdomain('WebPurifyTextReplace');

// set paramters to default, if not exists

add_option('webpurify_userkey', '');

if(isset($_POST[webpurify_key]))
    {
      update_option('webpurify_userkey',$_POST[webpurify_key]);
    }

$userkey = get_option('webpurify_userkey');

?>
<div class="wrap"> 
	<h2><?php _e('Configure: WebPurify Plugin', 'WebPurifyTextReplace') ?></h2>
	<p><?php _e('In order to use this plugin you must enter your "WebPurify API Key."<br/><br/><br/>Purchase a WebPurify License at: <a href="https://www.webpurify.com/newkey.php?utm_source=wp_pluginlink&utm_medium=plugin&utm_campaign=wp_pluginlink"  target=_blank >www.webpurify.com</a>', 'WebPurifyTextReplace') ?></strong></p>
	<form name="form1" method="post" action="<?php echo(get_option('siteurl') . '/wp-admin/admin.php?page=webpurifytextreplace/WebPurifyTextReplace.php'); ?>">

		<fieldset class="options">
			    <?php _e('Enter WebPurify API Key', 'WebPurifyTextReplace') ?>: <input type="text" size="50" name="webpurify_key" value="<?php echo $userkey ?>" />
                <br/><br />
                <p class="submit"> <input type="submit" name="store_key" value="<?php _e('Save API Key &amp; Activate Plugin', 'WebPurifyTextReplace') ?>" ></p>
		</fieldset>
	</form> 
</div>
