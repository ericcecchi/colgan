<?php
/**
 * WP-Members Pluggable Functions
 *
 * These functions replace those in the wp-members plugin
 *
 */

if( ! function_exists( 'wpmem_login' ) ):
/**
 * Logs in the user
 *
 * Logs in the the user using wp_signon (since 2.5.2). If login
 * is successful, it redirects and exits; otherwise "loginfailed"
 * is returned.
 *
 * @since 0.1
 *
 * @uses apply_filters Calls 'wpmem_login_redirect' hook to get $redirect_to
 *
 * @uses wp_signon
 * @uses wp_redirect Redirects to $redirect_to if login is successful
 * @return string Returns "loginfailed" if the login fails
 */
function wpmem_login()
{
	if( isset( $_POST['redirect_to'] ) ) {
		$redirect_to = $_POST['redirect_to'];
	} else {
		$redirect_to = $_SERVER['PHP_SELF'];
	}

	$redirect_to = apply_filters( 'wpmem_login_redirect', $redirect_to );

	if( isset( $_POST['rememberme'] ) == 'forever' ) {
		$rememberme = true;
	} else {
		$rememberme = false;
	}

	if( $_POST['log'] && $_POST['pwd'] ) {

		$user_login = sanitize_user( $_POST['log'] );

		$user_login = wpmem_login_check_for_email($user_login);

		$creds = array();
		$creds['user_login']    = $user_login;
		$creds['user_password'] = $_POST['pwd'];
		$creds['remember']      = $rememberme;

		$user = wp_signon( $creds, false );

		if( ! is_wp_error( $user ) ) {
			if( ! $using_cookie )
				wp_setcookie( $user_login, $user_pass, false, '', '', $rememberme );
			wp_redirect( $redirect_to );
			exit();
		} else {
			return "loginfailed";
		}

	} else {
		//login failed
		return "loginfailed";
	}

} // end of login function
endif;


if ( ! function_exists( 'wpmem_login_check_for_email' ) ):
/**
 * Get Username from Email
 *
 * Takes the username and checks if there is a user with an email that matches.
 * 	If there is, the username associated with the email is returned.
 * 	Otherwise the username is returned as it was passed in.
 *
 * @uses get_user_by
 * @return string Returns username associated with email or the same
 * 	username that was passed in
 */
function wpmem_login_check_for_email($username) {
	if ( !empty( $username ) )
		$user = get_user_by( 'email', $username );
	if ( isset( $user->user_login, $user ) )
		$username = $user->user_login;
	return $username;
} // end of check for email in the username
endif;

if ( ! function_exists( 'wpmem_inc_login' ) ):
/**
 * Login Dialog
 *
 * Loads the login form for user login
 *
 * @since 1.8
 *
 * @uses wpmem_login_form()
 *
 * @param  string $page
 * @return string $str the generated html for the login form
 */
function wpmem_inc_login( $page="page" )
{
	global $wpmem_regchk;

	$str = '';

	if( $page == "page" ){
			 if( $wpmem_regchk!="success" ){

			$arr = get_option( 'wpmembers_dialogs' );

			// this shown above blocked content
			$str = '<p>' . __( stripslashes( $arr[0] ), 'wp-members' ) . '</p>';

			/**
			 * Filter the post restricted message.
			 *
			 * @since 2.7.3
			 *
			 * @param string $str The post restricted message.
			 */
			$str = apply_filters( 'wpmem_restricted_msg', $str );

		}
	}

	/** create the default inputs **/
	$default_inputs = array(
		array(
			'name'   => __( 'Email', 'wp-members' ),
			'type'   => 'text',
			'tag'    => 'log',
			'class'  => 'username',
			'div'    => 'div_text'
		),
		array(
			'name'   => __( 'Password', 'wp-members' ),
			'type'   => 'password',
			'tag'    => 'pwd',
			'class'  => 'password',
			'div'    => 'div_text'
		)
	);

	/**
	 * Filter the array of login form fields.
	 *
	 * @since 2.9.0
	 *
	 * @param array $default_inputs An array matching the elements used by default.
	 */
	$default_inputs = apply_filters( 'wpmem_inc_login_inputs', $default_inputs );

		$defaults = array(
		'heading'      => __( 'Existing Users Log In', 'wp-members' ),
		'action'       => 'login',
		'button_text'  => __( 'Log In', 'wp-members' ),
		'inputs'       => $default_inputs
	);

	/**
	 * Filter the arguments to override login form defaults.
	 *
	 * @since 2.9.0
	 *
	 * @param array $args An array of arguments to use. Default null.
	 */
	$args = apply_filters( 'wpmem_inc_login_args', '' );

	$arr  = wp_parse_args( $args, $defaults );

	$str  = $str . wpmem_login_form( $page, $arr );

	return $str;
}
endif;

if( ! function_exists( 'wpmem_registration' ) ):
/**
 * Register function
 *
 * Handles registering new users and updating existing users.
 *
 * @since 2.2.1
 *
 * @uses do_action Calls 'wpmem_pre_register_data' action
 * @uses do_action Calls 'wpmem_post_register_data' action
 * @uses do_action Calls 'wpmem_register_redirect' action
 * @uses do_action Calls 'wpmem_pre_update_data' action
 * @uses do_action Calls 'wpmem_post_update_data' action
 *
 * @param  string $toggle toggles the function between 'register' and 'update'.
 * @global int    $user_ID
 * @global string $wpmem_themsg
 * @global array  $userdata
 * @return string $wpmem_themsg|success|editsuccess
 */
