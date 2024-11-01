<?php
/*
Plugin Name: Video Lead Form
Plugin URI: http://video-lead-form.com/wordpress-plugin
Description: Video Lead Form turns your videos into lead generation machines. By automatically embedding a lead form directly into the video users have a clean experience of watching your video and providing you with their information. This plugin provides the ability to upload, manage, and embed your videos from directly within wordpress.
Version: 0.6
Author: Sumilux
Author URI: http://video-lead-form.com/
License: GPL2
*/

/*  Copyright 2012  Sumilux  (email : contact@video-lead-form.com)

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

/*
 * Define contstant to hold VLF API url endpoint
 */
define('VLF_API_URL','http://www.video-lead-form.com/app');
define('VLF_FRAME_URL','https://www.video-lead-form.com/app');

/*
 * Check if session is already started, if not start one
 */
if ( !session_id() ){
    session_start();
}

/*
 * Add top-level menu option to admin page
 */
add_action('admin_menu', 'register_vlf_menu_page');
function register_vlf_menu_page(){
    add_menu_page( 'Video Lead Form Administration', 
                   'Video Lead Form', 
                   'manage_options', 
                   'video-lead-form', 
                   'vlf_admin_page',
                   plugins_url( 'videoleadform/images/vlf-icon-small.png', __FILE__ )
                 );
}

$action = getRequestVar('action');
if($action == 'login'){
    vlf_login_action();
} elseif($action == 'register'){
    vlf_register_action();
} elseif($action == 'logout'){
    vlf_logout_action();
}


/*
 * Function to render main admin page.
 * Should display login/signup for first time visitors
 */
function vlf_admin_page(){
    $vlf_email_address = get_option('vlf_email_address',false);
    $vlf_api_token = get_option('vlf_api_token',false);
    
    if(!$vlf_email_address || !$vlf_api_token){
        vlf_login_page();
    } else {
        vlf_manage_page();
    }   
}

/*
 * Login page functionality. Uses Social Sign In API
 */
