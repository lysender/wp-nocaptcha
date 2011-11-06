<?php
/*
Plugin Name: WP NoCaptcha Plugin
Plugin URI: http://www.lysender.com
Description: Adds javascript based invisible captcha on comment forms.
Version: 0.1.0
Author: Lysender
Author URI: http://www.lysender.com
License: GPL2
*/
	
require_once dirname(__FILE__).'/wp-nocaptcha.class.php';

$wpNoCaptcha = new WP_NoCaptcha_Plugin;

/** 
 * Filter before posting comment - preprocess comment
 */
function nocpatcha_comment_post($comment)
{
	global $wpNoCaptcha;

	// Added for compatibility with WP Wall plugin
	// This does NOT add CAPTCHA to WP Wall plugin,
	// It just prevents the "Error: You did not enter a Captcha phrase." when submitting a WP Wall comment
	if ( function_exists('WPWall_Widget') && isset($_POST['wpwall_comment']) )
	{
		// skip capthca
		return $comment;
	}

	// Skip the captcha if user is logged in and the settings allow
	if (is_user_logged_in())
	{
		return $comment;
	}

	// skip captcha for comment replies from admin menu
	if ( isset($_POST['action']) && $_POST['action'] == 'replyto-comment' &&
	( check_ajax_referer( 'replyto-comment', '_ajax_nonce', false ) || check_ajax_referer( 'replyto-comment', '_ajax_nonce-replyto-comment', false )) ) {
		  // skip capthca
		  return $comment;
	}

	// Skip captcha for trackback or pingback
	if ( $comment['comment_type'] != '' && $comment['comment_type'] != 'comment' ) {
		// skip capthca
		return $comment;
	}

	if (isset($_POST['captcha_nocaptcha']) && !empty($_POST['captcha_nocaptcha']))
	{
		$c = trim($_POST['captcha_nocaptcha']);
		
		if ($c === $wpNoCaptcha->getToken())
		{
			return $comment;
		}
	}

	// Everything else here fails captcha
	wp_die('ERROR: Captcha invalid or not present');
}

/** 
 * Adding nocaptcha on form
 */
function nocaptcha_comment_form_wp3()
{
	global $wpNoCaptcha;

	// skip the captcha if user is logged in and the settings allow
	if (is_user_logged_in())
	{
		return true;
	}

	echo '<input type="hidden" name="captcha_nocaptcha" id="captcha_nocaptcha" />';
	echo '<script type="text/javascript">
			var WPNoCaptcha = {
				token: null,
				tokenSet: false,
				pressCount: 0,
				minPressCount: 1,

				setToken: function() {
					if (this.token) {
						$("#captcha_nocaptcha").val(this.token);
						this.tokenSet = true;
					}
					return this;
				},

				keyPressed: function() {
					(function(w){
						if (!w.tokenSet) {
							w.pressCount = w.pressCount + 1;

							if (w.pressCount >= w.minPressCount) {
								w.setToken();
							}
						}
					})(WPNoCaptcha);
				}
			};

			$(function(){
				var d = {action: "nocaptcha_token"};
				var u = "'.plugins_url('wp-nocaptcha-token.php', __FILE__).'";

				$.post(u, d, function(data){
					if (data) {
						WPNoCaptcha.token = $.trim(data);

						$("#commentform #comment").keypress(WPNoCaptcha.keyPressed);
					}
				});
			});
		</script>';
	return true;
}

// Initialize
add_filter('preprocess_comment', 'nocpatcha_comment_post', 1);
add_action('comment_form_after_fields', 'nocaptcha_comment_form_wp3', 1);
add_action('comment_form_logged_in_after', 'nocaptcha_comment_form_wp3', 1);