function wpmem_registration( $toggle )
{
	// get the globals
	global $user_ID, $wpmem_themsg, $userdata;

	// check the nonce
	if( defined( 'WPMEM_USE_NONCE' ) ) {
		if( empty( $_POST ) || !wp_verify_nonce( $_POST['wpmem-form-submit'], 'wpmem-validate-submit' ) ) {
			$wpmem_themsg = __( 'There was an error processing the form.', 'wp-members' );
			return;
		}
	}

	// is this a registration or a user profile update?
	if( $toggle == 'register' ) {
		$fields['username'] = ( isset( $_POST['user_email'] ) ) ? $_POST['user_email'] : '';
	}

	// add the user email to the $fields array for _data hooks
	$fields['user_email'] = ( isset( $_POST['user_email'] ) ) ? $_POST['user_email'] : '';

	// build the $fields array from $_POST data
	$wpmem_fields = get_option( 'wpmembers_fields' );
	for( $row = 0; $row < count( $wpmem_fields ); $row++ ) {
		if( $wpmem_fields[$row][4] == 'y' ) {
			if( $wpmem_fields[$row][2] != 'password' ) {
				$fields[$wpmem_fields[$row][2]] = ( isset( $_POST[$wpmem_fields[$row][2]] ) ) ? sanitize_text_field( $_POST[$wpmem_fields[$row][2]] ) : '';
			} else {
				// we do have password as part of the registration form
				$fields['password'] = $_POST['password'];
			}
		}
	}

	/**
	 * Filter the submitted form field date prior to validation.
	 *
	 * @since 2.8.2
	 *
	 * @param array $fields An array of the posted form field data.
	 */
	$fields = apply_filters( 'wpmem_pre_validate_form', $fields );

	// check for required fields
	$wpmem_fields_rev = array_reverse( $wpmem_fields );

	for( $row = 0; $row < count($wpmem_fields); $row++ ) {
		$pass_chk = ( $toggle == 'update' && $wpmem_fields_rev[$row][2] == 'password' ) ? true : false;
		if( $wpmem_fields_rev[$row][5] == 'y' && $pass_chk == false ) {
			if( ! $fields[$wpmem_fields_rev[$row][2]] ) { $wpmem_themsg = sprintf( __('Sorry, %s is a required field.', 'wp-members'), $wpmem_fields_rev[$row][1] ); }
		}
	}

	switch( $toggle ) {

	case "register":

		if( !$fields['username'] ) { $wpmem_themsg = __( 'Sorry, username is a required field', 'wp-members' ); return $wpmem_themsg; exit(); }
		if( !validate_username( $fields['username'] ) ) { $wpmem_themsg = __( 'The username cannot include non-alphanumeric characters.', 'wp-members' ); return $wpmem_themsg; exit(); }
		if( !is_email( $fields['user_email']) ) { $wpmem_themsg = __( 'You must enter a valid email address.', 'wp-members' ); return $wpmem_themsg; exit(); }
		if( $wpmem_themsg ) { return "empty"; exit(); }
		if( username_exists( $fields['username'] ) ) { return "user"; exit(); }
		if( email_exists( $fields['user_email'] ) ) { return "email"; exit(); }

		$wpmem_captcha = get_option( 'wpmembers_captcha' ); // get the captcha settings (api keys)
		if( WPMEM_CAPTCHA == 1 && $wpmem_captcha[0] && $wpmem_captcha[1] ) { // if captcha is on, check the captcha

			if( $wpmem_captcha[0] && $wpmem_captcha[1] ) {   // if there is no api key, the captcha never displayed to the end user
				if( !$_POST["recaptcha_response_field"] ) { // validate for empty captcha field
					$wpmem_themsg = __( 'You must complete the CAPTCHA form.', 'wp-members' );
					return "empty"; exit();
				}
			}

			// check to see if the recaptcha library has already been loaded by another plugin
			if( ! function_exists( '_recaptcha_qsencode' ) ) { require_once('wp-members/lib/recaptchalib.php'); }

			$publickey  = $wpmem_captcha[0];
			$privatekey = $wpmem_captcha[1];

			// the response from reCAPTCHA
			$resp = null;
			// the error code from reCAPTCHA, if any
			$error = null;

			if( $_POST["recaptcha_response_field"] ) {

				$resp = recaptcha_check_answer (
					$privatekey,
					$_SERVER["REMOTE_ADDR"],
					$_POST["recaptcha_challenge_field"],
					$_POST["recaptcha_response_field"]
				);

				if( ! $resp->is_valid ) {

					// set the error code so that we can display it
					global $wpmem_captcha_err;
					$wpmem_captcha_err = $resp->error;
					$wpmem_captcha_err = wpmem_get_captcha_err( $wpmem_captcha_err );

					return "captcha";
					exit();

				}
			} // end check recaptcha
		}

		// check for user defined password
		$fields['password'] = ( ! isset( $_POST['password'] ) ) ? wp_generate_password() : $_POST['password'];

		// add for _data hooks
		$fields['user_registered'] = gmdate( 'Y-m-d H:i:s' );
		$fields['user_role']       = get_option( 'default_role' );
		$fields['wpmem_reg_ip']    = $_SERVER['REMOTE_ADDR'];
		$fields['wpmem_reg_url']   = $_REQUEST['redirect_to'];

		/**
		 * these native fields are not installed by default, but if they
		 * are added, use the $_POST value - otherwise, default to username.
		 * value can be filtered with wpmem_register_data
		 */
		$fields['user_nicename']   = ( isset( $_POST['user_nicename'] ) ) ? $_POST['user_nicename'] : $fields['username'];
		$fields['display_name']    = ( isset( $_POST['display_name'] ) )  ? $_POST['display_name']  : $fields['first_name'].' '.$fields['last_name'];
		$fields['nickname']        = ( isset( $_POST['nickname'] ) )      ? $_POST['nickname']      : $fields['username'];

		/**
		 * Filter registration data after validation before data insertion.
		 *
		 * @since 2.8.2
		 *
		 * @param array $fields An array of the registration field data.
		 */
		$fields = apply_filters( 'wpmem_register_data', $fields );

		// _data hook is before any insertion/emails
		do_action( 'wpmem_pre_register_data', $fields );

		// if the _pre_register_data hook sends back an error message
		if( $wpmem_themsg ){ return $wpmem_themsg; }

		// inserts to wp_users table
		$fields['ID'] = wp_insert_user( array (
			'user_pass'       => $fields['password'],
			'user_login'      => $fields['username'],
			'user_nicename'   => $fields['user_nicename'],
			'user_email'      => $fields['user_email'],
			'display_name'    => $fields['display_name'],
			'nickname'        => $fields['nickname'],
			'user_registered' => $fields['user_registered'],
			'role'            => $fields['user_role']
		) );

		// set remaining fields to wp_usermeta table
		for( $row = 0; $row < count( $wpmem_fields ); $row++ ) {
			if( $wpmem_fields[$row][2] != 'password' ) {
				if( $wpmem_fields[$row][2] == 'user_url' ) { // if the field is user_url, it goes in the wp_users table
					$fields['user_url'] = ( isset( $fields['user_url'] ) ) ? $fields['user_url'] : '';
					wp_update_user( array ( 'ID' => $fields['ID'], 'user_url' => $fields['user_url'] ) );
				} else {
					if( $wpmem_fields[$row][2] != 'user_email' ) { // email is already done above, so if it's not email...
						if( $wpmem_fields[$row][4] == 'y' ) { // are we using this field?
							update_user_meta( $fields['ID'], $wpmem_fields[$row][2], $fields[$wpmem_fields[$row][2]] );
						}
					}
				}
			}
		}

		// capture IP address of user at registration
		update_user_meta( $fields['ID'], 'wpmem_reg_ip', $fields['wpmem_reg_ip'] );

		// store the registration url
		update_user_meta( $fields['ID'], 'wpmem_reg_url', $fields['wpmem_reg_url'] );

		// set user expiration, if used
		if( WPMEM_USE_EXP == 1 && WPMEM_MOD_REG != 1 ) { wpmem_set_exp( $fields['ID'] ); }

		// _data hook after insertion but before email
		do_action( 'wpmem_post_register_data', $fields );

		require_once( 'wp-members/wp-members-email.php' );

		// if this was successful, and you have email properly
		// configured, send a notification email to the user
		wpmem_inc_regemail( $fields['ID'], $fields['password'], WPMEM_MOD_REG );

		// notify admin of new reg, if needed;
		if( WPMEM_NOTIFY_ADMIN == 1 ) { wpmem_notify_admin( $fields['ID'], $wpmem_fields ); }

		// add action for redirection
		do_action( 'wpmem_register_redirect' );

		// successful registration message
		return "success"; exit();
		break;

	case "update":

		if( $wpmem_themsg ) { return "updaterr"; exit(); }

		// doing a check for existing email is not the same as a new reg. check first to
		// see if it's different, then check if it is a valid address and it exists.
		global $current_user; get_currentuserinfo();
		if( $fields['user_email'] !=  $current_user->user_email ) {
			if( email_exists( $fields['user_email'] ) ) { return "email"; exit(); }
			if( !is_email( $fields['user_email']) ) { $wpmem_themsg = __( 'You must enter a valid email address.', 'wp-members' ); return "updaterr"; exit(); }
		}

		// add the user_ID to the fields array
		$fields['ID'] = $user_ID;

		/**
		 * Filter registration data after validation before data insertion.
		 *
		 * @since 2.8.2
		 *
		 * @param array $fields An array of the registration field data.
		 */
		$fields = apply_filters( 'wpmem_register_data', $fields );

		// _pre_update_data hook is before data insertion
		do_action( 'wpmem_pre_update_data', $fields );

		// if the _pre_update_data hook sends back an error message
		// @todo - double check this. it should probably return "updaterr" and the hook should globalize wpmem_themsg
		if( $wpmem_themsg ){ return $wpmem_themsg; }

		for( $row = 0; $row < count( $wpmem_fields ); $row++ ) {

			switch( $wpmem_fields[$row][2] ) {

			case( 'user_url' ):
			case( 'user_email'  ):
			case( 'user_nicename' ):
			case( 'display_name' ):
			case( 'nickname' ):
				$fields[$wpmem_fields[$row][2]] = ( isset( $fields[$wpmem_fields[$row][2]] ) ) ? $fields[$wpmem_fields[$row][2]] : '';
				wp_update_user( array( 'ID' => $user_ID, $wpmem_fields[$row][2] => $fields[$wpmem_fields[$row][2]] ) );
				break;

			case( 'password' ):
				// do nothing...
				break;

			default: // everything else goes into wp_usermeta
				if( $wpmem_fields[$row][4] == 'y' ) {
					update_user_meta( $user_ID, $wpmem_fields[$row][2], $fields[$wpmem_fields[$row][2]] );
				}
				break;
			}
		}

		// _post_update_data hook is after insertion
		do_action( 'wpmem_post_update_data', $fields );

		return "editsuccess"; exit();
		break;
	}
} // end registration function
endif;