function vlf_login_page($errMsg = false, $successMsg = false){
    
    $currentMessage = getCurrentMessage();
    clearCurrentMessage();
    if(is_array($currentMessage)){
        if($currentMessage['type'] == 'error'){
            $errMsg = $currentMessage['msg'];
        } elseif($currentMessage['type'] == 'success') {
            $successMsg = $currentMessage['msg'];
        }
    }
    
?>
    <style type="text/css">
        #vlf-login-page-container { width: 800px; margin: auto; padding: 10px; }
        #vlf-login-page-new-user-container { width: 350px; float: left; display: inline;}
        #vlf-login-page-existing-login-container { width: 350px; float: right; display: inline;}
        #vlf-login-page-header { padding: 10px 0px 20px 0px; border-width: 0px 0px 5px 0px; border-style: solid;}
        #vlf-login-page-body { padding: 20px 0px 20px 0px; }
        #vlf-login-page-logo { width: 300px; display: inline; float: left;}
        #vlf-login-page-intro { width: 400px; display: inline;}
        h3.errMsg { color: red; }
        h3.successMsg { color: green; }
    </style>
    <script type="text/javascript">
        function vlf_registration_validation(){
            var formObj = document.forms['vlf_register'];
            if(formObj.vlf_first_name.value == null || formObj.vlf_first_name.value == '' || formObj.vlf_first_name.value.length < 2){
                alert("First Name is required");
                return false;
            } else if(formObj.vlf_last_name.value == null || formObj.vlf_last_name.value == '' || formObj.vlf_last_name.value.length < 2){
                alert("Last Name is required");
                return false;
            } else {
                var x = formObj.vlf_email_address.value;
            	var atpos=x.indexOf("@");
            	var dotpos=x.lastIndexOf(".");
            	if (atpos<1 || dotpos<atpos+2 || dotpos+2>=x.length){
            	    alert("Not a valid e-mail address");
            	    return false;
            	} else {
                	return true;
            	}
            }
        }

        function vlf_login_validation(formObj){
            if(formObj.vlf_api_token.value == null || formObj.vlf_api_token.value == '' || formObj.vlf_api_token.value.length != 32){
                alert("API Token is required");
                return false;
            } else {
                var x = formObj.vlf_email_address.value;
            	var atpos=x.indexOf("@");
            	var dotpos=x.lastIndexOf(".");
            	if (atpos<1 || dotpos<atpos+2 || dotpos+2>=x.length){
            	    alert("Not a valid e-mail address");
            	    return false;
            	} else {
                	return true;
            	}
            }
        }
    </script>
    <div id='vlf-login-page-container'>
        <div id='vlf-login-page-header'>
            <div id="vlf-login-page-logo">
                <img src="<?php echo plugins_url( 'videoleadform/images/vlf-logo.jpg', __FILE__ );?>" />
            </div>
            <div id="vlf-login-page-intro">
                <h1>Video Lead Form</h1>
                <p>Video Lead Form takes your videos to the next level by giving you the ability to
                generate sales leads directly from within the video! You no longer need to let your viewers
                hunt for a contact form on your site because the lead form loads directly on top of
                the video. Learn more at <a href="http://video-lead-form.com/" target="_blank">http://video-lead-form.com/</a></p>
            </div>
        </div>
        <div id='vlf-login-page-body'>
            <?php if($errMsg){ echo "<h3 class='errMsg'>$errMsg</h3>"; } ?>
            <?php if($successMsg){ echo "<h3 class='successMsg'>$successMsg</h3>"; } ?>
            <div id='vlf-login-page-new-user-container'>
                <h1>New User Registration</h1>
                <p>Account registration is simple, just enter your name and email address
                and we'll email you your API token.</p>
                <form name="vlf_register" id="vlf_register" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" onsubmit="return vlf_registration_validation();">
                  <label for="vlf_first_name">First Name</label>
                  <input type="text" name="vlf_first_name" id="vlf_first_name" />
                  <br />
                  <label for="vlf_last_name">Last Name</label>
                  <input type="text" name="vlf_last_name" id="vlf_last_name" />
                  <br />
                  <label for="vlf_email_address">Email Address</label>
                  <input type="text" name="vlf_email_address" id="vlf_email_address" />
                  <br />
                  <label for="vlf_site_url">Site URL</label>
                  <input type="text" name="vlf_site_url" id="vlf_site_url" value="<?php echo get_option('siteurl'); ?>" disabled/>
                  <br />
                  <input type="hidden" name="action" value="register" />
                  <input type="submit" value="Register" />
                </form>
            </div>
            <div id='vlf-login-page-existing-login-container'>
                <h1>Already Have An Account?</h1>
                <p>If you already have an API token from Video Lead Form, you can log in now.</p>
                <form name="vlf_login" id="vlf_login" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post"  onsubmit="return vlf_login_validation(this);">
                  <label for="vlf_email_address">Email Address</label>
                  <input type="text" name="vlf_email_address" id="vlf_email_address" />
                  <br />
                  <label for="vlf_api_token">API Token</label>
                  <input type="text" name="vlf_api_token" id="vlf_eapi_token" />
                  <br />
                  <input type="hidden" name="action" value="login" />
                  <input type="submit" value="Log In" />
                </form>
            </div>
        </div>
    </div>
<?php
}

/*
 * Handle login submitions
 */
function vlf_login_action(){
    $vlf_email_address = getRequestVar('vlf_email_address');
    $vlf_api_token = getRequestVar('vlf_api_token');
    
    if(!$vlf_email_address || !$vlf_api_token){
        setCurrentMessage('Please provide a valid Email Address and API Token.');
    } else {
        
        /*
         * Call VLF API to validate user account
         */
        $results = wp_remote_get(VLF_API_URL."/index.php/?r=wp/authenticateUser&email=$vlf_email_address&api_token=$vlf_api_token",array('sslverify'=>false));
        
        if(is_wp_error($results)){
            setCurrentMessage('An error occured while trying to call the VLF API.');
        } elseif(!$results['response'] || !$results['response']['code'] || $results['response']['code'] != 200){
            setCurrentMessage('The VLF API returned a server level error.');
        } else {
            $userData = json_decode($results['body'],true);
            if(!is_array($userData)){
                setCurrentMessage('The API results from the VLF API are not formed properly.');
            } elseif($userData['validUser'] && $userData['validUser'] == true){
                update_option('vlf_email_address',$vlf_email_address);
                update_option('vlf_api_token',$vlf_api_token);
            } else {
                setCurrentMessage('Invalid email address or api token, please check the values and try again.');
            }
        }
        
    }
    require_once(ABSPATH . 'wp-includes/pluggable.php');
    wp_redirect( $_SERVER['HTTP_REFERER'] );
    exit();
    
}

/*
 * Handle new user registrations
 */
