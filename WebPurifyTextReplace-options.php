<?php
/*
Plugin Name: WebPurify Profanity Filter
Plugin URI: http://www.webpurify.com/wp-plugin.php
Version: 2.0
Author: WebPurify
Author URI: http://www.webpurify.com
Description: Uses the powerful WebPurify Profanity Filter API to stop profanity in comments.3
*/
/*  Copyright 2009  WebPurify  (email : support@webpurify.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

add_action('admin_menu', 'webpurify_options_page');

function webpurify_options_page() {
	add_options_page('WebPurify Options', 'WebPurify', 'manage_options','webpurifytextreplace/WebPurifyTextReplace.php');
}

function WebPurifyTextReplace($commentID) {
    global $wpdb;

    $API_KEY = get_option('webpurify_userkey');

    $table_name = $wpdb->prefix . "comments";
    $getcomment = "SELECT comment_content from ".$table_name." where comment_ID = ".$commentID.";";
    $content = $wpdb->get_var($getcomment);


    $params = array(
      'api_key' => $API_KEY,
      'method' => 'webpurify.live.replace',
      'text' => $content,
      'replacesymbol' => '*'
    );


    $encoded_params = array();

    foreach ($params as $k => $v){
        $encoded_params[] = urlencode($k).'='.urlencode($v);
    }

#
# call the API and decode the response
#
    $url = "http://www.webpurify.com/services/rest/?".implode('&', $encoded_params);

	$response = simplexml_load_file($url,'SimpleXMLElement', LIBXML_NOCDATA);
    $ar = $response->text;

    $update_comment = "UPDATE ".$table_name." set comment_content = '".mysql_escape_string($ar)."' where comment_ID = ".$commentID.";";
    $results = $wpdb->query($update_comment);

}

add_action('admin_menu', 'webpurify_options_page');
add_action('comment_post','WebPurifyTextReplace');
?>
