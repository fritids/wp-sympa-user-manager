<?php
/*  
Plugin Name: SYMPA user manager
Plugin URI: https://github.com/qagren/wp-sympa-user-manager.git
Description: A wordpress plugin to manage users and synchronize to SYMPA list server 
Author: Quentin Agren
Version: 0.1
 */
function admin_panel () 
{
  include "admin-panel.php";
}

// Add entry into admin panel
add_action('admin_menu', 'sympa_user_manager_admin_panel');
function sympa_user_manager_admin_panel () 
{
  add_users_page("Sympa users", "Sympa users", "list_users", "sympa-users", "admin_panel");
}


// Custom front registration page
// First solution : using a wp page..
add_shortcode('sympa-user-registration', 'sympa_front_registration');
function sympa_front_registration ()
{
  ob_start();
  include "registration-form.php";
  $ret = ob_get_contents();
  ob_end_clean();
  return $ret;
}


// Custom front registration 
// Secon solution : using registration hooks


// Redefining user notification function

// Redefine user notification function
if ( !function_exists('wp_new_user_notification') ) {

  function wp_new_user_notification( $user_id, $plaintext_pass = '') {

		$user = new WP_User( $user_id );

		$user_login = stripslashes( $user->user_login );
		$user_email = stripslashes( $user->user_email );

		$message  = sprintf( __('New user registration on %s:'), get_option('blogname') ) . "\r\n\r\n";
		$message .= sprintf( __('Username: %s'), $user_login ) . "\r\n\r\n";
		$message .= sprintf( __('E-mail: %s'), $user_email ) . "\r\n";

		@wp_mail(
			get_option('admin_email'),
			sprintf(__('[%s] New User Registration'), get_option('blogname') ),
			$message
		);

  }
}



//Adding password and newsletter inputs

add_action('register_form','sympa_register_form');
function sympa_register_form ()
{
  include('sympa-register-extras.php');  
}


// Check for valid inputs

// Add temporary user to the database
add_action('user_register','sympa_add_user');
function sympa_add_user($user_id) 
{
  print_r($_POST);
  // Get the user
  $user = get_user_by('id', $user_id);
  
  //Remember mailing list choice 
  add_user_meta($user_id, 'liste_diff', $_POST['liste_diff']);

  // Set password
  wp_update_user( array ('ID' => $user_id, 'user_pass' => $_POST['pass']) );   
  // Remember login in meta value
  $login = $user->user_login ;
  add_user_meta($user_id, 'tmp_login', $login);
  
  // Set hash for login  
  $pass = $user->user_pass;
  $hash = md5($login.$pass.'toto');
  global $wpdb;
  $wpdb->update($wpdb->users, array('user_login' => $hash), array('ID' => $user_id));
    
  // Send notification mail
  $url = "http://localhost/wordpress/wp-login.php?hash=".$hash;
  $message = " Pour confirmer ton inscription :".$url ;
  wp_mail(
	  $user->user_email,
	  'Confirmation inscription',
	  $message);
	  
    
}

// Redirect, wait for validation
function sympa_register_redirection()
{
  return 'http://localhost/wordpress/wp-login.php?action=register&status=mailsent';
}
add_filter('registration_redirect','sympa_register_redirection') ;


// Filter to apply when login page is requested to validate email

function the_login_message( $message ) 
{  
  if (isset($_GET['status']) && $_GET['status'] =='mailsent')
    {
      return '<p class="message register"> Un mail de confirmation vous a été envoyé. Consultez-le pour valider votre inscription.</p>';
    }

  if (isset($_GET['hash']))
      {
	$hash = $_GET['hash'];
	$user = get_user_by('login',$hash);
	
	if ($user != false)
	  {
	    $user_id = $user->ID;
	    $user_login = get_user_meta($user_id, 'tmp_login',true);
	    $user_mail = $user->user_email;
	    
	    // Set login back to original
	    global $wpdb;
	    $wpdb->update($wpdb->users, array('user_login' => $user_login), array('ID' => $user_id));
  
	    // Update sympa
	    $liste_diff = get_user_meta($user_id, 'liste_diff',true);
	    $a_ete_ajoute='';

	    if ($liste_diff == "oui")
	      {
		$dest = "sympa@listes.ens-lyon.fr";
		$subject="";
		$message = "ADD clubrock ".$user_mail;
		$msg = mail($dest,$subject,$message,'From: quentin.agren@ens-lyon.fr', '-f quentin.agren@ens-lyon.fr');
		$a_ete_ajoute = '<p class="message register"> Vous avez été ajouté à la liste de diffusion </p>';
	      }
	    return '<p class="message register"> Votre adresse mail a été validée. Vous pouvez maintenant vous connecter ! </p>'.$a_ete_ajoute ;
	  }
	else
	  {
	    echo  $hash; echo $user->user_login ;
	  }
      }
}
add_filter( 'login_message', 'the_login_message' );


?>

