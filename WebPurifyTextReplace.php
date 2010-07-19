<?php
/*
please see http://www.webpurify.com/wp-plugin.php for more information.
 */

load_plugin_textdomain('WebPurifyTextReplace');

// set paramters to default, if not exists

add_option('webpurify_userkey', '');
add_option('webpurify_lang','en');
add_option('webpurify_r','*');

if(isset($_POST[webpurify_key]))
{
      update_option('webpurify_userkey',$_POST[webpurify_key]);
}

if(isset($_POST[webpurify_lang]))
{
      update_option('webpurify_lang',$_POST[webpurify_lang]);
}

if(isset($_POST[webpurify_r]))
{
	  if ($_POST[webpurify_r] == "") {
	  	$_POST[webpurify_r] = "*";
	  }
      update_option('webpurify_r',$_POST[webpurify_r]);
}


$userkey = get_option('webpurify_userkey');
$wplang = get_option('webpurify_lang');
$repc = get_option('webpurify_r');
?>
<div class="wrap"> 
	<h2><?php _e('Configure: WebPurify Plugin', 'WebPurifyTextReplace') ?></h2>
	<p><?php _e('In order to use this plugin you must enter your "WebPurify API Key."<br/><br/><br/>Purchase a WebPurify License at: <a href="https://www.webpurify.com/newkey.php?utm_source=wp_pluginlink&utm_medium=plugin&utm_campaign=wp_pluginlink"  target=_blank >www.webpurify.com</a>', 'WebPurifyTextReplace') ?></strong></p>
	<form name="form1" method="post" action="<?php echo(get_option('siteurl') . '/wp-admin/admin.php?page=webpurifytextreplace/WebPurifyTextReplace.php'); ?>">

		<fieldset class="options">
			    <?php _e('Enter WebPurify API Key', 'WebPurifyTextReplace') ?>: <input type="text" size="50" name="webpurify_key" value="<?php echo $userkey ?>" />
                <br/><br />
                Language Preference: <input type="radio" name="webpurify_lang" value="en" <?php if ($wplang == "en" || !$wplang) { ?>checked<?php } ?>> English <input type="radio" name="webpurify_lang" value="sp" <?php if ($wplang == "sp") { ?>checked<?php } ?>> Spanish <input type="radio" name="webpurify_lang" value="ar" <?php if ($wplang == "ar") { ?>checked<?php } ?>> Arabic <input type="radio" name="webpurify_lang" value="it" <?php if ($wplang == "it") { ?>checked<?php } ?>> Italian
                <br/><br/>
                
                Replacement Character: <input type="text" size="1" name="webpurify_r" maxlength="1" value="<?php echo $repc?>">
                
                <p class="submit"> <input type="submit" name="store_key" value="<?php _e('Save Settings', 'WebPurifyTextReplace') ?>" ></p>
		</fieldset>
		
		
	</form> 
</div>