if ( ! function_exists( 'wpmem_login_form' ) ):
/**
 * Login Form Dialog
 *
 * Builds the form used for login, change password, and reset password.
 *
 * @since 2.5.1
 *
 * @param  string $page
 * @param  array  $arr   The elements needed to generate the form (login|reset password|forgotten password)
 * @return string $form  The HTML for the form as a string
 */
function wpmem_login_form( $page, $arr )
{
	// extract the arguments array
	extract( $arr );

	// set up default wrappers
	$defaults = array(

		// wrappers
		'heading_before'  => '<legend>',
		'heading_after'   => '</legend>',
		'fieldset_before' => '<fieldset>',
		'fieldset_after'  => '</fieldset>',
		'main_div_before' => '<div class="row"><div id="wpmem_login" class="col-sm-6">',
		'main_div_after'  => '</div>',
		'txt_before'      => '[wpmem_txt]',
		'txt_after'       => '[/wpmem_txt]',
		'row_before'      => '',
		'row_after'       => '',
		'buttons_before'  => '<div class="button_div">',
		'buttons_after'   => '</div>',
		'link_before'     => '<div align="right" class="link-text">',
		'link_after'      => '</div>',

		// classes & ids
		'form_id'         => '',
		'form_class'      => 'form',
		'button_id'       => '',
		'button_class'    => 'buttons',

		// other
		'strip_breaks'    => true,
		'wrap_inputs'     => true,
		'remember_check'  => true,
		'n'               => "\n",
		't'               => "\t",

	);

	/**
	 * Filter the default form arguments.
	 *
	 * This filter accepts an array of various elements to replace the form defaults. This
	 * includes default tags, labels, text, and small items including various booleans.
	 *
	 * @since 2.9.0
	 *
	 * @param array          An array of arguments to merge with defaults. Default null.
	 * @param string $action The action being performed by the form. login|pwdreset|pwdchange.
 	 */
	$args = apply_filters( 'wpmem_login_form_args', '', $action );

	// merge $args with defaults and extract
	extract( wp_parse_args( $args, $defaults ) );

	// build the input rows
	foreach ( $inputs as $input ) {
		$label = '<label for="' . $input['tag'] . '">' . $input['name'] . '</label>';
		$field = wpmem_create_formfield( $input['tag'], $input['type'], '', '', $input['class'] );
		$field_before = ( $wrap_inputs ) ? '<div class="' . $input['div'] . '">' : '';
		$field_after  = ( $wrap_inputs ) ? '</div>' : '';
		$rows[] = array(
			'row_before'   => $row_before,
			'label'        => $label,
			'field_before' => $field_before,
			'field'        => $field,
			'field_after'  => $field_after,
			'row_after'    => $row_after
		);
	}

	/**
	 * Filter the array of form rows.
	 *
	 * This filter receives an array of the main rows in the form, each array element being
	 * an array of that particular row's pieces. This allows making changes to individual
	 * parts of a row without needing to parse through a string of HTML.
	 *
	 * @since 2.9.0
	 *
	 * @param array  $rows   An array containing the form rows.
	 * @param string $action The action being performed by the form. login|pwdreset|pwdchange.
 	 */
	$rows = apply_filters( 'wpmem_login_form_rows', $rows, $action );

	// put the rows from the array into $form
	$form = '';
	foreach( $rows as $row_item ) {
		$row  = ( $row_item['row_before']   != '' ) ? $row_item['row_before'] . $n . $row_item['label'] . $n : $row_item['label'] . $n;
		$row .= ( $row_item['field_before'] != '' ) ? $row_item['field_before'] . $n . $t . $row_item['field'] . $n . $row_item['field_after'] . $n : $row_item['field'] . $n;
		$row .= ( $row_item['row_before']   != '' ) ? $row_item['row_after'] . $n : '';
		$form.= $row;
	}

	// build hidden fields, filter, and add to the form
	$redirect_to = ( isset( $_REQUEST['redirect_to'] ) ) ? esc_url( $_REQUEST['redirect_to'] ) : get_permalink();
	$hidden = wpmem_create_formfield( 'redirect_to', 'hidden', $redirect_to ) . $n;
	$hidden = $hidden . wpmem_create_formfield( 'a', 'hidden', $action ) . $n;
	$hidden = ( $action != 'login' ) ? $hidden . wpmem_create_formfield( 'formsubmit', 'hidden', '1' ) : $hidden;

	/**
	 * Filter the hidden field HTML.
	 *
	 * @since 2.9.0
	 *
	 * @param string $hidden The generated HTML of hidden fields.
	 * @param string $action The action being performed by the form. login|pwdreset|pwdchange.
 	 */
	$form = $form . apply_filters( 'wpmem_login_hidden_fields', $hidden, $action );

	// build the buttons, filter, and add to the form
	if ( $action == 'login' ) {
		$remember_check = ( $remember_check ) ? $t . wpmem_create_formfield( 'rememberme', 'checkbox', 'forever' ) . '&nbsp;' . __('Remember me', 'wp-members') . '&nbsp;&nbsp;' . $n : '';
		$buttons =  $remember_check . $t . '<input type="submit" name="Submit" value="' . $button_text . '" class="' . $button_class . '" />' . $n;
	} else {
		$buttons = '<input type="submit" name="Submit" value="' . $button_text . '" class="' . $button_class . '" />' . $n;
	}

	/**
	 * Filter the HTML for form buttons.
	 *
	 * The string includes the buttons, as well as the before/after wrapper elements.
	 *
	 * @since 2.9.0
	 *
	 * @param string $buttons The generated HTML of the form buttons.
	 * @param string $action  The action being performed by the form. login|pwdreset|pwdchange.
 	 */
	$form = $form . apply_filters( 'wpmem_login_form_buttons', $buttons_before . $n . $buttons . $buttons_after . $n, $action );

	if ( ( WPMEM_MSURL != null || $page == 'members' ) && $action == 'login' ) {

		/**
		 * Filter the forgot password link.
		 *
		 * @since 2.8.0
		 *
		 * @param string The forgot password link.
	 	 */
		$link = apply_filters( 'wpmem_forgot_link', wpmem_chk_qstr( WPMEM_MSURL ) . 'a=pwdreset' );
		$str  = __( 'Forgot password?', 'wp-members' ) . '&nbsp;<a href="' . $link . '">' . __( 'Click here to reset', 'wp-members' ) . '</a>';
		$form = $form . $link_before . apply_filters( 'wpmem_forgot_link_str', $str ) . $link_after . $n;

	}

	if ( ( WPMEM_REGURL != null ) && $action == 'login' ) {

		/**
		 * Filter the link to the registration page.
		 *
		 * @since 2.8.0
		 *
		 * @param string The registration page link.
	 	 */
		$link = apply_filters( 'wpmem_reg_link', WPMEM_REGURL );
		$str  = __( 'New User?', 'wp-members' ) . '&nbsp;<a href="' . $link . '">' . __( 'Click here to register', 'wp-members' ) . '</a>';
		$form = $form . $link_before . apply_filters( 'wpmem_reg_link_str', $str ) . $link_after . $n;

	}

	// apply the heading
	$form = $heading_before . $heading . $heading_after . $n . $form;

	// apply fieldset wrapper
	$form = $fieldset_before . $n . $form . $fieldset_after . $n;

	// apply form wrapper
	$form = '<form action="' . get_permalink() . '" method="POST" id="' . $form_id . '" class="' . $form_class . '">' . $n . $form . '</form>';

	// apply anchor
	$form = '<a name="login"></a>' . $n . $form;

	// apply main wrapper
	$form = $main_div_before . $n . $form . $n . $main_div_after;

	// apply wpmem_txt wrapper
	$form = $txt_before . $form . $txt_after;

	// remove line breaks
	$form = ( $strip_breaks ) ? str_replace( array( "\n", "\r", "\t" ), array( '','','' ), $form ) : $form;

	/**
	 * Filter the generated HTML of the entire form.
	 *
	 * @since 2.7.4
	 *
	 * @param string $form   The HTML of the final generated form.
	 * @param string $action The action being performed by the form. login|pwdreset|pwdchange.
 	 */
	$form = apply_filters( 'wpmem_login_form', $form, $action );

	/**
	 * Filter before the form.
	 *
	 * This rarely used filter allows you to stick any string onto the front of
	 * the generated form.
	 *
	 * @since 2.7.4
	 *
	 * @param string $str    The HTML to add before the form. Default null.
	 * @param string $action The action being performed by the form. login|pwdreset|pwdchange.
 	 */
	$form = apply_filters( 'wpmem_login_form_before', '', $action ) . $form;

	return $form;
} // end wpmem_login_form
endif;

