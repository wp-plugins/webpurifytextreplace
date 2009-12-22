<?php
/*
Plugin Name: WebPurifyTextReplace
Plugin URI: http://www.webpurify.com/wp-plugin.php
Description: Filters user comments for profanity BEFORE they are inserted into the database using the WebPurify API. All Legacy comments (ie all comments posted before the time you starting using this API&nbsp;will not be checked)  Replaces each letter of profane words with a "*". Please register for a key at: <a target="new" href="http://www.webpurify.com/apply.php">http://www.webpurify.com/apply.php</a>
Version: 2.0
Author: WebPurify
Author URI: http://www.webpurify.com
*/
/*  Copyright 2009  WebPurify  (email : comments@webpurify.com)

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


function webpurify_options_page() {
	add_options_page('WebPurify Options', 'WebPurify', 9, 'webpurify/WebPurifyTextReplace.php');
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
//add_filter('comment_text','WebPurifyTextReplace');
?>
