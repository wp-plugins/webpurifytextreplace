<?php
/*
Plugin Name: WebPurifyTextReplace
Plugin URI: http://www.webpurify.com/wp-plugin.php
Description: Filters user comments for profanity BEFORE they are inserted into the database using the WebPurify API. All Legacy comments (ie all comments posted before the time you starting using this API&nbsp;will not be checked)  Replaces each letter of profane words with a "*". Please register for a key at: <a target="new" href="http://www.webpurify.com/apply.php">http://www.webpurify.com/apply.php</a>
Version: 1.5
Author: WebPurify
Author URI: http://www.webpurify.com
*/
/*  Copyright 2007  WebPurify  (email : comments@webpurify.com)

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
	add_options_page('WebPurify Options', 'WebPurify', 9, '?page=webpurify/WebPurifyTextReplace-options.php');
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

    $rsp = file_get_contents($url);
    $ar = ParseXML($rsp);

    $update_comment = "UPDATE ".$table_name." set comment_content = '".mysql_real_escape_string($ar)."' where comment_ID = ".$commentID.";";
    $results = $wpdb->query($update_comment);

}

function ParseXML($xml) {
// Gets XML in a string and parses it into an array.

// Create the parser object
$parser = xml_parser_create();

xml_parse_into_struct($parser, trim($xml), &$structure, &$index);
xml_parser_free($parser);

// Hack up the XML and put it into the array
foreach($structure as $s)
{
      if ($s["tag"] == "TEXT") {
           $cleancontent = $s['value'];
      }
}

return $cleancontent;
}

add_action('admin_head', 'webpurify_options_page');
add_action('comment_post','WebPurifyTextReplace');
//add_filter('comment_text','WebPurifyTextReplace');
?>