if ( ! function_exists( 'wpmem_inc_registration' ) ):
/**
 * Registration Form Dialog
 *
 * Outputs the form for new user registration and existing user edits.
 *
 * @since 2.5.1
 *
 * @param  string $toggle       (optional) Toggles between new registration ('new') and user profile edit ('edit')
 * @param  string $heading      (optional) The heading text for the form, null (default) for new registration
 * @global string $wpmem_regchk Used to determine if the form is in an error state
 * @global array  $userdata     Used to get the user's registration data if they are logged in (user profile edit)
 * @return string $form         The HTML for the entire form as a string
 */
function wpmem_inc_registration( $toggle = 'new', $heading = '' )
{
	global $wpmem_regchk, $userdata;

	// set up default wrappers
	$defaults = array(

		// wrappers
		'heading_before'   => '<legend>',
		'heading_after'    => '</legend>',
		'fieldset_before'  => '<fieldset>',
		'fieldset_after'   => '</fieldset>',
		'main_div_before'  => '<div id="wpmem_reg" class="col-sm-6">',
		'main_div_after'   => '</div></div>',
		'txt_before'       => '[wpmem_txt]',
		'txt_after'        => '[/wpmem_txt]',
		'row_before'       => '',
		'row_after'        => '',
		'buttons_before'   => '<div class="button_div">',
		'buttons_after'    => '</div>',

		// classes & ids
		'form_id'          => '',
		'form_class'       => 'form',
		'button_id'        => '',
		'button_class'     => 'buttons',

		// required field tags and text
		'req_mark'         => '<font class="req">*</font>',
		'req_label'        => __( 'Required field', 'wp-members' ),
		'req_label_before' => '<div class="req-text">',
		'req_label_after'  => '</div>',

		// buttons
		'show_clear_form'  => true,
		'clear_form'       => __( 'Reset Form', 'wp-members' ),
		'submit_register'  => __( 'Register', 'wp-members' ),
		'submit_update'    => __( 'Update Profile', 'wp-members' ),

		// other
		'strip_breaks'     => true,
		'use_nonce'        => false,
		'wrap_inputs'      => true,
		'n'                => "\n",
		't'                => "\t",

	);

	/**
	 * Filter the default form arguments.
	 *
	 * This filter accepts an array of various elements to replace the form defaults. This
	 * includes default tags, labels, text, and small items including various booleans.
	 *
	 * @since 2.9.0
	 *
	 * @param array           An array of arguments to merge with defaults. Default null.
	 * @param string $toggle  Toggle new registration or profile update. new|edit.
	 */
	$args = apply_filters( 'wpmem_register_form_args', '', $toggle );

	// merge $args with defaults and extract
	extract( wp_parse_args( $args, $defaults ) );

	// Username is editable if new reg, otherwise user profile is not
	if( $toggle == 'edit' ) {
		// this is the User Profile edit - username is not editable
		$val   = $userdata->user_login;
		$label = '<label for="username" class="text">' . __( 'Login email', 'wp-members' ) . '</label>';
		$input = '<p class="noinput">' . $val . '</p>';
		$field_before = ( $wrap_inputs ) ? '<div class="div_text">' : '';
		$field_after  = ( $wrap_inputs ) ? '</div>' : '';
	} else {
		// this is a new registration
		$val   = ( isset( $_POST['user_email'] ) ) ? stripslashes( $_POST['user_email'] ) : '';
		// $label = '<label for="username" class="text">' . __( 'Email', 'wp-members' ) . $req_mark . '</label>';
		// $input = wpmem_create_formfield( 'user_email', 'text', $val, '', 'username' );
		$label = '';
		$input = '';

	}
	$field_before = ( $wrap_inputs ) ? '<div class="div_text">' : '';
	$field_after  = ( $wrap_inputs ) ? '</div>': '';

	if( $input != '' ) {
		// add the username row to the array
		$rows['username'] = array(
			'order'        => 0,
			'meta'         => 'username',
			'type'         => 'text',
			'value'        => $val,
			'row_before'   => $row_before,
			'label'        => $label,
			'field_before' => $field_before,
			'field'        => $input,
			'field_after'  => $field_after,
			'row_after'    => $row_after
		);
	}

	/**
	 * Filter the array of form fields.
	 *
	 * The form fields are stored in the WP options table as wpmembers_fields. This
	 * filter can filter that array after the option is retreived before the fields
	 * are parsed. This allows you to change the fields that may be used in the form
	 * on the fly.
	 *
	 * @since 2.9.0
	 *
	 * @param array           The array of form fields.
	 * @param string $toggle  Toggle new registration or profile update. new|edit.
	 */
	$wpmem_fields = apply_filters( 'wpmem_register_fields_arr', get_option( 'wpmembers_fields' ), $toggle );
	//$wpmem_fields = get_option( 'wpmembers_fields' );

	// loop through the remaining fields
	foreach( $wpmem_fields as $field )
	{
		// start with a clean row
		$val = ''; $label = ''; $input = ''; $field_before = ''; $field_after = '';

		// skips user selected passwords for profile update
		$do_row = ( $toggle == 'edit' && $field[2] == 'password' ) ? false : true;

		// skips tos, makes tos field hidden on user edit page, unless they haven't got a value for tos
		if( $field[2] == 'tos' && $toggle == 'edit' && ( get_user_meta( $userdata->ID, 'tos', true ) ) ) {
			$do_row = false;
			$hidden_tos = wpmem_create_formfield( $field[2], 'hidden', get_user_meta( $userdata->ID, 'tos', true ) );
		}

		// if the field is set to display and we aren't skipping, construct the row
		if( $field[4] == 'y' && $do_row == true ) {

			// label for all but TOS
			if( $field[2] != 'tos' ) {

				$class = ( $field[3] == 'password' ) ? 'text' : $field[3];

				$label = '<label for="' . $field[2] . '" class="' . $class . '">' . __( $field[1], 'wp-members' );
				$label = ( $field[5] == 'y' ) ? $label . $req_mark : $label;
				$label = $label . '</label>';

			}

			// gets the field value for both edit profile and submitted reg w/ error
			if( ( $toggle == 'edit' ) && ( $wpmem_regchk != 'updaterr' ) ) {

				switch( $field[2] ) {
					case( 'description' ):
						$val = htmlspecialchars( get_user_meta( $userdata->ID, 'description', 'true' ) );
						break;

					case( 'user_email' ):
						$val = $userdata->user_email;
						break;

					case( 'user_url' ):
						$val = esc_url( $userdata->user_url );
						break;

					default:
						$val = htmlspecialchars( get_user_meta( $userdata->ID, $field[2], 'true' ) );
						break;
				}

			} else {

				$val = ( isset( $_POST[ $field[2] ] ) ) ? $_POST[ $field[2] ] : '';

			}

			// does the tos field
			if( $field[2] == 'tos' ) {

				$val = ( isset( $_POST[ $field[2] ] ) ) ? $_POST[ $field[2] ] : '';

				// should be checked by default? and only if form hasn't been submitted
				$val   = ( ! $_POST && $field[8] == 'y' ) ? $field[7] : $val;
				$input = wpmem_create_formfield( $field[2], $field[3], $field[7], $val );
				$input = ( $field[5] == 'y' ) ? $input . $req_mark : $input;

				// determine if TOS is a WP page or not...
				$tos_content = stripslashes( get_option( 'wpmembers_tos' ) );
				if ( ( wpmem_test_shortcode( $tos_content, 'wp-members' ) ) ) {
					$link = do_shortcode( $tos_content );
					$tos_pop = '<a href="' . $link . '" target="_blank">';
				} else {
					$tos_pop = "<a href=\"#\" onClick=\"window.open('" . WP_PLUGIN_URL . "/wp-members/wp-members-tos.php','mywindow');\">";
				}

				/**
				 * Filter the TOS link text.
				 *
				 * @since 2.7.5
				 *
				 * @param string          The link text.
				 * @param string $toggle  Toggle new registration or profile update. new|edit.
				 */
				$input.= apply_filters( 'wpmem_tos_link_txt', sprintf( __( 'Please indicate that you agree to the %s TOS %s', 'wp-members' ), $tos_pop, '</a>' ), $toggle );

				// in previous versions, the div class would end up being the same as the row before.
				$field_before = ( $wrap_inputs ) ? '<div class="div_text">' : '';
				$field_after  = ( $wrap_inputs ) ? '</div>' : '';

			} else {

				// for checkboxes
				if( $field[3] == 'checkbox' ) {
					$valtochk = $val;
					$val = $field[7];
					// if it should it be checked by default (& only if form not submitted), then override above...
					if( $field[8] == 'y' && ( ! $_POST && $toggle != 'edit' ) ) { $val = $valtochk = $field[7]; }
				}

				// for dropdown select
				if( $field[3] == 'select' ) {
					$valtochk = $val;
					$val = $field[7];
				}

				if( ! isset( $valtochk ) ) { $valtochk = ''; }

				// for all other input types
				$input = wpmem_create_formfield( $field[2], $field[3], $val, $valtochk );

				// determine input wrappers
				$field_before = ( $wrap_inputs ) ? '<div class="div_' . $class . '">' : '';
				$field_after  = ( $wrap_inputs ) ? '</div>' : '';
			}

		}

		// if the row is set to display, add the row to the form array
		if( $field[4] == 'y' ) {
			$rows[$field[2]] = array(
				'order'        => $field[0],
				'meta'         => $field[2],
				'type'         => $field[3],
				'value'        => $val,
				'row_before'   => $row_before,
				'label'        => $label,
				'field_before' => $field_before,
				'field'        => $input,
				'field_after'  => $field_after,
				'row_after'    => $row_after
			);
		}
	}

	/**
	 * Filter the array of form rows.
	 *
	 * This filter receives an array of the main rows in the form, each array element being
	 * an array of that particular row's pieces. This allows making changes to individual
	 * parts of a row without needing to parse through a string of HTML.
	 *
	 * @since 2.9.0
	 *
	 * @param array  $rows    An array containing the form rows.
	 * @param string $toggle  Toggle new registration or profile update. new|edit.
	 */
	$rows = apply_filters( 'wpmem_register_form_rows', $rows, $toggle );

	// put the rows from the array into $form
	$form = '';
	foreach( $rows as $row_item ) {
		$row  = ( $row_item['row_before']   != '' ) ? $row_item['row_before'] . $n . $row_item['label'] . $n : $row_item['label'] . $n;
		$row .= ( $row_item['field_before'] != '' ) ? $row_item['field_before'] . $n . $t . $row_item['field'] . $n . $row_item['field_after'] . $n : $row_item['field'] . $n;
		$row .= ( $row_item['row_after']    != '' ) ? $row_item['row_after'] . $n : '';
		$form.= $row;
	}

	// do captcha if enabled
	if( WPMEM_CAPTCHA == 1 && $toggle != 'edit' ) { // don't show on edit page!

		// get the captcha options
		$wpmem_captcha = get_option('wpmembers_captcha');

		// start with a clean row
		$row = '';

		if( $wpmem_captcha[0] && $wpmem_captcha[1] ) {
			$row = '<div class="clear"></div>';
			$row.= '<div align="right" class="captcha">' . wpmem_inc_recaptcha( $wpmem_captcha[0], $wpmem_captcha[2] ) . '</div>';
		}

		// add the captcha row to the form
		/**
		 * Filter the HTML for the CAPTCHA row.
		 *
		 * @since 2.9.0
		 *
		 * @param string          The HTML for the entire row (includes HTML tags plus reCAPTCHA).
		 * @param string $toggle  Toggle new registration or profile update. new|edit.
		 */
		$form.= apply_filters( 'wpmem_register_captcha_row', $row_before . $row . $row_after, $toggle );
	}

	// create hidden fields
	$var    = ( $toggle == 'edit' ) ? 'update' : 'register';
	$hidden = '<input name="a" type="hidden" value="' . $var . '" />' . $n;
	$hidden.= '<input name="redirect_to" type="hidden" value="' . get_permalink() . '" />' . $n;
	$hidden = ( isset( $hidden_tos ) ) ? $hidden . $hidden_tos . $n : $hidden;

	/**
	 * Filter the hidden field HTML.
	 *
	 * @since 2.9.0
	 *
	 * @param string $hidden The generated HTML of hidden fields.
	 * @param string $toggle Toggle new registration or profile update. new|edit.
	 */
	$hidden = apply_filters( 'wpmem_register_hidden_fields', $hidden, $toggle );

	// add the hidden fields to the form
	$form.= $hidden;

	// create buttons and wrapper
	$button_text = ( $toggle == 'edit' ) ? $submit_update : $submit_register;
	$buttons = '<input name="submit" type="submit" value="' . $button_text . '" class="' . $button_class . '" />' . $n;

	/**
	 * Filter the HTML for form buttons.
	 *
	 * The string passed through the filter includes the buttons, as well as the HTML wrapper elements.
	 *
	 * @since 2.9.0
	 *
	 * @param string $buttons The generated HTML of the form buttons.
	 * @param string $toggle  Toggle new registration or profile update. new|edit.
	 */
	$buttons = apply_filters( 'wpmem_register_form_buttons', $buttons, $toggle );

	// add the buttons to the form
	$form.= $buttons_before . $n . $buttons . $buttons_after . $n;

	// add the required field notation to the bottom of the form
	$form.= $req_label_before . $req_mark . $req_label . $req_label_after;

	// apply the heading
	/**
	 * Filter the registration form heading.
	 *
	 * @since 2.8.2
	 *
	 * @param string $str
	 * @param string $toggle Toggle new registration or profile update. new|edit.
	 */
	$heading = ( !$heading ) ? apply_filters( 'wpmem_register_heading', __( 'New User Registration', 'wp-members' ), $toggle ) : $heading;
	$form = $heading_before . $heading . $heading_after . $n . $form;

	// apply fieldset wrapper
	$form = $fieldset_before . $n . $form . $n . $fieldset_after;

	// apply attribution if enabled
	$form = $form . wpmem_inc_attribution();

	// apply nonce
	$form = ( defined( 'WPMEM_USE_NONCE' ) || $use_nonce ) ? wp_nonce_field( 'wpmem-validate-submit', 'wpmem-form-submit' ) . $n . $form : $form;

	// apply form wrapper
	$form = '<form name="form" method="post" action="' . get_permalink() . '" id="' . $form_id . '" class="' . $form_class . '">' . $n . $form. $n . '</form>';

	// apply anchor
	$form = '<a name="register"></a>' . $n . $form;

	// apply main div wrapper
	$form = $main_div_before . $n . $form . $n . $main_div_after . $n;

	// apply wpmem_txt wrapper
	$form = $txt_before . $form . $txt_after;

	// remove line breaks if enabled for easier filtering later
	$form = ( $strip_breaks ) ? str_replace( array( "\n", "\r", "\t" ), array( '','','' ), $form ) : $form;

	/**
	 * Filter the generated HTML of the entire form.
	 *
	 * @since 2.7.4
	 *
	 * @param string $form   The HTML of the final generated form.
	 * @param string $toggle Toggle new registration or profile update. new|edit.
	 * @param array  $rows   The rows array
	 * @param string $hidden The HTML string of hidden fields
	 */
	$form = apply_filters( 'wpmem_register_form', $form, $toggle, $rows, $hidden );

	/**
	 * Filter before the form.
	 *
	 * This rarely used filter allows you to stick any string onto the front of
	 * the generated form.
	 *
	 * @since 2.7.4
	 *
	 * @param string $str    The HTML to add before the form. Default null.
	 * @param string $toggle Toggle new registration or profile update. new|edit.
	 */
	$form = apply_filters( 'wpmem_register_form_before', '', $toggle ) . $form;

	// return the generated form
	return $form;
} // end wpmem_inc_registration
endif;

add_action('wpmem_post_register_data', 'my_registration_hook', 1);

function my_registration_hook($fields) {
		$user_login = $fields[username];
		$user_id = $fields[ID];

		wp_set_current_user($user_id);
		wp_set_auth_cookie($user_login);
		do_action('wp_login', $user_login);

		wp_set_current_user($fields[ID]);
}

?>