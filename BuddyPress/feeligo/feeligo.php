<?php
/*  Copyright YEAR  PLUGIN_AUTHOR_NAME  (email : PLUGIN AUTHOR EMAIL)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
?>
<?php 
require_once(str_replace('//','/',dirname(__FILE__).'/').'api.php');
require_once(str_replace('//','/',dirname(__FILE__).'/').'actions.php'); 
//register /feeligo/api route
add_action( 'send_headers', 'action::feeligo_router');
//write the footer script
add_action('wp_footer', 'action::footer_script');
//write the header script
add_action('wp_head', 'action::header_script'); 
//write the header styles
add_action('wp_enqueue_scripts', 'action::header_css');
?>