function vlf_register_action(){
    $vlf_first_name = getRequestVar('vlf_first_name');
    $vlf_last_name = getRequestVar('vlf_last_name');
    $vlf_email_address = getRequestVar('vlf_email_address');
    
    $errMsg = $successMsg = null;
    
    if(!$vlf_first_name || !$vlf_last_name || !$vlf_email_address){
        setCurrentMessage('Missing either First Name, Last Name, or Email Address.');
    } else {
        /*
         * Call VLF API to register new user
         */
        $site_url = get_option('siteurl','unknown');
        $results = wp_remote_get(VLF_API_URL."/index.php/?r=wp/registerNewUser&first_name=$vlf_first_name&last_name=$vlf_last_name&email=$vlf_email_address&site_url=$site_url&source=wordpress",array('sslverify'=>false));
        
        if(is_wp_error($results)){
            setCurrentMessage('An error occured while trying to call the VLF API.');
        } elseif(!$results['response'] || !$results['response']['code'] || $results['response']['code'] != 200){
            setCurrentMessage('The VLF API returned a server level error.');
        } else {
            $userData = json_decode($results['body'],true);
            
            if(!is_array($userData)){
                setCurrentMessage('The API results from the VLF API are not formed properly.');
            } elseif($userData['registrationSuccessful'] && $userData['registrationSuccessful'] == true){
                setCurrentMessage('Registration successful. Please check your email to retreive your API Token and then return to this page to log in.','success');
            } else {
                setCurrentMessage('A problem occured during registration, please check your information and try again.');
            }
        }
    }
    
    require_once(ABSPATH . 'wp-includes/pluggable.php');
    wp_redirect( $_SERVER['HTTP_REFERER'] );
    exit();
}

/*
 * Log user out
 */
function vlf_logout_action(){
    delete_option('vlf_email_address');
    delete_option('vlf_api_token');
    clearCurrentMessage();
}

/*
 * Main manage/upload page
 */
function vlf_manage_page(){
    $vlf_email_address = get_option('vlf_email_address',false);
    $vlf_api_token = get_option('vlf_api_token',false);
?>
    <h1>Manage Your Videos</h1>
    Logged in as <?php echo $vlf_email_address?> <a href="<?php echo $_SERVER['REQUEST_URI']; ?>&action=logout">(Log Out)</a>
    <iframe id="vlf_manage_frame" width="800" height="2000" src="<?php echo getVlfIframeUrl($vlf_email_address,$vlf_api_token);?>"></iframe>
<?php    
}

function getVlfIframeUrl($vlf_email_address,$vlf_api_token){
    $url = VLF_FRAME_URL."/index.php?r=video/index&oid=wp_$vlf_api_token&uid=wp_$vlf_api_token&email=$vlf_email_address&source=wordpress";
    return $url;
}

/*
 * Shortcode function for embedding videos
 */
function vlf_func( $atts ){
    extract( shortcode_atts( array(
        'vid' => false,
        'height' => false,
        'width' => '800'
    ), $atts ) );
    
    if(!$vid){
        return 'vid attribute is required.';
    }
    
    if($height){
        $uHeight = "height='".esc_attr($height)."'";
    }
    if($width){
        $uWidth = "width='".esc_attr($width)."'";
    }
    
    $vUrl = VLF_API_URL.'/?r=video/videoinfo&vcid='.$vid.'&width='.$width.'&height='.$height;
    $iUrl = VLF_API_URL.'/embed.html?src='.urlencode($vUrl);
    
    return "<iframe src='".esc_attr($iUrl)."' $uWidth $uHeight></iframe>";
}
add_shortcode( 'vlf', 'vlf_func' );

/*
 * Basic function to get form fields or a default value if not submitted
 */
function getRequestVar($name,$default=false){
    $method = strtolower($_SERVER['REQUEST_METHOD']);
    if($method == 'get'){
        if(isset($_GET[$name])){
            return $_GET[$name];
        }
    } elseif ($method == 'post'){
        if(isset($_POST[$name])){
            return $_POST[$name];
        }
    }
    
    return $default;
}

function setCurrentMessage($msg,$type='error'){
    $_SESSION['VLF_MSG'] = array('msg' => $msg, 'type' => $type);
}

function getCurrentMessage(){
    if(isset($_SESSION['VLF_MSG'])){
        return $_SESSION['VLF_MSG'];
    } else {
        return false;
    }
}

function clearCurrentMessage(){
    unset($_SESSION['VLF_MSG']);
}
